<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class ResultController extends Controller
{
	public function trainData()
	{
		$title = 'Train Data';

		$data = DB::table('train_data')
			->select('id', 'lemmatized', 'label')
			->where('created_by', Auth::user()->id)
			->paginate(20);

		$positifCount = DB::table('train_data')
			->where('created_by', Auth::user()->id)
			->where('label', 'positif')
			->count();

		$negatifCount = DB::table('train_data')
			->where('created_by', Auth::user()->id)
			->where('label', 'negatif')
			->count();

		return view('result.train-data', compact(
			'title',
			'data',
			'positifCount',
			'negatifCount'
		));
	}

	public function testData()
	{
		$title = 'Test Data';

		$data = DB::table('test_data')
			->select('id', 'lemmatized', 'label')
			->where('created_by', Auth::user()->id)
			->paginate(20);

		if ($data->isEmpty()) {
			return redirect()->back()->with('error', 'Data uji tidak ditemukan.');
		}

		return view('result.test-data', compact('title', 'data'));
	}

	public function naiveBayes()
	{
		$title = 'Naive Bayes';

		$data = DB::table('train_data')
			->select('id', 'lemmatized', 'label')
			->where('created_by', Auth::user()->id)
			->get();

		$features = [];
		$labels = [];
		$total_doc = count($data);

		foreach ($data as $d) {
			$cleanJson = str_replace("'", '"', $d->lemmatized);

			$tokens = json_decode($cleanJson, true);

			if (!is_array($tokens)) {
				$tokens = [];
			}

			$token_counts = array_count_values($tokens);
			$features[$d->id] = $token_counts;
			$labels[$d->id] = $d->label;
		}

		// Probabilitas prior P(class)
		$class_prob = [];
		foreach ($labels as $label) {
			if (!isset($class_prob[$label])) {
				$class_prob[$label] = 0;
			}
			$class_prob[$label]++;
		}
		foreach ($class_prob as $label => $count) {
			$class_prob[$label] = $count / $total_doc;
		}

		// hitung jumlah kata per kelas dan vocab
		$cond_prob = [];                // p(word|class)
		$raw_counts = [];              // count(word|class)
		$word_counts_by_class = [];    // total kata per kelas
		$vocab = []; // total kata unik

		foreach ($features as $doc_id => $terms) {
			$label = $labels[$doc_id];

			$word_counts_by_class[$label] = $word_counts_by_class[$label] ?? 0;
			$cond_prob[$label] = $cond_prob[$label] ?? [];
			$raw_counts[$label] = $raw_counts[$label] ?? [];

			foreach ($terms as $word => $count) {
				$raw_counts[$label][$word] = ($raw_counts[$label][$word] ?? 0) + $count;

				$cond_prob[$label][$word] = ($cond_prob[$label][$word] ?? 0) + $count;

				$word_counts_by_class[$label] += $count;
				$vocab[$word] = true;
			}
		}

		$vocab_size = count($vocab);

		foreach ($cond_prob as $label => $word_counts) {
			$total_words = $word_counts_by_class[$label];
			foreach ($word_counts as $word => $count) {
				$cond_prob[$label][$word] = ($count + 1) / ($total_words + $vocab_size);
			}
		}

		// simpan hasil pelatihan 
		$directory = 'result/naive-bayes-models';
		if (!Storage::disk('public')->exists($directory)) {
			Storage::disk('public')->makeDirectory($directory);
		}

		$timestamp = now()->setTimezone('Asia/Makassar')->format('Y-m-d_H-i-s');
		$filename = $directory . '/nb_model_user_' . Auth::id() . '_' . $timestamp . '.json';

		$modelData = [
			'model_info' => [
				'algorithm' => 'Naive Bayes',
				'total_documents' => $total_doc,
				'vocab_size' => $vocab_size,
				'classes' => array_keys($class_prob),
				'created_by' => Auth::user()->id,
				'created_at' => now()->setTimezone('Asia/Makassar')->format('Y-m-d H:i:s')
			],
			'model_parameters' => [
				'class_probabilities' => $class_prob,
				'conditional_probabilities' => $cond_prob,
				'vocabulary' => array_keys($vocab),
				'word_counts_by_class' => $word_counts_by_class,
				'raw_word_counts' => $raw_counts
			]
		];

		$saveResult = Storage::disk('public')->put($filename, json_encode($modelData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

		if (!$saveResult) {
			return redirect()->back()->with('error', 'Gagal menyimpan model pelatihan.');
		}

		return view('result.naive-bayes', compact(
			'title',
			'class_prob',
			'cond_prob',
			'vocab_size',
			'word_counts_by_class',
			'raw_counts'
		));
	}

	public function predictedDetails()
	{
		$title = "Predicted Details";

		$testData = DB::table('test_data')
			->select('id', 'lemmatized', 'label')
			->where('created_by', Auth::user()->id)
			->get();

		$trainData = DB::table('train_data')
			->select('id', 'lemmatized', 'label')
			->where('created_by', Auth::user()->id)
			->get();

		if ($testData->isEmpty() || $trainData->isEmpty()) {
			return redirect()->back()->with('error', 'Data latih atau uji kosong.');
		}

		if ($testData->isEmpty() || $trainData->isEmpty()) {
			return redirect()->back()->with('error', 'Data latih atau uji kosong.');
		}

		// Naive Bayes Training
		$classCounts = [];
		$wordFreq = [];

		foreach ($trainData as $item) {
			$label = $item->label;
			$classCounts[$label] = ($classCounts[$label] ?? 0) + 1;

			$cleanJson = str_replace("'", '"', $item->lemmatized);
			$tokens = json_decode($cleanJson, true) ?: [];

			foreach ($tokens as $word) {
				$wordFreq[$label][$word] = ($wordFreq[$label][$word] ?? 0) + 1;
			}
		}

		$totalTrainDocs = count($trainData);

		$classProb = [];
		foreach ($classCounts as $class => $count) {
			$classProb[$class] = $count / $totalTrainDocs;
		}

		$vocab = [];
		foreach ($wordFreq as $label => $words) {
			foreach (array_keys($words) as $word) {
				$vocab[$word] = true;
			}
		}

		$vocab = array_keys($vocab);
		$vocabSize = count($vocab);

		$condProb = [];
		foreach ($wordFreq as $class => $freqs) {
			$totalWords = array_sum($freqs);
			foreach ($vocab as $word) {
				$count = $freqs[$word] ?? 0;
				$condProb[$class][$word] = ($count + 1) / ($totalWords + $vocabSize);
			}
		}

		$labelsActual = [];
		$labelsPredicted = [];
		$predictedDetails = [];

		foreach ($testData as $item) {
			$id = $item->id;
			$actual = $item->label;

			$cleanJson = str_replace("'", '"', $item->lemmatized);
			$tokens = json_decode($cleanJson, true) ?: [];
			$termFreq = array_count_values($tokens);

			$scores = [];
			foreach ($classProb as $class => $prior) {
				$score = log($prior);
				$totalWordsInClass = array_sum($wordFreq[$class] ?? []);

				foreach ($termFreq as $word => $count) {
					$prob = $condProb[$class][$word] ?? (1 / ($totalWordsInClass + $vocabSize));
					$score += $count * log($prob);
				}

				$scores[$class] = $score;
			}

			$predicted = array_keys($scores, max($scores))[0];

			$labelsActual[$id] = $actual;
			$labelsPredicted[$id] = $predicted;

			$predictedDetails[] = [
				'ulasan' => $item->lemmatized,
				'aktual' => $actual,
				'prediksi' => $predicted
			];
		}

		$page = request()->get('page', 1);
		$perPage = 10;
		$collection = collect($predictedDetails);
		$paginatedPredictions = new LengthAwarePaginator(
			$collection->forPage($page, $perPage),
			$collection->count(),
			$perPage,
			$page,
			['path' => request()->url(), 'query' => request()->query()]
		);

		return view('result.predicted-details', compact('title', 'paginatedPredictions'));
	}

	public function confusionMatrix(Request $request)
	{
		$title = "Confusion Matrix";

		$testData = DB::table('test_data')
			->select('id', 'lemmatized', 'label')
			->where('created_by', Auth::user()->id)
			->get();

		$trainData = DB::table('train_data')
			->select('id', 'lemmatized', 'label')
			->where('created_by', Auth::user()->id)
			->get();

		if ($testData->isEmpty() || $trainData->isEmpty()) {
			return redirect()->back()->with('error', 'Data latih atau uji kosong.');
		}

		$classCounts = [];
		$wordFreq = [];

		foreach ($trainData as $item) {
			$label = $item->label;
			$classCounts[$label] = ($classCounts[$label] ?? 0) + 1;

			$cleanJson = str_replace("'", '"', $item->lemmatized);
			$tokens = json_decode($cleanJson, true) ?: [];

			foreach ($tokens as $word) {
				$wordFreq[$label][$word] = ($wordFreq[$label][$word] ?? 0) + 1;
			}
		}

		$totalTrainDocs = count($trainData);

		//prior probabilities P(class)
		//menghitung probabilitas kelas
		$classProb = [];
		foreach ($classCounts as $class => $count) {
			$classProb[$class] = $count / $totalTrainDocs;
		}

		//menghitung total kata per kelas dan vocab
		$vocab = [];
		foreach ($wordFreq as $label => $words) {
			foreach (array_keys($words) as $word) {
				$vocab[$word] = true;
			}
		}

		$vocab = array_keys($vocab);
		$vocabSize = count($vocab);

		//likelihood probabilities P(word|class)
		//menghitung probabilitas kondisi dengan Laplace smoothing
		$condProb = [];
		foreach ($wordFreq as $class => $freqs) {
			$totalWords = array_sum($freqs);
			foreach ($vocab as $word) {
				$count = $freqs[$word] ?? 0;
				$condProb[$class][$word] = ($count + 1) / ($totalWords + $vocabSize);
			}
		}

		$labelsActual = [];
		$labelsPredicted = [];
		$predictedDetails = [];

		foreach ($testData as $item) {
			$id = $item->id;
			$actual = $item->label;

			$cleanJson = str_replace("'", '"', $item->lemmatized);
			$tokens = json_decode($cleanJson, true) ?: [];
			$termFreq = array_count_values($tokens);


			// posterior probabilities P(class|words)
			$scores = [];
			foreach ($classProb as $class => $prior) {
				$score = log($prior);
				$totalWordsInClass = array_sum($wordFreq[$class] ?? []);

				foreach ($termFreq as $word => $count) {
					$prob = $condProb[$class][$word] ?? (1 / ($totalWordsInClass + $vocabSize));
					$score += $count * log($prob);
				}

				$scores[$class] = $score;
			}

			$predicted = array_keys($scores, max($scores))[0];

			$labelsActual[$id] = $actual;
			$labelsPredicted[$id] = $predicted;
		}

		$classes = array_values(array_unique(array_merge(
			array_values($labelsActual),
			array_values($labelsPredicted)
		)));

		$confMatrix = [];
		foreach ($classes as $actual) {
			$confMatrix[$actual] = array_fill_keys($classes, 0);
		}

		foreach ($labelsActual as $id => $actual) {
			$predicted = $labelsPredicted[$id];
			$confMatrix[$actual][$predicted]++;
		}

		$metrics = [];
		$totalTest = count($testData);
		$correct = 0;

		foreach ($classes as $class) {
			$TP = $confMatrix[$class][$class];
			$FP = array_sum(array_column($confMatrix, $class)) - $TP;
			$FN = array_sum($confMatrix[$class]) - $TP;
			$TN = $totalTest - $TP - $FP - $FN;

			$precision = ($TP + $FP) ? $TP / ($TP + $FP) : 0;
			$recall = ($TP + $FN) ? $TP / ($TP + $FN) : 0;
			$f1 = ($precision + $recall) ? 2 * ($precision * $recall) / ($precision + $recall) : 0;

			$metrics[$class] = [
				'precision' => round($precision * 100, 2),
				'recall' => round($recall * 100, 2),
				'f1_score' => round($f1 * 100, 2),
			];

			$correct += $TP;
		}

		$accuracy = $totalTest > 0 ? $correct / $totalTest : 0;

		return view('result.conf-matrix', compact(
			'confMatrix',
			'classes',
			'metrics',
			'accuracy',
			'title',
		));
	}
}

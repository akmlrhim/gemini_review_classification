<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
	public function trainData()
	{
		$title = 'Train Data';

		$data = DB::table('train_data')
			->select('id', 'lemmatized', 'label')
			->where('created_by', Auth::user()->id)
			->paginate(20);

		if ($data->isEmpty()) {
			return redirect()->back()->with('error', 'Data latih tidak ditemukan.');
		}

		return view('result.train-data', compact('title', 'data'));
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


	public function calculateNaiveBayes()
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

		// Hitung jumlah kata per kelas dan vocab
		$cond_prob = [];                // P(word|class)
		$raw_counts = [];              // Count(word|class)
		$word_counts_by_class = [];    // Total kata per kelas
		$vocab = [];

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

		// Hitung probabilitas kondisi dengan Laplace smoothing
		//peluang kemunculan kata dalam kelas
		$vocab_size = count($vocab);

		foreach ($cond_prob as $label => $word_counts) {
			$total_words = $word_counts_by_class[$label];
			foreach ($word_counts as $word => $count) {
				$cond_prob[$label][$word] = ($count + 1) / ($total_words + $vocab_size);
			}
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

		// naive bayes 
		$classCounts = []; //jumlah dokumen per kelas
		$wordFreq = []; // frekuensi kata per kelas


		//hitung frekurensi kata per kelas dari data training
		foreach ($trainData as $item) {
			$label = $item->label;
			$classCounts[$label] = ($classCounts[$label] ?? 0) + 1;

			$cleanJson = str_replace("'", '"', $item->lemmatized);

			$tokens = json_decode($cleanJson, true);
			if (!is_array($tokens)) {
				$tokens = [];
			}

			foreach ($tokens as $word) {
				$wordFreq[$label][$word] = ($wordFreq[$label][$word] ?? 0) + 1;
			}
		}

		$totalTrainDocs = count($trainData); //total docs train data

		// hitung probabilitas prior (p|kelas)
		$classProb = [];
		foreach ($classCounts as $class => $count) {
			$classProb[$class] = $count / $totalTrainDocs;
		}

		// buat daftar kata unik dari semua kelas
		$vocab = [];
		foreach ($wordFreq as $label => $words) {
			foreach (array_keys($words) as $word) {
				$vocab[$word] = true;
			}
		}

		$vocab = array_keys($vocab);
		$vocabSize = count($vocab); // jumlah kata unik


		// hitung probabilitas P(word|class) dengan menghilangkan kemungkinan nilai nol
		$condProb = [];
		foreach ($wordFreq as $class => $freqs) {
			$totalWords = array_sum($freqs);
			foreach ($vocab as $word) {
				$count = $freqs[$word] ?? 0;
				$condProb[$class][$word] = ($count + 1) / ($totalWords + $vocabSize); // laplace smoothing
			}
		}

		// prediksi label dengan model (data training)
		$labelsActual = []; // simpan label asli dari datatest
		$labelsPredicted = []; // simpan hasil prediksi label model

		foreach ($testData as $item) {
			$id = $item->id;
			$labelsActual[$id] = $item->label;

			$cleanJson = str_replace("'", '"', $item->lemmatized);

			$tokens = json_decode($cleanJson, true);

			if (!is_array($tokens)) {
				$tokens = [];
			}
			$termFreq = array_count_values($tokens); // hitung frekuensi kata dalam dokumen

			$scores = [];

			//hitung skor log probabilitas untuk setiap kelas
			foreach ($classProb as $class => $prior) {
				$score = log($prior); // log(P(class))
				$totalWordsInClass = array_sum($wordFreq[$class] ?? []);

				foreach ($termFreq as $word => $count) {
					// probabilitas sudah dihitung dengan smoothing, untuk menghindari nilai nol
					$prob = $condProb[$class][$word] ?? (1 / ($totalWordsInClass + $vocabSize));
					$score += $count * log($prob); // log(P(word|class))^count
				}

				$scores[$class] = $score;
			}

			// ambil kelas dengan skor tertinggi sebagai prediksi
			$labelsPredicted[$id] = array_keys($scores, max($scores))[0];
		}

		// CONFUSION MATRIX 
		//ambil semua kelas unik dari label asli dan prediksi
		$classes = array_values(array_unique(array_merge(
			array_values($labelsActual),
			array_values($labelsPredicted)
		)));

		//init confusion matrix
		$confMatrix = [];
		foreach ($classes as $actual) {
			$confMatrix[$actual] = array_fill_keys($classes, 0);
		}

		foreach ($labelsActual as $id => $actual) {
			$predicted = $labelsPredicted[$id];
			$confMatrix[$actual][$predicted]++;
		}

		//metrik
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
			'title'
		));
	}
}

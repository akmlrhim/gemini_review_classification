<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
	public function calculateBagOfWords()
	{
		$title = 'Bag of Words';

		$data = DB::table('train_data')
			->select('id', 'lemmatized')
			->where('created_by', Auth::user()->id)
			->paginate(2)
			->appends(request()->query());

		$tf = [];

		foreach ($data as $d) {
			$tokens = explode(' ', $d->lemmatized);
			$tf[$d->id] = array_count_values($tokens);
		}

		return view('result.bag-of-words', compact(
			'title',
			'tf',
			'data'
		));
	}

	public function calculateNaiveBayes()
	{
		$title = 'Naive Bayes';

		$data = DB::table('train_data')
			->select('id', 'lemmatized', 'label')
			->where('created_by', Auth::user()->id)
			->get();

		$features = []; //tf-idf
		$labels = []; //label
		$total_doc = count($data);

		foreach ($data as $d) {
			$tokens = explode(' ', $d->lemmatized);
			$token_counts = array_count_values($tokens); // bow
			$features[$d->id] = $token_counts;
			$labels[$d->id] = $d->label;
		}

		$class_prob = [];
		$cond_prob = [];

		foreach ($labels as $label) {
			if (!isset($class_prob[$label])) {
				$class_prob[$label] = 0;
			}
			$class_prob[$label]++;
		}

		foreach ($class_prob as $label => $count) {
			$class_prob[$label] = $count / $total_doc;
		}

		foreach ($features as $doc_id => $terms) {
			$label = $labels[$doc_id];
			foreach ($terms as $word => $count) {
				if (!isset($cond_prob[$label][$word])) {
					$cond_prob[$label][$word] = 0;
				}
				$cond_prob[$label][$word] += $count;
			}
		}

		foreach ($cond_prob as $label => $word_counts) {
			$total_words = array_sum($word_counts);
			foreach ($word_counts as $word => $count) {
				$cond_prob[$label][$word] = ($count + 1) / ($total_words + count($features));
			}
		}

		return view('result.naive-bayes', compact(
			'title',
			'class_prob',
			'cond_prob',
		));
	}

	public function confusionMatrix(Request $request)
	{
		$title = "Confusion Matrix";

		// Ambil data dari database
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

		// TRAINING DATA
		$classCounts = [];
		$wordFreq = [];

		foreach ($trainData as $item) {
			$label = $item->label;
			$classCounts[$label] = ($classCounts[$label] ?? 0) + 1;

			$tokens = explode(' ', $item->lemmatized);
			foreach ($tokens as $word) {
				$wordFreq[$label][$word] = ($wordFreq[$label][$word] ?? 0) + 1;
			}
		}

		$totalTrainDocs = count($trainData);

		// P(Label)
		$classProb = [];
		foreach ($classCounts as $class => $count) {
			$classProb[$class] = $count / $totalTrainDocs;
		}

		// Vocab
		$vocab = [];

		foreach ($wordFreq as $label => $words) {
			foreach (array_keys($words) as $word) {
				$vocab[$word] = true;
			}
		}

		$vocab = array_keys($vocab);
		$vocabSize = count($vocab);

		// P(Kata | Label)
		$condProb = [];
		foreach ($wordFreq as $class => $freqs) {
			$totalWords = array_sum($freqs);
			foreach ($vocab as $word) {
				$count = $freqs[$word] ?? 0;
				$condProb[$class][$word] = ($count + 1) / ($totalWords + $vocabSize);
			}
		}


		// LABEL PREDIKSI 
		$labelsActual = [];
		$labelsPredicted = [];

		foreach ($testData as $item) {
			$id = $item->id;
			$labelsActual[$id] = $item->label;

			$tokens = explode(' ', $item->lemmatized);
			$termFreq = array_count_values($tokens);

			$scores = [];
			foreach ($classProb as $class => $prior) {
				$score = log($prior);
				$totalWordsInClass = array_sum($wordFreq[$class] ?? []);

				foreach ($termFreq as $word => $count) {
					$prob = $condProb[$class][$word] ?? (1 / ($totalWordsInClass + $vocabSize));
					$score += $count * log($prob); // log(P(word|class))^count
				}

				$scores[$class] = $score;
			}

			$labelsPredicted[$id] = array_keys($scores, max($scores))[0];
		}

		// CONFUSION MATRIX 
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

		// =====================
		// METRIK
		// =====================
		$metrics = [];
		$totalTest = count($testData);
		$correct = 0;

		foreach ($classes as $class) {
			$TP = $confMatrix[$class][$class];
			$FP = array_sum(array_column($confMatrix, $class)) - $TP;
			$FN = array_sum($confMatrix[$class]) - $TP;
			$TN = $totalTest - $TP - $FP - $FN;

			$precision = $TP / ($TP + $FP);
			$recall =  $TP / ($TP + $FN);
			$f1 =  2 * ($precision * $recall) / ($precision + $recall);

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

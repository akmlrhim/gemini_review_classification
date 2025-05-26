<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
	public function calculateNaiveBayes()
	{
		$title = 'Naive Bayes';

		$data = DB::table('preprocessing')
			->select('id', 'lemmatized', 'label')
			->get();

		$features = []; //tf-idf
		$labels = []; //label
		$total_doc = count($data);

		foreach ($data as $d) {
			$tokens = explode(' ', $d->lemmatized);
			$token_counts = array_count_values($tokens);
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

	public function formInput()
	{
		$title = "Confusion Matrix";
		return view('result.conf-matrix-form', compact('title'));
	}

	public function processFormInput(Request $request)
	{
		$request->validate([
			'test_size' => 'required|numeric|min:1|max:100'
		]);

		session(['test_size' => $request->test_size]);

		return redirect()->route('result.confusion-matrix');
	}

	public function confusionMatrix(Request $request)
	{
		$title = "Confusion Matrix";

		$testSize = $request->session()->get('test_size') / 100;
		$randomSeed = 42;

		$data = DB::table('preprocessing')->select('id', 'lemmatized', 'label')
			->get()
			->toArray();

		srand($randomSeed); //random seed 
		shuffle($data); // acak data

		$total = count($data);
		$testCount = (int) round($total * $testSize);
		$testData = array_slice($data, 0, $testCount); // data uji
		$trainData = array_slice($data, $testCount); // data latih

		// inisialisasi array utk aktual dan prediksi
		$labels_actual = [];
		$labels_predicted = [];

		$tf = [];
		foreach ($testData as $d) {
			$tokens = explode(' ', $d->lemmatized);
			$tf[$d->id] = array_count_values($tokens);
			$labels_actual[$d->id] = $d->label;
		}

		$class_counts = [];
		$word_freq = [];

		foreach ($trainData as $d) {
			$label = $d->label;
			$class_counts[$label] = ($class_counts[$label] ?? 0) + 1;
			$tokens = explode(' ', $d->lemmatized);
			foreach ($tokens as $word) {
				$word_freq[$label][$word] = ($word_freq[$label][$word] ?? 0) + 1;
			}
		}

		// prior probability
		$total_train_docs = count($trainData);
		$class_prob = [];
		foreach ($class_counts as $class => $count) {
			$class_prob[$class] = $count / $total_train_docs;
		}

		$cond_prob = [];
		foreach ($word_freq as $class => $freqs) {
			$total_words = array_sum($freqs);
			$vocab_size = count(array_unique(array_merge(...array_values($word_freq))));
			foreach ($freqs as $word => $count) {
				$cond_prob[$class][$word] = ($count + 1) / ($total_words + $vocab_size);
			}
		}

		foreach ($tf as $doc_id => $terms) {
			$scores = [];
			foreach ($class_prob as $class => $prior) {
				$scores[$class] = log($prior);
				$total_words_in_class = array_sum($word_freq[$class] ?? []);
				$vocab_size = count(array_unique(array_merge(...array_values($word_freq))));
				foreach ($terms as $word => $count) {

					//prob kata di kelas
					$prob = $cond_prob[$class][$word] ?? (1 / ($total_words_in_class + $vocab_size));
					$scores[$class] += $count * log($prob);
				}
			}

			// ambil kelas skor tertinggi sebagai prediksi
			$labels_predicted[$doc_id] = array_keys($scores, max($scores))[0];
		}

		// inisialisasi confusion matrix 
		$classes = ['positif', 'negatif'];
		$conf_matrix = [];
		foreach ($classes as $actual) {
			foreach ($classes as $predicted) {
				$conf_matrix[$actual][$predicted] = 0;
			}
		}

		// hitung confusion matrix berdasarkan prediksi
		foreach ($labels_actual as $id => $actual_label) {
			$predicted_label = $labels_predicted[$id];
			$conf_matrix[$actual_label][$predicted_label]++;
		}

		//hitung metriks
		$metrics = [];
		$total_test = count($testData);
		foreach ($classes as $class) {
			$TP = $conf_matrix[$class][$class]; //true positif
			$FP = array_sum(array_column($conf_matrix, $class)) - $TP; //false positif
			$FN = array_sum($conf_matrix[$class]) - $TP; // false negatif
			$TN = $total_test - $TP - $FP - $FN; // true negatif

			$precision = $TP + $FP > 0 ? $TP / ($TP + $FP) : 0; //presisi
			$recall = $TP + $FN > 0 ? $TP / ($TP + $FN) : 0; //recall
			$f1 = $precision + $recall > 0 ? 2 * ($precision * $recall) / ($precision + $recall) : 0; //f1 score

			$metrics[$class] = [
				'precision' => round($precision, 4) * 100,
				'recall' => round($recall, 4) * 100,
				'f1_score' => round($f1, 4) * 100,
			];
		}

		// hitung akurasi 
		$correct = 0;
		foreach ($labels_actual as $id => $actual) {
			// if ($labels_predicted[$id] === $actual) $correct++;
			if ($labels_predicted[$id] === $actual) {
				$correct++;
			}
		}
		$accuracy = $total_test > 0 ? $correct / $total_test : 0;

		return view('result.conf-matrix', compact(
			'conf_matrix',
			'classes',
			'metrics',
			'accuracy',
			'title',
			'testSize',
			'randomSeed'
		));
	}
}

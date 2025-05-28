<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;

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

		// Ambil ukuran data test dalam persentase dari session
		$testSize = ($request->session()->get('test_size') ?? 30) / 100;
		$randomSeed = 0;

		// Ambil semua data preprocessing dari DB
		$data = DB::table('preprocessing')
			->select('id', 'lemmatized', 'label')
			->get()
			->toArray();

		// Set seed dan acak data untuk reproducibility
		srand($randomSeed);
		shuffle($data);

		// Hitung jumlah data dan batas data uji
		$totalData = count($data);
		$testCount = (int) round($totalData * $testSize);

		// Pisahkan data uji dan data latih
		$testData = array_slice($data, 0, $testCount);
		$trainData = array_slice($data, $testCount);

		// Inisialisasi array untuk label aktual dan frekuensi term dalam data uji
		$labelsActual = [];
		$tfTest = [];

		foreach ($testData as $item) {
			$tokens = explode(' ', $item->lemmatized);
			$tfTest[$item->id] = array_count_values($tokens);
			$labelsActual[$item->id] = $item->label;
		}

		// Hitung frekuensi kelas dan frekuensi kata per kelas di data latih
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

		// Total dokumen latih
		$totalTrainDocs = count($trainData);

		// Hitung prior probability tiap kelas
		$classProb = [];
		foreach ($classCounts as $class => $count) {
			$classProb[$class] = $count / $totalTrainDocs;
		}

		// Buat vocab global unik dari seluruh kelas dan kata
		$vocab = [];
		foreach ($wordFreq as $freqs) {
			$vocab = array_merge($vocab, array_keys($freqs));
		}
		$vocab = array_unique($vocab);
		$vocabSize = count($vocab);

		// Hitung conditional probability tiap kata per kelas dengan smoothing Laplace
		$condProb = [];
		foreach ($wordFreq as $class => $freqs) {
			$totalWordsInClass = array_sum($freqs);
			foreach ($vocab as $word) {
				$countWord = $freqs[$word] ?? 0;
				$condProb[$class][$word] = ($countWord + 1) / ($totalWordsInClass + $vocabSize);
			}
		}

		// Prediksi kelas untuk data uji
		$labelsPredicted = [];
		foreach ($tfTest as $docId => $terms) {
			$scores = [];
			foreach ($classProb as $class => $prior) {
				$scores[$class] = log($prior);
				$totalWordsInClass = array_sum($wordFreq[$class] ?? []);
				// Karena condProb sudah lengkap untuk vocab, cukup pakai condProb langsung
				foreach ($terms as $word => $count) {
					$prob = $condProb[$class][$word] ?? (1 / ($totalWordsInClass + $vocabSize));
					$scores[$class] += $count * log($prob);
				}
			}
			// Ambil kelas dengan skor tertinggi
			$labelsPredicted[$docId] = array_keys($scores, max($scores))[0];
		}

		// Kelas unik
		$classes = array_values(array_unique(array_map(fn($d) => $d->label, $data)));

		// Inisialisasi confusion matrix dengan 0
		$confMatrix = [];
		foreach ($classes as $actual) {
			$confMatrix[$actual] = array_fill_keys($classes, 0);
		}

		// Hitung confusion matrix
		foreach ($labelsActual as $id => $actualLabel) {
			$predictedLabel = $labelsPredicted[$id] ?? null;
			if ($predictedLabel !== null) {
				$confMatrix[$actualLabel][$predictedLabel]++;
			}
		}

		// Hitung metrik per kelas
		$metrics = [];
		$totalTest = count($testData);

		foreach ($classes as $class) {
			$TP = $confMatrix[$class][$class];
			$FP = array_sum(array_column($confMatrix, $class)) - $TP;
			$FN = array_sum($confMatrix[$class]) - $TP;
			$TN = $totalTest - $TP - $FP - $FN;

			$precision = ($TP + $FP) > 0 ? $TP / ($TP + $FP) : 0;
			$recall = ($TP + $FN) > 0 ? $TP / ($TP + $FN) : 0;
			$f1 = ($precision + $recall) > 0 ? 2 * ($precision * $recall) / ($precision + $recall) : 0;

			$metrics[$class] = [
				'precision' => round($precision * 100, 2),
				'recall' => round($recall * 100, 2),
				'f1_score' => round($f1 * 100, 2),
			];
		}

		// Hitung akurasi keseluruhan
		$correct = 0;
		foreach ($labelsActual as $id => $actual) {
			if (($labelsPredicted[$id] ?? null) === $actual) {
				$correct++;
			}
		}
		$accuracy = $totalTest > 0 ? $correct / $totalTest : 0;

		return view('result.conf-matrix', compact(
			'confMatrix',
			'classes',
			'metrics',
			'accuracy',
			'title',
			'testSize'
		));
	}
}

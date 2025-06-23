<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

class PrintController extends Controller
{
	public function preprocessing()
	{
		$data['title'] = '2. Hasil akhir preprosesing';
		$data['preprosesing'] = DB::table('preprocessing')
			->select('lemmatized', 'label')
			->where('created_by', Auth::user()->id)
			->get();

		ini_set('memory_limit', '512M');
		set_time_limit(300);

		// $pdf = Pdf::loadView('print.preprosesing', $data)->setPaper('A4', 'landscape');

		// return $pdf->stream('preprosesing' . time() . '.pdf');


		$html = View::make('print.preprosesing', $data)->render();

		$filename = 'preprosesing.doc';

		// Set headers agar browser mengunduh sebagai Word
		return response($html)
			->header('Content-Type', 'application/msword')
			->header('Content-Disposition', 'attachment;filename="' . $filename . '"');
	}

	public function trainData()
	{
		$data['title'] = '3. Data train';
		$data['train'] = DB::table('train_data')
			->select('lemmatized', 'label')
			->where('created_by', Auth::user()->id)
			->get();

		ini_set('memory_limit', '512M');
		set_time_limit(300);

		// $pdf = Pdf::loadView('print.train', $data)->setPaper('A4', 'landscape');

		// return $pdf->stream('train' . time() . '.pdf');

		$html = View::make('print.train', $data)->render();

		$filename = 'train.doc';

		// Set headers agar browser mengunduh sebagai Word
		return response($html)
			->header('Content-Type', 'application/msword')
			->header('Content-Disposition', 'attachment;filename="' . $filename . '"');
	}

	public function predictedDetails()
	{
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

		// $pdf = Pdf::loadView('print.predicted', [
		// 	'predictedDetails' => $predictedDetails
		// ])->setPaper('A4', 'portrait');

		// return $pdf->stream('hasil_prediksi.pdf');

		$html = View::make('print.predicted', [
			'predictedDetails' => $predictedDetails
		])->render();

		$filename = 'hasil_prediksi.doc';

		// Set headers agar browser mengunduh sebagai Word
		return response($html)
			->header('Content-Type', 'application/msword')
			->header('Content-Disposition', 'attachment;filename="' . $filename . '"');
	}
}

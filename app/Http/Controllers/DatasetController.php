<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DatasetController extends Controller
{
	public function index()
	{
		$title = 'Dataset';
		$dataset = DB::table('datasets')->paginate(10);

		return view('dataset.index', compact('title', 'dataset'));
	}

	public function importCSV()
	{
		$title = 'Import Dataset';
		return view('dataset.import', compact('title'));
	}

	public function importData(Request $request)
	{
		$request->validate([
			'file' => 'required|mimes:csv',
		]);

		$file = $request->file('file');
		$handle = fopen($file->path(), 'r');

		fgetcsv($handle);
		$chunksize = 25;

		while (!feof($handle)) {
			$chunkdata = [];

			for ($i = 0; $i < $chunksize; $i++) {
				$data = fgetcsv($handle);

				if ($data === false) {
					break;
				}

				$chunkdata[] = $data;
			}

			$this->getChunkData($chunkdata);
		}
		fclose($handle);

		return redirect()->route('dataset.index')->with('success', 'Dataset import successfully !');
	}

	public function getChunkData($chunkdata)
	{
		foreach ($chunkdata as $column) {
			$reviewId = $column[0];
			$userName = $column[1];
			$userImage = $column[2];
			$content = $column[3];
			$score = $column[4];
			$thumbUpCount = $column[5];
			$reviewCreatedVersion = $column[6];
			$at = $column[7];
			$replyContent = $column[8];
			$repliedAt = $column[9];
			$appVersion = $column[10];

			$dataset = new Dataset();
			$dataset->reviewId = $reviewId;
			$dataset->userName = $userName;
			$dataset->userImage = $userImage;
			$dataset->content = $content;
			$dataset->score = $score;
			$dataset->thumbUpCount = $thumbUpCount;
			$dataset->reviewCreatedVersion = $reviewCreatedVersion;
			$dataset->at = $at;
			$dataset->replyContent = $replyContent;
			$dataset->repliedAt = $repliedAt;
			$dataset->appVersion = $appVersion;
			$dataset->save();
		}
	}

	public function deleteAll()
	{
		$datasetCount = DB::table('datasets')->count();
		if ($datasetCount == 0) {
			return redirect()->back()->with('info', 'Dataset is already empty!');
		}

		DB::table('datasets')->truncate();
		return redirect()->route('dataset.index')->with('success', 'Dataset deleted successfully!');
	}
}

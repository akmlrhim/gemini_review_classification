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
		$dataset = DB::table('datasets')
			->orderBy('created_at', 'asc')
			->paginate(10);

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
			'file' => 'required',
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

		return redirect()->route('dataset.index')->with('success', 'Dataset import successfully');
	}

	public function getChunkData($chunkdata)
	{
		foreach ($chunkdata as $column) {
			$review = $column[0];

			$dataset = new Dataset();
			$dataset->review = $review;
			$dataset->save();
		}
	}

	public function deleteAll()
	{
		DB::table('datasets')->truncate();
		return redirect()->route('dataset.index')->with('success', 'Dataset deleted successfully');
	}
}

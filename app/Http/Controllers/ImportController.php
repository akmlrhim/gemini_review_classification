<?php

namespace App\Http\Controllers;

use App\Models\Preprocessing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
	public function index()
	{
		$title = 'Import Data';
		return view('import.index', compact('title'));
	}

	public function store(Request $request)
	{
		$request->validate([
			'file' => 'required|mimes:csv|max:2048',
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

		return redirect()->route('preprocessing.index')->with('success', 'Import successfully');
	}

	public function getChunkData($chunkdata)
	{
		foreach ($chunkdata as $column) {
			$case_folding = $column[0];
			$tokenize = $column[1];
			$stopword = $column[2];
			$lemmatized = $column[3];
			$label = $column[4];

			$preprocessing = new Preprocessing();
			$preprocessing->case_folding = $case_folding;
			$preprocessing->tokenize = $tokenize;
			$preprocessing->stopword = $stopword;
			$preprocessing->lemmatized = $lemmatized;
			$preprocessing->label = $label;
			$preprocessing->created_by = Auth::user()->id;
			$preprocessing->save();
		}
	}
}

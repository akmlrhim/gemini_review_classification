<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Preprocessing;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
			'file' => 'required|file|mimes:csv,txt|max:51200',
		]);

		$file = $request->file('file');
		$filename = uniqid('import_') . '.' . $file->getClientOriginalExtension();

		$filePath = 'uploads/' . $filename;
		Storage::disk('public')->putFileAs('uploads/', $file, $filename);

		$absolutePath = storage_path('app/public/' . $filePath);

		if (!file_exists($absolutePath)) {
			return back()->withErrors(['error' => 'File gagal disimpan.']);
		}

		$handle = fopen($absolutePath, 'r');
		if (!$handle) {
			return back()->withErrors(['error' => 'Gagal membuka file.']);
		}

		$header = fgetcsv($handle);
		if ($header === false) {
			fclose($handle);
			return back()->withErrors(['error' => 'File kosong atau tidak valid.']);
		}

		$chunksize = 25;
		while (!feof($handle)) {
			$chunkdata = [];

			for ($i = 0; $i < $chunksize && !feof($handle); $i++) {
				$data = fgetcsv($handle);
				if ($data === false || array_filter($data) === []) {
					continue;
				}
				$chunkdata[] = $data;
			}

			if (!empty($chunkdata)) {
				$this->getChunkData($chunkdata);
			}
		}

		fclose($handle);

		return redirect()->route('preprocessing.index')->with('success', 'Import berhasil.');
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

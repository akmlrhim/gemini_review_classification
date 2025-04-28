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
}

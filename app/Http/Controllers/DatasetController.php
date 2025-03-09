<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DatasetController extends Controller
{
	public function index()
	{
		$title = 'Dataset';
		// $dataset = DB::table('dataset')->get();

		return view('dataset.index', compact('title'));
	}
}

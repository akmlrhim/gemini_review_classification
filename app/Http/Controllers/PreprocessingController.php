<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PreprocessingController extends Controller
{
	public function index()
	{
		$title = 'Preprocessing';
		return view('preprocessing.index', compact('title'));
	}

	public function label()
	{
		$title = 'Labeling';
		return view('preprocessing.label', compact('title'));
	}
}

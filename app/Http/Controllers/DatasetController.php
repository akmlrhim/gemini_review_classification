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

	public function contents()
	{
		$title = "Only column content on datasets";
		$contents = DB::table('datasets')
			->select('id', 'content')
			->paginate(20);

		return view('dataset.contents', compact('title', 'contents'));
	}

	public function search(Request $request)
	{
		$title = 'Dataset';
		$search = $request->search;
		$dataset = DB::table('datasets')
			->where('content', 'like', '%' . $search . '%')
			->paginate(10)
			->appends(['search' => $search]);;

		return view('dataset.index', compact('title', 'dataset'));
	}

	public function deleteAll()
	{
		DB::table('datasets')->truncate();
		return redirect()->route('dataset.index')->with('success', 'Semua data dihapus!');
	}
}

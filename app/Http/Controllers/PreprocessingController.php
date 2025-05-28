<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PreprocessingController extends Controller
{
	public function index()
	{
		$title = 'Preprocessing';
		$prepro = DB::table('preprocessing')
			->select('id', 'case_folding', 'tokenize', 'stopword', 'lemmatized', 'label')
			->where('created_by', Auth::user()->id)
			->paginate(30);

		return view('preprocessing.index', compact(
			'title',
			'prepro',
		));
	}

	public function search(Request $request)
	{
		$title = 'Search';
		$search = $request->search;
		$prepro = DB::table('preprocessing')
			->where('case_folding', 'like', '%' . $search . '%')
			->where('created_by', Auth::user()->id)
			->paginate(10)
			->appends(['search' => $search]);

		return view('preprocessing.index', compact(
			'title',
			'prepro',
		));
	}

	public function deleteAll()
	{
		DB::table('preprocessing')
			->where('created_by', Auth::user()->id)
			->delete();

		return redirect()->route('preprocessing.index')->with('success', 'Data berhasil dihapus');
	}

	public function labeling()
	{
		$title = "Labeling";
		$label = DB::table('preprocessing')
			->select('id', 'case_folding', 'label')
			->where('created_by', Auth::user()->id)
			->paginate(20);

		return view('preprocessing.label.index', compact('title', 'label'));
	}

	public function editLabel(int $id)
	{
		$title = "Edit Label";
		$label = DB::table('preprocessing')->find($id);

		return view('preprocessing.label.edit', compact('label', 'title'));
	}

	public function updateLabel(Request $request, int $id)
	{
		$request->validate(['label' => 'required']);

		DB::table('preprocessing')
			->where('id', $id)
			->update(['label' => $request->label]);

		return redirect()->route('preprocessing.label')->with('success', 'Label berhasil diupdate');
	}

	public function calculateBagOfWords()
	{
		$title = 'Bag of Words';

		$data = DB::table('preprocessing')
			->select('id', 'lemmatized')
			->where('created_by', Auth::user()->id)
			->paginate(2)
			->appends(request()->query());

		$tf = [];

		foreach ($data as $d) {
			$tokens = explode(' ', $d->lemmatized);
			$tf[$d->id] = array_count_values($tokens);
		}

		return view('preprocessing.bag-of-words', compact(
			'title',
			'tf',
			'data'
		));
	}
}

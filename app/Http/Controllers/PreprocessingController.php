<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

		DB::table('train_data')
			->where('created_by', Auth::user()->id)
			->delete();

		DB::table('test_data')
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

	public function splitData(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'train_data' => 'required|numeric|min:1|max:90',
		]);

		if ($validator->fails()) {
			return redirect()->back()
				->withErrors($validator)
				->withInput()
				->with('showModal', true);
		}

		$trainDataRatio = $request->train_data / 100;

		$preprocessedData = DB::table('preprocessing')
			->where('created_by', Auth::id())
			->get()
			->toArray();

		if (count($preprocessedData) < 2) {
			return redirect()->back()->with('error', 'Jumlah data kurang dari 2, tidak bisa dibagi.');
		}

		// Acak data
		srand(10);
		shuffle($preprocessedData);

		$total = count($preprocessedData);
		$trainCount = round($total * $trainDataRatio);
		$trainData = array_slice($preprocessedData, 0, $trainCount);
		$testData = array_slice($preprocessedData, $trainCount);

		DB::table('train_data')->where('created_by', Auth::id())->delete();
		DB::table('test_data')->where('created_by', Auth::id())->delete();

		foreach ($trainData as $item) {
			DB::table('train_data')->insert([
				'lemmatized' => $item->lemmatized,
				'label' => $item->label,
				'created_by' => $item->created_by,
				'created_at' => now(),
				'updated_at' => now(),
			]);
		}

		// Masukkan data testing
		foreach ($testData as $item) {
			DB::table('test_data')->insert([
				'lemmatized' => $item->lemmatized,
				'label' => $item->label,
				'created_by' => $item->created_by,
				'created_at' => now(),
				'updated_at' => now(),
			]);
		}

		return redirect()->back()->with('success', 'Data berhasil di-split menjadi training dan testing.');
	}
}

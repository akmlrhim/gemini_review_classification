<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PreprocessingController extends Controller
{
	public function index()
	{
		$title = 'Preprocessing';
		$prepro = DB::table('preprocessing')->paginate(30);

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
			->paginate(10)
			->appends(['search' => $search]);

		return view('preprocessing.index', compact(
			'title',
			'prepro',
		));
	}

	public function deleteAll()
	{
		DB::table('preprocessing')->truncate();
		return redirect()->route('preprocessing.index')->with('success', 'Data berhasil dihapus');
	}

	public function calculateTfIdf()
	{
		$title = 'TF-IDF';
		$data = DB::table('preprocessing')->select('id', 'lemmatized')->get();

		$tf = [];
		$df = [];
		$idf = [];
		$tf_idf = [];
		$total_doc = count($data);

		$hasil_tf_idf = [];

		foreach ($data as $d) { // loop stiap data
			$tokens = explode(' ', $d->lemmatized); // token dengan spasi
			$tf[$d->id] = array_count_values($tokens); //htung frekuensi kemunculan kata

			foreach (array_unique($tokens) as $token) { //htung jlh dokumen yang mengandung stiap token (df)
				//jika token belum ada di df, inisialisasi dengan 0
				if (!isset($df[$token])) {
					$df[$token] = 0;
				}
				//

				//+1 jika token ada di df
				$df[$token]++;
			}
		}

		//loop setiap kata beserta frekuensi kemunculannya yang mengandung kata tsb
		foreach ($df as $token => $doc_count) {

			//hitung nilai idf (inverse document frequency) utk stiap kata/token
			// rumus idf = log(total dokumen / jumlah dokumen yang mengandung kata/token)
			$idf[$token] = log($total_doc / $doc_count);
		}

		// loop melalui setiap dokumen dan daftar kata didalamnya
		foreach ($tf as $doc_id => $terms) {

			// loop setiap kata 
			foreach ($terms as $kata => $fq) {
				//hitung tf_idf untuk setiap kata dalam dokumen
				//tf adalah frekuensi kemunculan kata di dokumen
				//idf adalah ukuran seberapa jarang kata muncul di seluruh dokumen
				//jika idf tidak ditemukan, gunakan 0 sebagai default
				$tf_idf[$doc_id][$kata] = $fq * ($idf[$kata] ?? 0);
			}
		}

		return view('preprocessing.tf-idf', compact(
			'title',
			'tf_idf',
		));
	}

	public function labeling()
	{
		$title = "Labeling";
		$isLabel = DB::table('preprocessing')
			->select('id', 'case_folding', 'label')->paginate(20);

		$hasLabelled = DB::table('preprocessing')
			->whereNotNull('label')
			->get();

		return view('preprocessing.label.index', compact('title', 'isLabel', 'hasLabelled'));
	}

	public function updateLabel(Request $request, int $id)
	{
		$request->validate(['label' => 'required']);

		DB::table('preprocessing')
			->where('id', $id)
			->update(['label' => $request->label]);

		return redirect()->route('preprocessing.label')->with('success', 'Label berhasil diupdate');
	}

	public function editLabel(int $id)
	{
		$title = "Edit Label";
		$label = DB::table('preprocessing')->find($id);

		return view('preprocessing.label.edit', compact('label', 'title'));
	}
}

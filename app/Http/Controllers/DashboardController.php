<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
	public function index()
	{
		$title = 'Dashboard';
		$jumlahDataset = DB::table('datasets')->count();

		$cleanedData = DB::table('preprocessing')
			->select('id', 'cleaned')
			->count();

		$label = DB::table('preprocessing')
			->select('label', DB::raw('count(*) as total'))
			->groupBy('label')
			->orderBy('total', 'desc')
			->get();

		return view('dashboard.index', compact(
			'title',
			'jumlahDataset',
			'cleanedData',
			'label',
		));
	}

	public function reset()
	{
		DB::table('datasets')->truncate();
		DB::table('preprocessing')->truncate();

		return redirect()->route('dashboard.index')->with('success', 'Berhasil!');
	}
}

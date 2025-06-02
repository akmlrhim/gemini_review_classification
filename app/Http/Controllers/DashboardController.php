<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
	public function index()
	{
		$title = 'Dashboard';
		$trainData = DB::table('train_data')
			->where('created_by', Auth::user()->id)
			->count();

		$testData = DB::table('test_data')
			->where('created_by', Auth::user()->id)
			->count();

		$totalData = DB::table('preprocessing')
			->where('created_by', Auth::user()->id)
			->count();

		$label = DB::table('preprocessing')
			->select('label', DB::raw('count(*) as total'))
			->groupBy('label')
			->orderBy('total', 'desc')
			->get();

		return view('dashboard.index', compact(
			'title',
			'trainData',
			'testData',
			'totalData',
			'label'
		));
	}
}

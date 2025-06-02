<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
	public function index()
	{
		$title = 'Dashboard';

		return view('dashboard.index', compact(
			'title',
		));
	}

	public function reset()
	{
		DB::table('preprocessing')->truncate();
		return redirect()->route('dashboard.index')->with('success', 'Berhasil!');
	}
}

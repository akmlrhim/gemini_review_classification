<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HasSplit
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		$hasTrainData = DB::table('train_data')
			->where('created_by', Auth::user()->id)
			->exists();

		$hasTestData = DB::table('test_data')
			->where('created_by', Auth::user()->id)
			->exists();

		if (!$hasTrainData || !$hasTestData) {
			return redirect()->back()->with('error', 'Anda belum melakukan pembagian data.');
		}

		return $next($request);
	}
}

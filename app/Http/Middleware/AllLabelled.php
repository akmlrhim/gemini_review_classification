<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AllLabelled
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		$isAllLabelled = DB::table('preprocessing')
			->whereNull('label')
			->exists();

		if ($isAllLabelled) {
			return redirect()->back()->with('error', 'Silahkan beri label pada semua data terlebih dahulu');
		}

		return $next($request);
	}
}

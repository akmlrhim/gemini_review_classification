<?php

use App\Http\Middleware\AllLabelled;
use App\Http\Middleware\AuthCheck;
use App\Http\Middleware\IsAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
	->withRouting(
		web: __DIR__ . '/../routes/web.php',
		commands: __DIR__ . '/../routes/console.php',
		health: '/up',
	)
	->withMiddleware(function (Middleware $middleware) {
		$middleware->alias([
			'auth' => AuthCheck::class,
			'labelled' => AllLabelled::class,
			'admin' => IsAdmin::class
		]);
	})
	->withExceptions(function (Exceptions $exceptions) {
		//
	})->create();

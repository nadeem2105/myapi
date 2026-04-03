<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // 👈 ADD THIS LINE
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function ($middleware) {
    $middleware->redirectGuestsTo(function ($request) {
        if ($request->is('api/*')) {
            abort(response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401));
        }

        return route('login');
    });
})
   ->withExceptions(function (Exceptions $exceptions) {
    $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, \Illuminate\Http\Request $request) {
        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
    });
})->create();

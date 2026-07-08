<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    api: __DIR__ . '/../routes/api.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware) {
    $middleware->trustProxies(at: '*', headers: Request::HEADER_X_FORWARDED_FOR
      | Request::HEADER_X_FORWARDED_HOST
      | Request::HEADER_X_FORWARDED_PORT
      | Request::HEADER_X_FORWARDED_PROTO
      | Request::HEADER_X_FORWARDED_AWS_ELB);

    $middleware->alias([
      'admin' => \App\Http\Middleware\EnsureAdmin::class,
    ]);
  })
  ->withExceptions(function (Exceptions $exceptions) {
    $exceptions->shouldRenderJsonWhen(function ($request, \Throwable $e) {
      return $request->is('api/*') || $request->expectsJson();
    });

    $exceptions->render(function (AuthenticationException $e, $request) {
      if ($request->is('api/*') || $request->expectsJson()) {
        return response()->json([
          'message' => 'Unauthenticated.',
        ], 401);
      }
    });

    $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, $request) {
      if ($request->is('api/*') || $request->expectsJson()) {
        return response()->json([
          'message' => 'The uploaded files are too large. Please upload files smaller than 10MB.',
        ], 413);
      }

      return back()->withInput()->with('error', 'The uploaded files are too large. Please upload files smaller than 10MB.');
    });
  })->create();

<?php

use App\Http\Api\Exceptions\Handlers\GlobalExceptionHandler;
use App\Http\Middleware\ApiLocalizationMiddleware;
use App\Http\Middleware\LocalizationMiddleware;
use App\Http\Middleware\SetEmployeeGuard;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/store-api.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            LocalizationMiddleware::class,
        ]);

        $middleware->api(prepend: [
            ApiLocalizationMiddleware::class,
        ]);

        $middleware->alias([
            'permission' => PermissionMiddleware::class,
            'guard.employee' => SetEmployeeGuard::class,
        ]);

        $middleware->redirectTo(
            guests: function ($request) {
                return route('crm.login');
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, Request $request) {
            return app(GlobalExceptionHandler::class)->render($e, $request);
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => __('Not Found')], 404);
            }

            return response()->view('errors.404', [], 404);
        });

        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => __('Forbidden')], 403);
            }

            return response()->view('errors.403', [], 403);
        });

        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => __('Resource not found')], 404);
            }

            return response()->view('errors.404', [], 404);
        });

        $exceptions->render(function (HttpException $e, Request $request) {
            $statusCode = $e->getStatusCode();

            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage() ?: __('Error')], $statusCode);
            }

            if (view()->exists("errors.{$statusCode}")) {
                return response()->view("errors.{$statusCode}", ['exception' => $e], $statusCode);
            }
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*') || $request->is('erp/*')) {
                return response()->json(['message' => __('Unauthenticated')], 401);
            }

            return redirect()->guest(route('crm.login'));
        });
    })->create();

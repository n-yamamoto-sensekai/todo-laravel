<?php

use App\Exceptions\TodoException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        $exceptions->render(function (TodoException $e, Request $request) {
            
            // ログ
            Log::warning('TODO業務例外が発生しました', [
                'message' => $e->getMessage(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ]);

            // JSONリクエストなら 422 JSONレスポンスを返す
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], 422);
            }

            // 通常リクエストなら前の画面に戻ってエラーを表示できるようにする）
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'todo' => $e->getMessage(),
                ]);
        });

    })->create();

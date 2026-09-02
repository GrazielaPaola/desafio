<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {})
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $registroNaoEncontrado = fn (Request $request) => $request->is('api/*')
            ? response()->json(['message' => 'Registro não encontrado.'], Response::HTTP_NOT_FOUND)
            : null;

        $exceptions->render(fn (ModelNotFoundException $exception, Request $request) => $registroNaoEncontrado($request));
        $exceptions->render(fn (NotFoundHttpException $exception, Request $request) => $registroNaoEncontrado($request));
    })->create();

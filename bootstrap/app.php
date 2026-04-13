<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);
        $middleware->alias([
            'admin'    => \App\Http\Middleware\IsAdministrator::class,
            'webadmin' => \App\Http\Middleware\IsWebAdmin::class,
            'partner'  => \App\Http\Middleware\IsPartner::class,
            'gm'       => \App\Http\Middleware\IsGamemaster::class,
            'feature'  => \App\Http\Middleware\PanelFeatureToggle::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

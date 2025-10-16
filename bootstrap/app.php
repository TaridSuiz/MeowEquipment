<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ถ้าโปรเจกต์ใช้ SweetAlert
        $middleware->web(append: [
            \RealRashid\SweetAlert\ToSweetAlert::class,
        ]);

        // สำคัญ: alias 'admin' ไว้ใช้ในกลุ่มเส้นทางหลังบ้าน
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();

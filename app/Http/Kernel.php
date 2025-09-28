<?php  

protected $middlewareGroups = [
    'web' => [
        // ...
        \RealRashid\SweetAlert\ToSweetAlert::class,
        'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
    ],
    // ...
];

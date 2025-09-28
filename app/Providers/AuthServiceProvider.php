<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [ /* หากมี Policy class map ที่นี่ */ ];

    public function boot(): void
    {
        $this->registerPolicies();

    
        Gate::define('admin-only', fn($user) => $user->role === 'admin');

        // แก้โปรไฟล์: เจ้าของหรือแอดมิน
        Gate::define('edit-user', function($auth, $user){
            return $auth->role === 'admin' || $auth->user_id === $user->user_id;
        });

        // ลบรีวิว: เจ้าของหรือแอดมิน
        Gate::define('delete-review', function($auth, $review){
            return $auth->role === 'admin' || $auth->user_id === $review->user_id;
        });
    }
}

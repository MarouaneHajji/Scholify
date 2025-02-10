<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Grade;
use App\Policies\GradePolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // ... other policies ...
        Grade::class => GradePolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
} 
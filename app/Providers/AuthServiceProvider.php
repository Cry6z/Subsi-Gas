<?php

namespace App\Providers;

use App\Models\Location;
use App\Policies\LocationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Location::class => LocationPolicy::class,
    ];
}

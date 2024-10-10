<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\GLSApiService;
use Illuminate\Support\Facades\Gate;
use App\Models\Parcel;
use App\Policies\ParcelPolicy;

class GLSApiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(GLSApiService::class, function ($app) {
            return new GLSApiService();
        });
    }

    public function boot()
{
    Gate::policy(Parcel::class, ParcelPolicy::class);

    // Dodajte ove definicije Gate-a
    Gate::define('viewAny', [ParcelPolicy::class, 'viewAny']);
    Gate::define('view', [ParcelPolicy::class, 'view']);
    Gate::define('create', [ParcelPolicy::class, 'create']);
    Gate::define('update', [ParcelPolicy::class, 'update']);
    Gate::define('delete', [ParcelPolicy::class, 'delete']);
    Gate::define('viewOnly', [ParcelPolicy::class, 'viewOnly']);
}
}
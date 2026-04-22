<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\CaseRecords;
use App\Models\User;
use App\Policies\CaseRecordsPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Override;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Model::unguard();
        Model::shouldBeStrict();
        Model::automaticallyEagerLoadRelationships();

        Gate::define('admin-access', fn (User $user) => $user->role === 'admin');
    }

    protected $policies = [
        CaseRecords::class => CaseRecordsPolicy::class,
    ];
}

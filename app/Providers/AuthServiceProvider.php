<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Artwork;
use App\Models\Resale;
use App\Models\Ownership;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Certificate;
use App\Policies\ArtworkPolicy;
use App\Policies\ResalePolicy;
use App\Policies\OwnershipPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\TransactionPolicy;
use App\Policies\CertificatePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Artwork::class => ArtworkPolicy::class,
        Resale::class => ResalePolicy::class,
        Ownership::class => OwnershipPolicy::class,
        Category::class => CategoryPolicy::class,
        Transaction::class => TransactionPolicy::class,
        Certificate::class => CertificatePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Implicit admin check - admin can do everything
        Gate::before(function ($user, $ability) {
            return $user->isAdmin() ? true : null;
        });
    }
}

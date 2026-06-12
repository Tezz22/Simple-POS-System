<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\Contracts\RoleRepositoryInterface;
use App\Repository\Eloquent\RoleRepository;
use App\Repository\Contracts\UserRepositoryInterface;
use App\Repository\Eloquent\UserRepository;
use App\Repository\Contracts\CategoryRepositoryInterface;
use App\Repository\Eloquent\CategoryRepository;
use App\Repository\Contracts\ProductRepositoryInterface;
use App\Repository\Eloquent\ProductRepository;
use App\Repository\Contracts\TransactionRepositoryInterface;
use App\Repository\Eloquent\TransactionRepository;
use App\Repository\Contracts\TransactionItemRepositoryInterface;
use App\Repository\Eloquent\TransactionItemRepository;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind Role Repository
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        
        // Bind User Repository
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(TransactionItemRepositoryInterface::class, TransactionItemRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\ColorRepository;
use App\Repositories\Interface\CategoryRepositoryInterface;
use App\Repositories\Interface\ColorRepositoryInterface;
use App\Repositories\Interface\MaterialRepositoryInterface;
use App\Repositories\Interface\ProductReceiptRepositoryInterface;
use App\Repositories\Interface\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\Interface\ProductRepositoryInterface;
use App\Repositories\Interface\RoleRepositoryInterface;
use App\Repositories\Interface\ShelfRepositoryInterface;
use App\Repositories\Interface\WarehouseRepositoryInterface;
use App\Repositories\ShelfRepository;
use App\Repositories\MaterialRepository;
use App\Repositories\ProductReceiptRepository;
use App\Repositories\ProductRepository;
use App\Repositories\RoleRepository;
use App\Repositories\WarehouseRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        $this->app->bind(WarehouseRepositoryInterface::class, WarehouseRepository::class);

        $this->app->bind(ColorRepositoryInterface::class, ColorRepository::class);

        $this->app->bind(ShelfRepositoryInterface::class, ShelfRepository::class);

        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);

        $this->app->bind(MaterialRepositoryInterface::class, MaterialRepository::class);

        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);

        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);

        $this->app->bind(ProductReceiptRepositoryInterface::class, ProductReceiptRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

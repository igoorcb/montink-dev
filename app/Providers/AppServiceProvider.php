<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Repositories\ProductRepositoryInterface;
use App\Infrastructure\Repositories\ProductRepository;
use App\Domain\Repositories\OrderRepositoryInterface;
use App\Infrastructure\Repositories\OrderRepository;
use App\Domain\Repositories\CouponRepositoryInterface;
use App\Infrastructure\Repositories\CouponRepository;
use App\Domain\Repositories\InventoryRepositoryInterface;
use App\Infrastructure\Repositories\InventoryRepository;
use App\Domain\Services\EmailServiceInterface;
use App\Infrastructure\Services\EmailService;
use App\Domain\Services\AddressServiceInterface;
use App\Infrastructure\Services\ViaCepService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(CouponRepositoryInterface::class, CouponRepository::class);
        $this->app->bind(InventoryRepositoryInterface::class, InventoryRepository::class);
        $this->app->bind(EmailServiceInterface::class, EmailService::class);
        $this->app->bind(AddressServiceInterface::class, ViaCepService::class);
    }

    public function boot(): void
    {
        //
    }
}

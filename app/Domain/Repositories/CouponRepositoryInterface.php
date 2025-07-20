<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Coupon;

interface CouponRepositoryInterface
{
    public function findById(int $id): ?Coupon;
    public function findByCode(string $code): ?Coupon;
    public function findAll(): \Illuminate\Database\Eloquent\Collection;
    public function create(array $data): Coupon;
    public function update(Coupon $coupon, array $data): Coupon;
    public function delete(Coupon $coupon): bool;
    public function findActive(): \Illuminate\Database\Eloquent\Collection;
} 
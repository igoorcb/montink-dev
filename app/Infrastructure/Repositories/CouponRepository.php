<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Coupon;
use App\Domain\Repositories\CouponRepositoryInterface;

class CouponRepository implements CouponRepositoryInterface
{
    public function findById(int $id): ?Coupon
    {
        return Coupon::find($id);
    }

    public function findByCode(string $code): ?Coupon
    {
        return Coupon::where('code', $code)->first();
    }

    public function findAll(): \Illuminate\Database\Eloquent\Collection
    {
        return Coupon::all();
    }

    public function create(array $data): Coupon
    {
        return Coupon::create($data);
    }

    public function update(Coupon $coupon, array $data): Coupon
    {
        $coupon->update($data);
        return $coupon;
    }

    public function delete(Coupon $coupon): bool
    {
        return $coupon->delete();
    }

    public function findActive(): \Illuminate\Database\Eloquent\Collection
    {
        return Coupon::where('active', true)->get();
    }
} 
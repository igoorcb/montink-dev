<?php

namespace App\Application\Services;

use Illuminate\Support\Str;

class OrderNumberGeneratorService
{
    public function generate(): string
    {
        return 'ORD-' . strtoupper(Str::random(8));
    }
} 
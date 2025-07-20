<?php

namespace App\Domain\Services;

interface AddressServiceInterface
{
    public function getAddressByCep(string $cep): ?array;
    public function validateCep(string $cep): bool;
} 
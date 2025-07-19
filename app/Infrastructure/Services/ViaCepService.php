<?php

namespace App\Infrastructure\Services;

use App\Domain\Services\AddressServiceInterface;
use Illuminate\Support\Facades\Http;

class ViaCepService implements AddressServiceInterface
{
    private const BASE_URL = 'https://viacep.com.br/ws/';

    public function getAddressByCep(string $cep): ?array
    {
        $cep = preg_replace('/[^0-9]/', '', $cep);
        
        if (strlen($cep) !== 8) {
            return null;
        }

        try {
            $response = Http::get(self::BASE_URL . $cep . '/json/');
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (!isset($data['erro'])) {
                    return [
                        'cep' => $data['cep'],
                        'logradouro' => $data['logradouro'],
                        'bairro' => $data['bairro'],
                        'localidade' => $data['localidade'],
                        'uf' => $data['uf'],
                        'ibge' => $data['ibge'],
                        'ddd' => $data['ddd']
                    ];
                }
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }

    public function validateCep(string $cep): bool
    {
        $cep = preg_replace('/[^0-9]/', '', $cep);
        return strlen($cep) === 8;
    }
} 
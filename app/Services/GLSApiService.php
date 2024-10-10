<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GLSApiService
{
    protected string $baseUrl;
    protected string $username;
    protected array $password;

    public function __construct()
    {
        $this->baseUrl = config('gls.api_url');
        $this->username = config('gls.username');
        $this->password = $this->encodePassword(config('gls.password'));
    }

    protected function encodePassword(string $password): array
    {
        return array_values(unpack('C*', hash('sha512', $password, true)));
    }

    public function prepareLabels($parcels)
    {
        $payload = [
            'Username' => $this->username,
            'Password' => $this->password,
            'ParcelList' => $parcels
        ];
    
        Log::info('GLS API Request', ['payload' => $payload]);
    
        $response = Http::post($this->baseUrl . 'json/PrepareLabels', $payload);
    
        Log::info('GLS API Response', ['response' => $response->json()]);
    
        if ($response->successful()) {
            $data = $response->json();
            if (!empty($data['PrepareLabelsError'])) {
                Log::error('GLS API Prepare Labels Error', ['errors' => $data['PrepareLabelsError']]);
                throw new \Exception('Error preparing labels: ' . json_encode($data['PrepareLabelsError']));
            }
            return $data;
        }
    
        Log::error('GLS API Request Failed', ['status' => $response->status(), 'body' => $response->body()]);
        throw new \Exception('Failed to prepare labels: ' . $response->body());
    }

    public function getPrintedLabels(array $parcelIds, int $printPosition): array
    {
        $payload = [
            'Username' => $this->username,
            'Password' => $this->password,
            'ParcelIdList' => $parcelIds,
            'PrintPosition' => $printPosition,
            'ShowPrintDialog' => false
        ];

        Log::debug('GLS API Request', ['payload' => $payload]);

        $response = Http::post($this->baseUrl . 'json/GetPrintedLabels', $payload);

        Log::debug('GLS API Response', ['response' => $response->json()]);

        return $response->json();
    }
}
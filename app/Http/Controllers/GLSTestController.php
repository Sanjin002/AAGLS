<?php

namespace App\Http\Controllers;

use App\Services\GLSApiService;
use Illuminate\Http\Request;

class GLSTestController extends Controller
{
    protected $glsApiService;

    public function __construct(GLSApiService $glsApiService)
    {
        $this->glsApiService = $glsApiService;
    }

    public function testApi()
    {
        try {
            // Kreirajte testni paket
            $testParcel = [
                'ClientNumber' => config('gls.client_number'), // Dodajte ovo u config/gls.php
                'ClientReference' => 'TEST_' . time(),
                'CODAmount' => 0,
                'CODReference' => 'TEST_COD_REF',
                'Content' => 'Test Content',
                'Count' => 1,
                'DeliveryAddress' => [
                    'City' => 'Zagreb',
                    'ContactEmail' => 'test@example.com',
                    'ContactName' => 'Test Contact',
                    'ContactPhone' => '+385123456789',
                    'CountryIsoCode' => 'HR',
                    'HouseNumber' => '1',
                    'Name' => 'Test Delivery',
                    'Street' => 'Test Street',
                    'ZipCode' => '10000',
                ],
                'PickupAddress' => [
                    'City' => 'Zagreb',
                    'ContactEmail' => 'pickup@example.com',
                    'ContactName' => 'Pickup Contact',
                    'ContactPhone' => '+385987654321',
                    'CountryIsoCode' => 'HR',
                    'HouseNumber' => '2',
                    'Name' => 'Test Pickup',
                    'Street' => 'Pickup Street',
                    'ZipCode' => '10000',
                ],
                'PickupDate' => date('Y-m-d'),
            ];

            // Pozovite GLS API
            $response = $this->glsApiService->prepareLabels([$testParcel]);

            // Ispišite odgovor
            return response()->json([
                'success' => true,
                'message' => 'GLS API test successful',
                'response' => $response
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'GLS API test failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
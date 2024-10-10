<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Parcel;

class SQLToMySQLController extends Controller
{
    public function fetchAndStore(Request $request)
    {
        $date = $request->input('date', now()->toDateString());

        $sqlServerData = DB::connection('sqlsrv')->select("YOUR SQL QUERY HERE", [$date]);

        foreach ($sqlServerData as $data) {
            $correctedData = $this->correctData($data);
            
            Parcel::updateOrCreate(
                ['acKey' => $correctedData['acKey']],
                $correctedData
            );
        }

        return response()->json(['message' => 'Data fetched and stored successfully']);
    }

    private function correctData($data)
    {
        $corrected = [
            'ClientNumber' => $data->ClientNumber,  // Pretpostavljamo da ovo polje postoji
            'ClientReference' => $data->ackey,
            'Content' => $data->acNote ?? 'Default Content',
            'Count' => 1,  // Default vrijednost, prilagodite prema potrebi
            'CODAmount' => 0,  // Default vrijednost, prilagodite prema potrebi
            'DeliveryCity' => $data->acName ?? 'Unknown City',
            'DeliveryContactEmail' => $data->acPhone ?? null,
            'DeliveryContactName' => $data->acName2 ?? 'Unknown Name',
            'DeliveryContactPhone' => $data->acPhone ?? 'Unknown Phone',
            'DeliveryCountryIsoCode' => $this->mapCountryToIsoCode($data->acCountry ?? 'HR'),
            'DeliveryName' => preg_replace('/[<>&]/', '', $data->acName3 ?? $data->acName2 ?? 'Unknown Name'),
            'DeliveryStreet' => $data->acAddress ?? 'Unknown Address',
            'DeliveryZipCode' => preg_replace('/^[A-Z]+ - /', '', $data->acPost ?? 'Unknown Zip'),
            'acKey' => $data->ackey,
        ];

        // Dodatne korekcije možete dodati ovdje

        return $corrected;
    }

    private function mapCountryToIsoCode($country)
    {
        $countryMap = [
            'Hrvatska' => 'HR',
            'Slovenija' => 'SI',
            'Mađarska' => 'HU',
            'Rumunjska' => 'RO',
            'Češka' => 'CZ',
            'Slovačka' => 'SK',
            // Dodajte ostale mape po potrebi
        ];

        return $countryMap[$country] ?? 'HR';  // Default na HR ako ne pronađemo mapu
    }
}
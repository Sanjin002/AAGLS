<?php

namespace App\Http\Controllers;

use App\Models\Parcel;
use Illuminate\Http\Request;
use App\Services\GLSApiService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Department;
use Illuminate\Support\Facades\DB;


class ParcelController extends Controller
{
    protected $glsApiService;

    public function __construct(GLSApiService $glsApiService)
    {
        $this->glsApiService = $glsApiService;
    }

    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $parcels = Parcel::all();
        } else {
            $departmentIds = $user->departments->pluck('id');
            $parcels = Parcel::whereIn('department_id', $departmentIds)->get();
        }
        return view('parcels.index', compact('parcels'));
    }

    public function create()
    {
        $user = Auth::user();
        $departments = $user->hasRole('admin') ? Department::all() : $user->departments;
        return view('parcels.create', compact('departments'));
    }

    public function store(Request $request)
{
    $user = Auth::user();
    $validator = Validator::make($request->all(), [
        'ClientReference' => 'required',
        'Content' => 'required',
        'Count' => 'required|integer|min:1',
        'CODAmount' => 'required|numeric|min:0',
        'DeliveryCity' => 'required',
        'DeliveryContactName' => 'required',
        'DeliveryContactPhone' => 'required',
        'DeliveryCountryIsoCode' => 'required|size:2',
        'DeliveryName' => 'required',
        'DeliveryStreet' => 'required',
        'DeliveryZipCode' => 'required',
        'department_id' => 'required|exists:departments,id',
    ]);

    $validator->after(function ($validator) use ($user) {
        $data = $validator->getData();
        if (!$user->hasRole('admin') && !$user->departments->contains($data['department_id'])) {
            $validator->errors()->add('department_id', 'You do not have permission to assign this department.');
        }
        if (!in_array($data['DeliveryCountryIsoCode'], ['HR', 'SI', 'HU', 'RO', 'CZ', 'SK'])) {
            $validator->errors()->add('DeliveryCountryIsoCode', 'Invalid country code. Must be one of HR, SI, HU, RO, CZ, SK.');
        }
        if (!preg_match('/^\d{4,5}$/', $data['DeliveryZipCode'])) {
            $validator->errors()->add('DeliveryZipCode', 'Invalid zip code format. Must be 4 or 5 digits.');
        }
        if (preg_match('/[<>&]/', $data['DeliveryName'])) {
            $validator->errors()->add('DeliveryName', 'Name contains invalid characters. < > & are not allowed.');
        }
    });

    if ($validator->fails()) {
        return redirect()->route('parcels.create')
            ->withErrors($validator)
            ->withInput();
    }

    $validatedData = $validator->validated();
    $validatedData['DeliveryZipCode'] = preg_replace('/^[A-Z]+ - /', '', $validatedData['DeliveryZipCode']);
    $validatedData['DeliveryName'] = preg_replace('/[<>&]/', '', $validatedData['DeliveryName']);
    $validatedData['acKey'] = Str::uuid()->toString();
    $validatedData['user_id'] = $user->id;

    // Dohvaćamo odjel i postavljamo ClientNumber
    $department = Department::findOrFail($validatedData['department_id']);
    $validatedData['ClientNumber'] = $department->customer_id;

    Parcel::create($validatedData);

    return redirect()->route('parcels.index')
        ->with('success', 'Parcel created successfully.');
}

public function show(Parcel $parcel)
{
    $user = Auth::user();
    if (!$user->hasRole('admin') && !$user->departments->contains($parcel->department_id)) {
        abort(403, 'Unauthorized action.');
    }
    return view('parcels.show', compact('parcel'));
}

public function edit(Parcel $parcel)
{
    $user = Auth::user();
    if (!$user->hasRole('admin') && !$user->departments->contains($parcel->department_id)) {
        return redirect()->route('parcels.index')->with('error', 'You do not have permission to edit this parcel.');
    }
    $departments = $user->hasRole('admin') ? Department::all() : $user->departments;
    return view('parcels.edit', compact('parcel', 'departments'));
}

public function update(Request $request, Parcel $parcel)
{
    $user = Auth::user();
    $validator = Validator::make($request->all(), [
        'ClientReference' => 'required',
        'Content' => 'required',
        'Count' => 'required|integer|min:1',
        'CODAmount' => 'required|numeric|min:0',
        'DeliveryCity' => 'required',
        'DeliveryContactName' => 'required',
        'DeliveryContactPhone' => 'required',
        'DeliveryCountryIsoCode' => 'required|size:2',
        'DeliveryName' => 'required',
        'DeliveryStreet' => 'required',
        'DeliveryZipCode' => 'required',
        'department_id' => 'required|exists:departments,id',
    ]);

    $validator->after(function ($validator) use ($user) {
        $data = $validator->getData();
        if (!$user->hasRole('admin') && !$user->departments->contains($data['department_id'])) {
            $validator->errors()->add('department_id', 'You do not have permission to assign this department.');
        }
        if (!in_array($data['DeliveryCountryIsoCode'], ['HR', 'SI', 'HU', 'RO', 'CZ', 'SK'])) {
            $validator->errors()->add('DeliveryCountryIsoCode', 'Invalid country code. Must be one of HR, SI, HU, RO, CZ, SK.');
        }
        if (!preg_match('/^\d{4,5}$/', $data['DeliveryZipCode'])) {
            $validator->errors()->add('DeliveryZipCode', 'Invalid zip code format. Must be 4 or 5 digits.');
        }
        if (preg_match('/[<>&]/', $data['DeliveryName'])) {
            $validator->errors()->add('DeliveryName', 'Name contains invalid characters. < > & are not allowed.');
        }
    });

    if ($validator->fails()) {
        return redirect()->route('parcels.edit', $parcel)
            ->withErrors($validator)
            ->withInput();
    }

    $validatedData = $validator->validated();
    $validatedData['DeliveryZipCode'] = preg_replace('/^[A-Z]+ - /', '', $validatedData['DeliveryZipCode']);
    $validatedData['DeliveryName'] = preg_replace('/[<>&]/', '', $validatedData['DeliveryName']);

    // Dohvaćamo odjel i postavljamo ClientNumber
    $department = Department::findOrFail($validatedData['department_id']);
    $validatedData['ClientNumber'] = $department->customer_id;

    $parcel->update($validatedData);

    return redirect()->route('parcels.index')
        ->with('success', 'Parcel updated successfully.');
}

    public function destroy(Parcel $parcel)
    {
        $user = Auth::user();
        if (!$user->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        $parcel->delete();
        return redirect()->route('parcels.index')
            ->with('success', 'Parcel deleted successfully.');
    }

    public function bulkAction(Request $request)
{
    $user = Auth::user();
    $parcelIds = $request->input('parcel_ids', []);
    $action = $request->input('action');
    $printPosition = $request->input('print_position', 1);

    if (empty($parcelIds)) {
        return redirect()->route('parcels.index')->with('error', 'No parcels selected.');
    }

    if ($user->hasRole('admin')) {
        $parcels = Parcel::whereIn('id', $parcelIds)->get();
    } else {
        $departmentIds = $user->departments->pluck('id');
        $parcels = Parcel::whereIn('id', $parcelIds)
            ->whereIn('department_id', $departmentIds)
            ->get();
    }

    if ($parcels->isEmpty()) {
        return redirect()->route('parcels.index')->with('error', 'No authorized parcels selected.');
    }

    if ($action === 'send') {
        return $this->prepareForGLS($request);
    } elseif ($action === 'print') {
        return $this->bulkPrintLabels($parcels, $printPosition);
    }

    return redirect()->route('parcels.index')->with('error', 'Invalid action.');
}

    protected function bulkPrintLabels($parcels, $printPosition)
    {
        $glsParcelIds = $parcels->pluck('gls_parcel_id')->filter()->values()->toArray();

        if (empty($glsParcelIds)) {
            return redirect()->route('parcels.index')->with('error', 'No valid GLS parcel IDs found for printing.');
        }

        try {
            $response = $this->glsApiService->getPrintedLabels($glsParcelIds, $printPosition);

            if (!empty($response['GetPrintedLabelsErrorList'])) {
                return redirect()->route('parcels.index')->with('error', 'Error getting labels from GLS: ' . $response['GetPrintedLabelsErrorList'][0]['ErrorDescription']);
            }

            if (empty($response['Labels'])) {
                return redirect()->route('parcels.index')->with('error', 'No labels returned from GLS API');
            }

            $pdfContent = implode(array_map('chr', $response['Labels']));

            foreach ($parcels as $parcel) {
                $parcel->update([
                    'gls_response' => $pdfContent,
                    'label_expiry' => now()->addHours(12),
                    'status' => 'printed'
                ]);
            }

            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="bulk-parcels-labels.pdf"');
        } catch (\Exception $e) {
            return redirect()->route('parcels.index')->with('error', 'Error communicating with GLS API: ' . $e->getMessage());
        }
    }


    private function formatParcelForGLS(Parcel $parcel): array
{
    $department = $parcel->department;
    
    if (!$department) {
        throw new \Exception("Parcel does not have an associated department.");
    }

    return [
        'ClientNumber' => (int) $department->customer_id,
        'ClientReference' => $parcel->ClientReference,
        'CODAmount' => $parcel->CODAmount,
        'CODReference' => $parcel->CODReference ?? '',
        'Content' => $parcel->Content,
        'Count' => $parcel->Count,
        'DeliveryAddress' => [
            'City' => $parcel->DeliveryCity,
            'ContactEmail' => $parcel->DeliveryContactEmail,
            'ContactName' => $parcel->DeliveryContactName,
            'ContactPhone' => $parcel->DeliveryContactPhone,
            'CountryIsoCode' => $parcel->DeliveryCountryIsoCode,
            'HouseNumber' => $parcel->DeliveryHouseNumber,
            'Name' => $parcel->DeliveryName,
            'Street' => $parcel->DeliveryStreet,
            'ZipCode' => $parcel->DeliveryZipCode,
        ],
        'PickupAddress' => [
            'City' => $department->pickup_city ?? 'RIJEKA',
            'ContactEmail' => $department->pickup_email ?? 'marinom@alarmautomatika.com',
            'ContactName' => $department->pickup_contact_name ?? 'Marino Mileta',
            'ContactPhone' => $department->pickup_phone ?? '0913223364',
            'CountryIsoCode' => $department->pickup_country_iso_code ?? 'HR',
            'HouseNumber' => $department->pickup_house_number ?? '123',
            'Name' => $department->pickup_name ?? 'alarm automatika',
            'Street' => $department->pickup_street ?? 'DRAŽICE-ZAMET',
            'ZipCode' => $department->pickup_zip_code ?? '51000',
        ],
        'PickupDate' => '/Date(' . (now()->timestamp * 1000) . ')/',
    ];
}

public function printLabel(Request $request, Parcel $parcel)
{
    if (!$parcel->gls_parcel_id) {
        return redirect()->route('parcels.show', $parcel)->with('error', 'This parcel has not been prepared for GLS yet.');
    }

    $printPosition = $request->input('print_position', 1);

    try {
        $response = $this->glsApiService->getPrintedLabels([$parcel->gls_parcel_id], $printPosition);

        if (!empty($response['GetPrintedLabelsErrorList'])) {
            Log::error('GLS API Error', $response['GetPrintedLabelsErrorList']);
            return redirect()->route('parcels.show', $parcel)->with('error', 'Error getting label from GLS: ' . $response['GetPrintedLabelsErrorList'][0]['ErrorDescription']);
        }

        $pdfContent = implode(array_map('chr', $response['Labels']));

        $parcel->update([
            'gls_response' => $pdfContent,
            'label_expiry' => now()->addHours(12),
            'status' => 'printed'
        ]);


        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="parcel-' . $parcel->id . '-label.pdf"');
    } catch (\Exception $e) {
        Log::error('GLS API Error', ['message' => $e->getMessage()]);
        return redirect()->route('parcels.show', $parcel)->with('error', 'Error communicating with GLS API: ' . $e->getMessage());
    }
}

public function sentParcels()
{
    $sentParcels = Parcel::whereIn('status', ['sent', 'printed'])->get();
    return view('parcels.sent', compact('sentParcels'));
}

public function prepareForGLS(Request $request)
{
    try {
        $parcelIds = $request->input('parcel_ids', []);
        $parcels = Parcel::whereIn('id', $parcelIds)->with('department')->get();

        if ($parcels->isEmpty()) {
            return redirect()->route('parcels.index')->with('error', 'No parcels selected.');
        }

        $parcelsData = $parcels->map(function ($parcel) {
            return $this->formatParcelForGLS($parcel);
        })->toArray();

        $response = $this->glsApiService->prepareLabels($parcelsData);

        $updatedCount = 0;
        foreach ($parcels as $parcel) {
            if (!empty($response['ParcelInfoList'])) {
                $parcelInfo = collect($response['ParcelInfoList'])->firstWhere('ClientReference', $parcel->ClientReference);
                if ($parcelInfo && isset($parcelInfo['ParcelId'])) {
                    $parcel->update([
                        'gls_parcel_id' => $parcelInfo['ParcelId'],
                        'status' => 'prepared'
                    ]);
                    $updatedCount++;
                }
            }
        }

        if ($updatedCount > 0) {
            return redirect()->route('parcels.index')->with('success', "{$updatedCount} parcels successfully prepared for GLS.");
        } else {
            return redirect()->route('parcels.index')->with('warning', 'No parcels were updated. Please check the GLS response.');
        }

    } catch (\Exception $e) {
        Log::error('GLS API Error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return redirect()->route('parcels.index')
            ->with('error', 'Error preparing labels. Please check the logs for more details.');
    }
}

public function printedParcels()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $printedParcels = Parcel::where('status', 'printed')->get();
        } else {
            $departmentIds = $user->departments->pluck('id');
            $printedParcels = Parcel::where('status', 'printed')
                ->whereIn('department_id', $departmentIds)
                ->get();
        }
        return view('parcels.printed', compact('printedParcels'));
    }

public function reprintLabel(Parcel $parcel)
{
    if (!$parcel->gls_response) {
        return redirect()->route('parcels.printed')->with('error', 'No stored label found for this parcel.');
    }

    return response($parcel->gls_response)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="parcel-' . $parcel->id . '-label.pdf"');
}

/*public function bulkSend(Request $request)
{
    $user = Auth::user();
    $parcelIds = $request->input('parcel_ids', []);
    
    if (empty($parcelIds)) {
        return redirect()->route('parcels.index')->with('error', 'No parcels selected.');
    }
    
    $parcels = Parcel::whereIn('id', $parcelIds)
        ->when(!$user->hasRole('admin'), function ($query) use ($user) {
            return $query->whereIn('department_id', $user->departments->pluck('id'));
        })
        ->get();

    if ($parcels->isEmpty()) {
        return redirect()->route('parcels.index')->with('error', 'No authorized parcels selected.');
    }

    foreach ($parcels as $parcel) {
        $department = $parcel->department;
        
        if (!$department) {
            continue; // Skip if no department
        }
        
        $glsUsername = $department->gls_username ?? null;
        $glsPassword = $department->gls_password ?? null;

        if (!$glsUsername || !$glsPassword) {
            continue; // Skip if no GLS credentials
        }

        // Pretpostavljamo da imate GLS API servis
        $response = $this->glsApiService->prepareLabels([$parcel->toArray()], $glsUsername, $glsPassword);

        if (isset($response['PrepareLabelsErrorList']) && !empty($response['PrepareLabelsErrorList'])) {
            $parcel->update(['status' => 'error', 'gls_response' => json_encode($response)]);
        } else {
            $parcel->update([
                'status' => 'sent',
                'gls_parcel_id' => $response['ParcelIdList'][0] ?? null,
                'gls_response' => json_encode($response)
            ]);
        }
    }

    return redirect()->route('parcels.index')
        ->with('success', 'Selected parcels have been processed.');
}*/

public function createFromPantheon(Request $request)
    {
        $user = auth()->user();
        $departments = $user->departments; // Dohvaća samo odjele kojima korisnik ima pristup

        
        // Dohvaćamo podatke iz SQL Servera (ograničeno na npr. posljednjih 1000 zapisa)
        $documents = DB::connection('sqlsrv')->select("
            SELECT TOP 1000 TSSCA.acPhone, TM.ackey, TM.acReceiver, TM.acPrsn3, TM.acDept, 
                   TSS.acSubject, TSS.acName2, TSS.acAddress, TSS.acName3, TSS.acPost, 
                   TSS.acCountry, TSS.acPhone, TSS.anClerk, TU.acSubject, TSS.acNote 
            FROM the_move AS TM 
            LEFT JOIN tHE_SetSubj AS TSS ON TM.acReceiver = TSS.acSubject 
            LEFT JOIN tPA_User AS TU ON TSS.anClerk = TU.anUserId 
            LEFT JOIN tHE_SetSubjContactAddress AS TSSCA ON TSSCA.acSubject = TSS.acSubject AND TSSCA.acType = 'E' 
            ORDER BY TM.adDate DESC
        ");
        
        return view('parcels.create-from-pantheon', compact('departments', 'documents'));
    }

public function storeFromPantheon(Request $request)
{
    $validatedData = $request->validate([
        'document_id' => 'required',
        'department_id' => 'required|exists:departments,id',
    ]);

    $document = DB::connection('sqlsrv')->select("SELECT TSSCA.acPhone, TM.ackey, TM.acReceiver, TM.acPrsn3, TM.acDept, TSS.acSubject, TSS.acName2, TSS.acAddress, TSS.acName3, TSS.acPost, TSPC.acName, TSS.acCountry, TSS.acPhone, TSS.anClerk, TU.acSubject, TSS.acNote FROM the_move AS TM LEFT JOIN tHE_SetSubj AS TSS ON TM.acReceiver = TSS.acSubject LEFT JOIN tPA_User AS TU ON TSS.anClerk = TU.anUserId LEFT JOIN tHE_SetSubjContactAddress AS TSSCA ON TSSCA.acSubject = TSS.acSubject AND TSSCA.acType = 'E' LEFT JOIN tHE_SetPostCode AS TSPC ON TSS.acPost = TSPC.acPost WHERE TM.ackey = ?", [$validatedData['document_id']])[0];

    $zipCode = preg_replace('/^.*?(\d{5}).*$/', '$1', $document->acPost);

    $parcel = Parcel::create([
        'acKey' => $document->ackey,
        'ClientNumber' => $validatedData['department_id'], // Pretpostavljamo da je ovo ispravan ClientNumber
        'ClientReference' => $document->ackey,
        'Content' => $document->acNote ?? 'Default Content',
        'Count' => 1, // Default vrijednost, prilagodite prema potrebi
        'CODAmount' => 0, // Default vrijednost, prilagodite prema potrebi
        'DeliveryCity' => $document->acName ?? 'Unknown City',
        'DeliveryContactName' => $document->acName2 ?? 'Unknown Name',
        'DeliveryContactPhone' => $document->acPhone ?? 'Unknown Phone',
        'DeliveryCountryIsoCode' => $this->mapCountryToIsoCode($document->acCountry ?? 'HR'),
        'DeliveryName' => preg_replace('/[<>&]/', '', $document->acName2 ?? $document->acName3 ?? 'Unknown Name'),
        'DeliveryStreet' => $document->acAddress ?? 'Unknown Address',
        'DeliveryZipCode' => preg_replace('/^[A-Z]{2}-/', '', $document->acPost ?? 'Unknown Zip'),
        'department_id' => $validatedData['department_id'],
        'user_id' => auth()->id(),
        'status' => 'pending'
    ]);

    return redirect()->route('parcels.index')->with('success', 'Parcel created from Pantheon document successfully.');
}

private function mapCountryToIsoCode($country)
{
    $country = strtolower(trim($country));
    $countryMap = [
        'hrvatska' => 'HR',
        'croatia' => 'HR',
        'slovenija' => 'SI',
        'slovenia' => 'SI',
        'italija' => 'IT',
        'italy' => 'IT',
        'Hrvatska' => 'HR',
        'Slovenija' => 'SI',
        'Mađarska' => 'HU',
        'Rumunjska' => 'RO',
        'Češka' => 'CZ',
        'Slovačka' => 'SK',
        // Dodajte ostale zemlje prema potrebi
    ];

    return $countryMap[$country] ?? 'HR';  // Default na HR ako ne pronađemo mapu
}

/*public function createFromPantheon(Request $request)
    {
        $departments = Department::all(); // ili filtrirati prema pravima korisnika
        return view('parcels.create-from-pantheon', compact('departments'));
    }*/

    public function searchPantheonDocuments(Request $request)
    {
        $query = $request->input('query');
        
        $results = DB::connection('sqlsrv')->select("
            SELECT TM.ackey, TSS.acName2
            FROM the_move AS TM 
            LEFT JOIN tHE_SetSubj AS TSS ON TM.acReceiver = TSS.acSubject 
            WHERE TM.ackey LIKE ? OR TSS.acName2 LIKE ?
            LIMIT 10
        ", ["%$query%", "%$query%"]);

        return response()->json($results);
    }



}
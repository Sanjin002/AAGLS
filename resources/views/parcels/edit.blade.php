<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Parcel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('parcels.update', $parcel) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="department_id" class="form-label">Department</label>
                            <select name="department_id" id="department_id" class="form-control" required>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ $parcel->department_id == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!--
<div class="mb-3">
    <label for="ClientNumber" class="form-label">Client Number</label>
    <input type="number" class="form-control" id="ClientNumber" name="ClientNumber" value="{{ old('ClientNumber') }}" required>
    <small class="text-muted">Must be a whole number</small>
</div>
-->
                        <div class="mb-3">
                            <label for="ClientReference" class="form-label">Client Reference</label>
                            <input type="text" class="form-control" id="ClientReference" name="ClientReference" value="{{ old('ClientReference', $parcel->ClientReference) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="Content" class="form-label">Content</label>
                            <input type="text" class="form-control" id="Content" name="Content" value="{{ old('Content', $parcel->Content) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="Count" class="form-label">Count</label>
                            <input type="number" class="form-control" id="Count" name="Count" value="{{ old('Count', $parcel->Count) }}" required min="1" step="1">
                            <small class="text-muted">Must be a whole number</small>
                        </div>
                        <div class="mb-3">
                            <label for="CODAmount" class="form-label">COD Amount</label>
                            <input type="number" step="0.01" class="form-control" id="CODAmount" name="CODAmount" value="{{ old('CODAmount', $parcel->CODAmount) }}" required min="0">
                        </div>
                        <div class="mb-3">
                            <label for="DeliveryCity" class="form-label">Delivery City</label>
                            <input type="text" class="form-control" id="DeliveryCity" name="DeliveryCity" value="{{ old('DeliveryCity', $parcel->DeliveryCity) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="DeliveryContactName" class="form-label">Delivery Contact Name</label>
                            <input type="text" class="form-control" id="DeliveryContactName" name="DeliveryContactName" value="{{ old('DeliveryContactName', $parcel->DeliveryContactName) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="DeliveryContactPhone" class="form-label">Delivery Contact Phone</label>
                            <input type="text" class="form-control" id="DeliveryContactPhone" name="DeliveryContactPhone" value="{{ old('DeliveryContactPhone', $parcel->DeliveryContactPhone) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="DeliveryCountryIsoCode" class="form-label">Delivery Country ISO Code</label>
                            <input type="text" class="form-control" id="DeliveryCountryIsoCode" name="DeliveryCountryIsoCode" value="{{ old('DeliveryCountryIsoCode', $parcel->DeliveryCountryIsoCode) }}" required maxlength="2">
                            <small class="text-muted">Must be one of: HR, SI, HU, RO, CZ, SK</small>
                        </div>
                        <div class="mb-3">
                            <label for="DeliveryName" class="form-label">Delivery Name</label>
                            <input type="text" class="form-control" id="DeliveryName" name="DeliveryName" value="{{ old('DeliveryName', $parcel->DeliveryName) }}" required>
                            <small class="text-muted">Cannot contain &lt;, &gt;, or &amp;</small>
                        </div>
                        <div class="mb-3">
                            <label for="DeliveryStreet" class="form-label">Delivery Street</label>
                            <input type="text" class="form-control" id="DeliveryStreet" name="DeliveryStreet" value="{{ old('DeliveryStreet', $parcel->DeliveryStreet) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="DeliveryZipCode" class="form-label">Delivery Zip Code</label>
                            <input type="text" class="form-control" id="DeliveryZipCode" name="DeliveryZipCode" value="{{ old('DeliveryZipCode', $parcel->DeliveryZipCode) }}" required>
                            <small class="text-muted">Must be 4 or 5 digits</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Parcel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Parcel Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>Parcel Details</h1>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Client Reference: {{ $parcel->ClientReference }}</h5>
                            <p class="card-text">
                                <strong>Client Number:</strong> {{ $parcel->ClientNumber }}<br>
                                <strong>Content:</strong> {{ $parcel->Content }}<br>
                                <strong>Count:</strong> {{ $parcel->Count }}<br>
                                <strong>COD Amount:</strong> {{ $parcel->CODAmount }}<br>
                                <strong>Delivery City:</strong> {{ $parcel->DeliveryCity }}<br>
                                <strong>Delivery Contact Name:</strong> {{ $parcel->DeliveryContactName }}<br>
                                <strong>Delivery Contact Phone:</strong> {{ $parcel->DeliveryContactPhone }}<br>
                                <strong>Delivery Country ISO Code:</strong> {{ $parcel->DeliveryCountryIsoCode }}<br>
                                <strong>Delivery Name:</strong> {{ $parcel->DeliveryName }}<br>
                                <strong>Delivery Street:</strong> {{ $parcel->DeliveryStreet }}<br>
                                <strong>Delivery Zip Code:</strong> {{ $parcel->DeliveryZipCode }}<br>
                                <strong>acKey:</strong> {{ $parcel->acKey }}<br>
                                <strong>Status:</strong> {{ ucfirst($parcel->status) }}
                            </p>
                        </div>
                    </div>

                    @if($parcel->status != 'prepared' && $parcel->status != 'printed')
                        <a href="{{ route('parcels.edit', $parcel) }}" class="btn btn-primary mt-3">Edit</a>
                    @endif
                    
                    @if($parcel->gls_parcel_id)
                        @if($parcel->status == 'prepared')
                            <form action="{{ route('parcels.print', $parcel) }}" method="GET" style="display: inline-block;">
                                <div class="form-group" style="display: inline-block; margin-right: 10px;">
                                    <select name="print_position" class="form-control" style="width: auto;">
                                        <option value="1">Top Left</option>
                                        <option value="2">Top Right</option>
                                        <option value="3">Bottom Left</option>
                                        <option value="4">Bottom Right</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success mt-3">Print Label</button>
                            </form>
                        @elseif($parcel->status == 'printed')
                            <a href="{{ route('parcels.reprint', $parcel) }}" class="btn btn-success mt-3">Reprint Label</a>
                        @endif
                    @else
                        <form action="{{ route('parcels.prepare-for-gls') }}" method="POST" style="display: inline-block;">
                            @csrf
                            <input type="hidden" name="parcel_ids[]" value="{{ $parcel->id }}">
                            <button type="submit" class="btn btn-warning mt-3">Prepare for GLS</button>
                        </form>
                    @endif

                    @if($parcel->status != 'prepared' && $parcel->status != 'printed')
                        <form action="{{ route('parcels.destroy', $parcel) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mt-3" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
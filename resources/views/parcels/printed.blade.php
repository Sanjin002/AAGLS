<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Printed Parcels') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <table class="table">
        <thead>
            <tr>
                <th>Client Reference</th>
                <th>Delivery Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($printedParcels as $parcel)
                <tr>
                    <td>{{ $parcel->ClientReference }}</td>
                    <td>{{ $parcel->DeliveryName }}</td>
                    <td>{{ ucfirst($parcel->status) }}</td>
                    <td>
                        <a href="{{ route('parcels.reprint', $parcel) }}" target="_blank" class="btn btn-sm btn-success" id="reprintLabelBtn">Reprint Label</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
        </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    document.getElementById('reprintLabelBtn').addEventListener('click', function() {
        setTimeout(function() {
            location.reload();
        }, 1000); // Osvježi nakon 1 sekunde
    });
</script>

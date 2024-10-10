<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Parcel Statistics</h3>
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-100 p-4 rounded">
                            <p class="text-xl font-bold">{{ $parcelStats['pending'] }}</p>
                            <p>Pending Parcels</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded">
                            <p class="text-xl font-bold">{{ $parcelStats['sent'] }}</p>
                            <p>Sent Parcels</p>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded">
                            <p class="text-xl font-bold">{{ $parcelStats['printed'] }}</p>
                            <p>Printed Parcels</p>
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold mb-4">Recent Parcels</h3>
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b">Reference</th>
                                <th class="px-6 py-3 border-b">Status</th>
                                <th class="px-6 py-3 border-b">Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentParcels as $parcel)
                            <tr>
                                <td class="px-6 py-4 border-b">{{ $parcel->ClientReference }}</td>
                                <td class="px-6 py-4 border-b">{{ ucfirst($parcel->status) }}</td>
                                <td class="px-6 py-4 border-b">{{ $parcel->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-6">
                        <a href="{{ route('parcels.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create New Parcel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
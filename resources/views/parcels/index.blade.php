<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Parcels') }}
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

                    <form action="{{ route('parcels.bulk-action') }}" method="POST" id="bulk-action-form">
                        @csrf
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all"></th>
                                    <th>Client Reference</th>
                                    <th>Delivery Name</th>
                                    <th>Delivery Address</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($parcels as $parcel)
                                    @if($parcel->status != 'printed')
                                        <tr>
                                            <td><input type="checkbox" name="parcel_ids[]" value="{{ $parcel->id }}"></td>
                                            <td>{{ $parcel->ClientReference }}</td>
                                            <td>{{ $parcel->DeliveryName }}</td>
                                            <td>{{ $parcel->DeliveryStreet }}, {{ $parcel->DeliveryCity }}, {{ $parcel->DeliveryZipCode }}</td>
                                            <td>{{ ucfirst($parcel->status) }}</td>
                                            <td>
                                                <a href="{{ route('parcels.show', $parcel) }}" class="btn btn-sm btn-info">View</a>
                                                @if($parcel->status != 'prepared')
                                                    <a href="{{ route('parcels.edit', $parcel) }}" class="btn btn-sm btn-primary">Edit</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mb-3">
                            <label for="print_position" class="form-label">Print Position</label>
                            <select name="print_position" id="print_position" class="form-control">
                                <option value="1">Top Left</option>
                                <option value="2">Top Right</option>
                                <option value="3">Bottom Left</option>
                                <option value="4">Bottom Right</option>
                            </select>
                        </div>
                        <button type="submit" name="action" value="send" class="btn btn-primary">Send Selected Parcels</button>
                        <button type="submit" name="action" value="print" class="btn btn-success">Print Selected Labels</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all');
            const parcelCheckboxes = document.getElementsByName('parcel_ids[]');

            function toggleAll() {
                parcelCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            }

            selectAllCheckbox.addEventListener('change', toggleAll);
        });
    </script>
    @endpush
</x-app-layout>
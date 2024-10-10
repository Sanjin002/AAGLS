<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Parcel from Pantheon') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <input type="text" id="search" placeholder="Search by ackey or customer name" class="w-full mb-4 p-2 border rounded">
                    <div id="search-results" class="mb-4 max-h-60 overflow-y-auto"></div>

                    <form method="POST" action="{{ route('parcels.store-from-pantheon') }}">
                        @csrf
                        <input type="hidden" id="selected_document_id" name="document_id">
                        
                        <div class="mb-4">
                            <label for="department_id" class="block text-sm font-medium text-gray-700">Department</label>
                            <select name="department_id" id="department_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="ml-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Create Parcel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Prebacujemo PHP podatke u JavaScript
        // Prebacujemo PHP podatke u JavaScript
var documents = @json($documents);

$(document).ready(function() {
    // Kreiramo Set za brzu provjeru jedinstvenih ackey vrijednosti
    var uniqueKeys = new Set(documents.map(doc => doc.ackey));
    
    // Kreiramo mapu za brzo pretraživanje
    var documentMap = new Map(documents.map(doc => [doc.ackey, doc]));

    $('#search').on('keyup', function() {
        var query = $(this).val().toLowerCase();
        if (query.length >= 3) {
            var filteredKeys = Array.from(uniqueKeys).filter(key => 
                key.toLowerCase().includes(query) || 
                (documentMap.get(key).acName2 && documentMap.get(key).acName2.toLowerCase().includes(query))
            );

            var results = '';
            filteredKeys.forEach(function(key) {
                var doc = documentMap.get(key);
                results += '<div class="search-result cursor-pointer p-2 hover:bg-gray-100" data-ackey="' + doc.ackey + '">' +
                           doc.ackey + ' - ' + (doc.acName2 || 'N/A') + '</div>';
            });
            $('#search-results').html(results);
        } else {
            $('#search-results').html('');
        }
    });

    $(document).on('click', '.search-result', function() {
        var ackey = $(this).data('ackey');
        $('#selected_document_id').val(ackey);
        $('#search').val($(this).text());
        $('#search-results').html('');
    });
});
    </script>
    @endpush
</x-app-layout>
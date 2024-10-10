<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Department') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.departments.store') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                            <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-4">
                            <label for="gls_account" class="block text-gray-700 text-sm font-bold mb-2">GLS Account:</label>
                            <input type="text" name="gls_account" id="gls_account" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-4">
                            <label for="department_password" class="block text-gray-700 text-sm font-bold mb-2">Department Password:</label>
                            <input type="password" name="department_password" id="department_password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-4">
                            <label for="customer_id" class="block text-gray-700 text-sm font-bold mb-2">Customer ID:</label>
                            <input type="text" name="customer_id" id="customer_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-4">
                            <label for="doc_type_id" class="block text-gray-700 text-sm font-bold mb-2">Document Type:</label>
                            <select name="doc_type_id" id="doc_type_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                @foreach($docTypes as $docType)
                                    <option value="{{ $docType->id }}">{{ $docType->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="pickup_city" class="block text-gray-700 text-sm font-bold mb-2">Pickup City:</label>
                            <input type="text" name="pickup_city" id="pickup_city" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <!-- Add other fields (pickup_street, pickup_zip, pickup_country, pickup_contact_name, pickup_contact_phone, pickup_contact_email) similarly -->

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Create Department
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
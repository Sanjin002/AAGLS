<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Department') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                <form method="POST" action="{{ route('admin.departments.update', $department) }}">
    @csrf
    @method('PUT')
                        
                        <div>
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $department->name) }}" required>
                        </div>

                        <div>
                            <label for="doc_type_id">Document Type</label>
                            <select name="doc_type_id" id="doc_type_id" required>
                                @foreach($docTypes as $docType)
                                    <option value="{{ $docType->id }}" {{ $department->doc_type_id == $docType->id ? 'selected' : '' }}>
                                        {{ $docType->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit">Update Department</button>
</form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
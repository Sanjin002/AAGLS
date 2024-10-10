<form method="POST" action="{{ route('admin.departments.update', $department) }}">
    @csrf
    @method('PUT')
    
    <div class="mb-4">
        <label for="name">Department Name</label>
        <input type="text" name="name" id="name" value="{{ old('name', $department->name) }}" required>
    </div>

    <div class="mb-4">
        <label for="doc_type_id">Document Type</label>
        <select name="doc_type_id" id="doc_type_id" required>
            @foreach($docTypes as $docType)
                <option value="{{ $docType->id }}" {{ old('doc_type_id', $department->doc_type_id) == $docType->id ? 'selected' : '' }}>
                    {{ $docType->code }} - {{ $docType->description }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label for="pickup_city">Pickup City</label>
        <input type="text" name="pickup_city" id="pickup_city" value="{{ old('pickup_city', $department->pickup_city) }}" required>
    </div>
    <div>
    <label>Departments</label>
    @foreach($departments as $department)
        <div>
            <input type="checkbox" name="departments[]" id="department_{{ $department->id }}" value="{{ $department->id }}"
                {{ $user->departments->contains($department) ? 'checked' : '' }}>
            <label for="department_{{ $department->id }}">{{ $department->name }}</label>
        </div>
    @endforeach
</div>

<div>
    <label>Documents</label>
    @foreach($documents as $document)
        <div>
            <input type="checkbox" name="documents[]" id="document_{{ $document->id }}" value="{{ $document->id }}"
                {{ in_array($document->id, $userDocuments) ? 'checked' : '' }}>
            <label for="document_{{ $document->id }}">{{ $document->name }}</label>
        </div>
    @endforeach
</div>

    <!-- Dodajte ostala polja za pick-up adresu -->

    <button type="submit">Update Department</button>
</form>
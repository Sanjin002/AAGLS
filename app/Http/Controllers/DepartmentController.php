<?php

namespace App\Http\Controllers;
use App\Models\Department;
use App\Models\DocType;

use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $departments = Department::all();
    return view('admin.departments.index', compact('departments'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $docTypes = DocType::all();
    return view('admin.departments.create', compact('docTypes'));
}

public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'gls_account' => 'required|string|max:255',
        'department_password' => 'required|string|max:255',
        'customer_id' => 'required|string|max:255',
        'doc_type_id' => 'required|exists:doc_types,id',
        'pickup_city' => 'required|string|max:255',
        'pickup_street' => 'required|string|max:255',
        'pickup_zip' => 'required|string|max:20',
        'pickup_country' => 'required|string|max:2',
        'pickup_contact_name' => 'required|string|max:255',
        'pickup_contact_phone' => 'required|string|max:20',
        'pickup_contact_email' => 'required|email|max:255',
    ]);

    $department = Department::create($validatedData);

    return redirect()->route('admin.departments.index')
        ->with('success', 'Department created successfully.');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
{
    $docTypes = DocType::all();
    return view('admin.departments.edit', compact('department', 'docTypes'));
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'gls_account' => 'required|string|max:255',
        'department_password' => 'required|string|max:255',
        'customer_id' => 'required|string|max:255',
        'doc_type_id' => 'required|exists:doc_types,id',
        'pickup_city' => 'required|string|max:255',
        'pickup_street' => 'required|string|max:255',
        'pickup_zip' => 'required|string|max:20',
        'pickup_country' => 'required|string|max:2',
        'pickup_contact_name' => 'required|string|max:255',
        'pickup_contact_phone' => 'required|string|max:20',
        'pickup_contact_email' => 'required|email|max:255',
    ]);

    $department->update($validatedData);

    return redirect()->route('admin.departments.index')->with('success', 'Department updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

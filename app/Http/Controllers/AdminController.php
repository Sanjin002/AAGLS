<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;  // Dodajte ovu liniju na vrh datoteke

class AdminController extends Controller
{
    public function indexDepartments()
    {
        $departments = Department::all();
        return view('admin.departments.index', compact('departments'));
    }

    public function createDepartment()
    {
        return view('admin.departments.create');
    }

    public function storeDepartment(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'gls_account' => 'required|string|max:255',
        ]);

        Department::create($validatedData);

        return redirect()->route('admin.departments.index')->with('success', 'Department created successfully');
    }

    // Dodajte i ostale metode za edit, update, delete...
}
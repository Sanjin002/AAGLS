<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use App\Models\Document;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles', 'departments')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

public function edit(User $user)
{
    $roles = Role::all();
    $departments = Department::all();
    $documents = Document::all(); // Dodajte ovo
    $userDocuments = $user->documents->pluck('id')->toArray(); // Dodajte ovo
    return view('admin.users.edit', compact('user', 'roles', 'departments', 'documents', 'userDocuments'));
}

public function update(Request $request, User $user)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'roles' => 'array',
        'departments' => 'array',
        'documents' => 'array',
    ]);

    $user->update($request->only(['name', 'email']));
    $user->roles()->sync($request->input('roles', []));
    $user->departments()->sync($request->input('departments', []));
    $user->documents()->sync($request->input('documents', []));

    return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

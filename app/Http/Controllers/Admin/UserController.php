<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'client'])],
        ]);

        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return redirect()->route('admin.users.index')->with('status', 'Utilisateur créé avec succès !');
    }

    public function edit($id)
    {
        $id = $this->decodeId($id);
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $id = $this->decodeId($id);
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'client'])],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = $data['role'];
        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        return redirect()->route('admin.users.index')->with('status', 'Utilisateur mis à jour !');
    }

    public function destroy(Request $request, $id)
    {
        $id = $this->decodeId($id);
        $user = User::findOrFail($id);

        // Un admin ne peut pas se supprimer lui-même
        if ($user->id === $request->user()->id) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'Utilisateur supprimé.');
    }
}

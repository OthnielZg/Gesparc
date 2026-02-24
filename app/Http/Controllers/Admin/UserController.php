<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Bureau;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('bureau.servic.direction')->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $bureaux = Bureau::with('servic.direction.ville')->get();
        return view('admin.users.create', compact('bureaux'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'prenom' => ['required', 'string', 'max:255'],
            'nom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string'],
            'id_bureau' => ['nullable', 'exists:bureaux,id'],
            'contact' => ['nullable', 'string'],
        ]);

        User::create([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'id_bureau' => $request->id_bureau,
            'contact' => $request->contact,
            'actif' => true,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit(User $user)
    {
        $bureaux = Bureau::with('servic.direction.ville')->get();
        return view('admin.users.edit', compact('user', 'bureaux'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'prenom' => ['required', 'string', 'max:255'],
            'nom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'string'],
            'id_bureau' => ['nullable', 'exists:bureaux,id'],
            'contact' => ['nullable', 'string'],
        ]);

        $user->update($request->only('prenom', 'nom', 'email', 'role', 'id_bureau', 'contact'));

        if ($request->filled('password')) {
            $request->validate(['password' => ['confirmed', Rules\Password::defaults()]]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour.');
    }

    public function toggleStatus(User $user)
    {
        $user->update(['actif' => !$user->actif]);
        $status = $user->actif ? 'activé' : 'désactivé';
        return back()->with('success', "Compte utilisateur $status.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé.');
    }
}

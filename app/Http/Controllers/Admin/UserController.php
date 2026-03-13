<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bureau;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('bureau.service.direction')->latest()->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $villes = \App\Models\Ville::all();
        $directions = \App\Models\Direction::all();
        $services = \App\Models\Service::all();
        $bureaux = \App\Models\Bureau::all();

        return view('admin.users.create', compact('villes', 'directions', 'services', 'bureaux'));
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
            'id_direction' => ['nullable', 'exists:directions,id'],
            'contact' => ['nullable', 'string'],
        ]);

        User::create([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'id_bureau' => $request->id_bureau,
            'id_direction' => $request->id_direction,
            'contact' => $request->contact,
            'actif' => true,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit(User $user)
    {
        $villes = \App\Models\Ville::all();
        $directions = \App\Models\Direction::all();
        $services = \App\Models\Service::all();
        $bureaux = \App\Models\Bureau::all();

        return view('admin.users.edit', compact('user', 'villes', 'directions', 'services', 'bureaux'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'prenom' => ['required', 'string', 'max:255'],
            'nom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'string'],
            'id_bureau' => ['nullable', 'exists:bureaux,id'],
            'id_direction' => ['nullable', 'exists:directions,id'],
            'contact' => ['nullable', 'string'],
        ]);

        $user->update($request->only('prenom', 'nom', 'email', 'role', 'id_bureau', 'id_direction', 'contact'));

        if ($request->filled('password')) {
            $request->validate(['password' => ['confirmed', Rules\Password::defaults()]]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas bloquer votre propre compte.');
        }

        $user->update(['actif' => ! $user->actif]);
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

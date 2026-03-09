<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        \Illuminate\Support\Facades\Log::info('Profile update attempt for user: ' . $user->id);

        if ($request->hasFile('photo')) {
            \Illuminate\Support\Facades\Log::info('Photo file detected in request');
            
            // Supprimer l'ancienne photo si elle existe
            if ($user->photo_path) {
                \Illuminate\Support\Facades\Log::info('Deleting old photo: ' . $user->photo_path);
                Storage::disk('public')->delete($user->photo_path);
            }
            
            // Sauvegarder la nouvelle photo
            $path = $request->file('photo')->store('profile-photos', 'public');
            \Illuminate\Support\Facades\Log::info('New photo stored at: ' . $path);
            $user->photo_path = $path;
        } else {
            \Illuminate\Support\Facades\Log::info('No photo file detected in request');
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
        \Illuminate\Support\Facades\Log::info('User profile saved successfully');

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's profile photo.
     */
    public function destroyPhoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->photo_path) {
            Storage::disk('public')->delete($user->photo_path);
            $user->photo_path = null;
            $user->save();
        }

        return Redirect::route('profile.edit')->with('status', 'photo-deleted');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-ci-green transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="font-black text-4xl text-gray-900 leading-tight tracking-tighter mb-1">
                    Mon <span class="text-ci-green">Profil</span>
                </h2>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Informations personnelles & Sécurité</p>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ====================================================== --}}
        {{-- COLONNE GAUCHE : CARTE IDENTITÉ                        --}}
        {{-- ====================================================== --}}
        <div class="space-y-6">

            {{-- Carte Avatar + Actions --}}
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                {{-- Bandeau Radar --}}
                <div class="px-8 py-4 bg-slate-50/60 border-b border-gray-100">
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Photo de profil</span>
                </div>
                <div class="p-8 flex flex-col items-center text-center gap-6">
                    {{-- Avatar --}}
                    <div class="h-28 w-28 rounded-[1.5rem] bg-slate-50 border border-gray-100 overflow-hidden flex items-center justify-center text-ci-green font-black text-4xl shadow-sm">
                        @if($user->photo_path)
                            <img src="{{ asset('storage/' . $user->photo_path) }}" alt="Avatar" class="h-full w-full object-cover">
                        @else
                            {{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}
                        @endif
                    </div>

                    {{-- Infos --}}
                    <div>
                        <p class="text-base font-black text-gray-900 tracking-tight">{{ $user->full_name }}</p>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">{{ $user->getRoleLabel() }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $user->email }}</p>
                    </div>

                    {{-- Bouton Changer Photo --}}
                    <div class="w-full space-y-3">
                        <button onclick="document.getElementById('photo_upload').click()"
                            class="w-full py-3 bg-ci-green text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-ci-green-dark transition-all flex items-center justify-center gap-2 shadow-md shadow-ci-green/20">
                            <i class="fa-solid fa-camera-rotate"></i> Changer la photo
                        </button>

                        {{-- Formulaire caché upload --}}
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
                            @csrf @method('patch')
                            <input type="hidden" name="prenom" value="{{ $user->prenom }}">
                            <input type="hidden" name="nom" value="{{ $user->nom }}">
                            <input type="hidden" name="email" value="{{ $user->email }}">
                            <input type="file" id="photo_upload" name="photo" onchange="this.form.submit()" accept="image/*">
                        </form>

                        @if($user->photo_path)
                            <form action="{{ route('profile.photo.destroy') }}" method="POST">
                                @csrf @method('delete')
                                <button type="submit"
                                    class="w-full py-2.5 bg-white border border-red-100 text-red-500 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-red-50 transition-all">
                                    Supprimer la photo
                                </button>
                            </form>
                        @endif

                        @if (session('status') === 'profile-updated')
                            <p class="text-[10px] font-black text-ci-green uppercase tracking-widest flex items-center justify-center gap-1">
                                <i class="fa-solid fa-circle-check"></i> Photo mise à jour
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Carte Structure --}}
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-8 py-4 bg-slate-50/60 border-b border-gray-100">
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Structure</span>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-xl bg-slate-50 border border-gray-100 flex items-center justify-center text-gray-400 flex-shrink-0">
                            <i class="fa-solid fa-building text-xs"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Direction</p>
                            <p class="text-sm font-bold text-gray-800">{{ $user->direction?->nom_direction ?? 'DGI Centrale' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-xl bg-slate-50 border border-gray-100 flex items-center justify-center text-gray-400 flex-shrink-0">
                            <i class="fa-solid fa-door-open text-xs"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Bureau</p>
                            <p class="text-sm font-bold text-gray-800">{{ $user->bureau?->nom_bureau ?? 'Non assigné' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================================================== --}}
        {{-- COLONNE DROITE : FORMULAIRES                           --}}
        {{-- ====================================================== --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Carte Informations Personnelles --}}
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-8 py-4 bg-slate-50/60 border-b border-gray-100">
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Informations personnelles</span>
                </div>
                <div class="p-8">
                    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                        @csrf @method('patch')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Prénom</label>
                                <input name="prenom" type="text"
                                    class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm"
                                    value="{{ old('prenom', $user->prenom) }}" required>
                                <x-input-error class="mt-1" :messages="$errors->get('prenom')" />
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Nom</label>
                                <input name="nom" type="text"
                                    class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm"
                                    value="{{ old('nom', $user->nom) }}" required>
                                <x-input-error class="mt-1" :messages="$errors->get('nom')" />
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Adresse Email</label>
                            <input name="email" type="email"
                                class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm"
                                value="{{ old('email', $user->email) }}" required>
                            <x-input-error class="mt-1" :messages="$errors->get('email')" />
                        </div>

                        <div class="pt-4 flex items-center gap-4">
                            <button type="submit"
                                class="px-8 py-3 bg-slate-900 text-white rounded-xl font-black text-sm hover:scale-105 transition-all shadow-md">
                                Enregistrer
                            </button>
                            @if (session('status') === 'profile-updated')
                                <span class="text-xs font-bold text-ci-green flex items-center gap-1">
                                    <i class="fa-solid fa-check"></i> Enregistré.
                                </span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Carte Sécurité / Mot de Passe --}}
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-8 py-4 bg-slate-50/60 border-b border-gray-100">
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Modifier le mot de passe</span>
                </div>
                <div class="p-8">
                    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                        @csrf @method('put')

                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Mot de passe actuel</label>
                            <input id="current_password" name="current_password" type="password"
                                class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-orange focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm"
                                placeholder="••••••••">
                            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Nouveau mot de passe</label>
                                <input id="password" name="password" type="password"
                                    class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-orange focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm"
                                    placeholder="Min. 8 caractères">
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Confirmation</label>
                                <input id="password_confirmation" name="password_confirmation" type="password"
                                    class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-orange focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm"
                                    placeholder="Répéter le mot de passe">
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
                            </div>
                        </div>

                        <div class="pt-4 flex items-center gap-4">
                            <button type="submit"
                                class="px-8 py-3 bg-slate-900 text-white rounded-xl font-black text-sm hover:scale-105 transition-all shadow-md">
                                Mettre à jour
                            </button>
                            @if (session('status') === 'password-updated')
                                <span class="text-xs font-bold text-ci-green flex items-center gap-1">
                                    <i class="fa-solid fa-check"></i> Mot de passe modifié.
                                </span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
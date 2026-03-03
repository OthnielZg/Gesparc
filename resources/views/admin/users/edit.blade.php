<x-app-layout>
    <div x-data="{ 
        role: '{{ old('role', $user->role) }}',
    }" class="max-w-5xl mx-auto space-y-12 py-10">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="flex items-center gap-6">
                <a href="{{ route('admin.users.index') }}" 
                    class="h-14 w-14 bg-white border border-gray-100 rounded-2xl flex items-center justify-center text-gray-400 hover:text-ci-orange hover:border-ci-orange/30 transition-all shadow-sm">
                    <i class="fa-solid fa-arrow-left text-lg"></i>
                </a>
                <div>
                    <h2 class="font-black text-4xl text-gray-900 leading-tight tracking-tighter mb-1">
                        Modifier <span class="text-ci-orange">Utilisateur</span>
                    </h2>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest px-1 border-l-3 border-ci-green">Mise à jour du compte {{ $user->full_name }}</p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-[3rem] p-12 border border-gray-100 shadow-[0_20px_80px_rgba(0,0,0,0.05)] relative overflow-hidden">
            <!-- Decorative circle -->
            <div class="absolute top-0 right-0 w-80 h-80 bg-ci-orange/5 rounded-full -translate-y-40 translate-x-40 pointer-events-none"></div>

            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="relative z-10 space-y-12">
                @csrf
                @method('PUT')

                <!-- Section: Identité -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4">
                        <div class="h-8 w-8 bg-ci-orange rounded-lg flex items-center justify-center text-white">
                            <i class="fa-solid fa-user-pen text-xs"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 uppercase tracking-tighter">Profil Utilisateur</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Prénom <span class="text-red-500">*</span></label>
                            <input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}" required
                                class="w-full bg-gray-50/50 border-2 border-transparent focus:border-ci-orange focus:bg-white rounded-2xl text-sm p-5 font-bold transition-all outline-none shadow-sm"
                                placeholder="ex: Jean-Marc">
                            @error('prenom') <p class="text-[10px] font-black text-red-500 mt-1 ml-1 uppercase tracking-widest">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Nom de Famille <span class="text-red-500">*</span></label>
                            <input type="text" name="nom" value="{{ old('nom', $user->nom) }}" required
                                class="w-full bg-gray-50/50 border-2 border-transparent focus:border-ci-orange focus:bg-white rounded-2xl text-sm p-5 font-bold transition-all outline-none shadow-sm"
                                placeholder="ex: KOFFI">
                            @error('nom') <p class="text-[10px] font-black text-red-500 mt-1 ml-1 uppercase tracking-widest">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <hr class="border-gray-50">

                <!-- Section: Accès -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4">
                        <div class="h-8 w-8 bg-ci-green rounded-lg flex items-center justify-center text-white">
                            <i class="fa-solid fa-shield-halved text-xs"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 uppercase tracking-tighter">Accès & Affectation</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Rôle Système <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="role" x-model="role" required
                                    class="w-full bg-gray-50/50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-2xl text-sm p-5 font-bold transition-all outline-none appearance-none cursor-pointer">
                                    @foreach(\App\Models\User::roles() as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-gray-300"><i class="fa-solid fa-chevron-down"></i></div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Bureau d'Affectation</label>
                            <div class="relative">
                                <select name="id_bureau" 
                                    class="w-full bg-gray-50/50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-2xl text-sm p-5 font-bold transition-all outline-none appearance-none cursor-pointer">
                                    <option value="">(AUGUNE AFFECTATION)</option>
                                    @foreach($bureaux as $bureau)
                                        <option value="{{ $bureau->id }}" {{ old('id_bureau', $user->id_bureau) == $bureau->id ? 'selected' : '' }}>
                                            {{ $bureau->nom_bureau }} ({{ $bureau->service->nom_service ?? 'DGI' }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-gray-300"><i class="fa-solid fa-chevron-down"></i></div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Email Professionnel <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full bg-gray-50/50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-2xl text-sm p-5 font-bold transition-all outline-none shadow-sm"
                                placeholder="ex: j.koffi@dgi.gouv.ci">
                            @error('email') <p class="text-[10px] font-black text-red-500 mt-1 ml-1 uppercase tracking-widest">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Contact / Téléphone</label>
                            <input type="text" name="contact" value="{{ old('contact', $user->contact) }}"
                                class="w-full bg-gray-50/50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-2xl text-sm p-5 font-bold transition-all outline-none shadow-sm"
                                placeholder="ex: +225 01 02 03 04 05">
                        </div>
                    </div>
                </div>

                <hr class="border-gray-50">

                <!-- Section: Sécurité -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4">
                        <div class="h-8 w-8 bg-slate-900 rounded-lg flex items-center justify-center text-white">
                            <i class="fa-solid fa-key text-xs"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 uppercase tracking-tighter">Sécurité & Mot de Passe</h3>
                    </div>

                    <div class="bg-slate-50 p-6 rounded-2xl border border-dotted border-slate-200 mb-8">
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">Laissez vide pour conserver le mot de passe actuel</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Nouveau Mot de Passe</label>
                            <input type="password" name="password"
                                class="w-full bg-gray-50/50 border-2 border-transparent focus:border-slate-900 focus:bg-white rounded-2xl text-sm p-5 font-bold transition-all outline-none shadow-sm">
                            @error('password') <p class="text-[10px] font-black text-red-500 mt-1 ml-1 uppercase tracking-widest">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Confirmation</label>
                            <input type="password" name="password_confirmation"
                                class="w-full bg-gray-50/50 border-2 border-transparent focus:border-slate-900 focus:bg-white rounded-2xl text-sm p-5 font-bold transition-all outline-none shadow-sm">
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="pt-12 flex flex-col md:flex-row items-center justify-end gap-6">
                    <a href="{{ route('admin.users.index') }}" 
                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-red-500 transition-colors">Annuler les modifications</a>
                    
                    <button type="submit" 
                        class="w-full md:w-auto px-12 py-6 bg-slate-900 text-white rounded-[2rem] font-black text-xs uppercase tracking-[0.3em] shadow-2xl shadow-slate-900/10 hover:scale-105 active:scale-95 transition-all">
                        Mettre à jour l'utilisateur
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
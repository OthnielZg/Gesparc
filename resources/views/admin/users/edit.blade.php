<x-app-layout>
@php
    $currentBureauId = old('id_bureau', $user->id_bureau);
    $currentDirectionId = old('id_direction', $user->id_direction);
    
    $currentBureau = $currentBureauId ? $bureaux->firstWhere('id', $currentBureauId) : null;
    $currentService = $currentBureau ? $services->firstWhere('id', $currentBureau->id_service) : null;
    $currentDirection = $currentDirectionId ? $directions->firstWhere('id', $currentDirectionId) : ($currentService ? $directions->firstWhere('id', $currentService->id_direction) : null);
    $currentVille = $currentDirection ? $villes->firstWhere('id', $currentDirection->id_ville) : null;
@endphp

    <div x-data="{ 
        role: '{{ old('role', $user->role) }}',
        villes: {{ $villes->map(fn($v) => ['id' => $v->id, 'nom' => $v->nom_ville])->toJson() }},
        allDirections: {{ $directions->map(fn($d) => ['id' => $d->id, 'nom' => $d->nom_direction, 'id_ville' => $d->id_ville])->toJson() }},
        allServices: {{ $services->map(fn($s) => ['id' => $s->id, 'nom' => $s->nom_service, 'id_direction' => $s->id_direction])->toJson() }},
        allBureaux: {{ $bureaux->map(fn($b) => ['id' => $b->id, 'nom' => $b->nom_bureau, 'id_service' => $b->id_service])->toJson() }},
        
        selectedVille: '{{ $currentVille->id ?? '' }}',
        selectedDirection: '{{ $currentDirection->id ?? '' }}',
        selectedService: '{{ $currentService->id ?? '' }}',
        selectedBureau: '{{ $currentBureau->id ?? '' }}',

        get filteredDirections() {
            return this.allDirections.filter(d => d.id_ville == this.selectedVille);
        },
        get filteredServices() {
            return this.allServices.filter(s => s.id_direction == this.selectedDirection);
        },
        get filteredBureaux() {
            return this.allBureaux.filter(b => b.id_service == this.selectedService);
        },

        resetBelow(level) {
            if (level <= 1) { this.selectedDirection = ''; }
            if (level <= 2) { this.selectedService = ''; }
            if (level <= 3) { this.selectedBureau = ''; }
        }
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
                                    class="w-full bg-gray-50/50 border-2 border-transparent focus:border-ci-orange focus:bg-white rounded-2xl text-sm p-5 font-bold transition-all outline-none appearance-none cursor-pointer">
                                    @foreach(\App\Models\User::roles() as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-gray-300"><i class="fa-solid fa-chevron-down"></i></div>
                            </div>
                        </div>

                        <!-- Affichage Hiérarchique Cascade -->
                        <template x-if="role !== 'admin' && role !== 'gestionnaire'">
                            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 bg-slate-50/50 p-8 rounded-[2rem] border border-slate-100">
                                <!-- Ville -->
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Ville <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <select x-model="selectedVille" @change="resetBelow(1)"
                                            class="w-full bg-white border-2 border-transparent focus:border-ci-orange rounded-2xl text-sm p-5 font-bold transition-all outline-none appearance-none cursor-pointer shadow-sm">
                                            <option value="">Sélectionnez une ville</option>
                                            <template x-for="v in villes" :key="v.id">
                                                <option :value="v.id" x-text="v.nom" :selected="v.id == selectedVille"></option>
                                            </template>
                                        </select>
                                        <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-gray-300"><i class="fa-solid fa-chevron-down text-sm"></i></div>
                                    </div>
                                </div>

                                <!-- Direction -->
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Direction <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <select name="id_direction" x-model="selectedDirection" @change="resetBelow(2)" :disabled="!selectedVille"
                                            class="w-full bg-white border-2 border-transparent focus:border-ci-orange rounded-2xl text-sm p-5 font-bold transition-all outline-none appearance-none cursor-pointer shadow-sm disabled:opacity-50">
                                            <option value="">Sélectionnez une direction</option>
                                            <template x-for="d in filteredDirections" :key="d.id">
                                                <option :value="d.id" x-text="d.nom" :selected="d.id == selectedDirection"></option>
                                            </template>
                                        </select>
                                        <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-gray-300"><i class="fa-solid fa-chevron-down text-sm"></i></div>
                                    </div>
                                </div>

                                <!-- Service -->
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Service</label>
                                    <div class="relative">
                                        <select x-model="selectedService" @change="resetBelow(3)" :disabled="!selectedDirection"
                                            class="w-full bg-white border-2 border-transparent focus:border-ci-orange rounded-2xl text-sm p-5 font-bold transition-all outline-none appearance-none cursor-pointer shadow-sm disabled:opacity-50">
                                            <option value="">Sélectionnez un service</option>
                                            <template x-for="s in filteredServices" :key="s.id">
                                                <option :value="s.id" x-text="s.nom" :selected="s.id == selectedService"></option>
                                            </template>
                                        </select>
                                        <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-gray-300"><i class="fa-solid fa-chevron-down text-sm"></i></div>
                                    </div>
                                </div>

                                <!-- Bureau -->
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Bureau</label>
                                    <div class="relative">
                                        <select name="id_bureau" x-model="selectedBureau" :disabled="!selectedService"
                                            class="w-full bg-white border-2 border-transparent focus:border-ci-orange rounded-2xl text-sm p-5 font-bold transition-all outline-none appearance-none cursor-pointer shadow-sm disabled:opacity-50">
                                            <option value="">Sélectionnez un bureau</option>
                                            <template x-for="b in filteredBureaux" :key="b.id">
                                                <option :value="b.id" x-text="b.nom" :selected="b.id == selectedBureau"></option>
                                            </template>
                                        </select>
                                        <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-gray-300"><i class="fa-solid fa-chevron-down text-sm"></i></div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Email Professionnel <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full bg-gray-50/50 border-2 border-transparent focus:border-ci-orange focus:bg-white rounded-2xl text-sm p-5 font-bold transition-all outline-none shadow-sm"
                                placeholder="ex: j.koffi@dgi.gouv.ci">
                            @error('email') <p class="text-[10px] font-black text-red-500 mt-1 ml-1 uppercase tracking-widest">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Contact / Téléphone</label>
                            <input type="text" name="contact" value="{{ old('contact', $user->contact) }}"
                                class="w-full bg-gray-50/50 border-2 border-transparent focus:border-ci-orange focus:bg-white rounded-2xl text-sm p-5 font-bold transition-all outline-none shadow-sm"
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
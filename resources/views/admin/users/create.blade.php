<x-app-layout>
    <div x-data="{ 
        role: '{{ old('role', 'agent') }}',
        villes: {{ $villes->map(fn($v) => ['id' => $v->id, 'nom' => $v->nom_ville])->toJson() }},
        allDirections: {{ $directions->map(fn($d) => ['id' => $d->id, 'nom' => $d->nom_direction, 'id_ville' => $d->id_ville])->toJson() }},
        allServices: {{ $services->map(fn($s) => ['id' => $s->id, 'nom' => $s->nom_service, 'id_direction' => $s->id_direction])->toJson() }},
        allBureaux: {{ $bureaux->map(fn($b) => ['id' => $b->id, 'nom' => $b->nom_bureau, 'id_service' => $b->id_service])->toJson() }},
        
        selectedVille: '{{ old('id_ville', '') }}',
        selectedDirection: '{{ old('id_direction', '') }}',
        selectedService: '{{ old('id_service', '') }}',
        selectedBureau: '{{ old('id_bureau', '') }}',

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
    }" class="space-y-6 pt-1 pb-6 px-4">

        <!-- En-tête -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.users.index') }}" 
                    class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-ci-orange hover:border-ci-orange/30 transition-all shadow-sm">
                    <i class="fa-solid fa-arrow-left text-base"></i>
                </a>
                <div>
                    <h2 class="font-black text-3xl text-gray-900 leading-tight tracking-tighter mb-0.5">
                        Nouvel <span class="text-ci-orange">Utilisateur</span>
                    </h2>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1 border-l-2 border-ci-green">Configuration des accès système</p>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto mt-4">
            <!-- Carte du Formulaire -->
            <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm relative overflow-hidden">
            <!-- Cercle décoratif -->
            <div class="absolute top-0 right-0 w-60 h-60 bg-ci-orange/5 rounded-full -translate-y-32 translate-x-32 pointer-events-none"></div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="relative z-10 space-y-8">
                @csrf

                <!-- Section : Identité -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <div class="h-6 w-6 bg-ci-orange rounded flex items-center justify-center text-white">
                            <i class="fa-solid fa-id-card text-[10px]"></i>
                        </div>
                        <h3 class="text-lg font-black text-slate-900 uppercase tracking-tighter">Informations Personnelles</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Prénom <span class="text-red-500">*</span></label>
                            <input type="text" name="prenom" value="{{ old('prenom') }}" required
                                class="w-full bg-gray-50/50 border-2 border-transparent focus:border-ci-orange focus:bg-white rounded-xl text-xs px-4 py-3 font-bold transition-all outline-none shadow-sm placeholder:text-gray-300"
                                placeholder="ex: Jean-Marc">
                            @error('prenom') <p class="text-[10px] font-black text-red-500 mt-1 ml-1 uppercase tracking-widest">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Nom de Famille <span class="text-red-500">*</span></label>
                            <input type="text" name="nom" value="{{ old('nom') }}" required
                                class="w-full bg-gray-50/50 border-2 border-transparent focus:border-ci-orange focus:bg-white rounded-xl text-xs px-4 py-3 font-bold transition-all outline-none shadow-sm placeholder:text-gray-300"
                                placeholder="ex: KOFFI">
                            @error('nom') <p class="text-[10px] font-black text-red-500 mt-1 ml-1 uppercase tracking-widest">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <hr class="border-gray-50">

                <!-- Section : Accès -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <div class="h-6 w-6 bg-ci-green rounded flex items-center justify-center text-white">
                            <i class="fa-solid fa-shield-halved text-[10px]"></i>
                        </div>
                        <h3 class="text-lg font-black text-slate-900 uppercase tracking-tighter">Accès & Affectation</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Rôle Système <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="role" x-model="role" required
                                    class="w-full bg-gray-50/50 border-2 border-transparent focus:border-ci-orange focus:bg-white rounded-xl text-xs px-4 py-3 font-bold transition-all outline-none appearance-none cursor-pointer">
                                    @foreach(\App\Models\User::roles() as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-300"><i class="fa-solid fa-chevron-down text-xs"></i></div>
                            </div>
                        </div>

                        <!-- Affichage Hiérarchique Cascade -->
                        <template x-if="role !== 'admin' && role !== 'gestionnaire'">
                            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 bg-slate-50/50 p-6 rounded-[1.5rem] border border-slate-100">
                                <!-- Ville -->
                                <div class="space-y-2">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Ville <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <select x-model="selectedVille" @change="resetBelow(1)"
                                            class="w-full bg-white border-2 border-transparent focus:border-ci-orange rounded-xl text-xs px-4 py-3 font-bold transition-all outline-none appearance-none cursor-pointer shadow-sm">
                                            <option value="">Sélectionnez une ville</option>
                                            <template x-for="v in villes" :key="v.id">
                                                <option :value="v.id" x-text="v.nom"></option>
                                            </template>
                                        </select>
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-300"><i class="fa-solid fa-chevron-down text-[10px]"></i></div>
                                    </div>
                                </div>

                                <!-- Direction -->
                                <div class="space-y-2">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Direction <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <select name="id_direction" x-model="selectedDirection" @change="resetBelow(2)" :disabled="!selectedVille"
                                            class="w-full bg-white border-2 border-transparent focus:border-ci-orange rounded-xl text-xs px-4 py-3 font-bold transition-all outline-none appearance-none cursor-pointer shadow-sm disabled:opacity-50">
                                            <option value="">Sélectionnez une direction</option>
                                            <template x-for="d in filteredDirections" :key="d.id">
                                                <option :value="d.id" x-text="d.nom" :selected="d.id == selectedDirection"></option>
                                            </template>
                                        </select>
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-300"><i class="fa-solid fa-chevron-down text-[10px]"></i></div>
                                    </div>
                                </div>

                                <!-- Service -->
                                <div class="space-y-2">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Service</label>
                                    <div class="relative">
                                        <select x-model="selectedService" @change="resetBelow(3)" :disabled="!selectedDirection"
                                            class="w-full bg-white border-2 border-transparent focus:border-ci-orange rounded-xl text-xs px-4 py-3 font-bold transition-all outline-none appearance-none cursor-pointer shadow-sm disabled:opacity-50">
                                            <option value="">Sélectionnez un service</option>
                                            <template x-for="s in filteredServices" :key="s.id">
                                                <option :value="s.id" x-text="s.nom" :selected="s.id == selectedService"></option>
                                            </template>
                                        </select>
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-300"><i class="fa-solid fa-chevron-down text-[10px]"></i></div>
                                    </div>
                                </div>

                                <!-- Bureau -->
                                <div class="space-y-2">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Bureau</label>
                                    <div class="relative">
                                        <select name="id_bureau" x-model="selectedBureau" :disabled="!selectedService"
                                            class="w-full bg-white border-2 border-transparent focus:border-ci-orange rounded-xl text-xs px-4 py-3 font-bold transition-all outline-none appearance-none cursor-pointer shadow-sm disabled:opacity-50">
                                            <option value="">Sélectionnez un bureau</option>
                                            <template x-for="b in filteredBureaux" :key="b.id">
                                                <option :value="b.id" x-text="b.nom" :selected="b.id == selectedBureau"></option>
                                            </template>
                                        </select>
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-300"><i class="fa-solid fa-chevron-down text-[10px]"></i></div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Email Professionnel <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full bg-gray-50/50 border-2 border-transparent focus:border-ci-orange focus:bg-white rounded-xl text-xs px-4 py-3 font-bold transition-all outline-none shadow-sm placeholder:text-gray-300"
                                placeholder="ex: j.koffi@dgi.gouv.ci">
                            @error('email') <p class="text-[10px] font-black text-red-500 mt-1 ml-1 uppercase tracking-widest">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Contact / Téléphone</label>
                            <input type="text" name="contact" value="{{ old('contact') }}"
                                class="w-full bg-gray-50/50 border-2 border-transparent focus:border-ci-orange focus:bg-white rounded-xl text-xs px-4 py-3 font-bold transition-all outline-none shadow-sm placeholder:text-gray-300"
                                placeholder="ex: +225 01 02 03 04 05">
                        </div>
                    </div>
                </div>

                <hr class="border-gray-50">

                <!-- Section : Sécurité -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <div class="h-6 w-6 bg-slate-900 rounded flex items-center justify-center text-white">
                            <i class="fa-solid fa-lock text-[10px]"></i>
                        </div>
                        <h3 class="text-lg font-black text-slate-900 uppercase tracking-tighter">Sécurité du Compte</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Mot de Passe <span class="text-red-500">*</span></label>
                            <input type="password" name="password" required
                                class="w-full bg-gray-50/50 border-2 border-transparent focus:border-slate-900 focus:bg-white rounded-xl text-xs px-4 py-3 font-bold transition-all outline-none shadow-sm">
                            @error('password') <p class="text-[10px] font-black text-red-500 mt-1 ml-1 uppercase tracking-widest">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Confirmation</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full bg-gray-50/50 border-2 border-transparent focus:border-slate-900 focus:bg-white rounded-xl text-xs px-4 py-3 font-bold transition-all outline-none shadow-sm">
                        </div>
                    </div>
                </div>

                <!-- Actions de pied form-->
                <div class="pt-6 flex flex-col md:flex-row items-center justify-end gap-6">
                    <a href="{{ route('admin.users.index') }}" 
                        class="text-[9px] font-black text-gray-400 uppercase tracking-widest hover:text-red-500 transition-colors">Annuler la procédure</a>
                    
                    <button type="submit" 
                        class="w-full md:w-auto px-8 py-3 bg-slate-900 text-white rounded-xl font-black text-[10px] uppercase tracking-[0.2em] shadow-md shadow-slate-900/10 hover:scale-105 active:scale-95 transition-all">
                        Créer le compte utilisateur
                    </button>
                </div>
            </form>
        </div>
        </div>
    </div>
</x-app-layout>
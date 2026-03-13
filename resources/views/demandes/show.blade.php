<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('demandes.index') }}" class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-ci-green transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="font-black text-3xl text-gray-900 leading-tight tracking-tighter">
                Détails du <span class="text-ci-orange">Dossier</span>
            </h2>
        </div>
    </x-slot>

    <!-- Workflow Stepper (Premium Ultra-Compact) -->
    <div class="mb-4">
        <div class="bg-white px-2 py-4 sm:p-5 rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative">
            <div class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-transparent via-gray-100 to-transparent"></div>
            
            @php
                $steps = [
                    ['statut' => 'creee', 'label' => 'Signalement', 'role' => $demande->utilisateur->getRoleLabel(), 'icon' => 'fa-paper-plane'],
                    ['statut' => 'pre_demande', 'label' => 'Officialisation', 'role' => 'Chef CDI', 'icon' => 'fa-file-signature'],
                    ['statut' => 'en_attente_dr', 'label' => 'Analyse DR', 'role' => 'Dir. Régional', 'icon' => 'fa-magnifying-glass-chart'],
                    ['statut' => 'transmise_gestionnaire', 'label' => 'Stock SEDMI', 'role' => 'Gestionnaire', 'icon' => 'fa-boxes-packing'],
                    ['statut' => 'proposition_sdgrte', 'label' => 'SDGRTE', 'role' => 'Resp. Matériel', 'icon' => 'fa-user-check'],
                    ['statut' => 'en_attente_dsi', 'label' => 'Décision DSI', 'role' => 'Directeur SI', 'icon' => 'fa-award'],
                ];
                $currentIdx = 0;
                $isTerminal = in_array($demande->statut, ['validee', 'rejetee', 'annulee']);
                $isFailed = in_array($demande->statut, ['rejetee', 'annulee']);
                
                foreach($steps as $idx => $step) {
                    if($demande->statut == $step['statut']) $currentIdx = $idx;
                }
                
                // Si la demande est validée, toutes les étapes sont terminées (vert index fictif > 5)
                if($demande->statut == 'validee') $currentIdx = 6;
                
                // Gestion du rejet (Optionnel : si rejetée, elle reste bloquée sur la dernière validation)
                if($isFailed && $demande->validations->count() > 0) {
                    $dernierStatut = $demande->validations->last()->niveau_validation;
                    $rejetMap = ['chef_cdi' => 1, 'DR' => 2, 'gestionnaire' => 3, 'SDGRTE' => 4, 'DSI' => 5];
                    $currentIdx = $rejetMap[$dernierStatut] ?? 0;
                }
            @endphp

            <div class="flex items-start justify-between relative px-2">
                @foreach($steps as $idx => $step)
                    <div class="flex-1 flex flex-col items-center relative z-10 w-0">
                        <!-- Connecting Line (Next segment) -->
                        @if($idx < 5)
                            <div class="absolute top-[17px] left-1/2 w-full h-[3px] bg-gray-100 -z-10 rounded-r-full overflow-hidden">
                                <div class="h-full bg-ci-green transition-all duration-1000" style="width: {{ $currentIdx > $idx ? '100' : '0' }}%"></div>
                            </div>
                        @endif

                        <!-- Circle Indicator -->
                        <div class="h-[34px] w-[34px] rounded-xl flex items-center justify-center transition-all duration-500 ring-4 ring-white
                            {{ $currentIdx > $idx || ($demande->statut == 'validee' && $idx == 5) ? 'bg-ci-green text-white shadow-sm shadow-green-200' : 
                               ($currentIdx == $idx && !$isFailed ? 'bg-ci-orange text-white scale-[1.15] shadow-md shadow-orange-200 rotate-6' : 
                               ($isFailed && $currentIdx == $idx ? 'bg-red-500 text-white animate-pulse' : 'bg-gray-50 text-gray-300')) }}">
                            @if($currentIdx > $idx || ($demande->statut == 'validee' && $idx == 5))
                                <i class="fa-solid fa-check text-[10px] sm:text-xs"></i>
                            @else
                                <i class="fa-solid {{ $step['icon'] }} text-[10px] sm:text-xs"></i>
                            @endif
                        </div>
                        
                        <div class="mt-2 sm:mt-3 text-center w-full px-1">
                            <p class="text-[8px] sm:text-[9px] font-black uppercase tracking-widest {{ $currentIdx >= $idx ? 'text-gray-900' : 'text-gray-400' }} truncate">{{ $step['label'] }}</p>
                            <p class="text-[7.5px] sm:text-[8px] font-bold text-gray-400/80 uppercase mt-0.5 truncate">{{ $step['role'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Details Content (Layout 50/50) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left: Infos & Historique -->
        <div class="space-y-8">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Informations Générales</p>
                        <h3 class="text-lg font-black text-gray-900 tracking-tighter">{{ $demande->reference }}</h3>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest 
                        bg-{{ $demande->statut_color }}-50 text-{{ $demande->statut_color == 'green' ? 'ci-green' : ($demande->statut_color == 'orange' ? 'ci-orange' : $demande->statut_color) }}-600 shadow-sm border border-{{ $demande->statut_color == 'green' ? 'green' : ($demande->statut_color == 'orange' ? 'orange' : $demande->statut_color) }}-100/50">
                        {{ $demande->statut_label }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Demandeur</p>
                        <p class="text-sm font-bold text-gray-900">{{ $demande->utilisateur->full_name }}</p>
                        <p class="text-[10px] font-bold text-ci-orange uppercase mt-0.5">{{ $demande->utilisateur->getRoleLabel() }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $demande->utilisateur->email }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Catégorie</p>
                        <p class="text-sm font-black text-gray-900 uppercase">{{ $demande->categorie_demandee }}</p>
                        <p class="text-xs text-gray-500">Quantité: {{ $demande->quantite }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Motif</p>
                        <p class="text-sm text-gray-700 bg-gray-50 p-4 rounded-2xl italic">"{{ $demande->motif_demande }}"</p>
                    </div>
                </div>
            </div>

            <!-- Validation Timeline -->
            @if($demande->validations->count() > 0)
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-6 border-l-4 border-ci-green pl-4">Historique des Validations</h4>
                    <div class="space-y-6">
                        @foreach($demande->validations as $v)
                            <div class="flex gap-4 p-4 rounded-2xl {{ $v->decision == 'OK' ? 'bg-green-50' : 'bg-red-50' }}">
                                <div class="h-10 w-10 flex-shrink-0 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-user-shield text-sm"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <p class="text-xs font-black uppercase">{{ $v->niveau_validation }}</p>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded {{ $v->decision == 'OK' ? 'bg-ci-green text-white' : 'bg-red-500 text-white' }}">{{ $v->decision }}</span>
                                    </div>
                                    <p class="text-xs font-bold text-gray-900">{{ $v->utilisateur->full_name }}</p>
                                    @if($v->commentaire)
                                        <p class="text-xs text-gray-500 mt-1 italic">"{{ $v->commentaire }}"</p>
                                    @endif
                                    <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest">{{ $v->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Right: Actions & Décisions -->
        <div class="space-y-8">
            <!-- Context Box (Sober Design) -->
            <div class="bg-white p-5 sm:p-6 rounded-[2rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-100 relative overflow-hidden hover:shadow-lg">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gray-50 rounded-full blur-3xl -mr-16 -mt-16"></div>
                <h4 class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                    <span class="h-1 w-3 bg-ci-green rounded-full"></span> 
                    Décision
                </h4>
                
                @php $user = Auth::user(); @endphp

                <!-- Action Chef CDI -->
                @if($user->hasRole('chef_cdi') && $demande->statut == 'pre_demande')
                    <div class="space-y-4" x-data="{ open: false, decision: 'OK' }">
                        <button @click="open = true; decision = 'OK'" class="w-full py-5 bg-ci-green-dark text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:scale-[1.02] transition-all shadow-xl shadow-green-900/10 group">
                            <i class="fa-solid fa-file-circle-check mr-2 group-hover:rotate-12 transition-transform"></i> Officialiser le besoin
                        </button>
                        <button @click="open = true; decision = 'KO'" class="w-full py-4 bg-white border-2 border-gray-100 text-gray-400 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:border-red-500/30 hover:text-red-500 transition-all">
                            Décliner la demande
                        </button>

                        <!-- Modal Chef CDI -->
                        <div x-show="open" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center p-4"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100">
                            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md"></div>
                            <div class="relative bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl text-gray-900 overflow-hidden flex flex-col"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 @click.away="open = false">
                                
                                <div class="px-8 pt-8 pb-4 flex items-center gap-4 bg-white/50 backdrop-blur-sm border-b border-gray-50">
                                    <div class="h-12 w-12 rounded-2xl flex items-center justify-center flex-shrink-0" :class="decision == 'OK' ? 'bg-green-50 text-ci-green' : 'bg-red-50 text-red-500'">
                                        <i class="fa-solid text-xl" :class="decision == 'OK' ? 'fa-file-circle-check' : 'fa-circle-xmark'"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-black tracking-tighter leading-none" x-text="decision == 'OK' ? 'Officialiser' : 'Rejeter' "></h3>
                                        <p class="text-[9px] font-black uppercase text-gray-400 tracking-[0.2em] mt-1">Chef de Service • Décision</p>
                                    </div>
                                </div>

                                <form action="{{ route('demandes.officialise', $demande) }}" method="POST" class="flex flex-col h-full overflow-hidden">
                                    @csrf
                                    <input type="hidden" name="decision" :value="decision">
                                    
                                    <div class="px-8 py-6 max-h-[60vh] overflow-y-auto space-y-6">
                                        <div class="space-y-1.5">
                                            <label class="text-[8px] font-black text-gray-400 uppercase tracking-widest block ml-1">
                                                Commentaire <span x-show="decision == 'KO'">* (Obligatoire)</span>
                                            </label>
                                            <textarea name="commentaire" :required="decision == 'KO'" placeholder="Observations ou motif du rejet..." class="w-full px-4 py-3 bg-gray-50/80 border border-gray-100 focus:border-ci-green focus:bg-white focus:ring-0 rounded-2xl text-[13px] font-bold outline-none h-24 transition-all resize-none text-gray-600"></textarea>
                                        </div>
                                    </div>

                                    <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-50 flex gap-4">
                                        <button type="button" @click="open = false" class="flex-1 py-4 text-gray-400 font-black uppercase tracking-widest text-[10px] hover:text-gray-600 transition-colors">Retour</button>
                                        <button type="submit" class="flex-[2] py-4 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-xl transition-all hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2"
                                                :class="decision == 'OK' ? 'bg-ci-green-dark shadow-green-900/10' : 'bg-red-600 shadow-red-900/10'">
                                            <i class="fa-solid fa-signature"></i>
                                            Signer la décision
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                @php
                    $statusMap = ['DR' => 'en_attente_dr', 'gestionnaire' => 'transmise_gestionnaire', 'SDGRTE' => 'proposition_sdgrte', 'DSI' => 'en_attente_dsi'];
                    $canValidate = ($statusMap[$user->role] ?? null) === $demande->statut;
                @endphp

                {{-- ═══ FORMULAIRE STANDARD (DR, Gestionnaire) ═══ --}}
                @if($canValidate && !$user->isSDGRTE() && !$user->isDSI())
                    <div class="space-y-4" x-data="{ open: false, decision: 'OK' }">
                        <button @click="open = true; decision = 'OK'" class="w-full py-5 bg-ci-green text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:scale-[1.02] transition-all shadow-xl shadow-green-900/10 group">
                            <i class="fa-solid fa-check-double mr-2 group-hover:scale-125 transition-transform"></i> Approuver le dossier
                        </button>
                        <button @click="open = true; decision = 'KO'" class="w-full py-4 bg-white border-2 border-gray-100 text-gray-400 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:border-red-500/30 hover:text-red-500 transition-all">
                            Rejeter ce dossier
                        </button>

                        <!-- Modal standard -->
                        <div x-show="open" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center p-4"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100">
                            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md"></div>
                            <div class="relative bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl text-gray-900 overflow-hidden flex flex-col"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 @click.away="open = false">
                                
                                <!-- Modal Header -->
                                <div class="px-8 pt-8 pb-4 flex items-center gap-4 bg-white/50 backdrop-blur-sm border-b border-gray-50">
                                    <div class="h-12 w-12 rounded-2xl flex items-center justify-center flex-shrink-0" :class="decision == 'OK' ? 'bg-green-50 text-ci-green' : 'bg-red-50 text-red-500'">
                                        <i class="fa-solid text-xl" :class="decision == 'OK' ? 'fa-circle-check' : 'fa-circle-xmark'"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-black tracking-tighter leading-none" x-text="decision == 'OK' ? 'Valider le dossier' : 'Rejeter le dossier'"></h3>
                                        <p class="text-[9px] font-black uppercase text-gray-400 tracking-[0.2em] mt-1">DGI • Décision Officielle</p>
                                    </div>
                                </div>

                                <form action="{{ route('validations.store', $demande) }}" method="POST" class="flex flex-col h-full overflow-hidden">
                                    @csrf
                                    <input type="hidden" name="decision" :value="decision">
                                    
                                    <!-- Modal Body (Scrollable) -->
                                    <div class="px-8 py-6 max-h-[60vh] overflow-y-auto custom-scrollbar-minimal space-y-6">
                                        @if($user->isGestionnaire())
                                            <div class="bg-gray-50/50 p-5 rounded-3xl space-y-4 border border-gray-100 relative overflow-hidden">
                                                <h4 class="text-[10px] font-black text-gray-900 uppercase tracking-widest flex items-center gap-2 mb-2">
                                                    <i class="fa-solid fa-boxes-packing text-ci-green"></i> 
                                                    Proposition Stock
                                                </h4>

                                                <div class="space-y-1.5">
                                                    <label class="text-[8px] font-black text-gray-400 uppercase tracking-widest block ml-1">Unité Sélectionnée *</label>
                                                    <div class="relative group">
                                                        <select name="id_materiel" required class="w-full px-4 py-3 bg-white border border-gray-200 focus:border-ci-green focus:ring-0 rounded-2xl text-[13px] font-bold shadow-sm outline-none transition-all text-gray-700 appearance-none cursor-pointer">
                                                            <option value="">Choisir un matériel...</option>
                                                            @foreach($materielsDisponibles as $m)
                                                                <option value="{{ $m->id }}">{{ $m->marque }} {{ $m->modele }} (S/N: {{ $m->numero_serie }})</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400">
                                                            <i class="fa-solid fa-chevron-down text-[10px]"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                <div class="space-y-1.5">
                                                    <label class="text-[8px] font-black text-gray-400 uppercase tracking-widest block ml-1">Précisions (Optionnel)</label>
                                                    <textarea name="note_gestionnaire" placeholder="Infos techniques..." class="w-full px-4 py-3 bg-white border border-gray-200 focus:border-ci-green focus:ring-0 rounded-2xl text-[13px] font-bold shadow-sm outline-none transition-all text-gray-600 h-20 resize-none"></textarea>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <div class="space-y-1.5">
                                            <label class="text-[8px] font-black text-gray-400 uppercase tracking-widest block ml-1">Votre Commentaire</label>
                                            <textarea name="commentaire" placeholder="Observations générales..." class="w-full px-4 py-3 bg-gray-50/80 border border-gray-100 focus:border-ci-green focus:bg-white focus:ring-0 rounded-2xl text-[13px] font-bold outline-none h-24 transition-all resize-none text-gray-600"></textarea>
                                        </div>
                                    </div>

                                    <!-- Modal Footer (Fixed) -->
                                    <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-50 flex gap-4">
                                        <button type="button" @click="open = false" class="flex-1 py-4 text-gray-400 font-black uppercase tracking-widest text-[10px] hover:text-gray-600 transition-colors">Abroger</button>
                                        <button type="submit" class="flex-[2] py-4 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-xl transition-all hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2"
                                                :class="decision == 'OK' ? 'bg-ci-green shadow-green-900/10' : 'bg-red-600 shadow-red-900/10'">
                                            <i class="fa-solid fa-signature"></i>
                                            Signer la Décision
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                @endif

                {{-- Section Validation SDGRTE (Version Compacte) --}}
                @if($canValidate && $user->isSDGRTE())
                    <div x-data="{ choix: 'valide_sedmi', rejet: false, submitting: false }" class="mt-2 relative">
                        <!-- Affichage des erreurs de validation -->
                        @if ($errors->any())
                            <div class="mb-4 p-4 bg-red-50 border border-red-100 rounded-2xl">
                                <ul class="list-disc list-inside text-[10px] font-bold text-red-600 uppercase tracking-widest">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                            <h4 class="text-[10px] font-black text-gray-900 uppercase tracking-widest leading-none">Mes Validations</h4>
                            <i class="fa-solid fa-microchip text-gray-300 text-sm"></i>
                        </div>

                        {{-- Proposition SEDMI --}}
                        @if($demande->materiel_propose)
                            <div class="mb-3 p-3 rounded-xl border transition-all flex items-center justify-between"
                                 :class="choix === 'contre_propose' ? 'bg-gray-50/50 border-gray-200' : 'bg-white border-ci-green/30 shadow-sm'">
                                <div>
                                    <p class="text-[7px] font-black uppercase tracking-widest mb-0.5 transition-colors"
                                       :class="choix === 'contre_propose' ? 'text-gray-400' : 'text-ci-green'">Offre SEDMI</p>
                                    <p class="text-[11px] font-bold leading-tight transition-all"
                                       :class="choix === 'contre_propose' ? 'text-gray-400 line-through' : 'text-gray-900'">{{ $demande->materiel_propose }}</p>
                                </div>
                                <button type="button" 
                                        @click="choix = (choix === 'valide_sedmi' ? 'contre_propose' : 'valide_sedmi')"
                                        class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all focus:outline-none"
                                        :class="choix === 'contre_propose' ? 'bg-gray-200 text-gray-600 hover:bg-gray-300' : 'bg-orange-50 text-ci-orange hover:bg-orange-100'">
                                    <i class="fa-solid fa-pen mr-1" x-show="choix === 'valide_sedmi'"></i>
                                    <i class="fa-solid fa-times mr-1" x-show="choix === 'contre_propose'"></i>
                                    <span x-text="choix === 'contre_propose' ? 'Annuler' : 'Modifier'"></span>
                                </button>
                            </div>
                        @endif

                        <form action="{{ route('validations.store', $demande) }}" method="POST" class="space-y-3" @submit="submitting = true">
                            @csrf
                            <input type="hidden" name="decision" value="OK">
                            <input type="hidden" name="decision_sdgrte" :value="choix">

                            <div x-show="choix === 'contre_propose'" x-collapse class="space-y-2 pt-1">
                                <select name="materiel_contre_propose" :required="choix === 'contre_propose'" 
                                        class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-bold outline-none focus:ring-2 focus:ring-ci-orange/30 focus:border-ci-orange transition-all">
                                    <option value="">Catalogue {{ $demande->categorie_demandee }}</option>
                                    @foreach($materielsCategorie as $mat)
                                        <option value="{{ $mat->marque }} {{ $mat->modele }}">{{ $mat->marque }} {{ $mat->modele }}</option>
                                    @endforeach
                                </select>
                                <textarea name="justification_sdgrte" rows="1" :required="choix === 'contre_propose'" 
                                          placeholder="Justification*" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-bold outline-none focus:ring-2 focus:ring-ci-orange/30 focus:border-ci-orange transition-all h-12"></textarea>
                            </div>

                            <input type="text" name="commentaire" placeholder="Note finale ou instructions (Optionnel)" class="w-full px-3 py-3 bg-white border border-gray-100 rounded-xl text-[10px] font-bold outline-none focus:ring-2 focus:ring-ci-green/30 focus:border-ci-green transition-all">

                            <div class="flex gap-2">
                                <button type="submit" 
                                        :disabled="submitting"
                                        class="flex-[3] py-3.5 bg-ci-green text-white rounded-xl font-black text-[9px] uppercase tracking-widest shadow-lg shadow-green-900/10 active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span x-show="!submitting">Transmettre au DSI</span>
                                    <span x-show="submitting"><i class="fa-solid fa-circle-notch fa-spin mr-2"></i>Transmission...</span>
                                </button>
                                <button type="button" @click="rejet = true" :disabled="submitting"
                                        class="flex-1 py-3.5 bg-gray-100 text-gray-400 rounded-xl font-black text-[9px] uppercase hover:bg-red-50 hover:text-red-500 transition-all disabled:opacity-50">Rejet</button>
                            </div>
                        </form>

                        {{-- Modal rejet SDGRTE --}}
                        <div x-show="rejet" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center p-4">
                            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md" @click="rejet = false"></div>
                            <div class="relative bg-white w-full max-w-md p-10 rounded-[3rem] shadow-2xl text-gray-900">
                                <h3 class="text-2xl font-black tracking-tighter mb-2">Rejeter le dossier</h3>
                                <p class="text-xs text-gray-400 mb-6">Cette action est irréversible. Le demandeur sera notifié.</p>
                                <form action="{{ route('validations.store', $demande) }}" method="POST" class="space-y-4">
                                    @csrf
                                    <input type="hidden" name="decision" value="KO">
                                    <textarea name="commentaire" rows="3" placeholder="Motif du rejet (obligatoire)..." required
                                              class="w-full p-4 bg-gray-50 rounded-2xl text-sm font-bold outline-none focus:ring-2 focus:ring-red-400 resize-none"></textarea>
                                    <div class="flex gap-3">
                                        <button type="button" @click="rejet = false" class="flex-1 py-3 text-gray-400 font-black text-xs uppercase tracking-widest">Annuler</button>
                                        <button type="submit" class="flex-1 py-3 bg-red-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-red-700 transition-all">Confirmer le rejet</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ═══ FORMULAIRE DSI (Version Compacte) ═══ --}}
                @if($canValidate && $user->isDSI())
                    <div x-data="{ choixDsi: 'valide_proposition', rejet: false, submitting: false }" class="mt-2 relative overflow-hidden">
                        <!-- Affichage des erreurs de validation -->
                        @if ($errors->any())
                            <div class="mb-4 p-4 bg-red-50 border border-red-100 rounded-2xl">
                                <ul class="list-disc list-inside text-[10px] font-bold text-red-600 uppercase tracking-widest">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                            <h4 class="text-[10px] font-black text-gray-900 uppercase tracking-widest leading-none">Mes Validations</h4>
                            <i class="fa-solid fa-gavel text-gray-100 text-lg"></i>
                        </div>

                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 flex flex-col justify-center">
                                <p class="text-[9px] font-black text-ci-orange uppercase tracking-widest mb-1">Option A (Gestionnaire)</p>
                                <p class="text-xs sm:text-sm font-bold text-gray-900 leading-tight">{{ $demande->materiel_propose ?? '—' }}</p>
                            </div>
                            @if($demande->hasContreProposition())
                                <div class="p-4 bg-orange-50 rounded-2xl border border-ci-orange/10 flex flex-col justify-center">
                                    <p class="text-[9px] font-black text-ci-orange uppercase tracking-widest mb-1">Option B (SDGRTE)</p>
                                    <p class="text-xs sm:text-sm font-bold text-gray-900 leading-tight">{{ $demande->materiel_contre_propose }}</p>
                                </div>
                            @else
                                <div class="p-4 bg-ci-green/5 rounded-2xl border border-ci-green/20 flex flex-col justify-center">
                                    <p class="text-[9px] font-black text-ci-green uppercase tracking-widest mb-1">Validation SDGRTE</p>
                                    <p class="text-xs sm:text-sm font-bold text-gray-700 leading-tight">Le SDGRTE a validé l'offre du gestionnaire.</p>
                                </div>
                            @endif
                        </div>

                        <form action="{{ route('validations.store', $demande) }}" method="POST" class="space-y-3" @submit="submitting = true">
                            @csrf
                            <input type="hidden" name="decision" value="OK">
                            <!-- Par défaut, on retient s'il y a une contre prop, sinon la prop sedmi -->
                            <input type="hidden" name="decision_dsi" :value="choixDsi">

                            <div class="grid grid-cols-2 gap-2">
                                <label class="flex items-center gap-2 p-2.5 rounded-xl cursor-pointer border transition-all"
                                       :class="choixDsi === 'valide_proposition' ? 'bg-white border-ci-green/30 shadow-sm' : 'bg-white/50 border-gray-100'">
                                    <input type="radio" x-model="choixDsi" value="valide_proposition" class="hidden">
                                    <div class="h-3 w-3 rounded-full border-2" :class="choixDsi === 'valide_proposition' ? 'border-ci-green bg-ci-green' : 'border-gray-200'"></div>
                                    <span class="text-[9px] font-black text-gray-700 uppercase">Valider</span>
                                </label>
                                <label class="flex items-center gap-2 p-2.5 rounded-xl cursor-pointer border transition-all"
                                       :class="choixDsi === 'autre' ? 'bg-orange-50 border-ci-orange/30 shadow-sm' : 'bg-white/50 border-gray-100'">
                                    <input type="radio" x-model="choixDsi" value="autre" class="hidden">
                                    <div class="h-3 w-3 rounded-full border-2" :class="choixDsi === 'autre' ? 'border-ci-orange bg-ci-orange' : 'border-gray-200'"></div>
                                    <span class="text-[9px] font-black text-gray-700 uppercase">Ajuster</span>
                                </label>
                            </div>

                            <div x-show="choixDsi === 'autre'" x-collapse class="space-y-2 pt-1">
                                <select name="materiel_retenu_dsi" :required="choixDsi === 'autre'" 
                                        class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-bold outline-none focus:ring-2 focus:ring-ci-orange/30 focus:border-ci-orange transition-all">
                                    <option value="">Catalogue {{ $demande->categorie_demandee }}</option>
                                    @foreach($materielsCategorie as $mat)
                                        <option value="{{ $mat->marque }} {{ $mat->modele }}">{{ $mat->marque }} {{ $mat->modele }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <input type="text" name="commentaire_dsi" placeholder="Instructions finales (Optionnel)" class="w-full px-3 py-3 bg-white border border-gray-100 rounded-xl text-[10px] font-bold outline-none focus:ring-2 focus:ring-ci-green/30 focus:border-ci-green transition-all">

                            <div class="flex gap-2">
                                <button type="submit" 
                                        :disabled="submitting"
                                        class="flex-[3] py-3.5 bg-ci-green-dark text-white rounded-xl font-black text-[9px] uppercase tracking-widest shadow-xl shadow-green-900/10 active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span x-show="!submitting">Signer et Clôturer</span>
                                    <span x-show="submitting"><i class="fa-solid fa-circle-notch fa-spin mr-2"></i>Signature...</span>
                                </button>
                                <button type="button" @click="rejet = true" :disabled="submitting"
                                        class="flex-1 py-3.5 bg-gray-100 text-gray-400 rounded-xl font-black text-[9px] uppercase hover:bg-red-50 hover:text-red-500 transition-all disabled:opacity-50">Annuler</button>
                            </div>
                        </form>

                        {{-- Modal annulation DSI --}}
                        <div x-show="rejet" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center p-4">
                            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md" @click="rejet = false"></div>
                            <div class="relative bg-white w-full max-w-md p-10 rounded-[3rem] shadow-2xl text-gray-900">
                                <h3 class="text-2xl font-black tracking-tighter mb-2">Annuler la demande</h3>
                                <p class="text-xs text-gray-400 mb-6">La demande sera définitivement annulée. L'agent sera notifié.</p>
                                <form action="{{ route('validations.store', $demande) }}" method="POST" class="space-y-4">
                                    @csrf
                                    <input type="hidden" name="decision" value="KO">
                                    <textarea name="commentaire" rows="3" placeholder="Motif de l'annulation (obligatoire)..." required
                                              class="w-full p-4 bg-gray-50 rounded-2xl text-sm font-bold outline-none focus:ring-2 focus:ring-red-400 resize-none"></textarea>
                                    <div class="flex gap-3">
                                        <button type="button" @click="rejet = false" class="flex-1 py-3 text-gray-400 font-black text-xs uppercase tracking-widest">Annuler</button>
                                        <button type="submit" class="flex-1 py-3 bg-red-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-red-700 transition-all">Confirmer l'annulation</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if(!$canValidate && !($user->hasRole('chef_cdi') && $demande->statut == 'pre_demande'))
                    <div class="flex flex-col items-center justify-center py-6 text-center opacity-40">
                        <i class="fa-solid fa-hourglass-half text-3xl mb-4 text-ci-orange"></i>
                        <p class="text-[11px] font-bold text-white/80 leading-relaxed uppercase tracking-[0.1em]">
                            Aucune action requise <br>pour votre rôle à ce stade.
                        </p>
                    </div>
                @endif
            </div>

            <!-- Final Result Box (Bordereau) -->
            @if($demande->statut == 'validee' && $demande->affectation)
                <div class="bg-white p-8 rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-gray-50 text-gray-900 border-t-8 border-t-ci-green">
                    <h4 class="text-[10px] font-black text-ci-green uppercase tracking-[0.2em] mb-6 block">Bordereau d'Affectation</h4>
                    <div class="flex items-start gap-4 mb-8">
                        <div class="h-14 w-14 bg-green-50 rounded-2xl flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-file-pdf text-ci-green text-3xl"></i>
                        </div>
                        <div>
                            <p class="font-black text-lg tracking-tighter leading-none mb-1">{{ $demande->affectation->reference_bordereau }}</p>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Document signé électroniquement</p>
                        </div>
                    </div>
                    <a href="{{ route('affectations.download', $demande->affectation) }}" 
                       class="flex items-center justify-center gap-3 w-full py-5 bg-gray-50 text-gray-800 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-ci-green hover:text-white transition-all group">
                        <i class="fa-solid fa-download text-ci-green group-hover:text-white transition-colors"></i> 
                        Télécharger le Bordereau
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

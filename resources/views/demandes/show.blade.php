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

    <!-- Workflow Stepper -->
    <!-- Workflow Stepper (Premium Design) -->
    <div class="mb-6">
        <div class="bg-white p-8 rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden relative">
            <div class="absolute top-0 left-0 w-full h-1 bg-gray-50"></div>
            
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

            <div class="flex items-center justify-between relative px-4">
                @foreach($steps as $idx => $step)
                    <div class="flex flex-col items-center relative z-10">
                        <!-- Circle Indicator -->
                        <div class="h-12 w-12 rounded-2xl flex items-center justify-center transition-all duration-500
                            {{ $currentIdx > $idx || ($demande->statut == 'validee' && $idx == 5) ? 'bg-ci-green text-white shadow-lg shadow-green-200' : 
                               ($currentIdx == $idx && !$isFailed ? 'bg-ci-orange text-white scale-110 shadow-lg shadow-orange-200 rotate-12' : 
                               ($isFailed && $currentIdx == $idx ? 'bg-red-500 text-white animate-pulse' : 'bg-gray-50 text-gray-300')) }}">
                            @if($currentIdx > $idx || ($demande->statut == 'validee' && $idx == 5))
                                <i class="fa-solid fa-check text-sm"></i>
                            @else
                                <i class="fa-solid {{ $step['icon'] }} text-xs"></i>
                            @endif
                        </div>
                        
                        <!-- Connecting Line (Next segment) -->
                        @if($idx < 5)
                            <div class="absolute top-6 left-[3rem] w-[calc(100%-1rem)] h-1 rounded-full overflow-hidden bg-gray-50 -z-10" style="width: calc({{ 100 / count($steps) }}vw - 5rem)">
                                <div class="h-full bg-ci-green transition-all duration-1000" style="width: {{ $currentIdx > $idx ? '100' : '0' }}%"></div>
                            </div>
                        @endif

                        <div class="mt-4 text-center">
                            <p class="text-[9px] font-black uppercase tracking-[0.2em] {{ $currentIdx >= $idx ? 'text-gray-900' : 'text-gray-300' }}">{{ $step['label'] }}</p>
                            <p class="text-[8px] font-bold text-gray-400 uppercase mt-1">{{ $step['role'] }}</p>
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
            <div class="bg-white p-8 rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-100 relative overflow-hidden transition-all hover:shadow-lg">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gray-50 rounded-full blur-3xl -mr-16 -mt-16"></div>
                <h4 class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                    <span class="h-1 w-3 bg-ci-green rounded-full"></span> 
                    Décision
                </h4>
                
                @php $user = Auth::user(); @endphp

                <!-- Action Chef CDI -->
                @if($user->hasRole('chef_cdi') && $demande->statut == 'pre_demande')
                    <form action="{{ route('demandes.officialise', $demande) }}" method="POST" class="mb-4">
                        @csrf
                        <button type="submit" class="w-full py-5 bg-ci-green-dark text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:scale-[1.02] transition-all shadow-xl shadow-green-900/10 group">
                            <i class="fa-solid fa-file-circle-check mr-2 group-hover:rotate-12 transition-transform"></i> Officialiser le besoin
                        </button>
                    </form>
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
                    <div x-data="{ choix: 'valide_sedmi', rejet: false }" class="mt-4 p-4 bg-gray-50/50 rounded-2xl border border-gray-100">
                        <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                            <h4 class="text-[10px] font-black text-gray-900 uppercase tracking-widest leading-none">Arbitrage SDGRTE</h4>
                            <i class="fa-solid fa-microchip text-gray-300 text-sm"></i>
                        </div>

                        {{-- Proposition SEDMI --}}
                        @if($demande->materiel_propose)
                            <div class="mb-3 p-3 bg-white rounded-xl border border-gray-100 flex items-center justify-between">
                                <div>
                                    <p class="text-[7px] font-black text-ci-orange uppercase tracking-widest mb-0.5">Offre SEDMI</p>
                                    <p class="text-[11px] font-bold text-gray-900 leading-tight">{{ $demande->materiel_propose }}</p>
                                </div>
                                <i class="fa-solid fa-laptop-code text-gray-100 italic"></i>
                            </div>
                        @endif

                        <form action="{{ route('validations.store', $demande) }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="hidden" name="decision" value="OK">
                            <input type="hidden" name="decision_sdgrte" :value="choix">

                            <div class="grid grid-cols-2 gap-2">
                                <label class="flex items-center gap-2 p-2.5 rounded-xl cursor-pointer border transition-all"
                                       :class="choix === 'valide_sedmi' ? 'bg-white border-ci-green/30 shadow-sm' : 'bg-white/50 border-gray-100'">
                                    <input type="radio" x-model="choix" value="valide_sedmi" class="hidden">
                                    <div class="h-3 w-3 rounded-full border-2" :class="choix === 'valide_sedmi' ? 'border-ci-green bg-ci-green' : 'border-gray-200'"></div>
                                    <span class="text-[9px] font-black text-gray-700 uppercase">Valider</span>
                                </label>
                                <label class="flex items-center gap-2 p-2.5 rounded-xl cursor-pointer border transition-all"
                                       :class="choix === 'contre_propose' ? 'bg-orange-50 border-ci-orange/30 shadow-sm' : 'bg-white/50 border-gray-100'">
                                    <input type="radio" x-model="choix" value="contre_propose" class="hidden">
                                    <div class="h-3 w-3 rounded-full border-2" :class="choix === 'contre_propose' ? 'border-ci-orange bg-ci-orange' : 'border-gray-200'"></div>
                                    <span class="text-[9px] font-black text-gray-700 uppercase">Ajuster</span>
                                </label>
                            </div>

                            <div x-show="choix === 'contre_propose'" x-collapse class="space-y-2 pt-1">
                                <select name="materiel_contre_propose" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-bold outline-none">
                                    <option value="">Catalogue {{ $demande->categorie_demandee }}</option>
                                    @foreach($materielsCategorie as $mat)
                                        <option value="{{ $mat->marque }} {{ $mat->modele }}">{{ $mat->marque }} {{ $mat->modele }}</option>
                                    @endforeach
                                </select>
                                <textarea name="justification_sdgrte" rows="1" placeholder="Justification*" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-bold outline-none h-12"></textarea>
                            </div>

                            <textarea name="commentaire" rows="1" placeholder="Note finale (Optionnelle)" class="w-full px-3 py-2 bg-white border border-gray-100 rounded-xl text-[10px] font-bold outline-none h-10 resize-none"></textarea>

                            <div class="flex gap-2">
                                <button type="submit" class="flex-[3] py-3.5 bg-ci-green text-white rounded-xl font-black text-[9px] uppercase tracking-widest shadow-lg shadow-green-900/10 active:scale-95 transition-all">Transmettre au DSI</button>
                                <button type="button" @click="rejet = true" class="flex-1 py-3.5 bg-gray-100 text-gray-400 rounded-xl font-black text-[9px] uppercase hover:bg-red-50 hover:text-red-500 transition-all">Rejet</button>
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
                    <div x-data="{ choixDsi: 'retient_sedmi', rejet: false }" class="mt-4 p-4 bg-white rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden">
                        <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                            <h4 class="text-[10px] font-black text-gray-900 uppercase tracking-widest leading-none">Arbitrage Final DSI</h4>
                            <i class="fa-solid fa-gavel text-gray-100 text-lg"></i>
                        </div>

                        <div class="grid grid-cols-2 gap-2 mb-3">
                            <div class="p-2.5 bg-gray-50 rounded-xl border border-gray-100">
                                <p class="text-[6px] font-black text-ci-orange uppercase tracking-widest mb-0.5">Option A (Gestionnaire)</p>
                                <p class="text-[10px] font-bold text-gray-900 truncate">{{ $demande->materiel_propose ?? '—' }}</p>
                            </div>
                            @if($demande->hasContreProposition())
                                <div class="p-2.5 bg-orange-50 rounded-xl border border-ci-orange/10">
                                    <p class="text-[6px] font-black text-ci-orange uppercase tracking-widest mb-0.5">Option B (SDGRTE)</p>
                                    <p class="text-[10px] font-bold text-gray-900 truncate">{{ $demande->materiel_contre_propose }}</p>
                                </div>
                            @else
                                <div class="px-2.5 flex items-center justify-center bg-gray-50/30 border border-dashed border-gray-100 rounded-xl">
                                    <p class="text-[7px] text-gray-300 font-bold uppercase">Consensus</p>
                                </div>
                            @endif
                        </div>

                        <form action="{{ route('validations.store', $demande) }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="hidden" name="decision" value="OK">
                            <input type="hidden" name="decision_dsi" :value="choixDsi">

                            <div class="grid grid-cols-3 gap-2">
                                <label @click="choixDsi = 'retient_sedmi'" class="p-2.5 border rounded-xl cursor-pointer text-center transition-all"
                                       :class="choixDsi === 'retient_sedmi' ? 'bg-ci-green/5 border-ci-green/30' : 'bg-white border-gray-100'">
                                    <span class="text-[9px] font-black uppercase">Plan A</span>
                                </label>
                                @if($demande->hasContreProposition())
                                    <label @click="choixDsi = 'retient_sdgrte'" class="p-2.5 border rounded-xl cursor-pointer text-center transition-all"
                                           :class="choixDsi === 'retient_sdgrte' ? 'bg-ci-orange/5 border-ci-orange/30' : 'bg-white border-gray-100'">
                                        <span class="text-[9px] font-black uppercase">Plan B</span>
                                    </label>
                                @endif
                                <label @click="choixDsi = 'autre'" class="p-2.5 border rounded-xl cursor-pointer text-center transition-all"
                                       :class="choixDsi === 'autre' ? 'bg-purple-50 border-purple-200' : 'bg-white border-gray-100'">
                                    <i class="fa-solid fa-pen-nib text-[9px]"></i>
                                </label>
                            </div>

                            <div x-show="choixDsi === 'autre'" x-collapse>
                                <input type="text" name="materiel_retenu_dsi" placeholder="Désignation précise*" class="w-full px-3 py-2 bg-gray-50 border border-purple-100 rounded-xl text-[10px] font-bold outline-none">
                            </div>

                            <textarea name="commentaire_dsi" rows="1" placeholder="Instructions (Optionnel)" class="w-full px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-[10px] font-bold outline-none h-12"></textarea>

                            <div class="flex gap-2">
                                <button type="submit" class="flex-[3] py-4 bg-ci-green-dark text-white rounded-xl font-black text-[9px] uppercase tracking-widest shadow-xl shadow-green-900/10 active:scale-95 transition-all">Valider l'Arbitrage</button>
                                <button type="button" @click="rejet = true" class="flex-1 py-4 bg-gray-100 text-gray-400 rounded-xl font-black text-[9px] uppercase hover:text-red-500 transition-all">Annuler</button>
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

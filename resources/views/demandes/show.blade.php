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
    <div class="mb-10">
        <div class="bg-white p-8 rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden relative">
            <div class="absolute top-0 left-0 w-full h-1 bg-gray-50"></div>
            
            @php
                $steps = [
                    ['statut' => 'pre_demande', 'label' => 'Signalement', 'role' => 'Agent', 'icon' => 'fa-paper-plane'],
                    ['statut' => 'en_attente_dr', 'label' => 'Officialisation', 'role' => 'Chef CDI', 'icon' => 'fa-file-signature'],
                    ['statut' => 'transmise_gestionnaire', 'label' => 'Analyse DR', 'role' => 'Dir. Régional', 'icon' => 'fa-magnifying-glass-chart'],
                    ['statut' => 'proposition_sdrevt', 'label' => 'Stock SEDMI', 'role' => 'Gestionnaire', 'icon' => 'fa-boxes-packing'],
                    ['statut' => 'en_attente_dsi', 'label' => 'SDREVT', 'role' => 'Resp. Matériel', 'icon' => 'fa-user-check'],
                    ['statut' => 'validee', 'label' => 'Décision DSI', 'role' => 'Directeur SI', 'icon' => 'fa-award'],
                ];
                $currentIdx = 0;
                $isTerminal = in_array($demande->statut, ['validee', 'rejetee', 'annulee']);
                
                foreach($steps as $idx => $step) {
                    if($demande->statut == $step['statut']) $currentIdx = $idx;
                }
                if($demande->statut == 'validee') $currentIdx = 5;
                $isFailed = in_array($demande->statut, ['rejetee', 'annulee']);
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

    <!-- Details Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Info -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Informations Générales</p>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tighter">{{ $demande->reference }}</h3>
                    </div>
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest 
                        bg-{{ $demande->statut_color }}-50 text-{{ $demande->statut_color == 'green' ? 'ci-green' : ($demande->statut_color == 'orange' ? 'ci-orange' : $demande->statut_color) }}-600">
                        {{ $demande->statut_label }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Demandeur</p>
                        <p class="text-sm font-bold text-gray-900">{{ $demande->utilisateur->full_name }}</p>
                        <p class="text-xs text-gray-500">{{ $demande->utilisateur->email }}</p>
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

        <!-- Right: Actions -->
        <div class="space-y-8">
            <!-- Role-Based Context Box -->
            <div class="bg-ci-green-dark p-8 rounded-[2.5rem] shadow-2xl text-white relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/5 rounded-full blur-3xl"></div>
                <h4 class="text-[10px] font-black text-ci-orange uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                    <span class="h-1 w-4 bg-ci-orange rounded-full"></span> 
                    Actions Décisives
                </h4>
                
                @php $user = Auth::user(); @endphp

                <!-- Action Chef CDI -->
                @if($user->hasRole('chef_cdi') && $demande->statut == 'pre_demande')
                    <form action="{{ route('demandes.officialise', $demande) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-5 bg-white text-ci-green-dark rounded-2xl font-black text-xs uppercase tracking-widest hover:scale-105 transition-all shadow-xl shadow-black/20 group">
                            <i class="fa-solid fa-file-circle-check mr-2 group-hover:rotate-12 transition-transform"></i> Officialiser le besoin
                        </button>
                    </form>
                @endif

                @php
                    $statusMap = ['DR' => 'en_attente_dr', 'gestionnaire' => 'transmise_gestionnaire', 'SDREVT' => 'proposition_sdrevt', 'DSI' => 'en_attente_dsi'];
                    $canValidate = ($statusMap[$user->role] ?? null) === $demande->statut;
                @endphp

                {{-- ═══ FORMULAIRE STANDARD (DR, Gestionnaire) ═══ --}}
                @if($canValidate && !$user->isSDREVT() && !$user->isDSI())
                    <div class="space-y-4" x-data="{ open: false, decision: 'OK' }">
                        <button @click="open = true; decision = 'OK'" class="w-full py-5 bg-white text-ci-green-dark rounded-2xl font-black text-xs uppercase tracking-widest hover:scale-105 transition-all shadow-xl group">
                            <i class="fa-solid fa-check-double mr-2 text-ci-orange group-hover:scale-125 transition-transform"></i> Valider le dossier
                        </button>
                        <button @click="open = true; decision = 'KO'" class="w-full py-4 bg-transparent border-2 border-white/20 text-white/60 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:border-red-500/50 hover:text-red-400 transition-all">
                            Rejeter ce dossier
                        </button>

                        <!-- Modal standard -->
                        <div x-show="open" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center p-4"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100">
                            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md"></div>
                            <div class="relative bg-white w-full max-w-lg p-10 rounded-[3rem] shadow-2xl text-gray-900"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 @click.away="open = false">
                                <div class="flex items-center gap-4 mb-8">
                                    <div class="h-16 w-16 rounded-3xl flex items-center justify-center" :class="decision == 'OK' ? 'bg-green-50 text-ci-green' : 'bg-red-50 text-red-500'">
                                        <i class="fa-solid text-2xl" :class="decision == 'OK' ? 'fa-circle-check' : 'fa-circle-xmark'"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-black tracking-tighter" x-text="decision == 'OK' ? 'Valider le dossier' : 'Rejeter le dossier'"></h3>
                                        <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Enregistrement de décision</p>
                                    </div>
                                </div>
                                <form action="{{ route('validations.store', $demande) }}" method="POST" class="space-y-6">
                                    @csrf
                                    <input type="hidden" name="decision" :value="decision">
                                    @if($user->isGestionnaire())
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block ml-1">Matériel proposé (Stock SEDMI)</label>
                                            <select name="id_materiel" required class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent focus:border-ci-green rounded-2xl text-sm font-bold outline-none transition-all">
                                                <option value="">Sélectionner une unité...</option>
                                                @foreach($materielsDisponibles as $m)
                                                    <option value="{{ $m->id }}">{{ $m->marque }} {{ $m->modele }} — S/N: {{ $m->numero_serie }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block ml-1">Note technique (Optionnel)</label>
                                            <textarea name="note_gestionnaire" placeholder="Justification du choix..." class="w-full p-5 bg-gray-50 border-2 border-transparent focus:border-ci-green rounded-2xl text-sm font-bold outline-none h-24"></textarea>
                                        </div>
                                    @endif
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block ml-1">Commentaire (Optionnel)</label>
                                        <textarea name="commentaire" placeholder="Ajoutez un commentaire..." class="w-full p-5 bg-gray-50 border-2 border-transparent focus:border-ci-green rounded-2xl text-sm font-bold outline-none h-28"></textarea>
                                    </div>
                                    <div class="flex gap-4 pt-4">
                                        <button type="button" @click="open = false" class="flex-1 py-4 text-gray-400 font-black uppercase tracking-widest text-[10px]">Abandonner</button>
                                        <button type="submit" class="flex-1 py-4 text-white rounded-2xl font-black uppercase tracking-widest text-[11px] shadow-xl transition-all hover:scale-105 active:scale-95"
                                                :class="decision == 'OK' ? 'bg-ci-green shadow-green-900/10' : 'bg-red-600 shadow-red-900/10'">
                                            Signer ma décision
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ═══ FORMULAIRE SDREVT — Valider ou Contre-proposer ═══ --}}
                @if($canValidate && $user->isSDREVT())
                    <div x-data="{ choix: 'valide_sedmi', rejet: false }">

                        {{-- Rappel proposition SEDMI --}}
                        @if($demande->materiel_propose)
                            <div class="mb-5 p-4 bg-white/10 rounded-2xl">
                                <p class="text-[9px] font-black text-ci-orange uppercase tracking-widest mb-1">Proposition du Gestionnaire (SEDMI)</p>
                                <p class="text-sm font-black text-white">{{ $demande->materiel_propose }}</p>
                                @if($demande->commentaire_gestionnaire)
                                    <p class="text-[10px] text-white/60 mt-1 italic">{{ $demande->commentaire_gestionnaire }}</p>
                                @endif
                            </div>
                        @endif

                        <form action="{{ route('validations.store', $demande) }}" method="POST" class="space-y-5">
                            @csrf
                            <input type="hidden" name="decision" value="OK">
                            <input type="hidden" name="decision_sdrevt" :value="choix">

                            {{-- Choix SDREVT --}}
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-white/60 uppercase tracking-widest block">Votre décision</label>
                                <label class="flex items-center gap-3 p-4 rounded-2xl cursor-pointer transition-all"
                                       :class="choix === 'valide_sedmi' ? 'bg-white/20 border-2 border-white/40' : 'bg-white/5 border-2 border-transparent'">
                                    <input type="radio" x-model="choix" value="valide_sedmi" class="hidden">
                                    <div class="h-5 w-5 rounded-full border-2 flex items-center justify-center transition-all"
                                         :class="choix === 'valide_sedmi' ? 'border-ci-orange bg-ci-orange' : 'border-white/30'">
                                        <div x-show="choix === 'valide_sedmi'" class="h-2 w-2 rounded-full bg-white"></div>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-white">✅ Valider la proposition SEDMI</p>
                                        <p class="text-[9px] text-white/50 mt-0.5">Transmettre telle quelle au DSI</p>
                                    </div>
                                </label>

                                <label class="flex items-center gap-3 p-4 rounded-2xl cursor-pointer transition-all"
                                       :class="choix === 'contre_propose' ? 'bg-orange-500/20 border-2 border-orange-400/40' : 'bg-white/5 border-2 border-transparent'">
                                    <input type="radio" x-model="choix" value="contre_propose" class="hidden">
                                    <div class="h-5 w-5 rounded-full border-2 flex items-center justify-center transition-all"
                                         :class="choix === 'contre_propose' ? 'border-ci-orange bg-ci-orange' : 'border-white/30'">
                                        <div x-show="choix === 'contre_propose'" class="h-2 w-2 rounded-full bg-white"></div>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-white">🔄 Soumettre une contre-proposition</p>
                                        <p class="text-[9px] text-white/50 mt-0.5">Proposer un matériel alternatif au DSI</p>
                                    </div>
                                </label>
                            </div>

                            {{-- Champs contre-proposition --}}
                            <div x-show="choix === 'contre_propose'" x-collapse class="space-y-4 pt-2">
                                <div>
                                    <label class="text-[10px] font-black text-white/60 uppercase tracking-widest block mb-1">Matériel alternatif proposé *</label>
                                    <input type="text" name="materiel_contre_propose"
                                           placeholder="Ex: HP EliteBook 840 G9"
                                           class="w-full px-4 py-3 bg-white/10 border border-white/20 text-white placeholder-white/30 rounded-2xl text-sm font-bold outline-none focus:border-ci-orange transition-all">
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-white/60 uppercase tracking-widest block mb-1">Référence / Modèle</label>
                                    <input type="text" name="reference_contre_propose"
                                           placeholder="Ex: HP-840G9-I7-16GB"
                                           class="w-full px-4 py-3 bg-white/10 border border-white/20 text-white placeholder-white/30 rounded-2xl text-sm font-bold outline-none focus:border-ci-orange transition-all">
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-white/60 uppercase tracking-widest block mb-1">Justification *</label>
                                    <textarea name="justification_sdrevt" rows="3"
                                              placeholder="Pourquoi ce matériel alternatif est-il plus approprié ?"
                                              class="w-full px-4 py-3 bg-white/10 border border-white/20 text-white placeholder-white/30 rounded-2xl text-sm font-bold outline-none focus:border-ci-orange transition-all resize-none"></textarea>
                                </div>
                            </div>

                            <div>
                                <label class="text-[10px] font-black text-white/60 uppercase tracking-widest block mb-1">Commentaire (Optionnel)</label>
                                <textarea name="commentaire" rows="2" placeholder="Note visible dans l'historique..."
                                          class="w-full px-4 py-3 bg-white/10 border border-white/20 text-white placeholder-white/30 rounded-2xl text-sm font-bold outline-none focus:border-ci-orange transition-all resize-none"></textarea>
                            </div>

                            <button type="submit" class="w-full py-5 bg-white text-ci-green-dark rounded-2xl font-black text-xs uppercase tracking-widest hover:scale-105 transition-all shadow-xl">
                                <i class="fa-solid fa-paper-plane mr-2"></i> Transmettre au DSI
                            </button>

                            <button type="button" @click="rejet = true"
                                    class="w-full py-3 bg-transparent border-2 border-white/20 text-white/60 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:border-red-500/50 hover:text-red-400 transition-all">
                                Rejeter ce dossier
                            </button>
                        </form>

                        {{-- Modal rejet SDREVT --}}
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

                {{-- ═══ FORMULAIRE DSI — Arbitrage final ═══ --}}
                @if($canValidate && $user->isDSI())
                    <div x-data="{ choixDsi: 'retient_sedmi', rejet: false }">

                        {{-- Proposition A (SEDMI) --}}
                        <div class="mb-3 p-4 bg-white/10 rounded-2xl">
                            <p class="text-[9px] font-black text-ci-orange uppercase tracking-widest mb-1">Proposition A — Gestionnaire SEDMI</p>
                            <p class="text-sm font-black text-white">{{ $demande->materiel_propose ?? '—' }}</p>
                        </div>

                        {{-- Proposition B (SDREVT) si elle existe --}}
                        @if($demande->hasContreProposition())
                            <div class="mb-5 p-4 bg-orange-500/20 border border-orange-400/30 rounded-2xl">
                                <p class="text-[9px] font-black text-orange-300 uppercase tracking-widest mb-1">Proposition B — SDREVT (Contre-proposition)</p>
                                <p class="text-sm font-black text-white">{{ $demande->materiel_contre_propose }}</p>
                                @if($demande->justification_sdrevt)
                                    <p class="text-[10px] text-white/60 mt-1 italic">{{ $demande->justification_sdrevt }}</p>
                                @endif
                            </div>
                        @else
                            <div class="mb-5 p-3 bg-white/5 rounded-2xl">
                                <p class="text-[9px] text-white/40 uppercase tracking-widest">SDREVT a validé la proposition SEDMI sans contre-proposition.</p>
                            </div>
                        @endif

                        <form action="{{ route('validations.store', $demande) }}" method="POST" class="space-y-5">
                            @csrf
                            <input type="hidden" name="decision" value="OK">
                            <input type="hidden" name="decision_dsi" :value="choixDsi">

                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-white/60 uppercase tracking-widest block">Votre arbitrage</label>

                                <label class="flex items-center gap-3 p-3 rounded-xl cursor-pointer transition-all"
                                       :class="choixDsi === 'retient_sedmi' ? 'bg-white/20 border-2 border-white/40' : 'bg-white/5 border-2 border-transparent'">
                                    <input type="radio" x-model="choixDsi" value="retient_sedmi" class="hidden">
                                    <div class="h-5 w-5 rounded-full border-2 flex-shrink-0 flex items-center justify-center"
                                         :class="choixDsi === 'retient_sedmi' ? 'border-ci-orange bg-ci-orange' : 'border-white/30'">
                                        <div x-show="choixDsi === 'retient_sedmi'" class="h-2 w-2 rounded-full bg-white"></div>
                                    </div>
                                    <p class="text-xs font-black text-white">Retenir A (Proposition SEDMI)</p>
                                </label>

                                @if($demande->hasContreProposition())
                                    <label class="flex items-center gap-3 p-3 rounded-xl cursor-pointer transition-all"
                                           :class="choixDsi === 'retient_sdrevt' ? 'bg-orange-500/20 border-2 border-orange-400/40' : 'bg-white/5 border-2 border-transparent'">
                                        <input type="radio" x-model="choixDsi" value="retient_sdrevt" class="hidden">
                                        <div class="h-5 w-5 rounded-full border-2 flex-shrink-0 flex items-center justify-center"
                                             :class="choixDsi === 'retient_sdrevt' ? 'border-ci-orange bg-ci-orange' : 'border-white/30'">
                                            <div x-show="choixDsi === 'retient_sdrevt'" class="h-2 w-2 rounded-full bg-white"></div>
                                        </div>
                                        <p class="text-xs font-black text-white">Retenir B (Contre-proposition SDREVT)</p>
                                    </label>
                                @endif

                                <label class="flex items-center gap-3 p-3 rounded-xl cursor-pointer transition-all"
                                       :class="choixDsi === 'autre' ? 'bg-purple-500/20 border-2 border-purple-400/40' : 'bg-white/5 border-2 border-transparent'">
                                    <input type="radio" x-model="choixDsi" value="autre" class="hidden">
                                    <div class="h-5 w-5 rounded-full border-2 flex-shrink-0 flex items-center justify-center"
                                         :class="choixDsi === 'autre' ? 'border-ci-orange bg-ci-orange' : 'border-white/30'">
                                        <div x-show="choixDsi === 'autre'" class="h-2 w-2 rounded-full bg-white"></div>
                                    </div>
                                    <p class="text-xs font-black text-white">Imposer une 3ᵉ option</p>
                                </label>
                            </div>

                            <div x-show="choixDsi === 'autre'" x-collapse>
                                <label class="text-[10px] font-black text-white/60 uppercase tracking-widest block mb-1">Matériel imposé par le DSI *</label>
                                <input type="text" name="materiel_retenu_dsi"
                                       placeholder="Désignation précise du matériel..."
                                       class="w-full px-4 py-3 bg-white/10 border border-white/20 text-white placeholder-white/30 rounded-2xl text-sm font-bold outline-none focus:border-ci-orange transition-all">
                            </div>

                            <div>
                                <label class="text-[10px] font-black text-white/60 uppercase tracking-widest block mb-1">Note de décision DSI</label>
                                <textarea name="commentaire_dsi" rows="2" placeholder="Commentaire visible par tous les acteurs..."
                                          class="w-full px-4 py-3 bg-white/10 border border-white/20 text-white placeholder-white/30 rounded-2xl text-sm font-bold outline-none focus:border-ci-orange transition-all resize-none"></textarea>
                            </div>

                            <button type="submit" class="w-full py-5 bg-white text-ci-green-dark rounded-2xl font-black text-xs uppercase tracking-widest hover:scale-105 transition-all shadow-xl">
                                <i class="fa-solid fa-gavel mr-2"></i> Valider ma décision finale
                            </button>

                            <button type="button" @click="rejet = true"
                                    class="w-full py-3 bg-transparent border-2 border-white/20 text-white/60 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:border-red-500/50 hover:text-red-400 transition-all">
                                Annuler la demande
                            </button>
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

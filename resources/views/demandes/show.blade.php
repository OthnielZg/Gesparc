<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('demandes.index') }}" class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-ci-green transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="font-black text-3xl text-gray-900 leading-tight tracking-tighter">
                Détails de la <span class="text-ci-orange">Demande</span>
            </h2>
        </div>
    </x-slot>

    <!-- Workflow Stepper -->
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 mb-8 overflow-x-auto">
        <div class="min-w-[800px] flex items-center justify-between relative">
            <!-- Background Line -->
            <div class="absolute h-1 bg-gray-100 top-1/2 left-0 right-0 -translate-y-1/2 z-0"></div>
            
            @php
                $steps = [
                    ['statut' => 'pre_demande', 'label' => 'Création', 'role' => 'Agent'],
                    ['statut' => 'en_attente_dr', 'label' => 'Officialisation', 'role' => 'Chef CDI'],
                    ['statut' => 'transmise_gestionnaire', 'label' => 'Valid. DR', 'role' => 'DR'],
                    ['statut' => 'proposition_sdrevt', 'label' => 'Analyse Stock', 'role' => 'Gestionnaire'],
                    ['statut' => 'en_attente_dsi', 'label' => 'Valid. SDREVT', 'role' => 'SDREVT'],
                    ['statut' => 'validee', 'label' => 'Approb. DSI', 'role' => 'DSI'],
                ];
                $currentIdx = 0;
                foreach($steps as $idx => $step) {
                    if($demande->statut == $step['statut']) $currentIdx = $idx;
                }
                if($demande->statut == 'validee') $currentIdx = 6;
                if($demande->statut == 'rejetee' || $demande->statut == 'annulee') $currentIdx = -1;
            @endphp

            @foreach($steps as $idx => $step)
                <div class="relative z-10 flex flex-col items-center group">
                    <div class="h-10 w-10 rounded-full flex items-center justify-center transition-all duration-500
                        {{ $currentIdx > $idx ? 'bg-ci-green text-white' : ($currentIdx == $idx ? 'bg-ci-orange text-white scale-125' : 'bg-white border-4 border-gray-200 text-gray-300') }}">
                        @if($currentIdx > $idx)
                            <i class="fa-solid fa-check text-sm"></i>
                        @else
                            <span class="text-xs font-black">{{ $idx + 1 }}</span>
                        @endif
                    </div>
                    <div class="mt-3 text-center">
                        <p class="text-[10px] font-black uppercase tracking-widest {{ $currentIdx >= $idx ? 'text-gray-900' : 'text-gray-300' }}">{{ $step['label'] }}</p>
                        <p class="text-[8px] font-bold text-gray-400">{{ $step['role'] }}</p>
                    </div>
                </div>
            @endforeach
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
            <div class="bg-slate-900 p-8 rounded-3xl shadow-xl text-white">
                <h4 class="text-[10px] font-black text-ci-orange uppercase tracking-[0.2em] mb-4">Actions Disponibles</h4>
                
                @php $user = Auth::user(); @endphp

                <!-- Action Chef CDI -->
                @if($user->hasRole('chef_cdi') && $demande->statut == 'pre_demande')
                    <form action="{{ route('demandes.officialise', $demande) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-4 bg-ci-green text-white rounded-2xl font-black text-sm hover:scale-105 transition-all shadow-lg shadow-green-900/40">
                            <i class="fa-solid fa-file-signature mr-2"></i> Officialiser la demande
                        </button>
                    </form>
                @endif

                <!-- Actions Validateurs -->
                @php
                    $statusMap = ['DR' => 'en_attente_dr', 'gestionnaire' => 'transmise_gestionnaire', 'SDREVT' => 'proposition_sdrevt', 'DSI' => 'en_attente_dsi'];
                    $canValidate = ($statusMap[$user->role] ?? null) === $demande->statut;
                @endphp

                @if($canValidate)
                    <div class="space-y-4" x-data="{ open: false, decision: 'OK' }">
                        <button @click="open = true; decision = 'OK'" class="w-full py-4 bg-white text-slate-900 rounded-2xl font-black text-sm hover:scale-105 transition-all">
                            <i class="fa-solid fa-check-double mr-2 text-ci-green"></i> Valider / Approuver
                        </button>
                        <button @click="open = true; decision = 'KO'" class="w-full py-4 bg-red-600 text-white rounded-2xl font-black text-sm hover:scale-105 transition-all opacity-80 hover:opacity-100">
                            <i class="fa-solid fa-xmark mr-2"></i> Rejeter la demande
                        </button>

                        <!-- Validation Modal (Mockup logic with alpine) -->
                        <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
                            <div class="bg-white w-full max-w-md p-8 rounded-3xl text-gray-900">
                                <h3 class="text-xl font-black mb-6 uppercase tracking-tighter" x-text="decision == 'OK' ? 'Confirmer la Validation' : 'Motif du Rejet'"></h3>
                                <form action="{{ route('validations.store', $demande) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="decision" :value="decision">
                                    
                                    @if($user->role == 'gestionnaire')
                                        <div class="mb-4">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Matériel Proposé</label>
                                            <select name="id_materiel" required class="w-full p-3 bg-gray-50 border-0 rounded-xl text-sm font-bold">
                                                <option value="">Choisir dans le stock...</option>
                                                @foreach(\App\Models\Materiel::where('categorie', $demande->categorie_demandee)->where('statut', 'disponible')->get() as $m)
                                                    <option value="{{ $m->id }}">{{ $m->numero_serie }} - {{ $m->marque }} {{ $m->modele }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    <textarea name="commentaire" placeholder="Votre commentaire ici..." class="w-full p-4 bg-gray-50 border-0 rounded-2xl text-sm mb-6 h-32"></textarea>
                                    
                                    <div class="flex gap-4">
                                        <button type="button" @click="open = false" class="flex-1 py-3 text-gray-500 font-bold">Annuler</button>
                                        <button type="submit" class="flex-1 py-3 text-white rounded-xl font-black shadow-lg" :class="decision == 'OK' ? 'bg-ci-green shadow-green-900/20' : 'bg-red-600 shadow-red-900/20'">Confirmer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if(!$canValidate && !($user->hasRole('chef_cdi') && $demande->statut == 'pre_demande'))
                    <p class="text-center text-xs text-slate-500 italic mt-4">Aucune action requise pour votre rôle à ce stade.</p>
                @endif
            </div>

            <!-- Final Result Box -->
            @if($demande->statut == 'validee' && $demande->affectation)
                <div class="bg-ci-green p-8 rounded-3xl shadow-xl text-white">
                    <h4 class="text-[10px] font-black text-white/60 uppercase tracking-[0.2em] mb-4">Affectation Générée</h4>
                    <div class="flex items-center gap-4 mb-6">
                        <div class="h-12 w-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-file-pdf text-2xl"></i>
                        </div>
                        <div>
                            <p class="font-black text-sm tracking-tighter">{{ $demande->affectation->reference_bordereau }}</p>
                            <p class="text-[10px] font-bold opacity-80 uppercase tracking-widest">Bordereau PDF prêt</p>
                        </div>
                    </div>
                    <a href="{{ route('affectations.download', $demande->affectation) }}" class="block w-full text-center py-4 bg-white text-ci-green rounded-2xl font-black text-sm hover:translate-y-[-2px] transition-all">
                        <i class="fa-solid fa-download mr-2"></i> Télécharger le PDF
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

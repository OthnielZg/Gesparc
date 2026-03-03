<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('affectations.dotations') }}" class="h-12 w-12 rounded-2xl bg-white border border-gray-100 flex items-center justify-center text-gray-400 hover:text-ci-orange hover:border-ci-orange/30 transition-all shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="font-black text-4xl text-gray-900 leading-tight tracking-tighter mb-1">
                        Finaliser <span class="text-ci-orange">la Remise</span>
                    </h2>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest flex items-center gap-2">
                        Dossier {{ $demande->reference }} • {{ $demande->utilisateur->full_name }}
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-12">
        <!-- Récapitulatif Gauche -->
        <div class="space-y-6">
            <!-- Carte Bénéficiaire -->
            <div class="bg-white rounded-[2.5rem] p-8 shadow-[0_20px_60px_rgba(0,0,0,0.03)] border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-50 pb-4">Bénéficiaire</p>
                <div class="flex items-center gap-4">
                    <div class="h-16 w-16 rounded-3xl bg-gray-50 flex items-center justify-center text-gray-300">
                        <i class="fa-solid fa-user-tie text-3xl"></i>
                    </div>
                    <div>
                        <h4 class="font-black text-lg text-gray-900 tracking-tighter uppercase leading-tight">{{ $demande->utilisateur->full_name }}</h4>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-1">{{ $demande->utilisateur->bureau?->service?->nom_service ?? 'Direction Générale' }}</p>
                    </div>
                </div>
                <div class="mt-8 pt-8 border-t border-gray-50 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-[9px] font-black text-gray-400 uppercase">Bureau</span>
                        <span class="text-[10px] font-black text-gray-900 uppercase">{{ $demande->utilisateur->bureau?->nom_bureau ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[9px] font-black text-gray-400 uppercase">Contact</span>
                        <span class="text-[10px] font-black text-gray-900">{{ $demande->utilisateur->email }}</span>
                    </div>
                </div>
            </div>

            <!-- Carte Matériel -->
            <div class="bg-black rounded-[2.5rem] p-8 text-white relative overflow-hidden">
                <div class="absolute -right-10 -top-10 opacity-5">
                    <i class="fa-solid fa-laptop text-[12rem] rotate-12"></i>
                </div>
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-6 border-b border-white/5 pb-4">Matériel Arbitré par DSI</p>
                <div class="flex items-center gap-4 mb-8">
                    <div class="h-16 w-16 rounded-3xl bg-white/5 flex items-center justify-center text-ci-orange ring-1 ring-white/10 shadow-inner">
                        <i class="fa-solid fa-laptop text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="font-black text-lg text-white tracking-tighter uppercase leading-tight">{{ $demande->getMaterielRetenuLabel() }}</h4>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-1">Catégorie: {{ $demande->categorie_demandee }}</p>
                    </div>
                </div>
                <div class="bg-white/5 p-5 rounded-2xl border border-white/5">
                    <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                        <span class="h-1 w-1 rounded-full bg-ci-orange"></span>
                        Note d'arbitrage
                    </p>
                    <p class="text-xs font-bold italic text-gray-400 leading-relaxed">"{{ $demande->commentaire_dsi ?? 'Décision validée sans commentaire particulier.' }}"</p>
                </div>
            </div>
        </div>

        <!-- Formulaire Droite -->
        <div class="lg:col-span-2">
            <form action="{{ route('affectations.storeFinalisation', $demande) }}" method="POST" class="bg-white rounded-[2.5rem] p-10 md:p-12 shadow-[0_20px_60px_rgba(0,0,0,0.03)] border border-gray-100 flex flex-col h-full relative">
                @csrf
                <div class="flex-1">
                    <div class="mb-12">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="h-1.5 w-8 rounded-full bg-ci-orange"></span>
                            <h3 class="text-2xl font-black text-gray-900 tracking-tighter">Informations de Remise</h3>
                        </div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Renseignez les derniers détails pour clôturer le circuit</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-10">
                        <!-- Date de remise -->
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-3">Date de la remise officielle</label>
                            <div class="relative group">
                                <div class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-ci-orange transition-colors">
                                    <i class="fa-solid fa-calendar-day"></i>
                                </div>
                                <input type="date" name="date_remise" value="{{ date('Y-m-d') }}" required
                                       class="w-full pl-14 pr-6 py-5 bg-gray-50 rounded-3xl border-none text-sm font-black text-gray-900 outline-none focus:ring-4 focus:ring-ci-orange/10 transition-all">
                            </div>
                        </div>

                        <!-- Lieu de remise -->
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-3">Lieu de la remise physique</label>
                            <div class="relative group">
                                <div class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-ci-orange transition-colors">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <input type="text" name="lieu_remise" placeholder="Ex: Bureau 204, SEDMI" required
                                       class="w-full pl-14 pr-6 py-5 bg-gray-50 rounded-3xl border-none text-sm font-black text-gray-900 outline-none focus:ring-4 focus:ring-ci-orange/10 transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-10">
                        <!-- État de réception -->
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-3">État lors de la remise</label>
                            <div class="relative group">
                                <div class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-ci-orange transition-colors">
                                    <i class="fa-solid fa-sparkles"></i>
                                </div>
                                <select name="etat_reception" required
                                        class="w-full pl-14 pr-12 py-5 bg-gray-50 rounded-3xl border-none text-sm font-black text-gray-900 outline-none focus:ring-4 focus:ring-ci-orange/10 transition-all appearance-none cursor-pointer">
                                    <option value="neuf">Neuf (Sortie de carton)</option>
                                    <option value="bon">Bon état (Déjà utilisé, propre)</option>
                                    <option value="usage">Usagé (Marques d'utilisation)</option>
                                    <option value="degrade">Dégradé (Défauts mineurs)</option>
                                </select>
                                <div class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-300 pointer-events-none">
                                    <i class="fa-solid fa-chevron-down text-[10px]"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Observations -->
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-3">Observations</label>
                            <div class="relative group">
                                <div class="absolute left-6 top-6 text-gray-300 group-focus-within:text-ci-orange transition-colors">
                                    <i class="fa-solid fa-comment-dots"></i>
                                </div>
                                <textarea name="observations" rows="1" placeholder="Notez ici toute remarque..."
                                          class="w-full pl-14 pr-6 py-5 bg-gray-50 rounded-3xl border-none text-sm font-black text-gray-900 outline-none focus:ring-4 focus:ring-ci-orange/10 transition-all resize-none"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-ci-orange/5 to-transparent p-8 rounded-[2.5rem] border border-ci-orange/10 mb-12 ring-4 ring-ci-orange/5">
                        <div class="flex items-start gap-6">
                            <div class="relative h-7 w-7 flex-shrink-0">
                                <input type="checkbox" name="confirme" id="confirme" required
                                       class="h-7 w-7 rounded-xl border-2 border-ci-orange/30 text-ci-orange focus:ring-4 focus:ring-ci-orange/20 cursor-pointer appearance-none checked:bg-ci-orange checked:border-ci-orange transition-all relative">
                                <i class="fa-solid fa-check absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-white text-[10px] pointer-events-none opacity-0 checked-sibling:opacity-100 peer-checked:opacity-100 transition-opacity"></i>
                                <style>
                                    input:checked + i { opacity: 1 !important; }
                                </style>
                            </div>
                            <label for="confirme" class="cursor-pointer group">
                                <span class="text-xs font-black text-gray-900 uppercase tracking-tighter block mb-1 group-hover:text-ci-orange transition-colors">Certification de remise physique</span>
                                <span class="text-[10px] font-bold text-gray-500 leading-relaxed block max-w-lg">
                                    Je certifie avoir procédé à la remise physique de l'équipement au bénéficiaire ce jour. 
                                    La signature du bordereau par l'agent confirmera définitivement la mise en service.
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row items-center justify-between gap-6 pt-6 border-t border-gray-50">
                    <p class="text-[9px] font-black text-gray-300 uppercase tracking-[0.2em] flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved"></i>
                        Action sécurisée par session Agent
                    </p>
                    <div class="flex items-center gap-4 w-full md:w-auto">
                        <a href="{{ route('affectations.dotations') }}" class="flex-1 md:flex-none text-center px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-[0.2em] hover:text-gray-900 transition-colors">Annuler</a>
                        <button type="submit" class="flex-[2] md:flex-none px-12 py-5 bg-black text-white text-[10px] font-black uppercase tracking-[0.3em] rounded-3xl shadow-2xl shadow-black/30 hover:bg-ci-orange hover:-translate-y-1 active:translate-y-0 transition-all flex items-center justify-center gap-3">
                            Clôturer & Imprimer Bordereau
                            <i class="fa-solid fa-print"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

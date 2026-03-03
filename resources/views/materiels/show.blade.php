<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('materiels.index') }}" class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-ci-green transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="font-black text-3xl text-gray-900 leading-tight tracking-tighter">
                    Fiche <span class="text-ci-orange">Matériel</span>
                </h2>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Référence: {{ $materiel->numero_serie }}</p>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Info Card -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden relative p-8 sm:p-10">
                <div class="absolute top-0 right-0 w-64 h-64 bg-gray-50/50 rounded-full -translate-y-32 translate-x-32 pointer-events-none"></div>
                
                <div class="relative z-10">
                    <div class="flex flex-col sm:flex-row justify-between items-start gap-6 border-b border-gray-100 pb-10 mb-10">
                        <div class="flex items-center">
                            <div class="h-20 w-20 bg-ci-green-dark/5 rounded-[2rem] flex items-center justify-center text-ci-green-dark border border-ci-green-dark/10 shadow-inner">
                                @php
                                    $icons = [
                                        'ordinateur portable' => 'fa-laptop',
                                        'ordinateur bureau' => 'fa-desktop',
                                        'imprimante' => 'fa-print',
                                        'scanner' => 'fa-barcode',
                                        'serveur' => 'fa-server',
                                    ];
                                    $icon = $icons[strtolower($materiel->categorie)] ?? 'fa-cube';
                                @endphp
                                <i class="fa-solid {{ $icon }} text-3xl"></i>
                            </div>
                            <div class="ml-6">
                                <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-lg text-[10px] font-black uppercase tracking-widest">{{ $materiel->categorie }}</span>
                                <h3 class="text-3xl font-black text-gray-900 uppercase tracking-tighter mt-2">{{ $materiel->marque }} {{ $materiel->modele }}</h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <i class="fa-solid fa-hashtag text-[8px] text-gray-300"></i>
                                    <span class="text-xs font-bold text-gray-400">S/N: {{ $materiel->numero_serie }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-col items-end">
                            @php
                                $statusLabel = match($materiel->statut) {
                                    'disponible' => 'Disponible',
                                    'affecté' => 'Actuellement Affecté',
                                    'en_panne' => 'Hors Service / Panne',
                                    'en_reparation' => 'En Réparation',
                                    default => $materiel->statut
                                };
                                $statusColor = match($materiel->statut) {
                                    'disponible' => 'bg-green-50 text-ci-green border-green-100',
                                    'affecté' => 'bg-orange-50 text-ci-orange border-orange-100',
                                    'en_panne' => 'bg-red-50 text-red-600 border-red-100',
                                    default => 'bg-gray-50 text-gray-500 border-gray-100'
                                };
                            @endphp
                            <span class="px-6 py-2 rounded-2xl text-xs font-black uppercase tracking-widest border {{ $statusColor }} shadow-sm">
                                {{ $statusLabel }}
                            </span>
                            <p class="text-[10px] font-bold text-gray-300 mt-2 uppercase tracking-widest italic">Dernière mise à jour: {{ $materiel->updated_at->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-6">
                            <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest flex items-center">
                                <span class="w-8 h-1 bg-ci-orange mr-3 rounded-full"></span> Spécifications
                            </h4>
                            <div class="grid grid-cols-2 gap-y-4">
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Marque</p>
                                    <p class="text-sm font-black text-gray-700 tracking-tight">{{ $materiel->marque }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Modèle</p>
                                    <p class="text-sm font-black text-gray-700 tracking-tight">{{ $materiel->modele }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Fournisseur</p>
                                    <p class="text-sm font-black text-gray-700 tracking-tight">{{ $materiel->fournisseur->nom_fournisseur ?? 'N/A' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Prix d'acquisition</p>
                                    <p class="text-sm font-black text-gray-700 tracking-tight">{{ number_format($materiel->prix_acquisition, 0, ',', ' ') }} FCFA</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest flex items-center">
                                <span class="w-8 h-1 bg-ci-green mr-3 rounded-full"></span> Description
                            </h4>
                            <p class="text-xs font-bold text-gray-500 leading-relaxed italic">
                                {{ $materiel->description ?: 'Aucune description technique supplémentaire fournie pour ce matériel.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Affectation History Placeholder -->
            <div class="bg-white rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.01)] border border-gray-100 p-8">
                <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-6">Affectation Actuelle</h4>
                @if($materiel->affectationActive)
                    <div class="flex items-center p-6 bg-ci-green-dark text-white rounded-[1.5rem] shadow-lg">
                        <div class="h-12 w-12 rounded-full bg-white/20 flex items-center justify-center mr-4">
                            <i class="fa-solid fa-user-check"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black uppercase tracking-widest opacity-60">Matériel affecté à</p>
                            <p class="text-lg font-black tracking-tight">{{ $materiel->affectationActive->utilisateur->full_name }}</p>
                            <p class="text-[10px] font-bold uppercase tracking-widest opacity-60 mt-0.5">{{ $materiel->affectationActive->utilisateur->bureau->nom_bureau ?? 'DGI CIV' }} • Depuis le {{ $materiel->affectationActive->date_affectation->format('d/m/Y') }}</p>
                        </div>
                    </div>
                @else
                    <div class="p-10 border-2 border-dashed border-gray-100 rounded-[1.5rem] text-center">
                        <i class="fa-solid fa-box-open text-3xl text-gray-100 mb-4"></i>
                        <p class="text-xs font-black text-gray-300 uppercase tracking-widest italic">Ce matériel est actuellement libre en stock.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Actions & QR Code -->
        <div class="space-y-8">
            <!-- QR Code Card -->
            <div class="bg-white rounded-[2.5rem] shadow-[0_15px_50px_rgba(0,0,0,0.04)] border border-gray-100 p-8 text-center relative overflow-hidden group">
                <div class="absolute -top-10 -right-10 w-24 h-24 bg-ci-orange/5 rounded-full group-hover:scale-110 transition-transform"></div>
                
                <h4 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-8">Asset QR Tag <span class="text-ci-orange italic">DGI</span></h4>
                
                <div id="qrcode" class="mx-auto bg-white p-3 border border-gray-100 rounded-2xl shadow-inner w-fit"></div>
                
                <div class="mt-8 space-y-6">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">ID Inventaire unique</p>
                        <p class="text-sm font-black text-slate-900 tracking-tighter">MAT-{{ str_pad($materiel->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    
                    <button onclick="printCard()" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-slate-900/10 hover:scale-105 transition-all active:scale-95 flex items-center justify-center gap-3">
                        <i class="fa-solid fa-print"></i>
                        Imprimer l'étiquette
                    </button>
                    
                    <p class="text-[9px] font-bold text-gray-400 italic">Scannez ce code pour accéder instantanément à la fiche technique complète depuis un terminal mobile.</p>
                </div>
            </div>

            <!-- Actions Quick List -->
            <div class="bg-white rounded-[2.5rem] border border-gray-100 p-6 space-y-3">
                <a href="{{ route('materiels.edit', $materiel) }}" class="w-full flex items-center justify-between px-6 py-4 bg-gray-50 hover:bg-ci-orange hover:text-white rounded-2xl transition-all group">
                    <span class="text-xs font-black uppercase tracking-widest">Mettre à jour</span>
                    <i class="fa-solid fa-pen-nib group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="#" class="w-full flex items-center justify-between px-6 py-4 bg-gray-50 hover:bg-ci-green hover:text-white rounded-2xl transition-all group">
                    <span class="text-xs font-black uppercase tracking-widest">Signaler Panne</span>
                    <i class="fa-solid fa-triangle-exclamation group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Printable Area Hidden -->
    <div id="print-area" class="hidden">
        <div style="width: 80mm; height: 40mm; padding: 5mm; border: 2px solid black; font-family: sans-serif; display: flex; align-items: center; justify-content: space-between;">
            <div style="width: 30mm; height: 30mm;" id="qrcode-print"></div>
            <div style="flex: 1; padding-left: 5mm;">
                <h2 style="margin: 0; font-size: 14pt; font-weight: 900;">DGI - GESPARC</h2>
                <p style="margin: 2mm 0; font-size: 9pt; font-weight: 700;">{{ $materiel->marque }} {{ $materiel->modele }}</p>
                <p style="margin: 0; font-size: 8pt; font-weight: 500;">SN: {{ $materiel->numero_serie }}</p>
                <p style="margin: 2mm 0 0; font-size: 10pt; font-weight: 900; color: #EE6B12;">MAT-{{ str_pad($materiel->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        window.addEventListener('load', function() {
            var qrcode = new QRCode(document.getElementById("qrcode"), {
                text: "{{ route('materiels.show', $materiel) }}",
                width: 160,
                height: 160,
                colorDark : "#0A0A0A",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });

            var qrcodePrint = new QRCode(document.getElementById("qrcode-print"), {
                text: "{{ route('materiels.show', $materiel) }}",
                width: 100,
                height: 100,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
        });

        function printCard() {
            var printContents = document.getElementById('print-area').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            window.location.reload();
        }
    </script>
    <style>
        @media print {
            body * { visibility: hidden; }
            #print-area, #print-area * { visibility: visible; }
            #print-area { position: absolute; left: 0; top: 0; width: 100%; height: 100%; }
        }
    </style>
    @endpush
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="font-black text-4xl text-gray-900 leading-tight tracking-tighter mb-1">
                    Mon <span class="text-ci-orange">Matériel</span>
                </h2>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Équipements informatiques qui vous sont affectés</p>
            </div>
        </div>
    </x-slot>

    @if($affectations->isEmpty())
        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_60px_rgba(0,0,0,0.03)] border border-gray-100 p-20 text-center">
            <div class="h-20 w-20 bg-gray-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-gray-200">
                <i class="fa-solid fa-laptop-slash text-4xl"></i>
            </div>
            <p class="text-gray-900 font-black uppercase tracking-widest text-xs">Aucun matériel affecté</p>
            <p class="text-gray-400 text-[10px] font-bold uppercase mt-2">Vous n'avez pas encore d'équipement attribué. Soumettez un signalement de besoin.</p>
            <a href="{{ route('demandes.create') }}" class="inline-flex items-center mt-8 px-8 py-4 bg-ci-green text-white font-black text-xs uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-green-900/10 hover:scale-105 transition-all active:scale-95">
                <i class="fa-solid fa-plus mr-3"></i> Faire une demande
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($affectations as $affectation)
                @php
                    $icons = [
                        'ordinateur portable' => ['icon' => 'fa-laptop', 'color' => 'text-blue-500', 'bg' => 'bg-blue-50'],
                        'ordinateur bureau'   => ['icon' => 'fa-desktop', 'color' => 'text-indigo-500', 'bg' => 'bg-indigo-50'],
                        'imprimante'          => ['icon' => 'fa-print', 'color' => 'text-orange-500', 'bg' => 'bg-orange-50'],
                        'scanner'             => ['icon' => 'fa-barcode', 'color' => 'text-purple-500', 'bg' => 'bg-purple-50'],
                        'serveur'             => ['icon' => 'fa-server', 'color' => 'text-gray-600', 'bg' => 'bg-gray-100'],
                        'onduleur'            => ['icon' => 'fa-battery-full', 'color' => 'text-green-500', 'bg' => 'bg-green-50'],
                    ];
                    $cat   = strtolower($affectation->materiel->categorie ?? 'autre');
                    $style = $icons[$cat] ?? ['icon' => 'fa-cube', 'color' => 'text-gray-500', 'bg' => 'bg-gray-50'];
                @endphp

                <div class="bg-white rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-100 p-8 relative overflow-hidden group hover:shadow-[0_20px_60px_rgba(0,0,0,0.06)] transition-all">
                    <div class="absolute top-0 right-0 w-32 h-32 {{ $style['bg'] }} opacity-30 rounded-full -translate-y-16 translate-x-16 pointer-events-none"></div>

                    <div class="flex items-start justify-between mb-6">
                        <div class="h-14 w-14 {{ $style['bg'] }} rounded-2xl flex items-center justify-center">
                            <i class="fa-solid {{ $style['icon'] }} {{ $style['color'] }} text-2xl"></i>
                        </div>
                        <span class="px-3 py-1 bg-green-50 text-ci-green border border-green-100 rounded-full text-[9px] font-black uppercase tracking-widest">
                            Actif
                        </span>
                    </div>

                    <h3 class="text-xl font-black text-gray-900 tracking-tighter mb-1">
                        {{ $affectation->materiel->marque }} {{ $affectation->materiel->modele }}
                    </h3>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">
                        {{ $affectation->materiel->categorie }}
                    </p>

                    <div class="space-y-3 border-t border-gray-50 pt-6">
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">N° de Série</span>
                            <span class="text-xs font-black text-gray-900 font-mono">{{ $affectation->materiel->numero_serie }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Date d'affectation</span>
                            <span class="text-xs font-black text-gray-900">{{ $affectation->date_affectation->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Réf. Bordereau</span>
                            <span class="text-xs font-black text-ci-orange">{{ $affectation->reference_bordereau }}</span>
                        </div>
                    </div>

                    <!-- Bouton Voir Matériel (remplace Télécharger le bordereau) -->
                    <button onclick="openMaterielModal({{ $affectation->id }}, '{{ addslashes($affectation->materiel->marque . ' ' . $affectation->materiel->modele) }}', '{{ addslashes($affectation->materiel->categorie) }}', '{{ $affectation->materiel->numero_serie }}', '{{ $affectation->reference_bordereau }}', '{{ $affectation->date_affectation->format('d/m/Y') }}', '{{ route('materiels.show', $affectation->materiel) }}', 'MAT-{{ str_pad($affectation->materiel->id, 5, '0', STR_PAD_LEFT) }}')"
                       class="mt-6 flex items-center justify-center gap-2 w-full py-4 bg-gray-50 text-gray-600 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-ci-green-dark hover:text-white transition-all group-hover:shadow-sm">
                        <i class="fa-solid fa-qrcode"></i> Voir la Fiche Matériel
                    </button>
                </div>
            @endforeach
        </div>

        <!-- MODAL Fiche Matériel -->
        <div id="materiel-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeMaterielModal()"></div>
            <div class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden">

                <!-- Header Modal -->
                <div class="bg-ci-green-dark p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -translate-y-20 translate-x-20 pointer-events-none"></div>
                    <button onclick="closeMaterielModal()" class="absolute top-6 right-6 h-9 w-9 bg-white/10 hover:bg-white/20 rounded-xl flex items-center justify-center transition-colors z-10">
                        <i class="fa-solid fa-xmark text-white"></i>
                    </button>
                    <p id="modal-cat" class="text-[10px] font-black text-white/50 uppercase tracking-widest mb-1"></p>
                    <h3 id="modal-title" class="text-2xl font-black text-white tracking-tight"></h3>
                </div>

                <!-- Body Modal -->
                <div class="p-8">
                    <div class="flex gap-8 items-start">

                        <!-- QR Code -->
                        <div class="flex-shrink-0 flex flex-col items-center gap-3">
                            <div id="modal-qrcode" class="bg-white border border-gray-100 rounded-2xl p-2 shadow-sm"></div>
                            <p id="modal-inv-id" class="text-[10px] font-black text-gray-400 uppercase tracking-widest"></p>
                        </div>

                        <!-- Infos -->
                        <div class="flex-1 space-y-4">
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">N° de Série</p>
                                <p id="modal-serie" class="text-sm font-black text-gray-900 font-mono"></p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Date d'affectation</p>
                                <p id="modal-date" class="text-sm font-black text-gray-900"></p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Réf. Bordereau</p>
                                <p id="modal-ref" class="text-sm font-black text-ci-orange"></p>
                            </div>
                        </div>
                    </div>

                    <p class="text-[9px] font-bold text-gray-400 italic text-center mt-6">Scannez le code QR pour accéder à la fiche complète depuis un mobile.</p>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        let currentQr = null;

        function openMaterielModal(id, title, cat, serie, ref, date, url, invId) {
            document.getElementById('modal-title').textContent = title;
            document.getElementById('modal-cat').textContent = cat;
            document.getElementById('modal-serie').textContent = serie;
            document.getElementById('modal-ref').textContent = ref;
            document.getElementById('modal-date').textContent = date;
            document.getElementById('modal-inv-id').textContent = invId;

            // Effacer l'ancien QR code
            const qrContainer = document.getElementById('modal-qrcode');
            qrContainer.innerHTML = '';
            if (currentQr) { currentQr = null; }

            currentQr = new QRCode(qrContainer, {
                text: url,
                width: 130,
                height: 130,
                colorDark: "#0A0A0A",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });

            const modal = document.getElementById('materiel-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeMaterielModal() {
            const modal = document.getElementById('materiel-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeMaterielModal();
        });
    </script>
    @endpush
</x-app-layout>

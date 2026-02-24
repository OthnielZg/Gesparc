<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-3xl text-gray-900 leading-tight tracking-tighter">
            Structure <span class="text-ci-orange">Organisationnelle</span>
        </h2>
        <p class="text-xs font-bold text-gray-500 mt-2 uppercase tracking-widest">Gérer la hiérarchie des services et bureaux</p>
    </x-slot>

    <div class="space-y-8" x-data="{ openModal: null, targetId: null, targetName: '' }">
        <!-- Villes Section -->
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-black text-gray-900 uppercase tracking-widest border-l-4 border-ci-green pl-4">Villes / Localités</h3>
                <button @click="openModal = 'ville'" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold uppercase tracking-widest hover:scale-105 transition-all">
                    <i class="fa-solid fa-plus mr-2"></i> Ajouter une Ville
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($villes as $ville)
                    <div class="border border-gray-100 rounded-2xl p-6 hover:shadow-md transition-shadow group">
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-sm font-black text-gray-900 uppercase tracking-tighter">{{ $ville->nom_ville }}</span>
                            <form action="{{ route('admin.organisation.ville.destroy', $ville) }}" method="POST" onsubmit="return confirm('Supprimer cette ville ?')">
                                @csrf @method('DELETE')
                                <button class="text-gray-300 hover:text-red-500"><i class="fa-solid fa-trash-can text-xs"></i></button>
                            </form>
                        </div>
                        
                        <!-- Directions in this Ville -->
                        <div class="space-y-3">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em]">Directions :</p>
                            @forelse($ville->directions as $dir)
                                <div class="bg-gray-50 p-3 rounded-xl">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-[11px] font-bold text-gray-700">{{ $dir->nom_direction }}</span>
                                        <form action="{{ route('admin.organisation.direction.destroy', $dir) }}" method="POST" onsubmit="return confirm('Supprimer cette direction ?')">
                                            @csrf @method('DELETE')
                                            <button class="text-gray-300 hover:text-red-500"><i class="fa-solid fa-xmark text-[10px]"></i></button>
                                        </form>
                                    </div>
                                    
                                    <!-- Services in this Direction -->
                                    <div class="ml-2 pl-3 border-l border-gray-200 mt-2 space-y-2">
                                        @foreach($dir->servics as $srv)
                                            <div class="flex justify-between items-center group/srv">
                                                <span class="text-[10px] text-gray-500 font-medium">{{ $srv->nom_servic }}</span>
                                                <div class="flex gap-2">
                                                    <button @click="openModal = 'bureau'; targetId = {{ $srv->id }}; targetName = '{{ $srv->nom_servic }}'" class="opacity-0 group-hover/srv:opacity-100 text-ci-green text-[10px]"><i class="fa-solid fa-plus"></i></button>
                                                </div>
                                            </div>
                                            <!-- Bureaux list snippet -->
                                            <div class="ml-2 flex flex-wrap gap-1 mt-1">
                                                @foreach($srv->bureaux as $bur)
                                                    <span class="px-2 py-0.5 bg-white border border-gray-100 rounded text-[8px] font-bold text-gray-400 flex items-center gap-1">
                                                        {{ $bur->nom_bureau }}
                                                        <form action="{{ route('admin.organisation.bureau.destroy', $bur) }}" method="POST" class="inline">
                                                            @csrf @method('DELETE')
                                                            <button class="hover:text-red-500"><i class="fa-solid fa-times"></i></button>
                                                        </form>
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endforeach
                                        <button @click="openModal = 'service'; targetId = {{ $dir->id }}; targetName = '{{ $dir->nom_direction }}'" class="text-[9px] font-black text-ci-orange uppercase mt-2 hover:underline">
                                            + Ajouter un service
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-[10px] text-gray-400 italic">Aucune direction.</p>
                            @endforelse
                        </div>
                        
                        <button @click="openModal = 'direction'; targetId = {{ $ville->id }}; targetName = '{{ $ville->nom_ville }}'" class="mt-4 w-full py-2 border border-dashed border-gray-200 rounded-xl text-[10px] font-black text-gray-400 uppercase hover:border-ci-green hover:text-ci-green transition-all">
                            + Ajouter une Direction
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Modals (Simple implementation with Alpine) -->
        <div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
            <div class="bg-white w-full max-w-md p-8 rounded-3xl text-gray-900" @click.away="openModal = null">
                <template x-if="openModal === 'ville'">
                    <form action="{{ route('admin.organisation.ville.store') }}" method="POST">
                        @csrf
                        <h4 class="text-xl font-black mb-6 uppercase tracking-tighter">Ajouter une Ville</h4>
                        <input type="text" name="nom_ville" required class="w-full p-4 bg-gray-50 border-0 rounded-2xl text-sm mb-6" placeholder="Nom de la ville (ex: San Pedro)">
                        <div class="flex gap-4">
                            <button type="button" @click="openModal = null" class="flex-1 py-3 text-gray-500 font-bold">Annuler</button>
                            <button type="submit" class="flex-1 py-3 bg-slate-900 text-white rounded-xl font-black shadow-lg">Ajouter</button>
                        </div>
                    </form>
                </template>

                <template x-if="openModal === 'direction'">
                    <form action="{{ route('admin.organisation.direction.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_ville" :value="targetId">
                        <h4 class="text-xl font-black mb-2 uppercase tracking-tighter">Nouvelle Direction</h4>
                        <p class="text-xs text-gray-400 mb-6 uppercase font-bold tracking-widest" x-text="'DANS : ' + targetName"></p>
                        <input type="text" name="nom_direction" required class="w-full p-4 bg-gray-50 border-0 rounded-2xl text-sm mb-6" placeholder="ex: Direction Régionale">
                        <div class="flex gap-4">
                            <button type="button" @click="openModal = null" class="flex-1 py-3 text-gray-500 font-bold">Annuler</button>
                            <button type="submit" class="flex-1 py-3 bg-ci-green text-white rounded-xl font-black shadow-lg">Ajouter</button>
                        </div>
                    </form>
                </template>

                <template x-if="openModal === 'service'">
                    <form action="{{ route('admin.organisation.service.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_direction" :value="targetId">
                        <h4 class="text-xl font-black mb-2 uppercase tracking-tighter">Nouveau Service</h4>
                        <p class="text-xs text-gray-400 mb-6 uppercase font-bold tracking-widest" x-text="'DANS : ' + targetName"></p>
                        <input type="text" name="nom_servic" required class="w-full p-4 bg-gray-50 border-0 rounded-2xl text-sm mb-6" placeholder="ex: Service Moyens Généraux">
                        <div class="flex gap-4">
                            <button type="button" @click="openModal = null" class="flex-1 py-3 text-gray-500 font-bold">Annuler</button>
                            <button type="submit" class="flex-1 py-3 bg-ci-orange text-white rounded-xl font-black shadow-lg">Ajouter</button>
                        </div>
                    </form>
                </template>

                <template x-if="openModal === 'bureau'">
                    <form action="{{ route('admin.organisation.bureau.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_servic" :value="targetId">
                        <h4 class="text-xl font-black mb-2 uppercase tracking-tighter">Nouveau Bureau</h4>
                        <p class="text-xs text-gray-400 mb-6 uppercase font-bold tracking-widest" x-text="'DANS : ' + targetName"></p>
                        <input type="text" name="nom_bureau" required class="w-full p-4 bg-gray-50 border-0 rounded-2xl text-sm mb-6" placeholder="ex: Bureau 402">
                        <div class="flex gap-4">
                            <button type="button" @click="openModal = null" class="flex-1 py-3 text-gray-500 font-bold">Annuler</button>
                            <button type="submit" class="flex-1 py-3 bg-slate-900 text-white rounded-xl font-black shadow-lg">Ajouter</button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>
</x-app-layout>

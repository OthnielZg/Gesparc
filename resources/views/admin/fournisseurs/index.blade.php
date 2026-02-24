<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-black text-3xl text-gray-900 leading-tight tracking-tighter">
                Gestion des <span class="text-ci-orange">Fournisseurs</span>
            </h2>
            <a href="{{ route('admin.fournisseurs.create') }}" class="inline-flex items-center px-6 py-3 bg-ci-green text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg shadow-green-900/10 hover:scale-105 transition-all">
                <i class="fa-solid fa-truck-plus mr-2"></i> Ajouter un Fournisseur
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Fournisseur</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Email</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Téléphone</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Adresse</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($fournisseurs as $f)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="text-sm font-black text-gray-900 uppercase tracking-tighter">{{ $f->nom_fournisseur }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $f->email ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $f->telephone ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 italic">{{ Str::limit($f->adresse, 30) ?: 'N/A' }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.fournisseurs.edit', $f) }}" class="p-2 text-gray-400 hover:text-blue-600"><i class="fa-solid fa-pen"></i></a>
                                    <form action="{{ route('admin.fournisseurs.destroy', $f) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce fournisseur ?')">
                                        @csrf @method('DELETE')
                                        <button class="p-2 text-gray-400 hover:text-red-600"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400 italic">Aucun fournisseur trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50">
            {{ $fournisseurs->links() }}
        </div>
    </div>
</x-app-layout>

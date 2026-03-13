<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.fournisseurs.index') }}" class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-ci-green transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="font-black text-3xl text-gray-900 leading-tight tracking-tighter">
                Modifier <span class="text-ci-orange">Fournisseur</span>
            </h2>
        </div>
    </x-slot>

    <div class="max-w-2xl">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8">
                <form action="{{ route('admin.fournisseurs.update', $fournisseur) }}" method="POST" class="space-y-6">
                    @csrf @method('PUT')
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Nom du Fournisseur</label>
                        <input type="text" name="nom_fournisseur" value="{{ old('nom_fournisseur', $fournisseur->nom_fournisseur) }}" required class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Email de contact</label>
                        <input type="email" name="email" value="{{ old('email', $fournisseur->email) }}" class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Téléphone</label>
                        <input type="text" name="telephone" value="{{ old('telephone', $fournisseur->telephone) }}" class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Adresse Physique</label>
                        <textarea name="adresse" rows="3" class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm">{{ old('adresse', $fournisseur->adresse) }}</textarea>
                    </div>
                    <div class="pt-6 flex justify-end gap-4">
                        <a href="{{ route('admin.fournisseurs.index') }}" class="px-6 py-3 border border-gray-200 text-gray-500 rounded-xl font-bold text-sm">Annuler</a>
                        <button type="submit" class="px-8 py-3 bg-slate-900 text-white rounded-xl font-black text-sm hover:scale-105 transition-all">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-ci-green transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="font-black text-3xl text-gray-900 leading-tight tracking-tighter">
                Nouvel <span class="text-ci-orange">Utilisateur</span>
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8">
                <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Prénom</label>
                            <input type="text" name="prenom" value="{{ old('prenom') }}" required class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Nom</label>
                            <input type="text" name="nom" value="{{ old('nom') }}" required class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Rôle</label>
                            <select name="role" required class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm">
                                @foreach(\App\Models\User::roles() as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Bureau</label>
                            <select name="id_bureau" class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm">
                                <option value="">Choisir un bureau...</option>
                                @foreach($bureaux as $bureau)
                                    <option value="{{ $bureau->id }}">{{ $bureau->nom_bureau }} ({{ $bureau->servic->nom_servic }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Email Professionnel</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Contact / Téléphone</label>
                            <input type="text" name="contact" value="{{ old('contact') }}" class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Mot de passe</label>
                            <input type="password" name="password" required class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" required class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm">
                        </div>
                    </div>
                    <div class="pt-6 flex justify-end gap-4">
                        <a href="{{ route('admin.users.index') }}" class="px-6 py-3 border border-gray-200 text-gray-500 rounded-xl font-bold text-sm">Annuler</a>
                        <button type="submit" class="px-8 py-3 bg-slate-900 text-white rounded-xl font-black text-sm hover:scale-105 transition-all">Créer le compte</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <div x-data="{ 
        activeTab: 'users', 
        search: ''
    }" class="space-y-8">

        <x-slot name="header">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h2 class="font-black text-4xl text-gray-900 leading-tight tracking-tighter mb-1">
                        Gestion <span class="text-ci-orange">Utilisateurs</span>
                    </h2>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Administration des comptes et accès • DGI</p>
                </div>
                
                <a href="{{ route('admin.users.create') }}" 
                    class="inline-flex items-center px-8 py-4 bg-ci-green text-white font-black text-xs uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-ci-green/10 hover:scale-105 transition-all active:scale-95">
                    <i class="fa-solid fa-user-plus text-white mr-3"></i> 
                    Nouvel Utilisateur
                </a>
            </div>
        </x-slot>

        <!-- Aperçu des Statistiques -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5 group hover:border-ci-orange transition-all">
                <div class="h-12 w-12 bg-ci-orange/5 rounded-2xl flex items-center justify-center text-ci-orange group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-users text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Total Comptes</p>
                    <p class="text-xl font-black text-slate-900 tracking-tighter">{{ $users->total() }}</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5 group hover:border-ci-green transition-all">
                <div class="h-12 w-12 bg-ci-green/5 rounded-2xl flex items-center justify-center text-ci-green group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-user-check text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Comptes Actifs</p>
                    <p class="text-xl font-black text-slate-900 tracking-tighter">{{ \App\Models\User::where('actif', true)->count() }}</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5 group hover:border-red-500 transition-all">
                <div class="h-12 w-12 bg-red-50 rounded-2xl flex items-center justify-center text-red-500 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-user-lock text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Comptes Bloqués</p>
                    <p class="text-xl font-black text-slate-900 tracking-tighter">{{ \App\Models\User::where('actif', false)->count() }}</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5 group hover:border-slate-900 transition-all">
                <div class="h-12 w-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-hover:bg-slate-900 group-hover:text-white transition-all">
                    <i class="fa-solid fa-shield-halved text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Administrateurs</p>
                    <p class="text-xl font-black text-slate-900 tracking-tighter">{{ \App\Models\User::where('role', 'admin')->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Barre d'outils et Recherche -->
        <div class="bg-white p-4 rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.02)] border border-gray-100 flex flex-col lg:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="h-10 w-10 bg-gray-50 rounded-xl flex items-center justify-center text-slate-400 border border-gray-100">
                    <i class="fa-solid fa-filter text-xs"></i>
                </div>
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Liste des collaborateurs DGI</span>
            </div>

            <div class="relative w-full lg:w-96">
                <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-gray-300">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" x-model="search" 
                       class="block w-full pl-12 pr-6 py-4 bg-gray-50 border-2 border-transparent focus:border-ci-orange focus:bg-white rounded-[1.5rem] transition-all outline-none text-sm font-bold shadow-sm placeholder:text-gray-300"
                       placeholder="Rechercher un utilisateur...">
            </div>
        </div>

        <!-- Tableau des Utilisateurs (Style Tableau de Bord Uniforme) -->
        <div class="bg-white rounded-[2.5rem] border border-gray-100 overflow-hidden shadow-[0_10px_40px_rgba(0,0,0,0.01)] relative min-h-[400px]">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 italic">Identité</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">Rôle & Accès</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">Bureau</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 text-center">Status</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $user)
                    <tr x-show="search === '' || '{{ strtolower($user->prenom . ' ' . $user->nom) }}'.includes(search.toLowerCase()) || '{{ strtolower($user->email) }}'.includes(search.toLowerCase())" class="hover:bg-gray-50/80 transition-all group">
                        <td class="px-8 py-6">
                            <div class="flex items-center">
                                <div class="h-12 w-12 bg-slate-50 border border-gray-100 rounded-2xl flex items-center justify-center text-slate-400 text-xs font-black mr-4 group-hover:border-ci-orange group-hover:text-ci-orange transition-all">
                                    {{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-900 uppercase tracking-tighter">{{ $user->full_name }}</p>
                                    <p class="text-[10px] font-medium text-gray-400 italic">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            @php
                                $roleColors = match($user->role) {
                                    'admin' => 'bg-slate-900 text-white shadow-slate-900/20',
                                    'gestionnaire' => 'bg-ci-orange text-white shadow-ci-orange/20',
                                    'agent' => 'bg-gray-50 text-gray-500 border border-gray-100',
                                    default => 'bg-slate-50 text-slate-400'
                                };
                            @endphp
                            <span class="px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest {{ $roleColors }} shadow-sm">
                                {{ $user->getRoleLabel() }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-[11px] font-black text-slate-800 uppercase tracking-tighter">{{ $user->bureau->nom_bureau ?? 'SANS BUREAU' }}</span>
                                <span class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">{{ $user->bureau?->service?->nom_service ?? 'DGI CIV' }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST">
                                @csrf
                                <button type="submit" title="{{ $user->actif ? 'Bloquer ce compte' : 'Activer ce compte' }}"
                                    class="inline-flex items-center px-4 py-2 rounded-2xl text-[9px] font-black uppercase tracking-widest transition-all shadow-sm border
                                    {{ $user->actif 
                                        ? 'bg-ci-green/10 text-ci-green border-ci-green/20 hover:bg-ci-green hover:text-white' 
                                        : 'bg-red-50 text-red-600 border-red-100 hover:bg-red-600 hover:text-white' }}">
                                    
                                    <span class="h-1.5 w-1.5 rounded-full bg-current mr-2 {{ $user->actif ? 'animate-pulse' : '' }}"></span>
                                    {{ $user->actif ? 'Actif' : 'Bloqué' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('admin.users.edit', $user) }}" class="h-10 w-10 flex items-center justify-center bg-white border border-gray-100 text-gray-400 rounded-xl hover:text-ci-orange hover:border-ci-orange/30 hover:shadow-lg transition-all"><i class="fa-solid fa-pen-to-square text-xs"></i></a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Confirmer la suppression définitive ?')">
                                        @csrf @method('DELETE')
                                        <button class="h-10 w-10 flex items-center justify-center bg-white border border-gray-100 text-gray-400 rounded-xl hover:text-red-500 hover:border-red-500/30 hover:shadow-lg transition-all"><i class="fa-solid fa-trash-can text-xs"></i></button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-8 py-20 text-center text-gray-400 font-bold uppercase text-[10px] tracking-widest italic">Aucun utilisateur enregistré</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($users->hasPages())
                <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-100">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

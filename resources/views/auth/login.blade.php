<x-guest-layout>
    <div class="min-h-screen flex flex-col md:flex-row overflow-hidden bg-white">
        
        <!-- Left Section: Image (Hidden on small mobile if needed) -->
        <div class="relative md:w-1/2 lg:w-3/5 h-64 md:h-auto overflow-hidden">
            <!-- Note: Using absolute path to existing assets for now or placeholder if not found -->
            <img src="{{ asset('img/bg-login.jpg') }}" 
                 alt="DGI Background" 
                 class="absolute inset-0 w-full h-full object-cover object-[center_25%]">
            
            <!-- Overlay with DGI Colors -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#009E60]/60 to-slate-900/80 mix-blend-multiply"></div>
            
            <!-- Branding on Image -->
            <div class="absolute inset-0 flex flex-col justify-end p-12 text-white hidden md:flex">
                <h2 class="text-4xl font-black leading-tight mb-2">
                    Simplifier la gestion,<br>
                    <span class="text-ci-orange">Fortifier l'efficacité.</span>
                </h2>
                <p class="text-gray-300 max-w-sm text-sm font-medium">
                    Portail de Gestion du Parc Informatique - Direction Générale des Impôts.
                </p>
            </div>
        </div>

        <!-- Right Section: Login Form -->
        <div class="flex-grow flex items-center justify-center p-8 bg-white z-10 relative shadow-2xl">
            <div class="w-full max-w-md">
                <!-- Logo & Title -->
                <div class="text-center mb-8">
                    <img src="{{ asset('img/logo_dgi.png') }}" alt="Logo DGI" class="h-20 mx-auto mb-6 drop-shadow-sm">
                    <h1 class="text-2xl font-black text-gray-900 tracking-tighter uppercase">
                        Connexion au Portail
                    </h1>
                    <p class="text-gray-500 text-sm mt-2 font-medium">République de Côte d'Ivoire</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-4 p-4 rounded-2xl bg-red-50 border border-red-100">
                        <ul class="text-xs font-bold text-red-600 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li><i class="fa-solid fa-circle-exclamation mr-2"></i> {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6 mt-8">
                    @csrf

                    <!-- Email Address -->
                    <div class="relative group">
                        <label for="email" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block transition-colors group-focus-within:text-ci-green">
                            Email professionnel
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                <i class="fa-solid fa-envelope text-xs"></i>
                            </span>
                            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                                class="block w-full pl-10 pr-4 py-4 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-2xl transition-all outline-none text-sm font-bold shadow-sm"
                                placeholder="votre.nom@dgi.ci">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="relative group mt-4">
                        <label for="password" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block transition-colors group-focus-within:text-ci-green">
                            Mot de passe
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                <i class="fa-solid fa-lock text-xs"></i>
                            </span>
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                class="block w-full pl-10 pr-4 py-4 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-2xl transition-all outline-none text-sm font-bold shadow-sm"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="block mt-4 flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded-lg border-gray-300 text-ci-green shadow-sm focus:ring-ci-green" name="remember">
                            <span class="ms-2 text-xs font-bold text-gray-500 uppercase tracking-widest">{{ __('Se souvenir de moi') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-xs font-bold text-ci-orange hover:underline uppercase tracking-widest" href="{{ route('password.request') }}">
                                {{ __('Oublié ?') }}
                            </a>
                        @endif
                    </div>

                    <div class="pt-2">
                        <button type="submit" 
                            class="w-full py-4 px-6 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 font-black text-sm flex items-center justify-center gap-3 group">
                            Se connecter
                            <i class="fa-solid fa-arrow-right text-ci-orange transition-transform group-hover:translate-x-1"></i>
                        </button>
                    </div>
                </form>

                <div class="mt-12 pt-8 border-t border-gray-100 flex justify-between items-center text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                    <span>© {{ date('Y') }} DGI - CI</span>
                    <span class="text-ci-green">GESPARC v1.0</span>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

<x-guest-layout>
    <div class="min-h-[100dvh] md:h-screen flex flex-col md:flex-row overflow-hidden bg-white">
        
        <!-- Left Section: Image (S'adapte: caché sur mobile, visible sur tablette/PC) -->
        <div class="relative hidden md:block md:w-1/2 lg:w-3/5 h-full overflow-hidden">
            <img src="{{ asset('images/bg_dgi.webp') }}" 
                 alt="DGI Background" 
                 class="absolute inset-0 w-full h-full object-cover object-center animate-slow-zoom">
            
            <!-- Overlay with DGI Colors -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#009E60]/60 to-slate-900/80 mix-blend-multiply"></div>
            
            <!-- Branding on Image -->
            <div class="absolute inset-0 flex flex-col justify-end p-8 lg:p-12 text-white">
                <h2 class="text-3xl lg:text-4xl font-black leading-tight mb-2">
                    Simplifier la gestion,<br>
                    <span class="text-ci-orange">Fortifier l'efficacité.</span>
                </h2>
                <p class="text-gray-300 max-w-sm text-xs lg:text-sm font-medium">
                    Portail de Gestion du Parc Informatique - Direction Générale des Impôts.
                </p>
            </div>
        </div>

        <!-- Right Section: Login Form (Prend toute la place sur mobile) -->
        <div class="flex-1 w-full flex items-center justify-center p-6 sm:p-8 lg:p-12 bg-white z-10 relative md:shadow-2xl overflow-y-auto">
            <div class="w-full max-w-md">
                <!-- Logo & Title -->
                <!-- Logo & Title -->
                <div class="text-center mb-6">
                    <img src="{{ asset('images/logo_dgi.png') }}" alt="Logo DGI" class="h-20 mx-auto mb-4 drop-shadow-sm">
                    <h1 class="text-2xl font-black text-gray-900 tracking-tighter uppercase">
                        Connexion au Portail
                    </h1>
                    <p class="text-gray-500 text-[10px] mt-1 font-medium uppercase tracking-widest">République de Côte d'Ivoire</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Info Message (Session Expired, etc.) -->
                @if(session('info'))
                    <div class="mb-4 p-4 rounded-2xl bg-blue-50 border border-blue-100 flex items-center gap-3">
                        <i class="fa-solid fa-circle-info text-blue-500"></i>
                        <p class="text-xs font-bold text-blue-700 tracking-tight">{{ session('info') }}</p>
                    </div>
                @endif

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

                <form method="POST" action="{{ route('login') }}" class="space-y-5 {{ $errors->any() ? 'mt-4' : 'mt-8' }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="relative group">
                        <label for="email" class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block transition-colors group-focus-within:text-ci-orange">
                            Email professionnel
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                <i class="fa-solid fa-envelope text-xs"></i>
                            </span>
                            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                                class="block w-full pl-10 pr-4 py-4 bg-gray-50 border-2 border-transparent focus:border-ci-orange focus:ring-ci-orange focus:bg-white rounded-2xl transition-all outline-none text-sm font-bold shadow-sm"
                                placeholder="votre.nom@dgi.ci">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="relative group mt-4 "x-data="{ show: false }">
                        <label for="password" class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block transition-colors group-focus-within:text-ci-orange">
                            Mot de passe
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                <i class="fa-solid fa-lock text-xs"></i>
                            </span>
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                class="block w-full pl-10 pr-4 py-4 bg-gray-50 border-2 border-transparent focus:border-ci-orange focus:ring-ci-orange focus:bg-white rounded-2xl transition-all outline-none text-sm font-bold shadow-sm"
                                placeholder="••••••••" :type="show ? 'text' : 'password'">
                            <!-- Bouton Œil magique -->
                            <button type="button" 
                                    @click="show = !show" 
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-ci-orange focus:outline-none transition-colors">
                                <i id="eye-icon" class="fa-solid fa-eye text-sm" :class="show ? 'fa-eye' : 'fa-eye-slash'"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="block mt-4 flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded-lg border-gray-300 text-ci-orange shadow-sm focus:ring-ci-orange" name="remember">
                            <span class="ms-2 text-[9px] font-black text-gray-500 uppercase tracking-widest">{{ __('Se souvenir de moi') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-[9px] font-black text-ci-orange hover:underline uppercase tracking-widest" href="{{ route('password.request') }}">
                                {{ __('Oublié ?') }}
                            </a>
                        @endif
                    </div>

                    <div class="pt-2">
                        <button type="submit" 
                            class="w-full py-4 px-6 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 font-black text-xs flex items-center justify-center gap-3 group">
                            Se connecter
                            <i class="fa-solid fa-arrow-right text-ci-orange transition-transform group-hover:translate-x-1"></i>
                        </button>
                    </div>
                </form>

                <div class="mt-6 pt-4 border-t border-gray-100 flex justify-between items-center text-[9px] text-gray-400 font-black uppercase tracking-widest">
                    <span>© {{ date('Y') }} DGI - CI</span>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

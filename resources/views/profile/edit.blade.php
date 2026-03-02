<x-app-layout>


    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Profile Summary Card -->
            <div
                class="p-8 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] sm:rounded-[2rem] border border-gray-100 relative overflow-hidden">
                <!-- Decorative background block -->
                <div class="absolute top-0 left-0 right-0 h-32 bg-ci-green"></div>

                <div class="relative flex flex-col items-center mt-12">
                    <!-- Large Avatar Circle -->
                    <div
                        class="h-32 w-32 rounded-full bg-white border-4 border-white shadow-xl flex items-center justify-center overflow-hidden text-ci-orange font-black text-5xl z-10">
                        @if(isset(Auth::user()->photo_path) && Auth::user()->photo_path)
                            <img src="{{ asset('storage/' . Auth::user()->photo_path) }}" alt="Photo de profil"
                                class="h-full w-full object-cover">
                        @else
                            {{ substr(Auth::user()->prenom, 0, 1) }}{{ substr(Auth::user()->nom, 0, 1) }}
                        @endif
                    </div>

                    <!-- User Info Text -->
                    <div class="mt-6 text-center">
                        <h3 class="text-3xl font-black text-gray-900 tracking-tight">{{ Auth::user()->full_name }}</h3>
                        <div class="mt-2 inline-flex items-center px-4 py-1.5 rounded-full bg-ci-bg text-ci-green-dark">
                            <i class="fa-solid fa-briefcase text-sm mr-2 opacity-70"></i>
                            <span
                                class="text-xs font-black uppercase tracking-widest">{{ Auth::user()->getRoleLabel() }}</span>
                        </div>
                        <p class="text-sm font-bold text-gray-400 mt-3">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Photo Update Card (Tuteur 100%) -->
            <div class="p-8 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] sm:rounded-[2rem] border border-gray-100">
                <div class="max-w-3xl mx-auto">
                    @include('profile.partials.update-photo-form')
                </div>
            </div>

            <!-- Password Update Card -->
            <div class="p-8 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] sm:rounded-[2rem] border border-gray-100">
                <div class="max-w-3xl mx-auto">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
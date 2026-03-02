<section>
    <header>
        <h2 class="text-lg font-black text-ci-green-dark">
            {{ __('Photo de profil') }}
        </h2>

        <p class="mt-1 text-sm font-bold text-gray-500">
            {{ __("Mettez à jour votre photo de profil pour personnaliser votre compte.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div
            class="flex flex-col md:flex-row items-center gap-8 bg-gray-50/50 p-8 rounded-[2rem] border border-dashed border-gray-200">
            <!-- Current Photo or Placeholder -->
            <div
                class="h-40 w-40 shrink-0 rounded-full bg-white border-4 border-white shadow-xl flex items-center justify-center overflow-hidden text-ci-green-dark font-black text-5xl group relative">
                @if(isset($user->photo_path) && $user->photo_path)
                    <img src="{{ asset('storage/' . $user->photo_path) }}" alt="Photo de profil"
                        class="h-full w-full object-cover">
                @else
                    {{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}
                @endif
                <div
                    class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    <i class="fa-solid fa-camera text-white text-2xl"></i>
                </div>
            </div>

            <div class="flex-1 w-full">
                <x-input-label for="photo" :value="__('Choisir une nouvelle photo')"
                    class="mb-3 text-xs font-black uppercase tracking-widest text-gray-400" />
                <div class="relative">
                    <input type="file" id="photo" name="photo" accept="image/*" required
                        class="block w-full text-sm text-gray-500
                        file:mr-6 file:py-3 file:px-6
                        file:rounded-2xl file:border-0
                        file:text-xs file:font-black file:uppercase file:tracking-widest
                        file:bg-ci-green file:text-white
                        hover:file:bg-ci-green-dark transition-all cursor-pointer border-2 border-transparent focus:border-ci-green bg-white rounded-2xl shadow-sm" />
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('photo')" />
                <p
                    class="mt-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider bg-white/50 inline-block px-3 py-1 rounded-full border border-gray-100">
                    Format: PNG, JPG ou GIF • Taille max: 2 Mo</p>
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
            <button type="submit"
                class="bg-ci-green inline-flex items-center px-6 py-3 border border-transparent rounded-xl font-black text-xs text-white uppercase tracking-widest hover:bg-ci-green-dark focus:bg-ci-green-dark active:bg-ci-green-dark focus:outline-none focus:ring-2 focus:ring-ci-orange focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                {{ __('Enregistrer la photo') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-bold text-ci-green"><i class="fa-solid fa-check mr-2"></i>{{ __('Sauvegardée.') }}
                </p>
            @endif
        </div>
    </form>
</section>
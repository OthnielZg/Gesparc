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

        <div class="flex items-center gap-6">
            <!-- Current Photo or Placeholder -->
            <div class="h-24 w-24 shrink-0 rounded-full bg-ci-bg border-4 border-white shadow-md flex items-center justify-center overflow-hidden text-ci-green-dark font-black text-3xl">
                @if(isset($user->photo_path) && $user->photo_path)
                    <img src="{{ asset('storage/' . $user->photo_path) }}" alt="Photo de profil" class="h-full w-full object-cover">
                @else
                    {{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}
                @endif
            </div>

            <div class="flex-1">
                <x-input-label for="photo" :value="__('Choisir une nouvelle photo')" class="mb-2" />
                <input type="file" id="photo" name="photo" accept="image/*" class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2.5 file:px-4
                    file:rounded-xl file:border-0
                    file:text-sm file:font-bold
                    file:bg-ci-bg file:text-ci-green-dark
                    hover:file:bg-ci-green hover:file:text-white transition-all cursor-pointer border border-gray-200 rounded-xl" />
                <x-input-error class="mt-2" :messages="$errors->get('photo')" />
                <p class="mt-2 text-xs font-bold text-gray-400">PNG, JPG ou GIF (Max. 2 Mo)</p>
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
            <button type="submit" class="bg-ci-green inline-flex items-center px-6 py-3 border border-transparent rounded-xl font-black text-xs text-white uppercase tracking-widest hover:bg-ci-green-dark focus:bg-ci-green-dark active:bg-ci-green-dark focus:outline-none focus:ring-2 focus:ring-ci-orange focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                {{ __('Enregistrer la photo') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-bold text-ci-green"
                ><i class="fa-solid fa-check mr-2"></i>{{ __('Sauvegardée.') }}</p>
            @endif
        </div>
    </form>
</section>

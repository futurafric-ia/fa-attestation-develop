
<x-base-layout>
    <div class="flex flex-col h-screen" style="background-color: #edf2f7">
        <div class="grid place-items-center mx-2 my-20 sm:my-auto">
            <div class="flex items-center px-4 py-2">
                <a href="{{ route('dashboard') }}" class="h-28 mx-auto">
                    <x-logo class="h-full w-full"/>
                </a>
            </div>
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 flex flex-col mt-4">
                <form method="POST">
                    @csrf
                    <div class="mb-4">
                        <x-label for="password" value="Mot de passe"></x-label>
                        <x-input class="mt-1" id="password" type="password" name="password" required autocomplete="new-password"></x-input>
                        @error('password')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <x-label for="password_confirmation" value="Retapez le mot de passe"></x-label>
                        <x-input class="mt-1" id="password_confirmation" type="password" name="password_confirmation" required
                        autocomplete="new-password"></x-input>
                    </div>
                    <div>
                        <x-button type="submit" class="w-full btn-primary">
                            Enregistrer et se connecter
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-base-layout>

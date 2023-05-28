<div class="bg-white max-w-md mx-auto rounded-md shadow-lg">
    @section('title', 'Confirm your password')

    <div >
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <a href="#">
                <x-logo class="w-auto h-30 mx-auto text-indigo-600" />
            </a>

            <h2 class="mt-6 text-2xl font-extrabold text-center text-gray-900 leading-9">
                {{ __('Confirmer votre mot de passe') }}
            </h2>
            <p class="mt-2 text-sm text-center text-gray-600 leading-5 max-w">
                {{ __("S'il vous plaît, veuillez entrer votre mot de passe avant de continuer") }}
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="px-4 py-8 bg-white shadow sm:rounded-lg sm:px-10">
                <form wire:submit.prevent="confirm">
                    <x-input label="{{ __('Mot de passe') }}" name="password" autofocus required
                        wire:model.lazy="password" />

                    <div class="flex items-center justify-end mt-6">
                        <div class="text-sm leading-5">
                            <a href="{{ route('password.request') }}"
                                class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                                {{ __('Mot de passe oublié?') }}
                            </a>
                        </div>
                    </div>

                    <div class="mt-6">
                        <span class="block w-full rounded-md shadow-sm">
                            <x-loading-button class="btn-primary" wire:target="confirm" type="submit">
                                {{ __('Continuer') }}
                            </x-loading-button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

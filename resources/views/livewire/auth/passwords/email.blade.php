<div>
    @section('title', 'Reset password')

    <section class="flex flex-col md:flex-row h-screen items-center bg-white">
        <img src="{{ asset('static/images/wave.png') }}" class="fixed hidden md:block lg:block inset-0 h-full" />
        <div class="w-screen h-screen flex flex-col justify-center items-center lg:grid lg:grid-cols-2">
            <img src="{{ asset('static/images/man_with_env.png') }}"
                class="cursor-pointer hidden lg:block  w-3/4 hover:scale-125 transition-all duration-500 transform mx-auto" />
            @if ($emailSentMessage)
                <div class="rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm leading-5 font-medium text-green-800">
                                {{ $emailSentMessage }}
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <form wire:submit.prevent="sendResetPasswordLink" class="flex flex-col justify-center items-center">
                    <div class="text-center">
                        <x-logo class="inline-block max-w-xs h-auto px-5 -py-2" />
                    </div>
                    <h2 class="mt-6 mb-5 text-2xl font-extrabold text-center text-gray-900 leading-9">
                        {{ __('Réinitialisation du mot de passe') }}
                    </h2>

                    <div>
                        <div class="relative">
                            <input name="email" required autofocus wire:model.defer="email"
                                placeholder="E-mail de récuperation"
                                class="pl-8 py-2 border-b-2 font-display focus:outline-none focus:border-blue-600 text-lg ml-3" />
                            <div
                                class="inline-flex items-center justify-center absolute left-0 top-0 h-full w-10 text-primary-500">
                                <span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <x-input-error for="email" class="mt-2" />
                    </div>

                    <div class="mt-8">
                        <span class="block w-full rounded-md shadow-sm">
                            <x-loading-button class="btn-primary" type="submit">
                                {{ __('Envoyer le lien de réinitialisation') }}
                            </x-loading-button>
                        </span>
                    </div>

                    <hr class="my-6" />

                    <p class="text-xs text-gray-500 mt-4">&copy; {{ date('Y') }} FA E-Attestation -
                        {{ __('Tous droits réservés') }}.</p>
                </form>
            @endif
        </div>
    </section>

</div>


    <section class="flex flex-col md:flex-row h-screen items-center bg-white">
        <img src="/static/images/wave.png" class="fixed hidden md:block lg:block inset-0 h-full"/>
            <div class="w-screen h-screen flex flex-col justify-center items-center lg:grid lg:grid-cols-2">
                <img src="/static/images/man_with_key.png" class="cursor-pointer hidden lg:block  w-3/4 hover:scale-125 transition-all duration-200 transform mx-auto"/>
                <form wire:submit.prevent="authenticate" class="flex flex-col justify-center items-center">
                    <div class="text-center">
                        <x-logo class="inline-block max-w-xs h-auto px-5 -py-2"/>
                    </div>
                    <h3 class="mt-2 mb-4 font-display font-bold text-2xl text-primary-800 text-center">
                        Connectez-vous à votre plateforme
                    </h3>

                    <div>
                        <div class="relative">
                            <x-input wire:loading.attr="disabled" wire:target="authenticate" type="email" placeholder="Adresse E-mail" wire:model.defer="email" name="email" class="focus:outline-none focus:border-blue-500 text-base"/>
                        </div>
                        <x-input-error for="email" class="mt-2" />
                    </div>

                    <div>
                        <div class="relative mt-6">
                            <x-input wire:loading.attr="disabled" wire:target="authenticate" type="password" placeholder="Mot de passe" name="password" wire:model.defer="password" class="focus:outline-none focus:border-blue-600 text-base"/>
                        </div>
                    </div>


                    <div class="flex items-center font-semibold justify-between mt-8 mr-5 text-sm text-primary-900">
                        <x-checkbox
                            wire:loading.attr="disabled"
                            wire:target="authenticate"
                            label="{{ __('Rester connecté') }}"
                            name="remember"
                            wire:model="remember"
                        />

                        <div class="text-xs leading-5 ml-5">
                            <a href="{{ route('password.request') }}"
                               class="text-sm  font-semibold text-primary-800 hover:text-primary-900 focus:text-primary-900">
                                {{ __('Mot de passe oublié?') }}
                            </a>
                        </div>
                    </div>

                    <x-loading-button type="submit" wire:target="authenticate" class="btn-primary px-10 mt-6 text-white hover:bg-opacity-50">Se connecter</x-loading-button>

                    <hr class="my-8"/>

                    <p class="text-xs text-gray-500 mt-4">&copy; {{ date('Y') }} FA E-Attestation -
                        {{ __('Tous droits réservés') }}.
                    </p>
                </form>
            </div>
    </section>

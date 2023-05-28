<div class="flex flex-col h-screen bg-gray-100">
    <div class="grid place-items-center mx-2 my-20 sm:my-auto">
        <div>
            <x-logo></x-logo>
        </div>
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 flex flex-col">
            <form wire:submit.prevent="resetPassword">
                <div>
                    <x-label for="email" value="Adresse E-mail"></x-label>
                    <x-input label="Adresse Email" name="email" required autofocus wire:model.defer="email"
                             autocomplete="email"/>
                    <x-input-error for="email" class="mt-2"/>
                </div>
                <div class="mt-2">
                    <x-label for="password" value="Mot de passe"></x-label>
                    <x-input label="'Mot de passe" name="password" type="password" required
                             wire:model.defer="password"/>
                    <x-input-error for="password" class="mt-2"/>
                </div>
                <div class="mt-2">
                    <x-label for="password" value="Retapez votre mot de passe"></x-label>
                    <x-input label="Mot de passe" name="password_confirmation" type="password" required
                             wire:model.defer="passwordConfirmation"/>
                    <x-input-error for="password" class="mt-2"/>
                </div>
                <div class="mt-4">
                    <x-loading-button class="btn-primary" type="submit" class="w-full">
                        Réinitialiser
                    </x-loading-button>
                </div>
            </form>
        </div>
    </div>
</div>

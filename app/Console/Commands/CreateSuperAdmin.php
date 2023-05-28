<?php

namespace App\Console\Commands;

use Domain\Authorization\Models\Role;
use Domain\User\Actions\CreateUserAction;
use Domain\User\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-super-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Créer un utilisateur super admin';

    private CreateUserAction $createUserAction;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CreateUserAction $createUserAction)
    {
        $this->createUserAction = $createUserAction;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->createUserAction->execute($this->getDetails());

        return 0;
    }

    /**
     * Ask for super admin details.
     */
    private function getDetails(): array
    {
        $details['identifier'] = $this->ask('Matricule');
        $details['last_name'] = $this->ask('Nom');
        $details['first_name'] = $this->ask('Prénoms');
        $details['email'] = $this->ask('Email');
        $details['password'] = $this->secret('Mot de passe');
        $details['confirm_password'] = $this->secret('Confirmer mot de passe');
        $details['email_verified_at'] = now();
        $details['type'] = User::TYPE_ADMIN;
        $details['active'] = true;
        $details['roles'] = [Role::SUPER_ADMIN];

        while (! $this->isValidPassword($details['password'], $details['confirm_password'])) {
            if (! $this->isRequiredLength($details['password'])) {
                $this->error('Le mot de passe doit contenir au moins six (6) caratères.');
            }

            if (! $this->isMatch($details['password'], $details['confirm_password'])) {
                $this->error('Les mots de passe de correspondent pas.');
            }

            $details['password'] = $this->secret('Mot de passe');
            $details['confirm_password'] = $this->secret('Confirmer mot de passe');
        }

        $details['password'] = Hash::make($details['password']);
        unset($details['confirm_password']);

        return $details;
    }

    /**
     * Check if password is valid.
     */
    private function isValidPassword(string $password, string $confirmPassword): bool
    {
        return $this->isRequiredLength($password) &&
            $this->isMatch($password, $confirmPassword);
    }

    /**
     * Check if password and confirm password matches.
     */
    private function isMatch(string $password, string $confirmPassword): bool
    {
        return $password === $confirmPassword;
    }

    /**
     * Checks if password is at least six characters.
     */
    private function isRequiredLength(string $password): bool
    {
        return strlen($password) >= 6;
    }
}

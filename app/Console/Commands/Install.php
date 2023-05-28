<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Storage;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hibiscus:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Démarres le processus d\'installation de système Hibiscus';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->verifyPHPVersion();
        $this->verifyExtensions();
        $this->verifyWritePermissions();
        $this->generateAppKey();
        $this->configureSystemSettings();
        $this->setupDatabase();
        $this->seedDatabase();
        $this->createSuperAdminUser();

        $this->newLine();

        $this->info('L\'installation du système Hibiscus s\'est terminée avec succès.');
    }

    /**
     * Verify that the command is running on the required PHP version.
     *
     * @return bool
     */
    protected function verifyPHPVersion()
    {
        $this->line('Vérification de la version de PHP...');

        if (version_compare(PHP_VERSION, '8.0', '>=')) {
            $this->info('La version PHP a été verifiée avec succès.');

            return true;
        }

        $this->info(
            sprintf('Vous utilisez une ancienne version de PHP (%s). Veuillez utilisez la version requise ou une version plus récente pour continuer.', PHP_VERSION)
        );

        exit;
    }

    /**
     * Verify required PHP extensions.
     *
     * @return void
     */
    protected function verifyExtensions()
    {
        $this->line('Vérification des extensions PHP...');

        $extensions = [
            'pdo_mysql', 'openssl', 'mbstring', 'tokenizer', 'curl', 'json',
        ];

        foreach ($extensions as $extension) {
            if (! extension_loaded($extension)) {
                $this->info(
                    sprintf('L\'extension PHP %s n\'est pas active.', $extension)
                );

                exit;
            }
        }

        $this->info('Les extensions PHP ont été verifiées avec succès.');
    }

    /**
     * Verify if specified folders are writable.
     *
     * @return void
     */
    protected function verifyWritePermissions()
    {
        $this->line('Vérification des permissions d\'écriture dans les dossiers...');

        $paths = [
            config_path('/'), storage_path('/'),
        ];

        foreach ($paths as $path) {
            if (! is_writable($path)) {
                $this->info(
                    sprintf('Impossible d\'écrire dans le chemin suivant (%s). Veuillez changer les permissions du dossier.', $path)
                );

                exit;
            }
        }

        $this->info('Les permissions des dossiers ont été verifiées avec succès.');
    }

    /**
     * Generate private application key.
     *
     * @return void
     */
    protected function generateAppKey()
    {
        $this->line('Génération de la clé de l\'application...');

        $this->call('key:generate', ['--force' => true]);
    }

    /**
     * Setup the database.
     *
     * @return void
     */
    protected function setupDatabase()
    {
        $this->line('Installation de la base de données...');

        $this->call('migrate:fresh', ['--force' => true]);

        $this->info('Base de données configurée avec succès.');
    }

    /**
     * Seed the database.
     *
     * @return void
     */
    protected function seedDatabase()
    {
        $this->line('Approvisionnement de la base de données...');

        $this->call('db:seed', ['--force' => true]);
    }

    /**
     * Create super admin user.
     *
     * @return void
     */
    protected function createSuperAdminUser()
    {
        $this->line('Création du super administrateur...');

        $this->call('create-super-admin');
    }
}

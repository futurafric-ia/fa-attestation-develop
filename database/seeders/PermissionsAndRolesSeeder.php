<?php

namespace Database\Seeders;

use Domain\Authorization\Models\Permission;
use Domain\Authorization\Models\Role;
use Illuminate\Database\Seeder;

/**
 * Class PermissionRoleTableSeeder.
 */
class PermissionsAndRolesSeeder extends Seeder
{
    /**
     * Run the database seed.
     */
    public function run()
    {
        Permission::create([
            'name' => 'view_backend',
            'description' => "Accès au panel d'administration de l'application",
        ]);

        $users = Permission::create([
            'name' => 'user.*',
            'description' => 'Toutes les permissions relatives aux utilisateurs',
        ]);

        $users->children()->saveMany([
            new Permission([
                'name' => 'user.list',
                'description' => 'Voir la liste des utilisateurs',
            ]),
            new Permission([
                'name' => 'user.create',
                'description' => 'Créer un utilisateur',
            ]),
            new Permission([
                'name' => 'user.update',
                'description' => 'Modifier un utilisateur',
            ]),
            new Permission([
                'name' => 'user.delete',
                'description' => 'Désactiver un utilisateur',
            ]),
            new Permission([
                'name' => 'user.impersonate',
                'description' => 'Impersonnifier un utilisateur',
            ]),
            new Permission([
                'name' => 'user.reset_password',
                'description' => 'Réinitialiser le mot de passe d\'un utilisateur',
            ]),
            new Permission([
                'name' => 'user.invite',
                'description' => 'Inviter un utilisateur externe sur la plateforme',
            ]),
        ]);

        $activities = Permission::create([
            'name' => 'activity.*',
            'description' => 'Toutes les permissions relatives aux activités',
        ]);

        $activities->children()->saveMany([
            new Permission([
                'name' => 'activity.list',
                'description' => 'Voir la liste des activités',
            ]),
        ]);

        $requests = Permission::create([
            'name' => 'request.*',
            'description' => 'Toutes les permissions relatives aux demandes',
        ]);

        $requests->children()->saveMany([
            new Permission([
                'name' => 'request.list',
                'description' => 'Voir la liste des demandes',
            ]),
            new Permission([
                'name' => 'request.create',
                'description' => 'Faire une demande',
            ]),
            new Permission([
                'name' => 'request.update',
                'description' => 'Modifier une demande',
            ]),
            new Permission([
                'name' => 'request.validate',
                'description' => 'Valider une demande',
            ]),
            new Permission([
                'name' => 'request.approve',
                'description' => 'Approuver une demande',
            ]),
            new Permission([
                'name' => 'request.reject',
                'description' => 'Réjéter une demande',
            ]),
            new Permission([
                'name' => 'request.cancel',
                'description' => 'Annuler une demande',
            ]),
        ]);

        $brokers = Permission::create([
            'name' => 'broker.*',
            'description' => 'Toutes les permissions relatives aux intermédiaires',
        ]);

        $brokers->children()->saveMany([
            new Permission([
                'name' => 'broker.list',
                'description' => 'Voir la liste des intermédiaires',
            ]),
            new Permission([
                'name' => 'broker.create',
                'description' => 'Créer un intermédiaire',
            ]),
            new Permission([
                'name' => 'broker.update',
                'description' => 'Modifier un intermédiaire',
            ]),
            new Permission([
                'name' => 'broker.delete',
                'description' => 'Désactiver un intermédiaire',
            ]),
        ]);

        $suppliers = Permission::create([
            'name' => 'supplier.*',
            'description' => 'Toutes les permissions relatives aux fournisseurs',
        ]);

        $suppliers->children()->saveMany([
            new Permission([
                'name' => 'supplier.list',
                'description' => 'Voir la liste des fournisseurs',
            ]),
            new Permission([
                'name' => 'supplier.create',
                'description' => 'Créer un fournisseur',
            ]),
            new Permission([
                'name' => 'supplier.update',
                'description' => 'Modifier un fournisseur',
            ]),
            new Permission([
                'name' => 'supplier.delete',
                'description' => 'Supprimer un fournisseur',
            ]),
        ]);

        $stock = Permission::create([
            'name' => 'stock.*',
            'description' => 'Toutes les permissions relatives au stock d\'attestations',
        ]);

        $stock->children()->saveMany([
            new Permission([
                'name' => 'stock.show',
                'description' => "Voir le stock d'attestations",
            ]),
        ]);

        $attestations = Permission::create([
            'name' => 'attestation.*',
            'description' => 'Toutes les permissions relatives aux attestations',
        ]);

        $attestations->children()->saveMany([
            new Permission([
                'name' => 'attestation.list',
                'description' => 'Voir la liste des attestations',
            ]),
            new Permission([
                'name' => 'attestation.list_anterior',
                'description' => 'Voir la liste des attestations antérieures',
            ]),
            new Permission([
                'name' => 'attestation.scan',
                'description' => 'Scanner les attestations',
            ]),
        ]);

        $supplies = Permission::create([
            'name' => 'supply.*',
            'description' => 'Toutes les permissions relatives aux approvisionnements',
        ]);

        $supplies->children()->saveMany([
            new Permission([
                'name' => 'supply.list',
                'description' => 'Voir la liste des approvisionnements',
            ]),
            new Permission([
                'name' => 'supply.create',
                'description' => 'Faire un approvisionnement',
            ]),
        ]);

        $deliveries = Permission::create([
            'name' => 'delivery.*',
            'description' => 'Toutes les permissions relatives aux livraisons',
        ]);

        $deliveries->children()->saveMany([
            new Permission([
                'name' => 'delivery.list',
                'description' => 'Voir la liste des livraisons',
            ]),
            new Permission([
                'name' => 'delivery.create',
                'description' => 'Faire une livraison',
            ]),
        ]);

        $analytics = Permission::create([
            'name' => 'analytics.*',
            'description' => 'Toutes les permissions relatives aux statistiques',
        ]);

        $analytics->children()->saveMany([
            new Permission([
                'name' => 'analytics.show',
                'description' => 'Voir les statistiques',
            ]),
        ]);

        Role::create([
            'id' => Role::SUPER_ADMIN,
            'name' => 'Super Administrateur',
        ])->givePermissionTo([
            'view_backend',
        ]);

        Role::create([
            'id' => Role::ADMIN,
            'name' => 'Administrateur',
        ])->givePermissionTo([
            'user.*',
            'supplier.*',
            'broker.*',
            'activity.*',
        ]);

        Role::create([
            'id' => Role::BROKER,
            'name' => 'Intermédiaire',
            'has_department' => true,
        ])->givePermissionTo([
            'request.list',
            'request.create',
            'request.update',
            'request.cancel',
            'delivery.list',
        ]);

        Role::create([
            'id' => Role::VALIDATOR,
            'name' => 'Validateur',
            'has_department' => true,
        ])->givePermissionTo([
            'request.list',
            'request.approve',
            'request.reject',
            'delivery.list',
        ]);

        Role::create([
            'id' => Role::SUPERVISOR,
            'name' => 'Chef de stock',
        ])->givePermissionTo([
            'request.list',
            'request.validate',
            'supply.list',
            'supply.create',
            'delivery.list',
            'attestation.list',
            'stock.*',
            'analytics.*',
        ]);

        Role::create([
            'id' => Role::MANAGER,
            'name' => 'Gestionnaire de stock',
        ])->givePermissionTo([
            'request.list',
            'attestation.list',
            'delivery.list',
            'delivery.create',
            'attestation.scan',
            'stock.*',
        ]);

        Role::create([
            'id' => Role::SOUSCRIPTOR,
            'name' => 'Souscripteur',
        ])->givePermissionTo([
            'attestation.list',
            'attestation.list_anterior',
            'attestation.scan',
        ]);

        Role::create([
            'id' => Role::AUDITOR,
            'name' => 'Controlleur Interne',
        ])->givePermissionTo([
            'attestation.list',
            'broker.list',
            'request.list',
            'delivery.list',
            'analytics.*',
        ]);
    }
}

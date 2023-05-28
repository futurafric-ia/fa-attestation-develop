<?php

Breadcrumbs::for('home', function ($trail) {
    $trail->push('Accueil', route('dashboard'));
});

Breadcrumbs::for('dashboard', function ($trail) {
    $trail->parent('home');
    $trail->push('Tableau de bord', route('dashboard'));
});

Breadcrumbs::for('notification.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Liste des notifications non lues', route('notification.index'));
});

Breadcrumbs::for('activities.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Liste des activités', route('activities.index'));
});

Breadcrumbs::for('backend.departments.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Liste des departements', route('backend.departments.index'));
});

Breadcrumbs::for('backend.users.reset', function ($trail) {
    $trail->parent('home');
    $trail->push('Réinitialisation de mot de passe', route('backend.users.reset'));
});

Breadcrumbs::for('backend.departments.create', function ($trail) {
    $trail->parent('backend.departments.index');
    $trail->push('Enregistrer un département', route('backend.departments.create'));
});

Breadcrumbs::for('backend.departments.edit', function ($trail, $id) {
    $trail->parent('backend.departments.index');
    $trail->push('Modifier un département');
});


Breadcrumbs::for('backend.imprimes.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Liste des types d\'attestation', route('backend.imprimes.index'));
});

Breadcrumbs::for('backend.imprimes.create', function ($trail) {
    $trail->parent('backend.imprimes.index');
    $trail->push('Enregistrer un type d\'attestation', route('backend.imprimes.create'));
});

Breadcrumbs::for('backend.imprimes.edit', function ($trail, $id) {
    $trail->parent('backend.imprimes.index');
    $trail->push('Modifier un type d\'attestation');
});

Breadcrumbs::for('backend.roles.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Liste des rôles et permissions', route('backend.roles.index'));
});

Breadcrumbs::for('backend.roles.create', function ($trail) {
    $trail->parent('backend.roles.index');
    $trail->push('Enregistrer un rôle', route('backend.roles.create'));
});

Breadcrumbs::for('backend.roles.edit', function ($trail, $id) {
    $trail->parent('backend.roles.index');
    $trail->push('Modifier un rôle');
});

Breadcrumbs::for('users.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Liste des utilisateurs', route('users.index'));
});

Breadcrumbs::for('users.create', function ($trail) {
    $trail->parent('users.index');
    $trail->push('Ajouter un utilisateur', route('users.create'));
});

Breadcrumbs::for('users.show', function ($trail, $id) {
    $trail->parent('users.index');
    $trail->push('Détails', route('users.show', $id));
});

Breadcrumbs::for('users.edit', function ($trail, $id) {
    $trail->parent('users.index');
    $trail->push('Modifier un utilisateur');
});

Breadcrumbs::for('brokers.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Liste des intermediaires', route('brokers.index'));
});

Breadcrumbs::for('brokers.create', function ($trail) {
    $trail->parent('brokers.index');
    $trail->push('Ajouter un intermediaire', route('brokers.create'));
});

Breadcrumbs::for('brokers.show', function ($trail, $id) {
    $trail->parent('brokers.index');
    $trail->push('Détails', route('brokers.show', $id));
});

Breadcrumbs::for('brokers.edit', function ($trail, $id) {
    $trail->parent('brokers.index');
    $trail->push('Modifier un intermédiaire');
});

Breadcrumbs::for('suppliers.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Liste des fournisseurs', route('suppliers.index'));
});

Breadcrumbs::for('suppliers.create', function ($trail) {
    $trail->parent('suppliers.index');
    $trail->push('Ajouter un fournisseur', route('suppliers.create'));
});

Breadcrumbs::for('suppliers.show', function ($trail, $id) {
    $trail->parent('suppliers.index');
    $trail->push('Détails', route('suppliers.show', $id));
});

Breadcrumbs::for('suppliers.edit', function ($trail, $id) {
    $trail->parent('suppliers.index');
    $trail->push('Modifier un fournisseur');
});

Breadcrumbs::for('request.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Liste des demandes', route('request.index'));
});

Breadcrumbs::for('request.create', function ($trail) {
    $trail->parent('request.index');
    $trail->push('Faire une demande', route('request.create'));
});

Breadcrumbs::for('request.show', function ($trail, $id) {
    $trail->parent('request.index');
    $trail->push('Détails de la demande', route('request.show', $id));
});

Breadcrumbs::for('request.edit', function ($trail, $id) {
    $trail->parent('request.index');
    $trail->push('Détails de la demande', route('request.show', $id));
    $trail->push('Modifier ma demande');
});

Breadcrumbs::for('request.stats', function ($trail) {
    $trail->parent('request.index');
    $trail->push('Statistiques');
});

Breadcrumbs::for('request.showValidationForm', function ($trail, $id) {
    $trail->parent('request.show', $id);
    $trail->push('Valider une demande', route('request.showValidationForm', $id));
});

Breadcrumbs::for('request.showApprovalForm', function ($trail, $id) {
    $trail->parent('request.show', $id);
    $trail->push('Valider une demande', route('request.showApprovalForm', $id));
});

Breadcrumbs::for('request.showDeliveryForm', function ($trail, $requestId) {
    $trail->parent('request.show', $requestId);
    $trail->push('Faire une livraison', route('request.showDeliveryForm', $requestId));
});

Breadcrumbs::for('delivery.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Liste des livraisons', route('delivery.index'));
});

Breadcrumbs::for('delivery.show', function ($trail, $id) {
    $trail->parent('delivery.index');
    $trail->push('Détails de la livraison', route('delivery.show', $id));
});

Breadcrumbs::for('delivery.reports', function ($trail) {
    $trail->parent('home');
    $trail->push('Statistiques');
});

Breadcrumbs::for('delivery.showInvoice', function ($trail) {
    $trail->parent('delivery.index');
    $trail->push('Borderaux de livraisons');
});

Breadcrumbs::for('supply.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Liste des approvisionnements', route('supply.index'));
});

Breadcrumbs::for('supply.supplyStockForm', function ($trail) {
    $trail->parent('supply.index');
    $trail->push('Approvisionner le stock', route('supply.supplyStockForm'));
});

Breadcrumbs::for('attestation.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Liste des attestations', route('attestation.index'));
});

Breadcrumbs::for('attestation.anterior.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Liste des attestations antérieures', route('attestation.anterior.index'));
});

Breadcrumbs::for('attestation.search', function ($trail) {
    $trail->parent('attestation.index');
    $trail->push('Rechercher des attestations', route('attestation.search'));
});


Breadcrumbs::for('stock.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Stock d\'attestations', route('attestation.index'));
});

Breadcrumbs::for('scan.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Liste des scans', route('scan.index'));
});

Breadcrumbs::for('scan.show', function ($trail, $scan) {
    $trail->parent('scan.index');
    $trail->push('Détails', route('scan.show', $scan));
});

Breadcrumbs::for('scan.manual', function ($trail) {
    $trail->parent('scan.index');
    $trail->push('Saisie manuelle', route('scan.manual'));
});

Breadcrumbs::for('scan.ocr', function ($trail) {
    $trail->parent('scan.index');
    $trail->push('OCR', route('scan.ocr'));
});

Breadcrumbs::for('scan.show.attestations.index', function ($trail, $scan) {
    $trail->parent('scan.show', $scan);
    $trail->push('Enregistrements réussis');
});

Breadcrumbs::for('scan.show.mismatches.index', function ($trail, $scan) {
    $trail->parent('scan.show', $scan);
    $trail->push('Attestations litigieuses');
});

Breadcrumbs::for('scan.mismatches.show.humanReview', function ($trail, $scan) {
    $trail->parent('scan.show', $scan);
    $trail->push('Révue humaine');
});

Breadcrumbs::for('settings.show', function ($trail) {
    $trail->parent('home');
    $trail->push('Paramètres');
});

Breadcrumbs::for('account.profile', function ($trail) {
    $trail->parent('home');
    $trail->push('Profil');
});

Breadcrumbs::for('account.password', function ($trail) {
    $trail->parent('home');
    $trail->push('Changement du mot de passe');
});

Breadcrumbs::for('report.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Rapports', route('report.index'));
});

Breadcrumbs::for('report.delivery', function ($trail) {
    $trail->parent('report.index');
    $trail->push('Livraisons');
});

Breadcrumbs::for('report.request', function ($trail) {
    $trail->parent('report.index');
    $trail->push('Demandes');
});

Breadcrumbs::for('notifications.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Notifications');
});

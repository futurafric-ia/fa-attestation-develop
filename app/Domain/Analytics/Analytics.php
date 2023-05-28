<?php

namespace Domain\Analytics;

use Domain\Attestation\Models\Attestation;
use Domain\Attestation\Models\AttestationType;
use Domain\Attestation\States\Attributed;
use Domain\Attestation\States\Returned;
use Domain\Attestation\States\Used;
use Domain\Broker\Models\Broker;
use Domain\Delivery\Models\Delivery;
use Domain\Department\Models\Department;
use Domain\Request\Models\Request;
use Domain\Request\States\Approved;
use Domain\Request\States\Cancelled;
use Domain\Request\States\Delivered;
use Domain\Request\States\Pending;
use Domain\Request\States\Rejected;
use Domain\Request\States\Validated;
use Domain\Scan\Models\Scan;
use Domain\Supply\Models\Supplier;
use Domain\Supply\Models\Supply;
use Domain\User\Models\User;

final class Analytics
{
    /**
     * Retournes le nombre total d'utilisateurs SAHAM.
     */
    public function totalUserCount(): int
    {
        return User::onlySahamUsers()->count();
    }

    /**
     * Retournes le nombre total d'intermediaires.
     */
    public function totalBrokerCount(): int
    {
        return Broker::count();
    }

    /**
     * Retournes le nombre total d'attestations
     */
    public function totalAttestationCount(): int
    {
        return Attestation::count();
    }

    /**
     * Retournes le nombre total de scan effectués.
     */
    public function totalScanCount(): int
    {
        return Scan::count();
    }

    /**
     * Retournes le nombre total de livraisons.
     */
    public function totalDeliveryCount(): int
    {
        return Delivery::count();
    }

    /**
     * Retournes le nombre total de départements.
     */
    public function totalDepartmentCount(): int
    {
        return Department::count();
    }

    /**
     * Retournes le nombre total de fournisseurs.
     */
    public function totalSupplierCount(): int
    {
        return Supplier::count();
    }

    /**
     * Retournes le nombre total de demande validées.
     *
     * @param Broker|null $broker
     * @return int
     */
    public function totalValidatedRequestCount(?Broker $broker = null): int
    {
        return $this->totalRequestCountForState(Validated::class, $broker);
    }

    /**
     * Retournes le nombre total de demande approvées.
     *
     * @param Broker|null $broker
     * @return int
     */
    public function totalApprovedRequestCount(?Broker $broker = null): int
    {
        $query = Request::query()->whereState('state', '!=', Pending::class)->allowedForUser(request()->user());

        $query->when($broker, function ($builder) use ($broker) {
            return $builder->where('broker_id', $broker->id);
        });

        return $query->count();

        return $this->totalRequestCountForState(Approved::class, $broker);
    }

    /**
     * Retournes le nombre total de demande en attente.
     *
     * @param Broker|null $broker
     * @return int
     */
    public function totalPendingRequestCount(?Broker $broker = null): int
    {
        return $this->totalRequestCountForState(Pending::class, $broker);
    }

    /**
     * Retournes le nombre total de demande livrées.
     *
     * @param Broker|null $broker
     * @return int
     */
    public function totalDeliveredRequestCount(?Broker $broker = null): int
    {
        return $this->totalRequestCountForState(Delivered::class, $broker);
    }

    /**
     * Retournes le nombre total de demande rejetées.
     *
     * @param Broker|null $broker
     * @return int
     */
    public function totalRejectedRequestCount(?Broker $broker = null): int
    {
        return $this->totalRequestCountForState(Rejected::class, $broker);
    }

    /**
     * Retournes le nombre total de demande annulées.
     *
     * @param Broker|null $broker
     * @return int
     */
    public function totalCancelledRequestCount(?Broker $broker = null): int
    {
        return $this->totalRequestCountForState(Cancelled::class, $broker);
    }

    /**
     * Retournes le nombre total d'utilisateurs d'un intermediaire.
     *
     * @param Broker|null $broker
     * @return int
     */
    public function totalBrokerUsersCount(Broker $broker): int
    {
        $query = User::query()
            ->onlyBrokers()
            ->whereHas('currentBroker', function ($builder) use ($broker) {
                return $builder->where('id', $broker->id);
            });

        return $query->count();
    }

    /**
     * Retournes le nombre total d'approvisionnements.
     *
     * @param Supplier|null $supplier
     * @return int
     */
    public function totalSupplyCount(?Supplier $supplier = null): int
    {
        $query = Supply::when($supplier, function ($builder) use ($supplier) {
            return $builder->where('supplier_id', $supplier->id);
        });

        return $query->count();
    }

    /**
     * Retournes le nombre total d'attestations en stock.
     */
    public function totalAvailableStock(): int
    {
        return Attestation::query()->deliverable()->count();
    }

    /**
     * Retournes le nombre total d'attestations verte en stock.
     */
    public function totalAvailableGreenStock(): int
    {
        return $this->totalAvailableStockForType(AttestationType::GREEN);
    }

    /**
     * Retournes le nombre total d'attestations jaune en stock.
     */
    public function totalAvailableYellowStock(): int
    {
        return $this->totalAvailableStockForType(AttestationType::YELLOW);
    }

    /**
     * Retournes le nombre total d'attestations brunes en stock.
     */
    public function totalAvailableBrownStock(): int
    {
        return $this->totalAvailableStockForType(AttestationType::BROWN);
    }

    /**
     * Retournes le nombre total d'attestation attribuées.
     *
     * @param Broker|null $broker
     * @return int
     */
    public function totalAttributedAttestationCount(?Broker $broker = null): int
    {
        return $this->totalAttestationCountForState(Attributed::class, $broker);
    }

    /**
     * Retournes le nombre total d'attestation utilisées.
     *
     * @param Broker|null $broker
     * @return int
     */
    public function totalUsedAttestationCount(?Broker $broker = null): int
    {
        return $this->totalAttestationCountForState(Used::class, $broker);
    }

    /**
     * Retournes le nombre total d'attestation retournées.
     *
     * @param Broker|null $broker
     * @return int
     */
    public function totalReturnedAttestationCount(?Broker $broker = null): int
    {
        return $this->totalAttestationCountForState(Returned::class, $broker);
    }

    /**
     * Retournes le nombre total d'attestation retournées.
     *
     * @param Broker|null $broker
     * @return int
     */
    public function totalCancelledAttestationCount(?Broker $broker = null): int
    {
        return $this->totalAttestationCountForState(Cancelled::class, $broker);
    }

    /**
     * Retournes le nombre total d'attestations d'un type en stock.
     *
     * @param int $type
     * @return int
     */
    public function totalAvailableStockForType($type): int
    {
        return Attestation::query()
            ->deliverable()
            ->when($type, function ($builder) use ($type) {
                return $builder->where('attestation_type_id', $type);
            })
            ->count();
    }

    /**
     * Retournes le nombre total d'attestations disponible pour une livraison venant
     * d'un fournisseur.
     *
     * @param Supplier $supplier
     * @return int
     */
    public function totalAvailableStockForSupplier(Supplier $supplier): int
    {
        return Attestation::query()
            ->deliverable()
            ->whereHas('supply', function ($builder) use ($supplier) {
                return $builder->where('supplier_id', $supplier->id);
            })
            ->count();
    }

    /**
     * Retournes le nombre total de demande pour un status donné.
     *
     * @param string $state
     * @param Broker|null $broker
     * @return int
     */
    public function totalRequestCountForState(string $state, ?Broker $broker = null): int
    {
        $query = Request::query()->whereState('state', $state)->onlyParent()->allowedForUser(request()->user());

        $query->when($broker, function ($builder) use ($broker) {
            return $builder->where('broker_id', $broker->id);
        });

        return $query->count();
    }

    /**
     * Retournes le nombre total de demandes d'un intermediaire.
     *
     * @param Broker $broker
     * @return int
     */
    public function totalRequestCountForBroker(Broker $broker): int
    {
        $query = Request::query()->where('broker_id', $broker->id);

        return $query->count();
    }

    /**
     * Retournes le nombre total d'attestations d'un status donné.
     *
     * @param string $state
     * @param Broker|null $broker
     * @return int
     */
    public function totalAttestationCountForState(string $state, ?Broker $broker = null): int
    {
        $query = Attestation::whereState('state', $state);

        $query->when($broker, function ($builder) use ($broker) {
            return $builder->where('current_broker_id', $broker->id);
        });

        return $query->count();
    }
}

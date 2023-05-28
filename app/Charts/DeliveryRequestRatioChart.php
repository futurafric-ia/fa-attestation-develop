<?php

namespace App\Charts;

use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use DB;
use Domain\Attestation\Models\AttestationType;
use Domain\Delivery\Models\Delivery;
use Domain\Delivery\States\Done;
use Domain\Request\Models\Request;
use Support\Date;

class DeliveryRequestRatioChart extends BaseChart
{
    public function handler(\Illuminate\Http\Request $httpRequest): Chartisan
    {
        $requestsData = $this->getInqueriesData($httpRequest);
        $deliveriesData = $this->getDeliveriesData($httpRequest);

        return Chartisan::build()
            ->labels(Date::MONTHS)
            ->dataset('Demandes', array_values($requestsData))
            ->dataset('Livraisons', array_values($deliveriesData));
    }

    private function getInqueriesData(\Illuminate\Http\Request $httpRequest)
    {
        $query = Request::select([DB::raw('count(id) as `total`'), DB::raw('MONTH(created_at) month')])
            ->onlyParent()
            ->whereYear('created_at', (int) $httpRequest->input('year') ?: date('Y'))
            ->groupBy('month');

        return $query->get()->reduce(function ($acc, $current) {
            $acc[$current->month] = $current->total;

            return $acc;
        }, $this->defaultValue());
    }

    private function getDeliveriesData(\Illuminate\Http\Request $httpRequest)
    {
        $query = Delivery::select([DB::raw('count(id) as `total`'), DB::raw('MONTH(delivered_at) month')])
            ->whereState('state', [Done::class])
            ->whereNotIn('attestation_type_id', [AttestationType::BROWN])
            ->whereYear('created_at', (int) $httpRequest->input('year') ?: date('Y'))
            ->groupBy('month');

        return $query->get()->reduce(function ($acc, $current) {
            $acc[$current->month] = $current->total;

            return $acc;
        }, $this->defaultValue());
    }

    private function defaultValue(): array
    {
        return [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
            6 => 0,
            7 => 0,
            8 => 0,
            9 => 0,
            10 => 0,
            11 => 0,
            12 => 0,
        ];
    }
}

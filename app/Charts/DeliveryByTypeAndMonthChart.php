<?php

namespace App\Charts;

use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use DB;
use Domain\Attestation\Models\AttestationType;
use Domain\Delivery\Models\Delivery;
use Domain\Delivery\States\Done;
use Illuminate\Http\Request;
use Support\Date;

class DeliveryByTypeAndMonthChart extends BaseChart
{
    public function handler(Request $request): Chartisan
    {
        $data = $this->getData($request);
        $chartisan = Chartisan::build()->labels(Date::MONTHS);

        AttestationType::all()->each(function ($attestationType) use (&$chartisan, $data) {
            $chartisan = $chartisan
                ->dataset(
                    $attestationType->name,
                    array_values($data[$attestationType->id] ?? $this->defaultValue())
                );
        });

        return $chartisan;
    }

    private function getData(Request $request)
    {
        $query = Delivery::select(['attestation_type_id', DB::raw('count(id) as `total`'), DB::raw('MONTH(created_at) as `month`')])
            ->whereState('state', Done::class)
            ->whereYear('created_at', (int) $request->input('year') ?: date('Y'))
            ->groupBy('attestation_type_id', 'month');

        $query->when($request->user()->currentBroker, function ($builder) use ($request) {
            $builder->where('broker_id', $request->user()->currentBroker->id);
        });

        return $query->get()->reduce(function ($acc, $current) {
            if (! isset($acc[$current->attestation_type_id])) {
                $acc[$current->attestation_type_id] = $this->defaultValue();
            }

            $acc[$current->attestation_type_id][$current->month] = $current->total;

            return $acc;
        }, []);
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

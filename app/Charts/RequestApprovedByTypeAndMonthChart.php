<?php

namespace App\Charts;

use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use DB;
use Domain\Attestation\Models\AttestationType;
use Domain\Request\Models\Request;
use Domain\Request\States\Approved;
use Domain\Request\States\Delivered;
use Domain\Request\States\Validated;
use Illuminate\Http\Request as HttpRequest;
use Support\Date;

class RequestApprovedByTypeAndMonthChart extends BaseChart
{
    public function handler(HttpRequest $httpRequest): Chartisan
    {
        $data = $this->getData($httpRequest);
        $chartisan = Chartisan::build()->labels(Date::MONTHS);

        AttestationType::requestable()->get()->each(function ($attestationType) use (&$chartisan, $data) {
            $chartisan = $chartisan
                ->dataset(
                    $attestationType->name,
                    array_values($data[$attestationType->id] ?? $this->defaultValue())
                );
        });

        return $chartisan;
    }

    private function getData(HttpRequest $request)
    {
        $query = Request::select(['attestation_type_id', DB::raw('count(id) as `total`'), DB::raw('MONTH(created_at) as `month`')])
            ->onlyParent()
            ->whereState('state', [Approved::class, Validated::class, Delivered::class])
            ->whereYear('created_at', (int) $request->input('year') ?: date('Y'))
            ->groupBy('attestation_type_id', 'month');

        $query->when($request->user()->isBroker(), function ($builder) use ($request) {
            $builder->where('broker_id', $request->user()->current_broker_id);
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

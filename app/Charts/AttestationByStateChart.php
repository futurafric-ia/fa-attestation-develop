<?php

namespace App\Charts;

use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use DB;
use Domain\Attestation\Models\Attestation;
use Illuminate\Http\Request;

class AttestationByStateChart extends BaseChart
{
    public function handler(Request $request): Chartisan
    {
        $data = $this->getData($request);

        return Chartisan::build()->labels(array_keys($data))->dataset('Dataset', array_values($data));
    }

    private function getData(Request $request)
    {
        $query = Attestation::select(['state', DB::raw('count(*) as total')])->groupBy('state');

        $query->when($request->user()->isBroker(), function ($builder) use ($request) {
            $builder->where('current_broker_id', $request->user()->current_broker_id);
        });

        $query->when($request->input('attestation_type_id'), function ($builder) use ($request) {
            $builder->where('attestation_type_id', $request->input('attestation_type_id'));
        });

        return $query->get()->reduce(function ($acc, $current) {
            $acc[$current->state->label()] = $current->total;

            return $acc;
        }, []);
    }
}

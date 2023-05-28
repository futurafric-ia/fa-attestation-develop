<?php

namespace Domain\Attestation\Parsers;

use Bassim\SuperExpressive\SuperExpressive;
use Illuminate\Support\Carbon;

abstract class AttestationParser
{
    public const ATTESTATION_TYPE = null;

    protected static array $cache = [];

    abstract public function parse(string $text): array;

    abstract public function getAttestationNumber(string $text): ?int;

    abstract public function getInsuredName(string $text): ?string;

    abstract public function getMatriculation(string $text): ?string;

    abstract public function getPoliceNumber(string $text): ?string;

    public function getVehicleType(string $text): ?string
    {
        $regexes = [
            SuperExpressive::create()->unicode()->caseInsensitive()->string("Transport de personne")->toRegexString(),
            SuperExpressive::create()->unicode()->caseInsensitive()->string("Voiture particulière")->toRegexString(),
            SuperExpressive::create()->unicode()->caseInsensitive()->string("Véhicule propre compte")->toRegexString(),
            SuperExpressive::create()->unicode()->caseInsensitive()->string("Engin de chantier")->toRegexString(),
        ];

        return $this->findMatch($text, $regexes);
    }

    public function getMake(string $text): ?string
    {
        $regexes = [
            "/TOYOTA/",
            "/HYUNDAI/",
            "/HYUNDAI SANTAFE/",
            "/LAND ROVER/",
            "/FORD/",
            "/MERCEDES/",
            "/PEUGEOT/",
            "/SINOTRUCK HOWO/",
            "/SUZUKI/",
            "/RENAULT/",
            "/MAZDA/",
            "/NISSAN/",
            "/FOTON/",
            "/MITSUBISHI/",
            "/DACIA/",
            "/HONDA/",
            "/ISUZU/",
        ];

        return $this->findMatch($text, $regexes) ?: null;
    }

    public function getStartDate(string $text): ?string
    {
        $dates = $this->getDates($text);

        return $dates ? $dates['start_date'] : null;
    }

    public function getEndDate(string $text): ?string
    {
        $dates = $this->getDates($text);

        return $dates ? $dates['end_date'] : null;
    }

    public function getDates(string $text): ?array
    {
        if (isset(self::$cache[$text])) {
            return self::$cache[$text];
        }

        $regexes = [
            SuperExpressive::create()
                ->exactly(2)->digit()
                ->char('/')
                ->exactly(2)->digit()
                ->char('/')
                ->exactly(4)->digit()
                ->toRegexString(),
        ];

        $results = $this->findMatches($text, $regexes);
        $dates = $results ? $results[0] : null;

        if ($dates && count($dates) !== 2) {
            return null;
        }

        [$_firstDate, $_lastDate] = $dates;

        try {
            $firstDate = Carbon::createFromFormat('d/m/Y', $_firstDate);
            $lastDate = Carbon::createFromFormat('d/m/Y', $_lastDate);

            if ($lastDate->greaterThan($firstDate)) {
                [$startDate, $endDate] = [$firstDate, $lastDate];
            } else {
                [$startDate, $endDate] = [$lastDate, $firstDate];
            }
        } catch (\Throwable $th) {
            return null;
        }

        self::$cache[$text] = ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d'),];

        return self::$cache[$text];
    }

    public function getAddress(string $text): ?string
    {
        $regexes = [
            SuperExpressive::create()
                ->oneOrMore()->digit()
                ->oneOrMore()->whitespaceChar()
                ->string('BP')
                ->oneOrMore()->whitespaceChar()
                ->atLeast(3)->digit()
                ->oneOrMore()->whitespaceChar()
                ->oneOrMore()->word()
                ->oneOrMore()->whitespaceChar()
                ->oneOrMore()->digit()
                ->toRegexString(),
        ];

        return $this->findMatch($text, $regexes);
    }

    protected function findMatch(string $text, array $regexes): ?string
    {
        $result = null;

        foreach ($regexes as $regex) {
            $result = preg_match($regex, $text, $matches) ? $matches[0] : null;

            if ($result) {
                break;
            }
        }

        return $result ? trim($result) : $result;
    }

    protected function findMatches(string $text, array $regexes): ?array
    {
        $results = null;

        foreach ($regexes as $regex) {
            $results = preg_match_all($regex, $text, $matches) ? $matches : null;

            if ($results) {
                break;
            }
        }

        return $results;
    }
}

<?php

namespace Domain\Attestation\Parsers;

use Bassim\SuperExpressive\SuperExpressive;
use Domain\Attestation\Models\AttestationType;

class GreenAttestationParser extends AttestationParser
{
    public const ATTESTATION_TYPE = AttestationType::GREEN;

    public function parse(string $text): array
    {
        return [
            'attestation_number' => $this->getAttestationNumber($text),
            'insured_name' => $this->getInsuredName($text),
            'police_number' => $this->getPoliceNumber($text),
            'matriculation' => $this->getMatriculation($text),
            'address' => $this->getAddress($text),
            'start_date' => $this->getStartDate($text),
            'end_date' => $this->getEndDate($text),
            'vehicle_type' => $this->getVehicleType($text),
            'make' => $this->getMake($text),
        ];
    }

    public function getAttestationNumber(string $text): ?int
    {
        $regexes = [
            "/\b805\d+/",
        ];

        return (int)$this->findMatch($text, $regexes) ?: null;
    }

    public function getMatriculation(string $text): ?string
    {
        $regexes = [
            "/\s\d{2,4}\s?[A-Z][A-Z]\s?\d{2,3}\s/",
        ];

        $match = $this->findMatch($text, $regexes);

        /**
         * Invalider les elements qui ont des formats de dates
         */
        if (preg_match("/(?:2020|2019|2018)\s?(?:DU|AU)?/i", $match)) {
            return null;
        }

        return $match;
    }

    public function getPoliceNumber(string $text): ?string
    {
        $regexes = [
            SuperExpressive::create()
                ->namedCapture('number')
                ->exactly(6)->digit()
                ->end()
                ->whitespaceChar()
                ->string('POLICY')
                ->toRegexString(),
            SuperExpressive::create()
                ->namedCapture('number')
                ->exactly(4)->digit()
                ->char('/')
                ->between(10, 11)->digit()
                ->end()
                ->toRegexString(),
            SuperExpressive::create()
                ->namedCapture('number')
                ->exactly(4)->digit()
                ->char('-')
                ->exactly(10)->digit()
                ->end()
                ->toRegexString(),
            SuperExpressive::create()
                ->unicode()
                ->anyOf()
                ->string('Nº')
                ->string('No')
                ->string('Police')
                ->end()
                ->oneOrMore()->whitespaceChar()
                ->namedCapture('number')
                ->atLeast(6)->digit()
                ->end()
                ->anyOf()
                ->optional()->string('Nº')
                ->optional()->string('POLICY')
                ->end()
                ->toRegexString(),
            SuperExpressive::create()
                ->namedCapture('number')
                ->exactly(6)->digit()
                ->end()
                ->whitespaceChar()
                ->string('Police')
                ->toRegexString(),
        ];

        $matches = $this->findMatches($text, $regexes);

        if (! $matches) {
            return null;
        }

        $match = trim(array_pop($matches['number']));

        preg_match("/\b2\d{5}/", $match, $matches);

        $match = $matches ? array_pop($matches) : $match;

        if (is_array($match)) {
            $match = array_pop($match);
        }

        return $match;
    }

    public function getInsuredName(string $text): ?string
    {
        $regexes = [
            SuperExpressive::create()
                ->anyOf()
                ->string('INSURED')
                ->string('INSURE')
                ->end()
                ->optional()->whitespaceChar()
                ->optional()->anyOf()->char(':')->char('.')->end()
                ->optional()->whitespaceChar()
                ->namedCapture('name')
                ->oneOrMore()->anyChar()
                ->end()
                ->optional()->whitespaceChar()
                ->anyOf()
                ->string('BP')
                ->string('Police')
                ->string('ABIDJAN')
                ->string('Polis')
                ->string('Poli')
                ->end()
                ->toRegexString(),
            SuperExpressive::create()
                ->unicode()
                ->anyOf()
                ->string('ASSURÉ')
                ->string('ASSURE')
                ->end()
                ->namedCapture('name')
                ->oneOrMore()->anyChar()
                ->end()
                ->anyOf()
                ->string('BP')
                ->string('Police')
                ->end()
                ->toRegexString(),
        ];

        $matches = $this->findMatches($text, $regexes);

        if (! $matches || count($matches) === 0) {
            return null;
        }

        /**
         * Retirier les carateres speciaux
         */
        $match = trim($matches['name'][0], "<>\")(?./'!: \t\n\r\0\x0B");

        /**
         * Retirer les addresses
         */
        $match = trim(explode('BP', $match)[0]);

        /**
         * Retirer les `Z` orphelins faisant references au `N` du numero d'attestations
         */
        $match = preg_replace("/(?:^Z\s+|\s+Z$)/", "", $match);

        return $match;
    }

    public function getVehicleType(string $text): ?string
    {
        $regexes = [
            "/VEHICULE MOTORISÉ/i",
            "/2 ROUES/",
            "/DEUX ROUES/",
            SuperExpressive::create()->unicode()->caseInsensitive()->string("Véhicule Motorisé")->toRegexString(),
        ];

        return $this->findMatch($text, $regexes) ?: null;
    }

    public function getMake(string $text): ?string
    {
        $regexes = [
            "/APSONIC/",
            "/YAMAHA/",
        ];

        return $this->findMatch($text, $regexes) ?: null;
    }
}

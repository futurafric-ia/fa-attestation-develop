<?php

namespace Domain\Attestation\Parsers;

use Bassim\SuperExpressive\SuperExpressive;
use Domain\Attestation\Models\AttestationType;

class BrownAttestationParser extends AttestationParser
{
    public const ATTESTATION_TYPE = AttestationType::BROWN;

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
            "/\b127\d+/",
            "/\b117\d+/",
            "/\b100\d+/",
            "/\b921\d+/",
            "/\b112\d+/",
            "/\b121\d+/",
            "/\b119\d+/",
            "/\b112\d+/",
            "/\b110\d+/",
            "/\b96\d+/",
            "/\b99\d+/",
        ];

        return (int) $this->findMatch($text, $regexes) ?: null;
    }

    public function getPoliceNumber(string $text): ?string
    {
        $regexes = [
            SuperExpressive::create()
                ->namedCapture('number')
                ->exactly(4)->digit()
                ->char('/')
                ->exactly(10)->digit()
                ->char('/')
                ->exactly(2)->digit()
                ->end()
                ->toRegexString(),
            SuperExpressive::create()
                ->namedCapture('number')
                ->exactly(4)->digit()
                ->optional()->whitespaceChar()
                ->char('/')
                ->optional()->whitespaceChar()
                ->exactly(10)->digit()
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
                ->namedCapture('number')
                ->exactly(14)->digit()
                ->end()
                ->toRegexString(),
            SuperExpressive::create()
                ->unicode()
                ->string('Nº')
                ->oneOrMore()->anyChar()
                ->namedCapture('number')
                ->atLeast(6)->digit()
                ->end()
                ->toRegexString(),
            SuperExpressive::create()
                ->string('No')
                ->oneOrMore()->anyChar()
                ->namedCapture('number')
                ->atLeast(6)->digit()
                ->end()
                ->toRegexString(),
            SuperExpressive::create()
                ->string('Police')
                ->namedCapture('number')
                ->oneOrMore()->anyChar()
                ->atLeast(6)->digit()
                ->oneOrMore()->anyChar()
                ->end()
                ->string('POLICY')
                ->toRegexString(),
        ];

        $matches = $this->findMatches($text, $regexes);

        return $matches ? $matches['number'][0] : null;
    }

    public function getMatriculation(string $text): ?string
    {
        $regexes = [
            "/\s\d{2,4}\s?[A-Z][A-Z]\s?\d{2,3}\s/",
        ];

        $matches = $this->findMatches($text, $regexes);

        if (! $matches || count($matches) === 0) {
            return null;
        }

        $match = array_pop($matches);
        $match = array_pop($match);

        /**
         * Invalider les formats de debut de dates qui respectent le format standard
         */
        if (preg_match("/(?:2020|2019|2018)\s?(?:DU|AU)?/i", $match)) {
            return null;
        }

        /**
         * Rétirer les numéros qui ont pu être confondu avec l'adresse
         */
        if (preg_match("/BP/", $match)) {
            return null;
        }

        return $match;
    }

    public function getInsuredName(string $text): ?string
    {
        $regexes = [
            SuperExpressive::create()
                ->string('Segur')
                ->namedCapture('name')
                ->optional()->whitespaceChar()
                ->oneOrMore()->anyChar()
                ->optional()->whitespaceChar()
                ->end()
                ->string('BP')
                ->toRegexString(),
            SuperExpressive::create()
                ->string('Insured No')
                ->namedCapture('name')
                ->optional()->whitespaceChar()
                ->oneOrMore()->anyChar()
                ->optional()->whitespaceChar()
                ->end()
                ->string('BP')
                ->toRegexString(),
            SuperExpressive::create()
                ->string('emissore')
                ->namedCapture('name')
                ->optional()->whitespaceChar()
                ->oneOrMore()->anyChar()
                ->optional()->whitespaceChar()
                ->end()
                ->string('Police')
                ->toRegexString(),
        ];

        $matches = $this->findMatches($text, $regexes);

        if (! $matches || count($matches) === 0) {
            return null;
        }

        $match = trim($matches['name'][0], ". \t\n\r\0\x0B");
        $match = preg_replace("/\d+\s+BP\s+\d{3,}\s+\w+\s+\d+/", "", $match);
        $match = trim($match, "ado \. \t\n\r\0\x0B");

        if ($this->getPoliceNumber($match)) {
            return null;
        }

        return $match;
    }
}

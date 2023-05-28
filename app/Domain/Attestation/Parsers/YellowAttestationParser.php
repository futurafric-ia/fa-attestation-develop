<?php

namespace Domain\Attestation\Parsers;

use Bassim\SuperExpressive\SuperExpressive;
use Domain\Attestation\Models\AttestationType;

class YellowAttestationParser extends AttestationParser
{
    public const ATTESTATION_TYPE = AttestationType::YELLOW;

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
            "/\b8022\d+/",
            "/\b802.2\d+/",
        ];

        return (int) str_replace('.', '', $this->findMatch($text, $regexes)) ?: null;
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
                ->namedCapture('number')
                ->exactly(14)->digit()
                ->end()
                ->toRegexString(),
            SuperExpressive::create()
                ->unicode()
                ->caseInsensitive()
                ->anyOf()
                ->string('Nº')
                ->string('No')
                ->string('Police')
                ->string('O')
                ->string('CI')
                ->end()
                ->oneOrMore()->anyChar()
                ->namedCapture('number')
                ->atLeast(6)->digit()
                ->end()
                ->optional()->whitespaceChar()
                ->optional()->string('Police')
                ->optional()->string('POLICY')
                ->optional()->string('POLICO')
                ->optional()->string('DU')
                ->toRegexString(),
            SuperExpressive::create()
                ->whitespaceChar()
                ->namedCapture('number')
                ->exactly(6)->digit()
                ->end()
                ->whitespaceChar()
                ->string('Police')
                ->toRegexString(),
        ];

        $matches = $this->findMatches($text, $regexes);

        return $matches ? $matches['number'][0] : null;
    }

    public function getMatriculation(string $text): ?string
    {
        $regexes = [
            SuperExpressive::create()->exactly(4)->digit()->char('/')->exactly(5)->digit()->string('WWCI01')->toRegexString(),
            SuperExpressive::create()->atLeast(2)->digit()->string('WWCI01')->toRegexString(),
            SuperExpressive::create()->atLeast(2)->digit()->string('WW-CI01')->toRegexString(),
            SuperExpressive::create()->atLeast(2)->digit()->optional()->whitespaceChar()->string('WW-CI')->toRegexString(),
            SuperExpressive::create()->exactly(4)->digit()->char('-')->exactly(4)->digit()->string('WW')->caseInsensitive()->toRegexString(),
            "/\s\d{2,4}\s?[A-Z][A-Z]\s?\d{2,3}\s/",
        ];

        $matches = $this->findMatches($text, $regexes);

        if (! $matches || count($matches) === 0) {
            return null;
        }

        $match = array_pop($matches);

        if (is_array($match)) {
            /**
             * Généralement quand plusieurs matchs sont trouvés, le dernier est le numéro d'immatriculation.
             */
            $match = array_pop($match);
        }

        /**
         * Invalider les matchs qui ne sont que des chiffres
         */
        if (preg_match("/^\d+$/", $match)) {
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
                ->string('INSURED')
                ->optional()->whitespaceChar()
                ->optional()->anyOf()->char(':')->char('.')->end()
                ->optional()->whitespaceChar()
                ->namedCapture('name')
                ->oneOrMore()->anyChar()
                ->end()
                ->anyOf()
                ->string('BP')
                ->string('Police')
                ->string('ABIDJAN')
                ->string('Polis')
                ->string('Poli')
                ->end()
                ->toRegexString(),
        ];

        $matches = $this->findMatches($text, $regexes);

        if (! $matches || count($matches) === 0) {
            return null;
        }

        /**
         * Retirer les caractères speciaux
         */
        $match = trim($matches['name'][0], ". ' , \t\n\r\0\x0B");
        $match = preg_replace("/[\>,\:\.\!]/", "", $match);

        /**
         * Rétirer les adresses
         */
        $match = trim(explode("BP", $match)[0], "\. \t\n\r\0\x0B");
        $match = trim(preg_replace_callback("/ABIDJAN/i", function ($item) use ($match) {
            return explode($item[0], $match)[0];
        }, $match));

        /**
         * Rétirer les chiffres à la fin
         */
        $match = preg_replace("/\d+$/", "", $match);


        return $match;
    }
}

<?php

namespace Domain\Attestation\Parsers;

class AttestationParserFactory
{
    public static function make(int $attestationType): AttestationParser
    {
        switch ($attestationType) {
            case YellowAttestationParser::ATTESTATION_TYPE:
                return new YellowAttestationParser();
            case BrownAttestationParser::ATTESTATION_TYPE:
                return new BrownAttestationParser();
            case GreenAttestationParser::ATTESTATION_TYPE:
                return new GreenAttestationParser();
            default:
                throw new \Exception("Ce type n'est pas supporté");

                break;
        }
    }
}

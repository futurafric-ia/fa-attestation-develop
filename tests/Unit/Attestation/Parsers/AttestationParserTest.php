<?php

namespace Tests\Unit\Attestation\Parsers;

use Domain\Attestation\Models\AttestationType;
use Domain\Attestation\Parsers\AttestationParserFactory;
use Tests\TestCase;

class AttestationParserTest extends TestCase
{
    public function testYellowAttestationParser()
    {
        $content = [
            'ASSURE : INSURED : ALMAO BP 3623 ABIDJAN 01 Police No 1001 /4000000117 POLICY NO : DU _01/01/2015 FROM 0Q:90 AU 31/12/2015 TO VEHICULE (Genrevehicule Utilitaire 23:59 VEHICULE (Type) : MARQUE NISSAN No 8022866412 MAKE : IMMATRICULATION OU NO DE CHASSIS REGISTRATION OR CHASSIS NO 21 7931 EY OT SAHAM Asturnage 8022866412 C.TID 0 CAT2',
            "ASSURÉ : 929195 INSURED. KANU EQUIPMENZ CI Police Nº 201683 POLICY NO. 20/05/2020 19/05/20 DU AU FROM TO VÉHICULE (Genre) V.P - VEHICULE (Type) : MARQUE MITSUBISHI MAKE : IMMATRICULATION QUIN' DE CHÂSSIS REGISTRATION OR CHASSIS NO SAHAM Assurance",
        ];

        $parser = AttestationParserFactory::make(AttestationType::YELLOW);
        $result = $parser->parse($content[0]);

        $this->assertArrayHasKey('attestation_number', $result);
        $this->assertArrayHasKey('insured_name', $result);
        $this->assertArrayHasKey('police_number', $result);
        $this->assertArrayHasKey('matriculation', $result);
        $this->assertArrayHasKey('address', $result);
    }

    public function testBrownAttestationParser()
    {
        $content = [
            'text' => [
                "Nom et adresse de l'Assure . Name and address of Insured Name e endereco do Segurade SG AGRICULTURE SA EDE Q6 BP. 6063 ABIDJAN 06 Z O Registration . $66 JV 01 Marque et Type Make and TYPTOYOTAAS Assureur PRADO Insurer . SegurSHAM ASSURANCE CI Bureau emerg1 B.P.. 3832 ABIDJAN 01 Issuing Burea 11742966 Police No Policy Numbe?03887 Apolice H09101/2020 To . 02/01/2021 ON Usage ou categorie du vehicule Use or category of the vehicule Usado o categoria do velculo Exemplaire Bureau National Copy for the National Office Cople para Escritorio Nacional",
            ],
        ];

        $parser = AttestationParserFactory::make(AttestationType::BROWN);
        $result = $parser->parse($content['text'][0]);

        $this->assertArrayHasKey('attestation_number', $result);
        $this->assertArrayHasKey('insured_name', $result);
        $this->assertArrayHasKey('police_number', $result);
        $this->assertArrayHasKey('matriculation', $result);
        $this->assertArrayHasKey('address', $result);
    }

    public function testGreenAttestationParser()
    {
        $content = [
            'text' => [
                "935861 CENTRAL TRADING O Jo 203874 01/01/2020 31/82/20 AU (Genre) 103 ROUES Typel SAFARI 8052209091 CULATION580 NDe CHASSIS ON OR CHASSIS NO MOTORISE A 2 OU 3 ROUES HE D'USAGE NO 5 SAHAM Surance",
            ],
        ];

        $parser = AttestationParserFactory::make(AttestationType::GREEN);
        $result = $parser->parse($content['text'][0]);

        $this->assertArrayHasKey('attestation_number', $result);
        $this->assertArrayHasKey('insured_name', $result);
        $this->assertArrayHasKey('police_number', $result);
        $this->assertArrayHasKey('matriculation', $result);
        $this->assertArrayHasKey('address', $result);
    }
}

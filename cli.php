<?php

require_once 'vendor/autoload.php';


$countries = [
//	new Country('Holland', [
//		new Airport('Amsterdam [AMS]', true)
//	]),
//	new Country('Ukraine', [
//		new Airport('Kiev [IEV] (+KBP)', true)
//	]),
//	new Country('Belgium', [
//		new Airport('Brussels [BRU] (+CRL)', true)
//	]),
//	new Country('Ireland', [
//		new Airport('Dublin [DUB]', true)
//	]),
//	new Country('Denmark', [
//		new Airport('Copenhagen [CPH]', true)
//	]),
    new Country('Spain', [
        new Airport('FUE'),
        new Airport('SPC'),
        new Airport('LPA'),
        new Airport('ACE'),
        new Airport('TFS'),
        new Airport('TFN'),
        new Airport('ALC'),
        new Airport('LEI'),
        new Airport('OVD'),
        new Airport('BIO'),
        new Airport('BCN'),
        new Airport('LCG'),
        new Airport('GRO'),
        new Airport('GRX'),
        new Airport('IBZ'),
        new Airport('XRY'),
        new Airport('MAD', true),
        new Airport('AGP'),
        new Airport('MAH'),
        new Airport('PNA'),
        new Airport('RMU'),
        new Airport('REU'),
        new Airport('SLM'),
        new Airport('EAS'),
        new Airport('SCQ'),
        new Airport('VLC'),
        new Airport('VLL'),
        new Airport('VIT'),
        new Airport('VGO'),
        new Airport('SDR'),
        new Airport('ZAZ'),
        new Airport('SVQ'),
        new Airport('PMI'),
        new Airport('RJL'),
        new Airport('LEN'),
        new Airport('CDT'),
    ]),
//	new Country('France', [
//		new Airport('Paris [CDG] (+ORY,BVA,XCR)', true)
//	]),
    new Country('Italy', [
        new Airport('CRV'),
        new Airport('BRI'),
        new Airport('PSR'),
        new Airport('BDS'),
        new Airport('SUF'),
        new Airport('CTA'),
        new Airport('LMP'),
        new Airport('PNL'),
        new Airport('PMO'),
        new Airport('REG'),
        new Airport('TPS'),
        new Airport('AHO'),
        new Airport('CAG'),
        new Airport('OLB'),
        new Airport('MXP'),
        new Airport('BGY'),
        new Airport('TRN'),
        new Airport('GOA'),
        new Airport('LIN'),
        new Airport('PMF'),
        new Airport('CUF'),
        new Airport('BLQ'),
        new Airport('TSF'),
        new Airport('FRL'),
        new Airport('TRS'),
        new Airport('RMI'),
        new Airport('VRN'),
        new Airport('VCE'),
        new Airport('CIA', true),
        new Airport('FCO', true),
        new Airport('NAP'),
        new Airport('PSA'),
        new Airport('FLR'),
        new Airport('PEG'),
        new Airport('AOI'),
        new Airport('CIY'),
    ]),
    new Country('Portugal', [
        new Airport('FAO'),
        new Airport('TER'),
        new Airport('PDL'),
        new Airport('OPO'),
        new Airport('PXO'),
        new Airport('LIS', true),
        new Airport('FNC'),
    ]),
//	new Country('Cyprus', [
//		new Airport('Cyprus [LCA] (+ECN,PFO)', true)
//	]),
//	new Country('Hungary', [
//		new Airport('Budapest [BUD]', true)
//	]),
//	new Country('Romania', [
//		new Airport('Bucharest [OTP] (+BBU)', true)
//	]),
//	new Country('Serbia', [
//		new Airport('Belgrade [BEG]', true)
//	]),
//	new Country('Turkey', [
//		new Airport('Istanbul [IST] (+SAW)', true)
//	]),
//    new Country('Morocco', [
//        new Airport('RBA', true)
//    ]),
//	new Country('Finland', [
//		new Airport('Helsinki [HEL]', true)
//	]),
//	new Country('Georgia', [
//		new Airport('Tbilisi [TBS]', true),
//		new Airport('Kutaisi [KUT]'),
//	]),
//	new Country('Island', [
//		new Airport('Reykjavik [RKV] (+KEF)', true),
//	])
];
$onlyCapitals = false;


$countriesProvider = new CountriesProvider($countries);

echo "START";
$config = [
    'onlyCapitals' => false,
    'maxPrice' => 400,
    'minStayPeriod' => 3,
    'maxStayPeriod' => 7,
    'from' => '01.03.2023',
    'to' => '30.04.2023',
    'onlyWeekends' => false,
    'exceptionDays' => [
        // Zjazdy
        '03.12.2022 00:00:00',
        '04.12.2022 00:00:00',
        '17.12.2022 00:00:00',
        '18.12.2022 00:00:00',
        '14.01.2023 00:00:00',
        '15.01.2023 00:00:00',
        '28.01.2023 00:00:00',
        '29.01.2023 00:00:00',
        '11.02.2023 00:00:00',
        '12.02.2023 00:00:00',
        '11.03.2023 00:00:00',
        '12.03.2023 00:00:00',
        '25.03.2023 00:00:00',
        '26.03.2023 00:00:00',
        '15.04.2023 00:00:00',
        '16.04.2023 00:00:00',
        '29.04.2023 00:00:00',
        '30.04.2023 00:00:00',
        '13.05.2023 00:00:00',
        '14.05.2023 00:00:00',
        '27.05.2023 00:00:00',
        '28.05.2023 00:00:00',
        '10.06.2023 00:00:00',
        '11.06.2023 00:00:00',
        '24.06.2023 00:00:00',
        '25.06.2023 00:00:00',

        // Kursy
        '21.01.2023 00:00:00',
        '22.01.2023 00:00:00',
        '04.02.2023 00:00:00',
        '05.02.2023 00:00:00',
        '18.02.2023 00:00:00',
        '04.03.2023 00:00:00',
        '05.03.2023 00:00:00',
        '18.03.2023 00:00:00',
        '19.03.2023 00:00:00',
        '01.04.2023 00:00:00',
        '02.04.2023 00:00:00',
        '22.04.2023 00:00:00',
        '23.04.2023 00:00:00',
        '20.05.2023 00:00:00',
        '21.05.2023 00:00:00',
        '03.06.2023 00:00:00',
        '04.06.2023 00:00:00',
        '17.06.2023 00:00:00',
    ],
];
$azAir = new AzAir($countriesProvider->getCountries(), $config);
$azAir->run();
//(new Booking())->run();
echo "END";
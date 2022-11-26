<?php

require_once 'vendor/autoload.php';
include 'config.php';

$countriesProvider = new CountriesProvider(config['countries'] ?? []);

echo "START";

$azAir = new AzAir($countriesProvider->getCountries(), config);
$azAir->run();
//(new Booking())->run();

echo "END";
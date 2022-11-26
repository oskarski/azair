<?php

$countries = [];

define('config', [
    'countries' => $countries,
    'onlyCapitals' => false,
    'maxPrice' => 350,
    'minStayPeriod' => 3,
    'maxStayPeriod' => 7,
    'from' => '01.03.2023',
    'to' => '30.04.2023',
    'onlyWeekends' => false,
    'useCachedFilesOnly' => false,
    'exceptionDays' => [
        '03.12.2022 00:00:00',
    ],
]);
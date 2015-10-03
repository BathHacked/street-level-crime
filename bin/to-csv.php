<?php

$json = file_get_contents('php://stdin');

$items = json_decode($json, true);

$header = [
    'id',
    'month',
    'category',
    'context',
    'location_latitude',
    'location_longitude',
    'location_street_name',
    'location_street_id',
    'location_type',
    'location_subtype',
    'location',
    'outcome_status',
    'outcome_status_category',
    'outcome_status_date',
];

fputcsv(STDOUT, $header);

foreach($items as $item)
{
    $row = [];

    foreach($header as $index => $col)
    {
        $row[] = isset($item[$col]) ? $item[$col] : null;
    }

    fputcsv(STDOUT, $row);
}



<?php

$json = file_get_contents('php://stdin');

$items = json_decode($json, true);

if(!is_array($items))
{
    fwrite(STDERR, 'Invalid input JSON' . PHP_EOL);
    exit;
}

$fields = [
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

if($argc == 2)
{
    if(!is_file($argv[1]))
    {
        fwrite(STDERR, "Invalid map file" . PHP_EOL);
        exit;
    }

    $map = json_decode(file_get_contents($argv[1]), true);

    if(!is_array($map))
    {
        fwrite(STDERR, "Invalid map file" . PHP_EOL);
        exit;
    }
}
else
{
    $map = array_combine($fields, $fields);
}

fputcsv(STDOUT, array_values($map));

foreach($items as $item)
{
    $row = [];

    foreach($map as $from => $to)
    {
        $row[] = isset($item[$from]) ? $item[$from] : null;
    }

    fputcsv(STDOUT, $row);
}



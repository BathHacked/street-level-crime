<?php

require_once('../vendor/autoload.php');

use \BathHacked\Fetcher;
use \Carbon\Carbon;

/**
 * Check boundary file exists & get it
 */

if($argc < 2)
{
    fwrite(STDERR, "Boundary file path is required" . PHP_EOL);
    exit;
}

$boundaryFile = $argv[1];
$lookbackMonths = $argc == 3 ? $argv[2] : null;

if(!is_file($boundaryFile))
{
    fwrite(STDERR, "Boundary file is not a file: " . $boundaryFile . PHP_EOL);
    exit;
}

$boundary = file_get_contents($boundaryFile);

/**
 * Set our default timezone to UTC
 */

date_default_timezone_set('UTC');

/**
 * Fetch the items from the API. Get list of dates & work through them
 */

$fetcher = new Fetcher('https://data.police.uk');

$dates = $fetcher->getAvailability();

$all = [];

$lookback = null;

if($lookbackMonths && is_numeric($lookbackMonths))
{
    $lookback = Carbon::now()->subMonths($lookbackMonths)->startOfMonth();
}


foreach($dates as $dateString)
{
    $date = Carbon::createFromFormat('Y-m', $dateString)->firstOfMonth();

    if($lookback && $date->lt($lookback)) continue;

    fwrite(STDERR, "Fetching " . $date->format('Y-m-d') . PHP_EOL);

    $items = $fetcher->getByBoundary($boundary, $date);

    $all = array_merge($all, $items);
}

echo json_encode($all, JSON_PRETTY_PRINT);

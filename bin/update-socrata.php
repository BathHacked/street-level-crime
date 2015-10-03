<?php

require('../vendor/autoload.php');

if(!is_file(realpath('../config/socrata.php')))
{
    die("Socrata configuration file not found. ");
}

$config = include('../config/socrata.php');

$updater = new \BathHacked\DatasetUpdater(
    $config['base_uri'],
    $config['app_token'],
    $config['email'],
    $config['password']
);

$payload = file_get_contents('php://stdin');

$result = $updater->update($config['resource_path'], $payload);

fwrite(STDERR, 'Update result :-' . PHP_EOL . json_encode($result, JSON_PRETTY_PRINT) .  PHP_EOL);
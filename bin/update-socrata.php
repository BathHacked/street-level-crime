<?php

require('../vendor/autoload.php');

if($argc < 2)
{
    fwrite(STDERR, 'Socrata configuration file is required' . PHP_EOL);
    exit;
}

if(!is_file($argv[1]))
{
    fwrite(STDERR, 'Invalid Socrata configuration file' . PHP_EOL);
    exit;
}

$config = json_decode(file_get_contents($argv[1]), true);

$fields = ['base_uri', 'app_token', 'email', 'password', 'resource_path'];

if(array_intersect(array_keys($config), $fields) != $fields)
{
    fwrite(STDERR, 'Invalid Socrata configuration file' . PHP_EOL);
    exit;
}

$mode = $argc == 3 ? $argv[2] : 'update';

$updater = new \BathHacked\DatasetUpdater(
    $config['base_uri'],
    $config['app_token'],
    $config['email'],
    $config['password']
);

$payload = file_get_contents('php://stdin');

$result = $updater->update($config['resource_path'], $payload, $mode);

fwrite(STDERR, 'Update result :-' . PHP_EOL . json_encode($result, JSON_PRETTY_PRINT) .  PHP_EOL);
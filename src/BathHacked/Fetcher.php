<?php

namespace BathHacked;

use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;

class Fetcher
{
    protected $baseUri;

    protected $client;

    /**
     * Client constructor.
     * @param $baseUri
     */
    public function __construct($baseUri)
    {
        $this->baseUri = $baseUri;

        $this->client = new GuzzleClient([
            'base_uri' => $baseUri,
            'debug' => false
        ]);
    }

    public function getAvailability()
    {
        $result = $this->client->request('GET', '/api/crimes-street-dates');

        $items = json_decode($result->getBody(), true);

        $items = array_pluck($items, 'date');

        sort($items);

        return $items;
    }

    /**
     * @param float $lat
     * @param float $lng
     * @param Carbon $date
     * @return array
     */
    public function getByLatLng($lat, $lng, $date)
    {
        $date = $date->startOfMonth();

        try {
            $result = $this->client->request('GET', '/api/crimes-street/all-crime', [
                'query' => [
                    'lat' => $lat,
                    'lng' => $lng,
                    'date' => $date->format('Y-m')
                ]
            ]);

        } catch (ClientException $e) {

            if($e->getCode() == 404) return [];

            throw $e;
        }

        return $this->parseResult($result->getBody());
    }

    public function getByBoundary($boundary, $date)
    {
        $date = $date->startOfMonth();

        try {
            $result = $this->client->request('POST', '/api/crimes-street/all-crime', [
                'form_params' => [
                    'poly' => $boundary,
                    'date' => $date->format('Y-m')
                ]
            ]);
        } catch (ClientException $e) {

            if($e->getCode() == 404) return [];

            throw $e;
        }

        return $this->parseResult($result->getBody());
    }

    /**
     * @param string $result
     * @return array
     */
    protected function parseResult($result)
    {
        $rows = json_decode($result, true);

        $all = [];

        foreach($rows as $row)
        {
            // Flatten the array to dot notation keys then replace dots with underscores
            $row = array_build(array_dot($row), function($key, $value) {
                return [str_replace('.', '_', $key), $value];
            });

            if(!empty($row['month']))
            {
                $row['month'] = Carbon::createFromFormat('Y-m', $row['month'])->startOfMonth()->toIso8601String();
            }
            if(!empty($row['outcome_status_date']))
            {
                $row['outcome_status_date'] = Carbon::createFromFormat('Y-m', $row['outcome_status_date'])->startOfMonth()->toIso8601String();
            }
            if(!empty($row['location_latitude']) && !empty($row['location_longitude']))
            {
                $row['location'] = sprintf('(%s, %s)', $row['location_latitude'], $row['location_longitude']);
            }

            $all[$row['id']] = $row;
        }

        return $all;
    }
}
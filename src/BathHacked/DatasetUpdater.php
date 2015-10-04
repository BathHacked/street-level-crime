<?php


namespace BathHacked;


use GuzzleHttp\Client;

class DatasetUpdater
{
    /**
     * @var string
     */
    protected $baseUri;

    /**
     * @var null|string
     */
    protected $appToken;

    /**
     * @var null|string
     */
    protected $email;

    /**
     * @var null|string
     */
    protected $password;

    /**
     * @var Client
     */
    protected $client;

    /**
     * DatasetUpdater constructor.
     * @param string $baseUri
     * @param string $appToken
     * @param string $email
     * @param string $password
     */
    public function __construct($baseUri, $appToken, $email, $password)
    {
        $this->baseUri = $baseUri;
        $this->appToken = $appToken;
        $this->email = $email;
        $this->password = $password;

        $this->client = new Client([
            'base_uri' => $baseUri,
            'debug' => false,
        ]);
    }

    /**
     * Upsert CSV payload into datastore
     *
     * @param string $path
     * @param string $payload
     * @return array
     */
    public function update($path, $payload, $mode = 'update')
    {
        $options = array(
            'body' => $payload,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'text/csv',
                'X-App-Token' => $this->appToken,
            ],
            'auth' => [$this->email, $this->password],
        );

        $verb = $mode == 'replace' ? 'PUT' : 'POST';

        $response = $this->client->request($verb, $path, $options);

        $response = $response->getBody()->getContents();

        return json_decode($response, true);
    }
}
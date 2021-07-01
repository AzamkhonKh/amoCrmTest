<?php
namespace Lib;


use GuzzleHttp\Client;

class ConnectionAmoCRM
{
    public $endpoint;
    public $version;
    public $url;
    public $headers = [
        'Content-Type' => 'application/json; charset=UTF-8',
        'Accept' => 'application/json'
    ];

    private $timeout;
    // means till end of request after handshake
    private $connectionTimeout;

    public function __construct()
    {
        $config = include realpath('../config.php');
        $this->endpoint = $config['endpoint'];
        $this->version = $config['version'];
        $this->timeout = $config['timeout'] ?? 5;
        $this->connectionTimeout = $config['connectionTimeout'] ?? 0;

    }

    public function request(string $method, array $query = [], array $body = [])
    {
        try {
            $query_param = [
                'headers' => $this->headers,
                'http_errors' => false,
                'verify' => false,
                'timeout' => $this->timeout,
                'connect_timeout' => $this->connectionTimeout
            ];
            // можно добавить patch, но для данной задачи эт не нужно
            if (!in_array($method, ['GET', 'POST'])) throw new Exception('unknown request method');
            if (!empty($body)) $query_param['body'] = json_encode($body);
            if (!empty($query)) $query_param['query'] = $query;
            $client = new Client();
            $result = $client->request($method, $this->url, $query_param);

            return json_decode($result->getBody()->getContents());
        } catch (\Exception $e) {
            return 'error: ' . $e->getMessage() . " | Line " . $e->getLine() . " | File : " . $e->getFile();
        }
    }
}
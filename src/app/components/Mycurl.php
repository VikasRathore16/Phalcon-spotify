<?php

namespace App\Components;

use Phalcon\Di\Injectable;


class Mycurl extends Injectable
{

    public function find($method, $query = '', $body = '')
    {
        $client = new \GuzzleHttp\Client();
        if ($method == 'GET') {
            $response = $client->request('GET', $this->config->get('url')['base_url'] . $query, [
                'headers' => [
                    'Authorization' => "Bearer " . $this->session->get('bearer'),
                ]
            ]);
            $response = (object)(json_decode($response->getBody(), true));
        }
        if ($method == "POST") {
            $response = $client->request(
                'POST',
                $this->config->get('url')['base_url'] . $query,
                [
                    'headers' => [
                        'Authorization' => "Bearer " . $this->session->get('bearer'),
                    ],
                    'body' => json_encode(
                        $body
                    )
                ],
            );
        }
        if ($method == "DELETE") {
            $response = $client->request(
                'DELETE',
                $this->config->get('url')['base_url'] . $query,
                [
                    'headers' => [
                        'Authorization' => "Bearer " . $this->session->get('bearer'),
                    ],
                    'body' => json_encode(
                        $body
                    )
                ],
            );
        }
        return $response;
    }
}

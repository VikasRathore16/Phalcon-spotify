<?php

namespace App\Components;

use Phalcon\Di\Injectable;
use Users;

class Mycurl extends Injectable
{

    public function find($method, $query = '', $body = '')
    {
        try {
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
        } catch (\Exception $e) {

            $user = Users::find($this->session->get('user_id'));
            $eventsManager = $this->di->get('EventsManager');
            $result =  $eventsManager->fire('token:beforeHandleRequest', $user);
            print_r($result);
            print_r($e->getMessage());
            die();
        }
    }
}

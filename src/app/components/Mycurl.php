<?php

namespace App\Components;

use Phalcon\Di\Injectable;
use Users;

/**
 * Mycurl class
 */
class Mycurl extends Injectable
{
    /**
     * find function
     * All API requests goes from this function
     * @param [string] $method
     * @param string $query
     * @param string $body
     * @return void
     */
    public function find($method, $query = '', $body = '')
    {
        try {
            //creating a new client
            $client = new \GuzzleHttp\Client();

            //checking method
            if ($method == 'GET') {
                $response = $client->request('GET', $this->config->get('url')['base_url'] . $query, [
                    'headers' => [
                        'Authorization' => "Bearer " . $this->session->get('bearer'),
                    ]
                ]);
                $response = (object)(json_decode($response->getBody(), true));
            }

            //checking method
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

            //checking method
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
            // if token expires, fire token event to create a new bearer token using refresh token
            $user = Users::find($this->session->get('user_id'));
            $eventsManager = $this->di->get('EventsManager');
            $result =  $eventsManager->fire('token:beforeHandleRequest', $user);

            $this->response->redirect('user/dashboard');
        }
    }
}

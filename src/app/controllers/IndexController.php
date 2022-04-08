<?php

use Phalcon\Mvc\Controller;


class IndexController extends Controller
{

    public function indexAction()
    {
        print_r($this->config->get('app')['bearer_token']);
        // die();
        $this->view->result = $this->find('/search?q=hello&type=track');
    }

    public function searchAction()
    {
        $query = $this->request->get('query');
        $result = $this->find('/search?q=' . $query . '&type=track,artist');
        $top_result = $this->topResults($result->tracks['items']);
        $this->view->result = $result;
        $this->view->top_result = $top_result;
        echo "<pre>";
        print_r($result);
        echo "</pre>";
        // die();
    }

    public function createPlaylistAction()
    {
        $result = $this->find("POST", '/users/312hyyvvs6hfnvahqxsrq223itvm/playlists');
    }

    private function topResults($items)
    {
        $popularity = 0;
        foreach ($items as $item) {
            if ($popularity < $item['popularity']) {
                $popularity = $item['popularity'];
                $top_result = $item;
            }
        }
        return $top_result;
    }

    public function find($method = "GET", $query = '', $slug = '', $format = '')
    {
        $client = new \GuzzleHttp\Client();
        if ($method == 'GET') {
            $response = $client->request('GET', $this->config->get('url')['base_url'] . $query . $slug . $format, [
                'headers' => [
                    'Authorization' => "Bearer " . $this->config->get('app')['bearer_token'],
                ]
            ]);
        }
        if ($method = "POST") {
            $response = $client->request(
                'POST',
                $this->config->get('url')['base_url'] . $query . $slug . $format,
                [
                    'headers' => [
                        'Authorization' => "Bearer " . $this->config->get('app')['bearer_token'],
                    ],
                    'body' => json_encode(
                        [
                            "name" => "New Playlist",
                            "description" => "New playlist description",
                            "public" => false
                        ]
                    )
                ],
            );
        }
        // $response = $client->post($this->config->get('url')['base_url'] . $query . $slug . $format, ['data' => 
        // )]);
        $response = (object)(json_decode($response->getBody(), true));
        return $response;
    }
}

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
        $this->session->set('query', $query);
        $result = $this->find('GET', '/search?q=' . $query . '&type=track,artist');
        $myPlaylists = $this->find('GET', '/me/playlists');
        $top_result = $this->topResults($result->tracks['items']);
        $this->view->result = $result;
        $this->view->top_result = $top_result;
        $this->view->myPlaylists = $myPlaylists;
        echo "<pre>";
        print_r($result);
        echo "</pre>";
        // die();
    }

    public function createPlaylistAction()
    {
        // print_r($this->session);
        // die();
        if ($this->request->get('playlist')) {
            $result = $this->find("POST", '/users/312hyyvvs6hfnvahqxsrq223itvm/playlists', $this->request->get('playlist'));
            $this->response->redirect('index/search?query=' . $this->session->get('query') . '&type=track,artist');
        }
    }

    public function likedSongsAction()
    {
        
        echo "<pre>";
        print_r($result);
        echo "</pre>";
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

    public function find($method, $query = '', $playlist = '')
    {
        $client = new \GuzzleHttp\Client();
        if ($method == 'GET') {
            $response = $client->request('GET', $this->config->get('url')['base_url'] . $query, [
                'headers' => [
                    'Authorization' => "Bearer " . $this->config->get('app')['bearer_token'],
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
                        'Authorization' => "Bearer " . $this->config->get('app')['bearer_token'],
                    ],
                    'body' => json_encode(
                        [
                            "name" => $playlist,
                            "description" => "New playlist description",
                            "public" => false
                        ]
                    )
                ],
            );
        }
        // $response = $client->post($this->config->get('url')['base_url'] . $query . $slug . $format, ['data' => 
        // )]);

        return $response;
    }
}

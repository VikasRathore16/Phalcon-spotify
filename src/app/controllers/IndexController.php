<?php

use Phalcon\Mvc\Controller;


class IndexController extends Controller
{

    public function indexAction()
    {

        print_r($this->config->get('app')['bearer_token']);
        $this->view->result = $this->find('/search?q=hello&type=track');
    }

    public function searchAction()
    {
        $query = $this->request->get('query');
        $url = '/search?q=' . $query . '&type=';
        foreach (array_slice($_POST, 1) as $key => $value) {
            $url .= $value . ',';
        }
        $url = rtrim($url, ",");
        $this->session->set('query', $query);
        $result = $this->find('GET', $url);
        $myPlaylists = $this->find('GET', '/me/playlists');
        $top_result = $this->topResults($result->tracks['items']);
        
        $this->view->result = $result;
        $this->view->top_result = $top_result;
        $this->view->myPlaylists = $myPlaylists;
    }

    public function createPlaylistAction()
    {
        if ($this->request->has('playlist')) {
            $playlist_body = [
                "name" => $this->request->get('playlist'),
                "description" => "New playlist description",
                "public" => false
            ];

            $result = $this->find("POST", '/users/312hyyvvs6hfnvahqxsrq223itvm/playlists', $playlist_body);

            $this->response->redirect('index/search?query=' . $this->session->get('query') . '&type=track,artist');
        }
    }

    public function deleteFromPlaylistAction()
    {
        $song = $this->request->get('song');
        $playlist_name = $this->request->get('myPlaylist');
        $playlist_body = [
            "tracks" => [
                [
                    "uri" => $song,
                ]
            ],
        ];

        $result = $this->find("DELETE", '/playlists/' . $playlist_name . '/tracks', $playlist_body);
        $this->response->redirect('index/myPlaylist?myPlaylist=' . $playlist_name);
    }

    public function myPlaylistAction()
    {

        if ($this->request->has('myPlaylist')) {
            $result = $this->find("GET", '/playlists/' . $this->request->get('myPlaylist'));
            $this->view->myPlaylist = $result;
        }
    }


    public function likedSongsAction()
    {

        echo "<pre>";
        print_r($result);
        echo "</pre>";
    }

    public function addToPlaylistAction()
    {
        $myPlaylists = $this->find('GET', '/me/playlists');
        $this->view->myPlaylists = $myPlaylists;
        if ($this->request->has('song') && $this->request->get('myPlaylist')) {
            $result = $this->find("POST", '/playlists/' . $this->request->get('myPlaylist') . '/tracks?uris=' . $this->request->get('song'));
            $this->view->myPlaylist = $result;
            $this->response->redirect('index/myPlaylist?myPlaylist=' . $this->request->get('myPlaylist'));
        }
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

    public function find($method, $query = '', $body = '')
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
                        'Authorization' => "Bearer " . $this->config->get('app')['bearer_token'],
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

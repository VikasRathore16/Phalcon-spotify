<?php

use Phalcon\Mvc\Controller;


class PlaylistController extends Controller
{
    public function createPlaylistAction()
    {

        if ($this->request->has('playlist')) {
            $playlist_body = [
                "name" => $this->request->get('playlist'),
                "description" => "New playlist description",
                "public" => false
            ];
            $current = $this->Mycurl->find('GET', '/me/');
            $result = $this->Mycurl->find("POST", '/users/' . $current->id . '/playlists', $playlist_body);
            $this->response->redirect('index/search?query=' . $this->session->get('query'));
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
        $result = $this->Mycurl->find("DELETE", '/playlists/' . $playlist_name . '/tracks', $playlist_body);
        $this->response->redirect('playlist/myPlaylist?myPlaylist=' . $playlist_name);
    }

    public function myPlaylistAction()
    {

        if ($this->request->has('myPlaylist')) {
            $result = $this->Mycurl->find("GET", '/playlists/' . $this->request->get('myPlaylist'));
            $this->view->myPlaylist = $result;
        }
    }

    public function addToPlaylistAction()
    {

        $myPlaylists = $this->Mycurl->find('GET', '/me/playlists');
        $this->view->myPlaylists = $myPlaylists;
        if ($this->request->has('song') && $this->request->get('myPlaylist')) {
            print_r($this->request->get('myPlaylist'));
            // die();
            $result = $this->Mycurl->find("POST", '/playlists/' . $this->request->get('myPlaylist') . '/tracks?uris=' . $this->request->get('song'));
            $this->view->myPlaylist = $result;
            $this->response->redirect('playlist/myPlaylist?myPlaylist=' . $this->request->get('myPlaylist'));
        }
    }

}

<?php

use Phalcon\Mvc\Controller;

/**
 * PlaylistController class
 */
class PlaylistController extends Controller
{
    /**
     * createPlaylist function
     * creates a new Playlist
     * @return void
     */
    public function createPlaylistAction()
    {
        //check if playlist name is passed or not
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

    /**
     * deleteFromPlaylist function
     * Delete tracks from Playlist
     * @return void
     */
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

    /**
     * myPlaylist function
     * gives all Playlists of a user
     * @return void
     */
    public function myPlaylistAction()
    {
        if ($this->request->has('myPlaylist')) {
            $result = $this->Mycurl->find("GET", '/playlists/' . $this->request->get('myPlaylist'));
            $this->view->myPlaylist = $result;
        }
    }

    /**
     * addToPlaylist function
     * Add a track to playlist
     * @return void
     */
    public function addToPlaylistAction()
    {

        if ($this->request->has('song') && $this->request->get('myPlaylist')) {
            $result = $this->Mycurl->find("POST", '/playlists/' . $this->request->get('myPlaylist') . '/tracks?uris=' . $this->request->get('song'));
            $this->response->redirect('playlist/myPlaylist?myPlaylist=' . $this->request->get('myPlaylist'));
        }
        $myPlaylists = $this->Mycurl->find('GET', '/me/playlists');
        $this->view->myPlaylists = $myPlaylists;
    }
}

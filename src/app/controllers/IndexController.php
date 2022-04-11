<?php

use Phalcon\Mvc\Controller;


class IndexController extends Controller
{

    public function indexAction()
    {
    }

    public function searchAction()
    {
        $query = $this->request->get('query');
        $url = '/search?q=' . $query . '&type=';

        if (!empty($_POST)) {
            $this->session->set('postArray', $_POST);
            $types = $this->session->get('postArray');
        } else {
            $types = $this->session->get('postArray');
        }

        foreach (array_slice($types, 1) as $key => $value) {
            $url .= $value . ',';
        }

        $url = rtrim($url, ",");
        $this->session->set('query', $query);
        $result = $this->Mycurl->Mycurl->find('GET', $url);
        $myPlaylists = $this->Mycurl->find('GET', '/me/playlists');
        $top_result = $this->topResults($result->tracks['items']);

        $this->view->result = $result;
        $this->view->top_result = $top_result;
        $this->view->myPlaylists = $myPlaylists;
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

    public function artistsAction()
    {
        $top_tracks = $this->Mycurl->find("GET", '/artists/' . $this->request->get('artist') . '/top-tracks?market=ES');
        $artist = $this->Mycurl->find("GET", '/artists/' . $this->request->get('artist'));
        $this->view->top_tracks = $top_tracks;
        $this->view->artist = $artist;
    }

}

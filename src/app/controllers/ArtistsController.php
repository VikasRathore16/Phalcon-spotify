<?php

use Phalcon\Mvc\Controller;

/**
 * IndexController class
 */
class ArtistsController extends Controller
{
    /**
     * index function
     * find artist top-tracks
     * @return void
     */
    public function indexAction()
    {
        $top_tracks = $this->Mycurl->find("GET", '/artists/' . $this->request->get('artists') . '/top-tracks?market=ES');
        $artist = $this->Mycurl->find("GET", '/artists/' . $this->request->get('artists'));

        //call view
        $this->view->top_tracks = $top_tracks;
        $this->view->artist = $artist;
    }
}

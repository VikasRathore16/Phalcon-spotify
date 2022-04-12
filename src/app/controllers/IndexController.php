<?php

use Phalcon\Mvc\Controller;

/**
 * IndexController class
 */
class IndexController extends Controller
{
    /**
     * index function
     * first page where we can search tracks , artists , etc
     * @return void
     */
    public function indexAction()
    {
    }

    /**
     * search function
     * find result on the basis of query
     * @return void
     */
    public function searchAction()
    {
        //checking if user is loggin or not
        if (!$this->session->has('user_id')) $this->response->redirect('user/login');

        $query = $this->request->get('query');
        $url = '/search?q=' . $query . '&type=';
        //checking query if empty or not
        if (!empty($_POST)) {
            $this->session->set('postArray', $_POST);
            $types = $this->session->get('postArray');
        } else {
            $types = $this->session->get('postArray');
        }

        //concatenating url
        foreach (array_slice($types, 1) as $key => $value) {
            $url .= $value . ',';
        }

        $url = rtrim($url, ",");

        //setting query to session
        $this->session->set('query', $query);
        $result = $this->Mycurl->Mycurl->find('GET', $url);
        $myPlaylists = $this->Mycurl->find('GET', '/me/playlists');
        $top_result = $this->topResults($result->tracks['items']);

        //view call
        $this->view->result = $result;
        $this->view->top_result = $top_result;
        $this->view->myPlaylists = $myPlaylists;
    }

    /**
     * Undocumented function
     * find top results of tracks query based on popularity from search Action
     * @param [array] $items
     * @return void
     */
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
}

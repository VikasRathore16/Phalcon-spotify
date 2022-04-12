<?php

use Phalcon\Mvc\Controller;

/**
 * SpotifyController class
 * generates bearer token
 */
class SpotifyController extends Controller
{
    /**
     * index function
     * connect to Spotify and generate code for a user
     * @return void
     */
    public function indexAction()
    {
        $client_id = $this->config->get('app')['client_id'];
        $scope = "playlist-read-private playlist-modify-private";
        $url = 'https://accounts.spotify.com/authorize?';

        $data = array(
            'response_type' => 'code',
            'client_id' => $client_id,
            'scope' => $scope,
            'redirect_uri' => 'http://localhost:8080/spotify/token',
        );

        $url = $url . http_build_query($data);
        $this->response->redirect($url);
    }

    /**
     * token function
     * generate bearer_token and refresh_token
     * @return void
     */
    public function tokenAction()
    {
        $code = $this->request->get('code');
        $client_id = $this->config->get('app')['client_id'];
        $client_key = $this->config->get('app')['client_key'];

        $url = "https://accounts.spotify.com/api/token";

        $headers = array(
            "Accept: application/json",
            "Content-Type: application/x-www-form-urlencoded",
            "Authorization: Basic " . base64_encode(
                $client_id . ":" . $client_key,
            ),
        );

        //init curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => 'http://localhost:8080/spotify/token',
        )));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);

        $result = json_decode($result);

        //saving access token and refresh token to database
        $user = Users::find($this->session->get('user_id'));
        $user[0]->bearer_token = $result->access_token;
        $user[0]->refresh_token = $result->refresh_token;
        $user[0]->save();

        //setting access token to session so that we do not have to access database again and again
        $this->session->set('bearer', $result->access_token);
        $this->response->redirect('user/dashboard');
    }
}

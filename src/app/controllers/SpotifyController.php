<?php

use Phalcon\Mvc\Controller;

class SpotifyController extends Controller
{
    public function indexAction()
    {
        $code = $this->request->get('code');
        $client_id = 'f559cc964f2046428482e389e7960d53';
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

    public function tokenAction()
    {
        $code = $this->request->get('code');
        $client_id = 'f559cc964f2046428482e389e7960d53';
        $client_key = '2c153c753ecf4fad988c7a95514d7cb4';
        $url = "https://accounts.spotify.com/api/token";

        $headers = array(
            "Accept: application/json",
            "Content-Type: application/x-www-form-urlencoded",
            "Authorization: Basic " . base64_encode(
                $client_id . ":" . $client_key,
            ),
        );

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
        print_r($result);
       
        $user = Users::find($this->session->get('user_id'));
        $user[0]->bearer_token = $result->access_token;
        $user[0]->refresh_token = $result->refresh_token;
        $user[0]->save();

        $this->session->set('bearer', $result->access_token);
        // print_r($this->session->get('bearer'));
        // die();
        // $this->session->set('bearer', 'BQAmAfQ-9iItwq0WkJPvjHyQO8RYne8dWAjUFDM5MpyrHNh8mfqGV3C3_EqTgS7Bi5dGKupyMnX5lJh_BEZMXq8lXLFHn-wtgmZ_qauY1gJkCD5hKV_yW2HB0WC7lxm1wKHJNqRyKWI44j5iUaI6mEzzb4A9qfJzArWaHOB-81LXZnvySs0nGYx2GdoReYH9q2eD9BLqB7qlQcrS');
        $this->response->redirect('user/dashboard');
    }
}

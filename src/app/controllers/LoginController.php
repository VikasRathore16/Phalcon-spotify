<?php

use Phalcon\Mvc\Controller;

class LoginController extends Controller
{
    public function indexAction()
    {
        $client_id = 'f559cc964f2046428482e389e7960d53';
        $scope = "playlist-read-private playlist-modify-private";
        $url = 'https://accounts.spotify.com/authorize?';

        $data = array(

            'response_type' => 'code',
            'client_id' => $client_id,
            'scope' => $scope,
            'redirect_uri' => 'http://localhost:8080/login/token',


        );
        $url = $url . http_build_query($data);
        $this->response->redirect($url);

        echo "<pre>";
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
            'redirect_uri' => 'http://localhost:8080/login/token',
        )));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $result = json_decode($result);
        print_r($result);
        $this->session->set('bearer', $result->access_token);
        $this->response->redirect('index');
    }
}

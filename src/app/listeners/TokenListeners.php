<?php

namespace App\Listeners;

use Phalcon\Http\Response;
use Phalcon\Di\Injectable;
use Phalcon\Events\Event;


class TokenListeners extends Injectable
{
    public function beforeHandleRequest(Event $event, $user)
    {

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
            'grant_type' => 'refresh_token',
            'refresh_token' => $user[0]->refresh_token,
        )));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);

        $result = json_decode($result);
        $this->session->set('bearer', $result->access_token);
        $user[0]->access_token = $result->access_token;
        $user[0]->save();
        // return $result;
        $this->response->redirect('user/dashboard');
    }
}

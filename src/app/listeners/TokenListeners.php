<?php

namespace App\Listeners;

use Phalcon\Http\Response;
use Phalcon\Di\Injectable;
use Phalcon\Events\Event;

/**
 * TokenListeners class
 * generate new bearer_token
 */
class TokenListeners extends Injectable
{
    /**
     * beforHandleRequest function
     * generate new bearer_token after expiry time with the help of refresh token
     * @param Event $event
     * @param [object] $user
     * @return void
     */
    public function beforeHandleRequest(Event $event, $user)
    {
        $client_id = $this->config->get('app')['client_id'];
        $client_key = $this->config->get('app')['client_key'];
        $url = "https://accounts.spotify.com/api/token";

        //header
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
            'grant_type' => 'refresh_token',
            'refresh_token' => $user[0]->refresh_token,
        )));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);

        $result = json_decode($result);

        //save new token to session and database
        $this->session->set('bearer', $result->access_token);
        $user[0]->access_token = $result->access_token;
        $user[0]->save();
        $this->response->redirect('user/dashboard');
    }
}

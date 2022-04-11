<?php

namespace App\Listeners;

use Phalcon\Http\Response;
use Phalcon\Di\Injectable;
use Phalcon\Events\Event;


class TokenListeners extends Injectable
{
    public function beforeHandleRequest(Event $event, \Phalcon\Mvc\Application $application)
    {
        try {
            /* something */
        } catch (\Exception $e) {
            die($e);
        }
    }
}

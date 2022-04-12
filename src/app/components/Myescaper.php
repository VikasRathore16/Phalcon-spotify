<?php

namespace App\Components;

use Phalcon\Escaper;

/**
 * Myescaper class
 */
class Myescaper
{
    public $escaper;

    /**
     * construct function
     * constructor
     */
    public function __construct()
    {
        $this->escaper =  new Escaper();
    }

    /**
     * santize function
     * escape all html characters from array
     * @param [array] $request
     * @return void
     */
    public function santize($request)
    {
        $arr = array();
        foreach ($request as $key => $value) {

            $arr[$key] = $this->escaper->escapeHtml($value);

            if ($value == '') {
                $arr[$key] = '';
            }
        }

        return $arr;
    }
}

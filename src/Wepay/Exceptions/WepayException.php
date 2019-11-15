<?php

namespace App\Wepay\Exceptions;

use Exception;
use Throwable;

class WepayException extends Exception {

    /**
     * @var array
     */
    public $response;

    /**
     * @param string         $message
     * @param int            $code
     * @param array          $response
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, $response = [], Throwable $previous = null)
    {
        $this->response = $response;

        parent::__construct($message, $code, $previous);
    }
}
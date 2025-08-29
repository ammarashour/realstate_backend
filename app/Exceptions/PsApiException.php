<?php

namespace App\Exceptions;

use Exception;

class PsApiException extends Exception
{
    protected $message;

    protected $statusCode;

    public function __construct($message = 'An error occurred', $statusCode = 400)
    {
        parent::__construct($message);
        $this->message = $message;
        $this->statusCode = $statusCode;
    }

    public function render()
    {
        return responseMsgApi($this->message, $this->statusCode);
    }
}

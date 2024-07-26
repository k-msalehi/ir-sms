<?php

namespace KMsalehi\IrSms\Exceptions;

use Exception;

class SmsException extends Exception
{
    protected $statusCode;
    protected $responseBody;

    public function __construct($message, $statusCode = 0, $responseBody = '', Exception $previous = null)
    {
        parent::__construct($message, $statusCode, $previous);

        $this->statusCode = $statusCode;
        $this->responseBody = $responseBody;
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getResponseBody()
    {
        return $this->responseBody;
    }
}

<?php

namespace App\Exceptions;

use Exception;

class OrderException extends Exception
{
    protected $error;
    protected $status;

    public function __construct($result, $resultStatus)
    {
        $this->error=$result;
        $this->status=$resultStatus;
    }
    public function report()
    {
        //
    }

    public function render()
    {
        return response($this->error, $this->status);
    }
}

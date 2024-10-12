<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{
    protected $message, $errors, $code;
    public function __construct($message = "Error occured", $errors = [], $code = 400)
    {
        $this->message = $message;
        $this->errors = $errors;
        $this->code = $code;
    }

    public function render(){
        return response()->json([
            'message' => $this->message,
            'errors' => $this->errors,
        ], $this->code);

    }
}

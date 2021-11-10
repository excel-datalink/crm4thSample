<?php

namespace App\Exceptions;

use Exception;

class OptimisticLockException extends Exception
{
    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function report()
    {

    }

    public function render()
    {
        return response()->json([
            'message' => 'error.',
            'data' => $this->message
        ], 422, [], JSON_UNESCAPED_UNICODE);
    }
}

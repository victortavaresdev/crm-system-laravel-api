<?php

namespace App\Exceptions\Custom;

use Exception;

class ConflictException extends Exception
{
    private string $_message;

    public function __construct(string $message)
    {
        $this->_message = $message;
    }

    public function render()
    {
        return response()->json(
            [
                'code' => 'CONFLICT',
                'message' => $this->_message,
                'status' => 409,
            ],
            409
        );
    }
}

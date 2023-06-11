<?php

namespace App\Exceptions\CustomExceptions;

use Exception;

class ConflictException extends Exception
{
    private string $info;

    public function __construct(string $message)
    {
        $this->info = $message;
    }

    public function render()
    {
        return response()->json(
            [
                'code' => 'CONFLICT',
                'message' => $this->info,
                'status' => 409
            ],
            409
        );
    }
}

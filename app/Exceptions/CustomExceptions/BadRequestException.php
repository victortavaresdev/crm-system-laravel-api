<?php

namespace App\Exceptions\CustomExceptions;

use Exception;

class BadRequestException extends Exception
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
                'code' => 'BAD_REQUEST',
                'message' => $this->info,
                'status' => 400
            ],
            400
        );
    }
}

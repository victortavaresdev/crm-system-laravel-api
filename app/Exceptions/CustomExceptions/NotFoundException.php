<?php

namespace App\Exceptions\CustomExceptions;

use Exception;

class NotFoundException extends Exception
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
                'code' => 'NOT_FOUND',
                'message' => $this->info,
                'status' => 404
            ],
            404
        );
    }
}

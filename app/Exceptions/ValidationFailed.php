<?php

namespace App\Exceptions;

use Exception;

class ValidationFailed extends Exception
{
    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function render()
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
        ], 422);
    }
}

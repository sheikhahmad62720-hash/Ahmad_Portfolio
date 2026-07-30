<?php

namespace App\Extensions;

use Illuminate\Database\LostConnectionDetector as BaseDetector;
use Throwable;

class LostConnectionDetector extends BaseDetector
{
    public function causedByLostConnection(Throwable $e): bool
    {
        $message = $e->getMessage();

        if (str_contains($message, 'unable to open database file')) {
            return true;
        }

        return parent::causedByLostConnection($e);
    }
}

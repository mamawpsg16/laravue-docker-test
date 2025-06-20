<?php

namespace App\Services;

use App\Contracts\LoggerInterface;
use Illuminate\Support\Facades\DB;

class DatabaseLogger implements LoggerInterface
{
    public function log(string $message):void
    {
        echo "{$message}.  DatabaseLogger" . PHP_EOL;
        // DB::table('logs')->insert(['message' => $message, 'created_at' => now()]);
    }
}

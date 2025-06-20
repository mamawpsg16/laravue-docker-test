<?php

// app/Services/FileLogger.php
namespace App\Services;

use App\Contracts\LoggerInterface;

class FileLogger implements LoggerInterface
{
    public function log(string $message)
    {
        // For example purposes
        file_put_contents(storage_path('logs/custom.log'), $message . PHP_EOL, FILE_APPEND);
        echo "{$message}.  FileLogger" . PHP_EOL;
    }
}

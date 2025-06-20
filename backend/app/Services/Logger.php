<?php

namespace App\Services;

class Logger {
    public function log(string $message) {
        echo "Log: " . $message;
    }
}
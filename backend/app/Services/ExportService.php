<?php
namespace App\Services;

use App\Contracts\LoggerInterface;

class ExportService {
    public function __construct(protected LoggerInterface $logger) {}
    public function run() {
        $this->logger->log('Exporting data...');
    }
}
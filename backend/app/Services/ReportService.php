<?php

// app/Services/ReportService.php
namespace App\Services;

use App\Contracts\LoggerInterface;

class ReportService
{
    protected $logger;
    protected $reportType;

    public function __construct(LoggerInterface $logger, string $reportType)
    {
        $this->logger = $logger;
        $this->reportType = $reportType;
    }

    public function generate()
    {
        $this->logger->log("Generating {$this->reportType} report.");
        return "Generated {$this->reportType} report!";
    }

    public function run(){
        $this->logger->log('Generating data...');
    }
}

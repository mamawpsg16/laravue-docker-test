<?php
// app/Services/ReportAggregator.php
namespace App\Services;

use App\Contracts\ReportInterface;

class ReportAggregator {
    protected $reports;

    public function __construct(ReportInterface ...$reports) {
        $this->reports = $reports;
    }

    public function generateAll() {
        return array_map(fn($report) => $report->generate(), $this->reports);
    }
}

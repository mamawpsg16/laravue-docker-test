<?php
namespace App\Reports;

use App\Contracts\ReportInterface;


class MemoryReport implements ReportInterface {
    public function generate() {
        return 'Memory usage report';
    }
}

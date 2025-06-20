<?php
namespace App\Reports;

use App\Contracts\ReportInterface;

// app/Reports/SpeedReport.php
class SpeedReport implements ReportInterface {
    public function generate() {
        return 'Server speed report';
    }
}
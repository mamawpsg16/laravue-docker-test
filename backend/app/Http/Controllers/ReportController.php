<?php

namespace App\Http\Controllers;


use App\Services\ReportAggregator;

class ReportController extends Controller {
    public function index(ReportAggregator $aggregator) {
        $reports = $aggregator->generateAll();

        return $reports;
    }
}
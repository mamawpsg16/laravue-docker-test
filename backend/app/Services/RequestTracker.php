<?php 
namespace App\Services;

class RequestTracker {
    public $id;

    public function __construct() {
        $this->id = uniqid('KAM_');
    }
}

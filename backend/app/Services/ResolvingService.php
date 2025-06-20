<?php
namespace App\Services;

class ResolvingService {
    public function __construct(protected $var) {
    }
    public function index() {
        return $this->var;
    }
}
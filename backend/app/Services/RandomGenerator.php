<?php

namespace App\Services;

class RandomGenerator {
    public $id;

    public function __construct()
    {
        $this->id = uniqid(); // generates a unique ID
    }
}

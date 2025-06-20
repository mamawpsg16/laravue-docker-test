<?php
namespace App\Contracts;

interface FilterInterface {
    public function apply(string $content): string;
}
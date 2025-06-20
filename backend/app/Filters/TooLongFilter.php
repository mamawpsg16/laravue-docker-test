<?php
namespace App\Filters;

use App\Contracts\FilterInterface;


class TooLongFilter implements FilterInterface {
    public function apply(string $content): string {
        return substr($content, 0, 100);
    }
}
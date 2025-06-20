<?php

namespace App\Services;

use App\Contracts\FilterInterface;

class Firewall {
    protected array $filters;

    public function __construct(FilterInterface ...$filters) {
        $this->filters = $filters;
    }

    public function process(string $input): string {
        foreach ($this->filters as $filter) {
            $input = $filter->apply($input);
        }
        return $input;
    }
}
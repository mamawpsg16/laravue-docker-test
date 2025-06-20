<?php

// app/Services/Transistor.php
namespace App\Services;

use App\Contracts\ParserInterface;


class Transistor
{
    protected $parser;

    public function __construct(ParserInterface $parser)
    {
        $this->parser = $parser;
    }

    public function publish()
    {
        return $this->parser->parse();  // Uses the PodcastParser
    }
}

<?php

// app/Services/PodcastParser.php
namespace App\Services;

use App\Contracts\ParserInterface;


class PodcastParser implements ParserInterface
{
    public function parse()
    {
        return 'Parsing podcast...';
    }
}

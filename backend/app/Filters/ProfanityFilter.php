<?php
namespace App\Filters;

use App\Contracts\FilterInterface;


class ProfanityFilter implements FilterInterface {
    public function apply(string $content): string {
        // List of profane words to censor
        $profanities = ['ass', 'damn', 'hell', 'shit']; 

        // Build regex pattern to match whole words, case-insensitive
        $pattern = array_map(function($word) {
            return '/\b' . preg_quote($word, '/') . '\b/i';
        }, $profanities);

        // Replace all profanities with '***'
        return preg_replace($pattern, '***', $content);
    }

}
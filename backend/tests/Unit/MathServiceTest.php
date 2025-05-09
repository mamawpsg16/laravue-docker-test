<?php

namespace Tests\Unit;

use App\Services\MathService;
use PHPUnit\Framework\TestCase;

class MathServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_add_numbers()
    {
        $math = new MathService();
        $this->assertEquals(5, $math->add(2, 3));
    }
}

<?php

namespace Tests\Unit;

use App\Traits\Scorable;
use PHPUnit\Framework\TestCase;

class ScorableTest extends TestCase
{
    use Scorable;

    public function test_has_passed_returns_true_when_percentage_meets_requirement(): void
    {
        $this->assertTrue($this->hasPassed(75, 70));
        $this->assertTrue($this->hasPassed(70, 70));
    }

    public function test_has_passed_returns_false_when_percentage_below_requirement(): void
    {
        $this->assertFalse($this->hasPassed(65, 70));
    }
}

<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Utility;

use StageRightLabs\Bloom\Ledger\Price;
use StageRightLabs\Bloom\Primitives\Int32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Utility\Copy;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Utility\Copy
 */
class CopyTest extends TestCase
{
    /**
     * @test
     * @covers ::deep
     */
    public function it_creates_a_deep_copy()
    {
        $a = Int32::of(1);
        $b = Int32::of(2);
        $price = Price::of($a, $b);
        /** @var Price */
        $copy = Copy::deep($price);

        $this->assertNotEquals(spl_object_id($price), spl_object_id($copy));
        $this->assertNotEquals(spl_object_id($a), spl_object_id($copy->getNumerator()));
        $this->assertNotEquals(spl_object_id($b), spl_object_id($copy->getDenominator()));
    }
}

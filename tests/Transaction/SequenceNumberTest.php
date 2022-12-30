<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\SequenceNumber;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\SequenceNumber
 */
class SequenceNumberTest extends TestCase
{
    /**
     * @test
     * @covers ::increment
     */
    public function it_can_be_incremented()
    {
        $original = SequenceNumber::of(1);
        $modified = $original->increment();

        $this->assertInstanceOf(SequenceNumber::class, $modified);
        $this->assertTrue($modified->isEqualTo(2));
    }
}

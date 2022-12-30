<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Account;

use StageRightLabs\Bloom\Account\ThresholdIndexes;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Account\ThresholdIndexes
 */
class ThresholdIndexesTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0 => ThresholdIndexes::THRESHOLD_MASTER_WEIGHT,
            1 => ThresholdIndexes::THRESHOLD_LOW,
            2 => ThresholdIndexes::THRESHOLD_MED,
            3 => ThresholdIndexes::THRESHOLD_HIGH,
        ];
        $memoType = new ThresholdIndexes();

        $this->assertEquals($expected, $memoType->getOptions());
    }
}

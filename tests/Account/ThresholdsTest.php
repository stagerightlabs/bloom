<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Account;

use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Account\Thresholds
 */
class ThresholdsTest extends TestCase
{
    /**
     * @test
     * @covers ::of
     */
    public function it_can_be_created_from_a_set_of_integers()
    {
        $thresholds = Thresholds::of(1, 2, 3, 4);
        $this->assertEquals('01020304', $thresholds->toHex());
    }

    /**
     * @test
     * @covers ::of
     */
    public function it_does_not_accept_values_higher_than_255()
    {
        $this->expectException(InvalidArgumentException::class);
        Thresholds::of(300);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $thresholds = (new Thresholds())->withRaw('ABCD');
        $buffer = XDR::fresh()->write($thresholds);

        $this->assertEquals('QUJDRA==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $thresholds = XDR::fromBase64('QUJDRA==')->read(Thresholds::class);

        $this->assertInstanceOf(Thresholds::class, $thresholds);
        $this->assertEquals('ABCD', $thresholds->getRaw());
    }

    /**
     * @test
     * @covers ::withRaw
     * @covers ::getRaw
     */
    public function it_accepts_a_code()
    {
        $thresholds = (new Thresholds())->withRaw('ABCD');

        $this->assertInstanceOf(Thresholds::class, $thresholds);
        $this->assertEquals('ABCD', $thresholds->getRaw());
    }

    /**
     * @test
     * @covers ::withRaw
     */
    public function it_will_not_accept_raw_values_longer_than_four_bytes()
    {
        $this->expectException(InvalidArgumentException::class);
        (new Thresholds())->withRaw(str_repeat('A', 5));
    }

    /**
     * @test
     * @covers ::toHex
     */
    public function it_can_be_converted_to_a_hex_string()
    {
        $thresholds = (new Thresholds())->withRaw('ABCD');
        $this->assertEquals('41424344', $thresholds->toHex());
    }

    /**
     * @test
     * @covers ::getMasterThreshold
     */
    public function it_returns_the_master_threshold()
    {
        $thresholdsA = Thresholds::of(1, 2, 3, 4);
        $this->assertEquals(1, $thresholdsA->getMasterThreshold());

        $thresholdsB = new Thresholds();
        $this->assertEquals(1, $thresholdsB->getMasterThreshold());
    }

    /**
     * @test
     * @covers ::getLowThreshold
     */
    public function it_returns_the_low_threshold()
    {
        $thresholdsA = Thresholds::of(1, 2, 3, 4);
        $this->assertEquals(2, $thresholdsA->getLowThreshold());

        $thresholdsB = new Thresholds();
        $this->assertEquals(0, $thresholdsB->getLowThreshold());
    }

    /**
     * @test
     * @covers ::getMediumThreshold
     */
    public function it_returns_the_medium_threshold()
    {
        $thresholdsA = Thresholds::of(1, 2, 3, 4);
        $this->assertEquals(3, $thresholdsA->getMediumThreshold());

        $thresholdsB = new Thresholds();
        $this->assertEquals(0, $thresholdsB->getMediumThreshold());
    }

    /**
     * @test
     * @covers ::getHighThreshold
     */
    public function it_returns_the_high_threshold()
    {
        $thresholdsA = Thresholds::of(1, 2, 3, 4);
        $this->assertEquals(4, $thresholdsA->getHighThreshold());

        $thresholdsB = new Thresholds();
        $this->assertEquals(0, $thresholdsB->getHighThreshold());
    }
}

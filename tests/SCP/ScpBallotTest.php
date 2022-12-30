<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Primitives\Value;
use StageRightLabs\Bloom\SCP\ScpBallot;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\ScpBallot
 */
class ScpBallotTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $scpBallot = (new ScpBallot())
            ->withCounter(UInt32::of(1))
            ->withValue(Value::of('example'));
        $buffer = XDR::fresh()->write($scpBallot);

        $this->assertEquals('AAAAAQAAAAdleGFtcGxlAA==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_counter_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $scpBallot = (new ScpBallot())
            ->withValue(Value::of('example'));
        XDR::fresh()->write($scpBallot);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_value_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $scpBallot = (new ScpBallot())
            ->withCounter(UInt32::of(1));
        XDR::fresh()->write($scpBallot);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $scpBallot = XDR::fromBase64('AAAAAQAAAAdleGFtcGxlAA==')
            ->read(ScpBallot::class);

        $this->assertInstanceOf(ScpBallot::class, $scpBallot);
        $this->assertInstanceOf(UInt32::class, $scpBallot->getCounter());
        $this->assertInstanceOf(Value::class, $scpBallot->getValue());
    }

    /**
     * @test
     * @covers ::withCounter
     * @covers ::getCounter
     */
    public function it_accepts_a_uint32_counter()
    {
        $scpBallot = (new ScpBallot())->withCounter(UInt32::of(1));

        $this->assertInstanceOf(ScpBallot::class, $scpBallot);
        $this->assertInstanceOf(UInt32::class, $scpBallot->getCounter());
    }

    /**
     * @test
     * @covers ::withCounter
     * @covers ::getCounter
     */
    public function it_accepts_a_native_int_counter()
    {
        $scpBallot = (new ScpBallot())->withCounter(1);

        $this->assertInstanceOf(ScpBallot::class, $scpBallot);
        $this->assertInstanceOf(UInt32::class, $scpBallot->getCounter());
    }

    /**
     * @test
     * @covers ::withValue
     * @covers ::getValue
     */
    public function it_accepts_value_object_as_a_value()
    {
        $scpBallot = (new ScpBallot())->withValue(Value::of('example'));

        $this->assertInstanceOf(ScpBallot::class, $scpBallot);
        $this->assertInstanceOf(Value::class, $scpBallot->getValue());
    }

    /**
     * @test
     * @covers ::withValue
     * @covers ::getValue
     */
    public function it_accepts_a_native_string_as_a_value()
    {
        $scpBallot = (new ScpBallot())->withValue('example');

        $this->assertInstanceOf(ScpBallot::class, $scpBallot);
        $this->assertInstanceOf(Value::class, $scpBallot->getValue());
    }
}

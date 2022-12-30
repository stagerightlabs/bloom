<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\DataValue;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Primitives\DataValue
 */
class DataValueTest extends TestCase
{
    /**
     * @test
     * @covers ::of
     */
    public function it_can_be_created_from_a_string()
    {
        $dataValue = DataValue::of('ABCD');

        $this->assertInstanceOf(DataValue::class, $dataValue);
        $this->assertEquals('ABCD', $dataValue->getRaw());
    }

    /**
     * @test
     * @covers ::of
     */
    public function it_can_be_created_from_an_existing_data_value()
    {
        $dataValueA = DataValue::of('ABCD');
        $dataValueB = DataValue::of($dataValueA);

        $this->assertInstanceOf(DataValue::class, $dataValueB);
        $this->assertEquals('ABCD', $dataValueB->getRaw());
        $this->assertNotEquals(spl_object_id($dataValueA), spl_object_id($dataValueB));
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $dataValue = DataValue::of('ABCD');
        $buffer = XDR::fresh()->write($dataValue);

        $this->assertEquals('AAAABEFCQ0Q=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $dataValue = XDR::fromBase64('AAAABEFCQ0Q=')->read(DataValue::class);

        $this->assertInstanceOf(DataValue::class, $dataValue);
        $this->assertEquals('ABCD', $dataValue->getRaw());
    }

    /**
     * @test
     * @covers ::withRaw
     * @covers ::getRaw
     */
    public function it_accepts_a_code()
    {
        $dataValue = (new DataValue())->withRaw('ABCD');

        $this->assertInstanceOf(DataValue::class, $dataValue);
        $this->assertEquals('ABCD', $dataValue->getRaw());
    }

    /**
     * @test
     * @covers ::withRaw
     */
    public function it_will_not_accept_values_longer_than_sixty_four_bytes()
    {
        $this->expectException(InvalidArgumentException::class);
        (new DataValue())->withRaw(str_repeat('A', 65));
    }

    /**
     * @test
     * @covers ::toNativeString
     * @covers ::__toString
     */
    public function it_can_be_converted_to_a_native_string()
    {
        $dataValue = (new DataValue())->withRaw('ABCD');

        $this->assertEquals('ABCD', $dataValue->__toString());
        $this->assertEquals('ABCD', $dataValue->toNativeString());
    }
}

<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Primitives\DataValue;
use StageRightLabs\Bloom\Primitives\OptionalDataValue;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Primitives\OptionalDataValue
 */
class OptionalDataValueTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrValueType
     */
    public function it_defines_an_xdr_value_type()
    {
        $this->assertEquals(DataValue::class, OptionalDataValue::getXdrValueType());
    }

    /**
     * @test
     * @covers ::some
     */
    public function it_can_be_instantiated_from_a_data_value()
    {
        $optional = OptionalDataValue::some(new DataValue('ABCD'));
        $this->assertInstanceOf(OptionalDataValue::class, $optional);
    }

    /**
     * @test
     * @covers ::some
     */
    public function it_can_be_instantiated_from_an_existing_optional_data_value()
    {
        $optionalA = OptionalDataValue::some(new DataValue('ABCD'));
        $optionalB = OptionalDataValue::some($optionalA);

        $this->assertInstanceOf(OptionalDataValue::class, $optionalB);
    }

    /**
     * @test
     * @covers ::some
     */
    public function it_can_be_instantiated_from_a_string()
    {
        $optional = OptionalDataValue::some('ABCD');
        $this->assertInstanceOf(OptionalDataValue::class, $optional);
    }

    /**
     * @test
     * @covers ::unwrap
     */
    public function it_returns_the_data_value()
    {
        $optional = OptionalDataValue::some(new DataValue());
        $this->assertInstanceOf(DataValue::class, $optional->unwrap());
    }
}

<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\SCP\StellarValueType;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\StellarValueType
 */
class StellarValueTypeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0 => StellarValueType::STELLAR_VALUE_BASIC,
            1 => StellarValueType::STELLAR_VALUE_SIGNED,
        ];
        $stellarValueType = new StellarValueType();

        $this->assertEquals($expected, $stellarValueType->getOptions());
    }

    /**
     * @test
     * @covers ::basic
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_basic_type()
    {
        $stellarValueType = StellarValueType::basic();

        $this->assertInstanceOf(StellarValueType::class, $stellarValueType);
        $this->assertEquals(StellarValueType::STELLAR_VALUE_BASIC, $stellarValueType->getType());
    }

    /**
     * @test
     * @covers ::signed
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_signed_type()
    {
        $stellarValueType = StellarValueType::signed();

        $this->assertInstanceOf(StellarValueType::class, $stellarValueType);
        $this->assertEquals(StellarValueType::STELLAR_VALUE_SIGNED, $stellarValueType->getType());
    }
}

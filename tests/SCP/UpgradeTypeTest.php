<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\SCP\UpgradeType;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\UpgradeType
 */
class UpgradeTypeTest extends TestCase
{
    /**
     * @test
     * @covers ::of
     */
    public function it_can_be_instantiated_from_a_string()
    {
        $upgradeType = UpgradeType::of('Hello World');

        $this->assertInstanceOf(UpgradeType::class, $upgradeType);
        $this->assertEquals('Hello World', $upgradeType->getValue());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $upgradeType = UpgradeType::of('Hello World');
        $buffer = XDR::fresh()->write($upgradeType)->toBase64();

        $this->assertEquals('AAAAC0hlbGxvIFdvcmxkAA==', $buffer);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $upgradeType = XDR::fromBase64('AAAAC0hlbGxvIFdvcmxkAA==')->read(UpgradeType::class);

        $this->assertInstanceOf(UpgradeType::class, $upgradeType);
        $this->assertEquals('Hello World', $upgradeType->getValue());
    }

    /**
     * @test
     * @covers ::__toString
     * @covers ::toNativeString
     */
    public function it_can_be_converted_to_a_native_string()
    {
        $upgradeType = UpgradeType::of('Hello World');
        $this->assertEquals('Hello World', (string)$upgradeType);

        $upgradeType = UpgradeType::of('Hello World');
        $this->assertEquals('Hello World', $upgradeType->toNativeString());
    }

    /**
     * @test
     * @covers ::getValue
     */
    public function it_returns_its_underlying_value()
    {
        $upgradeType = UpgradeType::of('Hello World');

        $this->assertEquals('Hello World', $upgradeType->getValue());
    }

    /**
     * @test
     * @covers ::withValue
     */
    public function its_value_can_be_set()
    {
        $upgradeType = (new UpgradeType())->withValue('Hello World');

        $this->assertEquals('Hello World', $upgradeType->getValue());
    }

    /**
     * @test
     * @covers ::withValue
     */
    public function it_rejects_values_that_exceed_the_maximum_length()
    {
        $this->expectException(InvalidArgumentException::class);
        UpgradeType::of('abcdefghijklmnopqrstuvwxyz012345abcdefghijklmnopqrstuvwxyz012345abcdefghijklmnopqrstuvwxyz012345abcdefghijklmnopqrstuvwxyz0123456');
    }

    /**
     * @test
     * @covers ::withValue
     */
    public function it_is_immutable()
    {
        $upgradeTypeA = UpgradeType::of('foo');
        $upgradeTypeB = $upgradeTypeA->withValue('bar');

        $this->assertEquals('foo', $upgradeTypeA->getValue());
        $this->assertEquals('bar', $upgradeTypeB->getValue());
    }
}

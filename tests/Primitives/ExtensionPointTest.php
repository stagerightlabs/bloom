<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Primitives;

use StageRightLabs\Bloom\Primitives\ExtensionPoint;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Primitives\ExtensionPoint
 */
class ExtensionPointTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_discriminator_type()
    {
        $this->assertEquals(XDR::INT, ExtensionPoint::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            0 => XDR::VOID,
        ];

        $this->assertEquals($expected, ExtensionPoint::arms());
    }

    /**
     * @test
     * @covers ::empty
     * @covers ::unwrap
     */
    public function it_can_be_instantiated_with_an_empty_value()
    {
        $extensionPoint = ExtensionPoint::empty();

        $this->assertInstanceOf(ExtensionPoint::class, $extensionPoint);
        $this->assertNull($extensionPoint->unwrap());
    }
}

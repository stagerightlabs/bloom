<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Account;

use StageRightLabs\Bloom\Account\OptionalSigner;
use StageRightLabs\Bloom\Account\Signer;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Account\OptionalSigner
 */
class OptionalSignerTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrValueType
     */
    public function it_defines_an_xdr_value_type()
    {
        $this->assertEquals(Signer::class, OptionalSigner::getXdrValueType());
    }

    /**
     * @test
     * @covers ::some
     */
    public function it_can_be_instantiated_from_an_signer()
    {
        $optional = OptionalSigner::some(new Signer());

        $this->assertInstanceOf(OptionalSigner::class, $optional);
    }

    /**
     * @test
     * @covers ::unwrap
     */
    public function it_returns_the_signer()
    {
        $optional = OptionalSigner::some(new Signer());
        $this->assertInstanceOf(Signer::class, $optional->unwrap());
    }
}

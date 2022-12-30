<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Account;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Cryptography\ED25519;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Cryptography\ED25519
 */
class ECD25519Test extends TestCase
{
    /**
     * @test
     * @covers ::fromAddress
     */
    public function it_can_be_instantiated_from_a_string_address()
    {
        $ed25519 = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $this->assertInstanceOf(ED25519::class, $ed25519);
    }

    /**
     * @test
     * @covers ::fromAddress
     */
    public function it_can_be_instantiated_from_an_addressable_object()
    {
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $ed25519 = ED25519::fromAddress($account);
        $this->assertInstanceOf(ED25519::class, $ed25519);
    }

    /**
     * @test
     * @covers ::fromAddress
     */
    public function it_rejects_invalid_addresses()
    {
        $this->expectException(InvalidArgumentException::class);
        ED25519::fromAddress('GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VFFOOBAR');
    }

    /**
     * @test
     * @covers ::getAddress
     */
    public function it_returns_the_address_string()
    {
        $ed25519 = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $this->assertEquals(
            'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            $ed25519->getAddress()
        );
    }
}

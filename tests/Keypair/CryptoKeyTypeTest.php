<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Keypair;

use StageRightLabs\Bloom\Keypair\CryptoKeyType;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Keypair\CryptoKeyType
 */
class CryptoKeyTypeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_has_options_defined_by_the_stellar_interface_definition_files()
    {
        $expected = [
            0   => 'keyTypeEd25519',
            1   => 'keyTypePreAuthTx',
            2   => 'keyTypeHashX',
            256 => 'keyTypeMuxedEd25519',
        ];

        $this->assertEquals($expected, CryptoKeyType::getOptions());
    }

    /**
     * @test
     * @covers ::ed25519
     */
    public function it_can_be_instantiated_as_an_ed25519_key_type()
    {
        $cryptoKeyType = CryptoKeyType::ed25519();
        $this->assertEquals('keyTypeEd25519', strval($cryptoKeyType));
    }

    /**
     * @test
     * @covers ::preAuthTx
     */
    public function it_can_be_instantiated_as_pre_auth_tx_key_type()
    {
        $cryptoKeyType = CryptoKeyType::preAuthTx();
        $this->assertEquals('keyTypePreAuthTx', strval($cryptoKeyType));
    }

    /**
     * @test
     * @covers ::hashX
     */
    public function it_can_be_instantiated_as_a_hash_x_key_type()
    {
        $cryptoKeyType = CryptoKeyType::hashX();
        $this->assertEquals('keyTypeHashX', strval($cryptoKeyType));
    }

    /**
     * @test
     * @covers ::muxedEd25519
     */
    public function it_can_be_instantiated_as_a_muxed_ed25519_key_type()
    {
        $cryptoKeyType = CryptoKeyType::muxedEd25519();
        $this->assertEquals('keyTypeMuxedEd25519', strval($cryptoKeyType));
    }
}

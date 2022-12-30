<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Keypair;

use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\Cryptography\Signature;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Exception\InvalidKeyException;
use StageRightLabs\Bloom\Keypair\Keypair;
use StageRightLabs\Bloom\Keypair\KeypairService;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Keypair\KeypairService
 */
class KeypairServiceTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     */
    public function the_keypair_service_can_be_located()
    {
        $bloom = new Bloom();
        $this->assertInstanceOf(KeypairService::class, $bloom->keypair);
    }

    /**
     * @test
     * @covers ::generate
     */
    public function it_generates_a_random_keypair()
    {
        $bloom = new Bloom();
        $keypair = $bloom->keypair->generate();
        $this->assertInstanceOf(Keypair::class, $keypair);
        $this->assertNotEmpty($keypair->getSeed());
        $this->assertNotEmpty($keypair->getPrivateKey());
        $this->assertNotEmpty($keypair->getAddress());
        $this->assertNotEmpty($keypair->getPublicKey());
    }

    /**
     * @test
     * @covers ::fromPrivateKey
     */
    public function it_generates_a_keypair_from_a_private_key()
    {
        $bloom = new Bloom();
        $bytes = hex2bin('ef891d35d54780ab2f0a55e36ad04b8b366ff184ef5d2c74b31e85dd92789974');
        $keypair = $bloom->keypair->fromPrivateKey($bytes);
        $this->assertInstanceOf(Keypair::class, $keypair);
        $this->assertEquals('SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6', $keypair->getSeed());
        $this->assertEquals($bytes, $keypair->getPrivateKey());
        $this->assertEquals('GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ', $keypair->getAddress());
        $this->assertEquals(hex2bin('aa06af492c86721f1f438836859480038b69da71ff3f044c78a2e48c959c5503'), $keypair->getPublicKey());
    }

    /**
     * @test
     * @covers ::fromPrivateKey
     */
    public function it_requires_32_bytes_for_private_keys()
    {
        $this->expectException(InvalidArgumentException::class);
        $bloom = new Bloom();
        $bytes = hex2bin('03a8');
        $bloom->keypair->fromPrivateKey($bytes);
    }

    /**
     * @test
     * @covers ::fromPublicKey
     */
    public function it_generates_a_keypair_from_a_public_key()
    {
        $bloom = new Bloom();
        $bytes = hex2bin('6a6d41c73943538086697978d040780d61c5ce6b34b1702ae104287633839dee');
        $keypair = $bloom->keypair->fromPublicKey($bytes);
        $this->assertInstanceOf(Keypair::class, $keypair);
        $this->assertEquals('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW', $keypair->getAddress());
        $this->assertEquals($bytes, $keypair->getPublicKey());
        $this->assertEmpty($keypair->getSeed());
        $this->assertEmpty($keypair->getPrivateKey());
    }

    /**
     * @test
     * @covers ::fromPublicKey
     */
    public function it_requires_32_bytes_for_public_keys()
    {
        $this->expectException(InvalidArgumentException::class);
        $bloom = new Bloom();
        $bytes = hex2bin('6a6d');
        $bloom->keypair->fromPublicKey($bytes);
    }

    /**
     * @test
     * @covers ::fromSeed
     */
    public function it_generates_a_keypair_from_a_seed()
    {
        $bloom = new Bloom();
        $seed = 'SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6';
        $keypair = $bloom->keypair->fromSeed($seed);
        $this->assertInstanceOf(Keypair::class, $keypair);
        $this->assertEquals($seed, $keypair->getSeed());
        $this->assertEquals(hex2bin('ef891d35d54780ab2f0a55e36ad04b8b366ff184ef5d2c74b31e85dd92789974'), $keypair->getPrivateKey());
        $this->assertEquals('GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ', $keypair->getAddress());
        $this->assertEquals(hex2bin('aa06af492c86721f1f438836859480038b69da71ff3f044c78a2e48c959c5503'), $keypair->getPublicKey());
    }

    /**
     * @test
     * @covers ::fromSeed
     */
    public function it_requires_a_valid_seed()
    {
        $this->expectException(InvalidKeyException::class);
        $bloom = new Bloom();
        $bloom->keypair->fromSeed('INVALID_SEED');
    }

    /**
     * @test
     * @covers ::fromAddress
     */
    public function it_generates_a_keypair_from_an_address()
    {
        $bloom = new Bloom();
        $address = 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW';
        $keypair = $bloom->keypair->fromAddress($address);
        $this->assertInstanceOf(Keypair::class, $keypair);
        $this->assertEquals($address, $keypair->getAddress());
        $this->assertEquals(hex2bin('6a6d41c73943538086697978d040780d61c5ce6b34b1702ae104287633839dee'), $keypair->getPublicKey());
        $this->assertEmpty($keypair->getSeed());
        $this->assertEmpty($keypair->getPrivateKey());
    }

    /**
     * @test
     * @covers ::fromAddress
     */
    public function it_requires_a_valid_address()
    {
        $this->expectException(InvalidKeyException::class);
        $bloom = new Bloom();
        $bloom->keypair->fromAddress('INVALID_ADDRESS');
    }

    /**
     * @test
     * @covers ::canSign
     */
    public function it_verifies_a_keypair_with_private_keys_can_sign()
    {
        $bloom = new Bloom();
        $seed = 'SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6';
        $keypair = $bloom->keypair->fromSeed($seed);
        $this->assertTrue($bloom->keypair->canSign($keypair));
    }

    /**
     * @test
     * @covers ::canSign
     */
    public function it_verifies_a_keypair_without_private_keys_can_not_sign()
    {
        $bloom = new Bloom();
        $address = 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW';
        $keypair = $bloom->keypair->fromAddress($address);
        $this->assertFalse($bloom->keypair->canSign($keypair));
    }

    /**
     * @test
     * @covers ::sign
     */
    public function it_can_sign_a_message_with_a_seed()
    {
        $bloom = new Bloom();
        $seed = 'SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6';
        $keypair = $bloom->keypair->fromSeed($seed);
        $signature = $bloom->keypair->sign($keypair, 'abcd');

        $this->assertInstanceOf(Signature::class, $signature);
    }

    /**
     * @test
     * @covers ::sign
     */
    public function it_cannot_sign_a_message_with_only_an_address()
    {
        $this->expectException(InvalidKeyException::class);
        $bloom = new Bloom();
        $address = 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW';
        $keypair = $bloom->keypair->fromAddress($address);
        $bloom->keypair->sign($keypair, 'abcd');
    }
}

<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Keypair;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\MuxedAccount;
use StageRightLabs\Bloom\Account\Signer;
use StageRightLabs\Bloom\Cryptography\DecoratedSignature;
use StageRightLabs\Bloom\Cryptography\Signature;
use StageRightLabs\Bloom\Cryptography\SignatureHint;
use StageRightLabs\Bloom\Exception\InvalidKeyException;
use StageRightLabs\Bloom\Keypair\Keypair;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Keypair\Keypair
 *
 * Keypairs used for testing
 * 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'
 * 'SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6'
 * 'MBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FEAAAAAAAAAAAAGPQ4'
 *
 * 'GAAYP5PGGRZLM5OQIPI4LJNENVJY4FFMKZW2U5IEKPAHS362B6G2XTG4'
 * 'SBEL6DL3UGA3W7TPRJFVFHKYG6LXRH6IN7FV7C46XDUKCTUX5EHUGOGC'
 */
class KeypairTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     */
    public function a_keypair_can_be_instantiated_with_an_address()
    {
        $keypair = new Keypair(address: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');

        $this->assertEquals(
            'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            $keypair->getAddress()
        );
        $this->assertNotEmpty($keypair->getPublicKey());
        $this->assertEmpty($keypair->getSeed());
        $this->assertEmpty($keypair->getPrivateKey());
        $this->assertFalse($keypair->canSign());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function it_can_be_instantiated_with_a_seed()
    {
        $keypair = new Keypair(seed: 'SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6');

        $this->assertEquals(
            'SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6',
            $keypair->getSeed()
        );
        $this->assertEquals(
            'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ',
            $keypair->getAddress()
        );
        $this->assertNotEmpty($keypair->getPublicKey());
        $this->assertNotEmpty($keypair->getPrivateKey());
        $this->assertTrue($keypair->canSign());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function it_can_be_instantiated_with_an_address_and_a_seed()
    {
        $keypair = new Keypair(
            address: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            seed: 'SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6',
        );

        $this->assertEquals(
            'SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6',
            $keypair->getSeed()
        );
        $this->assertEquals(
            'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            $keypair->getAddress()
        );
        $this->assertNotEmpty($keypair->getPublicKey());
        $this->assertNotEmpty($keypair->getPrivateKey());
        $this->assertTrue($keypair->canSign());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function it_can_be_instantiated_with_public_bytes()
    {
        $keypair = new Keypair(
            publicKey: hex2bin('6a6d41c73943538086697978d040780d61c5ce6b34b1702ae104287633839dee')
        );

        $this->assertEquals(
            'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            $keypair->getAddress()
        );
        $this->assertEmpty($keypair->getSeed());
        $this->assertNotEmpty($keypair->getPublicKey());
        $this->assertEmpty($keypair->getPrivateKey());
        $this->assertFalse($keypair->canSign());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function it_can_be_instantiated_with_private_bytes()
    {
        $keypair = new Keypair(
            privateKey: hex2bin('ef891d35d54780ab2f0a55e36ad04b8b366ff184ef5d2c74b31e85dd92789974')
        );

        $this->assertEquals(
            'SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6',
            $keypair->getSeed()
        );
        $this->assertEquals(
            'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ',
            $keypair->getAddress()
        );
        $this->assertNotEmpty($keypair->getPublicKey());
        $this->assertNotEmpty($keypair->getPrivateKey());
        $this->assertTrue($keypair->canSign());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function it_can_be_instantiated_with_public_and_private_bytes()
    {
        $keypair = new Keypair(
            privateKey: hex2bin('ef891d35d54780ab2f0a55e36ad04b8b366ff184ef5d2c74b31e85dd92789974'),
            publicKey: hex2bin('6a6d41c73943538086697978d040780d61c5ce6b34b1702ae104287633839dee')
        );

        $this->assertEquals(
            'SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6',
            $keypair->getSeed()
        );
        $this->assertEquals(
            'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ',
            $keypair->getAddress()
        );
        $this->assertNotEmpty($keypair->getPublicKey());
        $this->assertNotEmpty($keypair->getPrivateKey());
        $this->assertTrue($keypair->canSign());
    }

    /**
     * @test
     * @covers ::sign
     */
    public function it_can_sign_a_message()
    {
        $keypair = Keypair::fromSeed('SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6');
        $signature = $keypair->sign('abcd');

        $this->assertInstanceOf(Signature::class, $signature);
        $this->assertTrue($keypair->verify('abcd', $signature->getRaw()));
    }

    /**
     * @test
     * @covers ::sign
     */
    public function it_cannot_sign_a_message_without_private_bytes()
    {
        $this->expectException(InvalidKeyException::class);
        $keypair = Keypair::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $keypair->sign('abcd');
    }

    /**
     * @test
     * @covers ::signDecorated
     */
    public function it_can_sign_a_message_and_return_a_decorated_signature()
    {
        $keypair = Keypair::fromSeed('SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6');
        $decorated = $keypair->signDecorated('abcd');

        $this->assertInstanceOf(DecoratedSignature::class, $decorated);
        $this->assertTrue($keypair->verify('abcd', $decorated->getSignature()));
    }

    /**
     * @test
     * @covers ::sign
     * @see https://github.com/stellar/js-stellar-base/blob/8df813ff2364ca5c95803db5ea9594d9768278c1/test/unit/signing_test.js
     */
    public function it_can_sign_correctly()
    {
        $keypair = Keypair::fromSeed('SAISG5AFELYRX7XWWNTR6UPBLHGPLCOM7CLFEYW5L6L5C4Q5HA65JSNU');
        $expected = hex2bin('587d4b472eeef7d07aafcd0b049640b0bb3f39784118c2e2b73a04fa2f64c9c538b4b2d0f5335e968a480021fdc23e98c0ddf424cb15d8131df8cb6c4bb58309');
        $data = 'hello world';

        $this->assertEquals($expected, $keypair->sign($data)->getRaw());
    }

    /**
     * @test
     * @covers ::verify
     * @see https://github.com/stellar/js-stellar-base/blob/8df813ff2364ca5c95803db5ea9594d9768278c1/test/unit/signing_test.js
     */
    public function it_can_verify_string_signatures_correctly()
    {
        $privateKey = hex2bin('1123740522f11bfef6b3671f51e159ccf589ccf8965262dd5f97d1721d383dd4');
        $keypair = new Keypair(privateKey: $privateKey);
        $validSignature = hex2bin('587d4b472eeef7d07aafcd0b049640b0bb3f39784118c2e2b73a04fa2f64c9c538b4b2d0f5335e968a480021fdc23e98c0ddf424cb15d8131df8cb6c4bb58309');
        $invalidSignature = hex2bin('687d4b472eeef7d07aafcd0b049640b0bb3f39784118c2e2b73a04fa2f64c9c538b4b2d0f5335e968a480021fdc23e98c0ddf424cb15d8131df8cb6c4bb58309');

        $this->assertTrue($keypair->verify('hello world', $validSignature));
        $this->assertFalse($keypair->verify('hello world', $invalidSignature));
    }

    /**
     * @test
     * @covers ::verify
     * @see https://github.com/stellar/js-stellar-base/blob/8df813ff2364ca5c95803db5ea9594d9768278c1/test/unit/signing_test.js
     */
    public function it_can_verify_signature_objects_correctly()
    {
        $privateKey = hex2bin('1123740522f11bfef6b3671f51e159ccf589ccf8965262dd5f97d1721d383dd4');
        $keypair = new Keypair(privateKey: $privateKey);
        $validSignature = Signature::of(hex2bin('587d4b472eeef7d07aafcd0b049640b0bb3f39784118c2e2b73a04fa2f64c9c538b4b2d0f5335e968a480021fdc23e98c0ddf424cb15d8131df8cb6c4bb58309'));
        $invalidSignature = Signature::of(hex2bin('687d4b472eeef7d07aafcd0b049640b0bb3f39784118c2e2b73a04fa2f64c9c538b4b2d0f5335e968a480021fdc23e98c0ddf424cb15d8131df8cb6c4bb58309'));

        $this->assertTrue($keypair->verify('hello world', $validSignature));
        $this->assertFalse($keypair->verify('hello world', $invalidSignature));
    }

    /**
     * @test
     * @covers ::verify
     */
    public function it_will_not_verify_signatures_without_a_private_key()
    {
        $this->expectException(InvalidKeyException::class);
        $keypair = Keypair::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $keypair->verify('hello world', new Signature());
    }

    /**
     * @test
     * @covers ::fromAddress
     */
    public function it_can_be_instantiated_from_an_address()
    {
        $keypair = Keypair::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');

        $this->assertEquals(
            'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            $keypair->getAddress()
        );
        $this->assertNotEmpty($keypair->getPublicKey());
        $this->assertEmpty($keypair->getSeed());
        $this->assertEmpty($keypair->getPrivateKey());
        $this->assertFalse($keypair->canSign());
    }

    /**
     * @test
     * @covers ::fromAddress
     */
    public function it_can_be_instantiated_from_an_addressable()
    {
        $accountId = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $keypair = Keypair::fromAddress($accountId);

        $this->assertEquals(
            'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            $keypair->getAddress()
        );
        $this->assertNotEmpty($keypair->getPublicKey());
        $this->assertEmpty($keypair->getSeed());
        $this->assertEmpty($keypair->getPrivateKey());
        $this->assertFalse($keypair->canSign());
    }

    /**
     * @test
     * @covers ::fromSeed
     */
    public function it_can_be_instantiated_from_a_seed()
    {
        $keypair = Keypair::fromSeed('SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6');

        $this->assertEquals(
            'SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6',
            $keypair->getSeed()
        );
        $this->assertEquals(
            'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ',
            $keypair->getAddress()
        );
        $this->assertNotEmpty($keypair->getPublicKey());
        $this->assertNotEmpty($keypair->getPrivateKey());
        $this->assertTrue($keypair->canSign());
    }

    /**
     * @test
     * @covers ::decorate
     */
    public function it_can_decorate_a_signature()
    {
        $keypair = Keypair::fromSeed('SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6');
        $decorated = $keypair->decorate(new Signature());

        $this->assertInstanceOf(DecoratedSignature::class, $decorated);
    }

    /**
     * @test
     * @covers ::getSignatureHint
     */
    public function it_returns_a_signature_hint()
    {
        // Address: GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ
        $keypair = Keypair::fromSeed('SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6');
        $hint = $keypair->getSignatureHint();

        $this->assertInstanceOf(SignatureHint::class, $hint);
        $this->assertEquals(hex2bin('959c5503'), $hint->getValue());
    }

    /**
     * @test
     * @covers ::getAccountId
     */
    public function it_returns_an_account_id()
    {
        $keypair = new Keypair(
            address: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            seed: 'SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6'
        );

        $this->assertInstanceOf(AccountId::class, $keypair->getAccountId());
    }

    /**
     * @test
     * @covers ::getMuxedAccount
     */
    public function it_returns_a_muxed_account()
    {
        $keypair = new Keypair(
            address: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            seed: 'SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6'
        );

        $this->assertInstanceOf(MuxedAccount::class, $keypair->getMuxedAccount());
    }

    /**
     * @test
     * @covers ::getWeightedSigner
     */
    public function it_returns_a_weighted_signer()
    {
        $keypair = new Keypair(
            address: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            seed: 'SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6'
        );

        $this->assertInstanceOf(Signer::class, $keypair->getWeightedSigner(10));
        $this->assertEquals(10, $keypair->getWeightedSigner(10)->getWeight()->toNativeInt());
    }

    /**
     * @test
     * @covers ::canSign
     */
    public function it_knows_whether_or_not_it_can_sign()
    {
        $yes = new Keypair(
            address: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            seed: 'SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6'
        );

        $no = new Keypair(
            address: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
        );

        $this->assertTrue($yes->canSign());
        $this->assertFalse($no->canSign());
    }

    /**
     * @test
     * @covers ::getSeed
     */
    public function it_returns_the_seed()
    {
        $keypair = new Keypair(
            address: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            seed: 'SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6'
        );

        $this->assertEquals(
            'SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6',
            $keypair->getSeed()
        );
    }

    /**
     * @test
     * @covers ::getAddress
     */
    public function it_returns_the_address()
    {
        $keypair = new Keypair(
            address: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            seed: 'SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6'
        );

        $this->assertEquals(
            'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            $keypair->getAddress()
        );
    }

    /**
     * @test
     * @covers ::getPublicKey
     */
    public function it_returns_the_public_bytes()
    {
        $keypair = new Keypair(
            address: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            seed: 'SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6'
        );

        $this->assertEquals(
            hex2bin('6a6d41c73943538086697978d040780d61c5ce6b34b1702ae104287633839dee'),
            $keypair->getPublicKey()
        );
    }

    /**
     * @test
     * @covers ::getPrivateKey
     */
    public function it_returns_the_private_bytes()
    {
        $keypair = new Keypair(
            address: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            seed: 'SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6'
        );

        $this->assertEquals(
            hex2bin('ef891d35d54780ab2f0a55e36ad04b8b366ff184ef5d2c74b31e85dd92789974'),
            $keypair->getPrivateKey()
        );
    }

    /**
     * @test
     * @covers ::getRaw
     */
    public function it_returns_the_keypair_as_raw_bytes()
    {
        $keypair = Keypair::fromSeed('SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6');

        $this->assertEquals(
            hex2bin('ef891d35d54780ab2f0a55e36ad04b8b366ff184ef5d2c74b31e85dd92789974aa06af492c86721f1f438836859480038b69da71ff3f044c78a2e48c959c5503aa06af492c86721f1f438836859480038b69da71ff3f044c78a2e48c959c5503'),
            $keypair->getRaw()
        );
    }

    /**
     * @test
     * @covers ::getRaw
     */
    public function it_will_not_return_raw_bytes_without_a_private_key()
    {
        $this->expectException(InvalidKeyException::class);
        $keypair = Keypair::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $keypair->getRaw();
    }
}

<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Keypair;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Exception\InvalidKeyException;
use StageRightLabs\Bloom\Keypair\StringKey;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Keypair\StringKey
 */
class StringKeyTest extends TestCase
{
    /**
     * @test
     * @covers ::decode
     */
    public function it_catches_base32_decoding_errors()
    {
        $decoded = StringKey::decodeAddress('THIS_IS_AN_INVALID_BASE_32_STRING');
        $this->assertFalse($decoded['valid']);
    }

    /**
     * @test
     * @covers ::decodeOrFail
     */
    public function it_can_return_a_decoded_payload_directly()
    {
        $key = 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS';
        $decoded = StringKey::decodeOrFail($key, StringKey::TYPE_ADDRESS);
        $this->assertEquals('55c01f8747ddbc86a6d0cb8d8a270754bad96749c17bde6812a079dfdea97a52', bin2hex($decoded));
    }

    /**
     * @test
     * @covers ::decodeOrFail
     */
    public function decoding_an_invalid_key_directly_throws_an_exception()
    {
        $this->expectException(InvalidKeyException::class);
        StringKey::decodeOrFail('GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VFFOOBAR');
    }

    /**
     * @test
     * @covers ::isValid
     */
    public function it_performs_a_boolean_validity_check()
    {
        $key = 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS';
        $this->assertTrue(StringKey::isValid($key, 'address'));
        $key = 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VFFOOBAR';
        $this->assertFalse(StringKey::isValid($key, 'address'));
    }

    /**
     * @test
     * @covers ::checkValidity
     */
    public function it_will_not_validate_unknown_key_types()
    {
        $decoded = StringKey::checkValidity('AAAAAAAAAAAAA', 'INVALID');
        $this->assertFalse($decoded['valid']);
    }

    /**
     * @test
     * @covers ::encodeSeed
     * @covers ::encode
     */
    public function it_encodes_seeds()
    {
        $bytes = hex2bin('ef891d35d54780ab2f0a55e36ad04b8b366ff184ef5d2c74b31e85dd92789974');
        $seed = StringKey::encodeSeed($bytes);

        $this->assertEquals('SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6', $seed);
    }

    /**
     * @test
     * @covers ::decodeSeed
     * @covers ::decode
     */
    public function it_decodes_seeds()
    {
        $seed = 'SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6';
        $decoded = StringKey::decodeSeed($seed);

        $this->assertEquals('ef891d35d54780ab2f0a55e36ad04b8b366ff184ef5d2c74b31e85dd92789974', bin2hex($decoded['content']));
        $this->assertTrue($decoded['valid']);
    }

    /**
     * @test
     * @covers ::isValidSeed
     * @covers ::checkValidity
     */
    public function it_validates_seeds()
    {
        $seed = 'SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6';
        $this->assertTrue(StringKey::isValidSeed($seed));

        $seed = 'SAB2R6B2TSBWNEUTYFD4XPAXNUO3ZSJ6GASOHRMN7GP4WKJTGPFOOBAR';
        $this->assertFalse(StringKey::isValidSeed($seed));
    }

    /**
     * @test
     * @covers ::encodeAddress
     * @covers ::encode
     */
    public function it_encodes_addresses()
    {
        $bytes = hex2bin('55c01f8747ddbc86a6d0cb8d8a270754bad96749c17bde6812a079dfdea97a52');
        $key = StringKey::encodeAddress($bytes);

        $this->assertEquals('GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS', $key);
    }

    /**
     * @test
     * @covers ::decodeAddress
     * @covers ::decode
     */
    public function it_decodes_addresses()
    {
        $key = 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS';
        $decoded = StringKey::decodeAddress($key);

        $this->assertEquals('55c01f8747ddbc86a6d0cb8d8a270754bad96749c17bde6812a079dfdea97a52', bin2hex($decoded['content']));
        $this->assertTrue($decoded['valid']);
    }

    /**
     * @test
     * @covers ::isValidAddress
     * @covers ::checkValidity
     */
    public function it_validates_addresses()
    {
        $key = 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS';
        $this->assertTrue(StringKey::isValidAddress($key));

        $key = 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VFFOOBAR';
        $this->assertFalse(StringKey::isValidAddress($key));


        $key = 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLTOOSHORT';
        $this->assertFalse(StringKey::isValidAddress($key));
    }

    /**
     * @test
     * @covers ::constructMuxedAddress
     * @covers ::encodeMuxedAddress
     */
    public function it_constructs_a_muxed_address_from_a_regular_address()
    {
        $key = StringKey::constructMuxedAddress('GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS', 1);
        $this->assertEquals('MBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FEAAAAAAAAAAAAGPQ4', $key);
    }

    /**
     * @test
     * @covers ::constructMuxedAddress
     * @covers ::encodeMuxedAddress
     */
    public function it_constructs_muxed_addresses_from_bytes()
    {
        $bytes = hex2bin('ac6e7da84a3aa4ea6808be66ac54053eb9c7718a6cd37e583523dcbc8650ee8d');
        $key = StringKey::constructMuxedAddress($bytes, 1);

        $this->assertEquals('MCWG47NIJI5KJ2TIBC7GNLCUAU7LTR3RRJWNG7SYGUR5ZPEGKDXI2AAAAAAAAAAAAGAYM', $key);
    }

    /**
     * @test
     * @covers ::constructMuxedAddress
     * @covers ::encodeMuxedAddress
     */
    public function it_will_not_construct_a_muxed_address_from_a_muxed_address()
    {
        $this->expectException(InvalidKeyException::class);
        StringKey::constructMuxedAddress('MCWG47NIJI5KJ2TIBC7GNLCUAU7LTR3RRJWNG7SYGUR5ZPEGKDXI2AAAAAAAAAAAAGAYM', 1);
    }

    /**
     * @test
     * @covers ::constructMuxedAddress
     * @covers ::encodeMuxedAddress
     */
    public function it_will_not_construct_a_muxed_address_from_an_invalid_regular_address()
    {
        $this->expectException(InvalidKeyException::class);
        StringKey::constructMuxedAddress('GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VFFOOBAR', 1);
    }

    /**
     * @test
     * @covers ::deconstructMuxedAddress
     * @covers ::decodeMuxedAddress
     */
    public function it_deconstructs_muxed_addresses()
    {
        $key = 'MCWG47NIJI5KJ2TIBC7GNLCUAU7LTR3RRJWNG7SYGUR5ZPEGKDXI2AAAAAAAAAAAAGAYM';
        $decoded = StringKey::deconstructMuxedAddress($key);

        $this->assertEquals('ac6e7da84a3aa4ea6808be66ac54053eb9c7718a6cd37e583523dcbc8650ee8d', bin2hex($decoded['ed25519']));
        $this->assertTrue($decoded['id']->isEqualTo(1));
    }

    /**
     * @test
     * @covers ::deconstructMuxedAddress
     * @covers ::decodeMuxedAddress
     */
    public function it_will_not_decode_invalid_muxed_addresses()
    {
        $this->expectException(InvalidKeyException::class);
        StringKey::deconstructMuxedAddress('INVALID_MUXED_ADDRESS');
    }

    /**
     * @test
     * @covers ::isValidMuxedAddress
     * @covers ::checkValidity
     */
    public function it_validates_muxed_addresses()
    {
        $key = 'MCWG47NIJI5KJ2TIBC7GNLCUAU7LTR3RRJWNG7SYGUR5ZPEGKDXI2AAAAAAAAAAAAGAYM';
        $this->assertTrue(StringKey::isValidMuxedAddress($key));

        $key = 'MCWG47NIJI5KJ2TIBC7GNLCUAU7LTR3RRJWNG7SYGUR5ZPEGKDXI2AAAAAAAAAAFOOBAR';
        $this->assertFalse(StringKey::isValidMuxedAddress($key));


        $key = 'MCWG47NIJI5KJ2TIBC7GNLCUAU7LTR3RRJWNG7SYGUR5TOOSHORT';
        $this->assertFalse(StringKey::isValidMuxedAddress($key));
    }

    /**
     * @test
     * @covers ::encodePreAuthTx
     * @covers ::encode
     */
    public function it_encodes_pre_auth_txs()
    {
        $bytes = hex2bin('4727c4218e77ca6ae14492f58f48a2b7cdb5dc0e3a781e3295e0cc751fbf1491');
        $key = StringKey::encodePreAuthTx($bytes);

        $this->assertEquals('TBDSPRBBRZ34U2XBISJPLD2IUK343NO4BY5HQHRSSXQMY5I7X4KJCBJJ', $key);
    }

    /**
     * @test
     * @covers ::decodePreAuthTx
     * @covers ::decode
     */
    public function it_decodes_pre_auth_txs()
    {
        $key = 'TBDSPRBBRZ34U2XBISJPLD2IUK343NO4BY5HQHRSSXQMY5I7X4KJCBJJ';
        $decoded = StringKey::decodePreAuthTx($key);

        $this->assertEquals('4727c4218e77ca6ae14492f58f48a2b7cdb5dc0e3a781e3295e0cc751fbf1491', bin2hex($decoded['content']));
        $this->assertTrue($decoded['valid']);
    }

    /**
     * @test
     * @covers ::isValidPreAuthTx
     * @covers ::checkValidity
     */
    public function it_validates_pre_auth_txs()
    {
        $key = 'TBDSPRBBRZ34U2XBISJPLD2IUK343NO4BY5HQHRSSXQMY5I7X4KJCBJJ';
        $this->assertTrue(StringKey::isValidPreAuthTx($key));

        $key = 'TBDSPRBBRZ34U2XBISJPLD2IUK343NO4BY5HQHRSSXQMY5I7X4FOOBAR';
        $this->assertFalse(StringKey::isValidPreAuthTx($key));
    }

    /**
     * @test
     * @covers ::encodeSha256Hash
     * @covers ::encode
     */
    public function it_encodes_sha256_hashes()
    {
        $bytes = hex2bin('6718ae2bae6b6b0a99e76efe8b5f9fdbedd348961b7c3ae05786cd04e3ae2b8c');
        $key = StringKey::encodeSha256Hash($bytes);

        $this->assertEquals('XBTRRLRLVZVWWCUZ45XP5C27T7N63U2ISYNXYOXAK6DM2BHDVYVYYNIT', $key);
    }

    /**
     * @test
     * @covers ::decodeSha256Hash
     * @covers ::decode
     */
    public function it_decodes_sha256_hashes()
    {
        $key = 'XBTRRLRLVZVWWCUZ45XP5C27T7N63U2ISYNXYOXAK6DM2BHDVYVYYNIT';
        $decoded = StringKey::decodeSha256Hash($key);

        $this->assertEquals('6718ae2bae6b6b0a99e76efe8b5f9fdbedd348961b7c3ae05786cd04e3ae2b8c', bin2hex($decoded['content']));
        $this->assertTrue($decoded['valid']);
    }

    /**
     * @test
     * @covers ::isValidSha256Hash
     * @covers ::checkValidity
     */
    public function it_validates_sha256_hashes()
    {
        $key = 'XBTRRLRLVZVWWCUZ45XP5C27T7N63U2ISYNXYOXAK6DM2BHDVYVYYNIT';
        $this->assertTrue(StringKey::isValidSha256Hash($key));

        $key = 'XBTRRLRLVZVWWCUZ45XP5C27T7N63U2ISYNXYOXAK6DM2BHDVYFOOBAR';
        $this->assertFalse(StringKey::isValidPreAuthTx($key));
    }

    /**
     * @test
     * @covers ::encodeSignedPayload
     */
    public function it_encodes_signed_payloads()
    {
        // GA7QYNF7SOWQ3GLR2BGMZEHXAVIRZA4KVWLTJJFC7MGXUA74P7UJVSGZ
        $address = hex2bin('3f0c34bf93ad0d9971d04ccc90f705511c838aad9734a4a2fb0d7a03fc7fe89a');
        // The length of the payload
        $length = pack('N', 29);
        // The content of the payload
        $payload = hex2bin('0102030405060708090a0b0c0d0e0f101112131415161718191a1b1c1d');
        // Padding to bring the total payload length up to a multiple of four
        $pad = hex2bin('000000');

        $this->assertEquals(
            'PA7QYNF7SOWQ3GLR2BGMZEHXAVIRZA4KVWLTJJFC7MGXUA74P7UJUAAAAAOQCAQDAQCQMBYIBEFAWDANBYHRAEISCMKBKFQXDAMRUGY4DUAAAAFGBU',
            StringKey::encodeSignedPayload($address . $length . $payload . $pad)
        );
    }

    /**
     * @test
     * @covers ::decodeSignedPayload
     */
    public function it_decodes_signed_payloads()
    {
        // GA7QYNF7SOWQ3GLR2BGMZEHXAVIRZA4KVWLTJJFC7MGXUA74P7UJVSGZ
        $address = hex2bin('3f0c34bf93ad0d9971d04ccc90f705511c838aad9734a4a2fb0d7a03fc7fe89a');
        // The length of the payload
        $length = pack('N', 29);
        // The content of the payload
        $payload = hex2bin('0102030405060708090a0b0c0d0e0f101112131415161718191a1b1c1d');
        // Padding to bring the total payload length up to a multiple of four
        $pad = hex2bin('000000');

        $decoded = StringKey::decodeSignedPayload('PA7QYNF7SOWQ3GLR2BGMZEHXAVIRZA4KVWLTJJFC7MGXUA74P7UJUAAAAAOQCAQDAQCQMBYIBEFAWDANBYHRAEISCMKBKFQXDAMRUGY4DUAAAAFGBU');

        $this->assertEquals(
            $address . $length . $payload . $pad,
            $decoded['content']
        );
    }

    /**
     * @test
     * @covers ::isValidSignedPayload
     * @covers ::checkValidity
     */
    public function it_validates_signed_payloads()
    {
        $key = 'PA7QYNF7SOWQ3GLR2BGMZEHXAVIRZA4KVWLTJJFC7MGXUA74P7UJUAAAAAOQCAQDAQCQMBYIBEFAWDANBYHRAEISCMKBKFQXDAMRUGY4DUAAAAFGBU';
        $this->assertTrue(StringKey::isValidSignedPayload($key));

        $key = 'PA7QYNF7SOWQ3GLR2BGMZEHXAVIRZA4KVWLTJJFC7MGXUA74P7UJUAAAAAOQCAQDAQCQMBYIBEFAWDANBYHRAEISCMKBKFQXDAMRUGY4Z2PQ';
        $this->assertFalse(StringKey::isValidSignedPayload($key));

        $key = 'PA7QYNF7SOWQ3GLR2BGMZEHXAVIRZA4KVWLTJJFTOOSHORT';
        $this->assertFalse(StringKey::isValidSignedPayload($key));
    }

    /**
     * @test
     * @covers ::addressFromSeed
     */
    public function test_it_gets_an_address_from_a_secret_seed()
    {
        $address = StringKey::addressFromSeed('SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6');
        $this->assertEquals('GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ', $address);
    }

    /**
     * @test
     * @covers ::addressFromSeed
     */
    public function it_cannot_get_an_address_from_an_invalid_seed()
    {
        $this->expectException(InvalidKeyException::class);
        StringKey::addressFromSeed('SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCFOOBAR');
    }

    /**
     * @test
     * @covers ::getPrefix
     */
    public function it_returns_byte_prefixes()
    {
        $addressPrefix = StringKey::getPrefix('address');
        $this->assertEquals(6 << 3, $addressPrefix);
    }

    /**
     * @test
     * @covers ::getPrefix
     */
    public function it_throws_an_exception_when_attempting_to_retrieve_an_unknown_prefix()
    {
        $this->expectException(InvalidArgumentException::class);
        StringKey::getPrefix('invalid');
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_can_determine_a_key_type()
    {
        $this->assertEquals(
            StringKey::TYPE_ADDRESS,
            StringKey::getType('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW')
        );
        $this->assertEquals(
            StringKey::TYPE_SEED,
            StringKey::getType('SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6')
        );
        $this->assertEquals(
            StringKey::TYPE_MUXED_ADDRESS,
            StringKey::getType('MCWG47NIJI5KJ2TIBC7GNLCUAU7LTR3RRJWNG7SYGUR5ZPEGKDXI3HYG')
        );
        $this->assertEquals(
            StringKey::TYPE_PRE_AUTH_TX,
            StringKey::getType('TBDSPRBBRZ34U2XBISJPLD2IUK343NO4BY5HQHRSSXQMY5I7X4KJCBJJ')
        );
        $this->assertEquals(
            StringKey::TYPE_SHA_256_HASH,
            StringKey::getType('XBTRRLRLVZVWWCUZ45XP5C27T7N63U2ISYNXYOXAK6DM2BHDVYVYYNIT')
        );
        $this->assertEquals(
            StringKey::TYPE_SIGNED_PAYLOAD,
            StringKey::getType('PA7QYNF7SOWQ3GLR2BGMZEHXAVIRZA4KVWLTJJFC7MGXUA74P7UJUAAAAAOQCAQDAQCQMBYIBEFAWDANBYHRAEISCMKBKFQXDAMRUGY4DUAAAAFGBU')
        );
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_cannot_determine_a_key_type_that_is_unknown()
    {
        $this->expectException(InvalidKeyException::class);
        StringKey::getType('UNKNOWNLVZVWWCUZ45XP5C27T7N63U2ISYNXYOXAK6DM2BHDVYVYYNIT');
    }

    /**
     * @test
     * @covers ::isValidSeed
     * @covers ::isValidAddress
     * @covers ::isValidMuxedAddress
     * @covers ::isValidPreAuthTx
     * @covers ::isValidSha256Hash
     */
    public function it_passes_sep23_validation_checks()
    {
        $this->assertTrue(StringKey::isValidAddress('GA7QYNF7SOWQ3GLR2BGMZEHXAVIRZA4KVWLTJJFC7MGXUA74P7UJVSGZ'));
        $this->assertTrue(StringKey::isValidMuxedAddress('MA7QYNF7SOWQ3GLR2BGMZEHXAVIRZA4KVWLTJJFC7MGXUA74P7UJUAAAAAAAAAAAACJUQ'));
        $this->assertTrue(StringKey::isValidMuxedAddress('MA7QYNF7SOWQ3GLR2BGMZEHXAVIRZA4KVWLTJJFC7MGXUA74P7UJVAAAAAAAAAAAAAJLK'));
        $this->assertTrue(StringKey::isValidSignedPayload('PA7QYNF7SOWQ3GLR2BGMZEHXAVIRZA4KVWLTJJFC7MGXUA74P7UJUAAAAAOQCAQDAQCQMBYIBEFAWDANBYHRAEISCMKBKFQXDAMRUGY4DUAAAAFGBU'));

        $this->assertFalse(StringKey::isValidAddress('GAAAAAAAACGC6'));
        $this->assertFalse(StringKey::isValidMuxedAddress('MA7QYNF7SOWQ3GLR2BGMZEHXAVIRZA4KVWLTJJFC7MGXUA74P7UJUAAAAAAAAAAAACJUR'));
        $this->assertFalse(StringKey::isValidAddress('GA7QYNF7SOWQ3GLR2BGMZEHXAVIRZA4KVWLTJJFC7MGXUA74P7UJVSGZA'));
        $this->assertFalse(StringKey::isValidAddress('GA7QYNF7SOWQ3GLR2BGMZEHXAVIRZA4KVWLTJJFC7MGXUA74P7UJUACUSI'));
        $this->assertFalse(StringKey::isValidAddress('G47QYNF7SOWQ3GLR2BGMZEHXAVIRZA4KVWLTJJFC7MGXUA74P7UJVP2I'));
        $this->assertFalse(StringKey::isValidMuxedAddress('MA7QYNF7SOWQ3GLR2BGMZEHXAVIRZA4KVWLTJJFC7MGXUA74P7UJVAAAAAAAAAAAAAJLKA'));
        $this->assertFalse(StringKey::isValidMuxedAddress('MA7QYNF7SOWQ3GLR2BGMZEHXAVIRZA4KVWLTJJFC7MGXUA74P7UJVAAAAAAAAAAAAAAV75I'));
        $this->assertFalse(StringKey::isValidMuxedAddress('M47QYNF7SOWQ3GLR2BGMZEHXAVIRZA4KVWLTJJFC7MGXUA74P7UJUAAAAAAAAAAAACJUQ'));
        $this->assertFalse(StringKey::isValidMuxedAddress('MA7QYNF7SOWQ3GLR2BGMZEHXAVIRZA4KVWLTJJFC7MGXUA74P7UJUAAAAAAAAAAAACJUK==='));
        $this->assertFalse(StringKey::isValidMuxedAddress('MA7QYNF7SOWQ3GLR2BGMZEHXAVIRZA4KVWLTJJFC7MGXUA74P7UJUAAAAAAAAAAAACJUO'));
        $this->assertFalse(StringKey::isValidSignedPayload('PA7QYNF7SOWQ3GLR2BGMZEHXAVIRZA4KVWLTJJFC7MGXUA74P7UJUAAAAAQACAQDAQCQMBYIBEFAWDANBYHRAEISCMKBKFQXDAMRUGY4DUPB6IAAAAAAAAPM'));
        $this->assertFalse(StringKey::isValidSignedPayload('PA7QYNF7SOWQ3GLR2BGMZEHXAVIRZA4KVWLTJJFC7MGXUA74P7UJUAAAAAOQCAQDAQCQMBYIBEFAWDANBYHRAEISCMKBKFQXDAMRUGY4Z2PQ'));
        $this->assertFalse(StringKey::isValidSignedPayload('PA7QYNF7SOWQ3GLR2BGMZEHXAVIRZA4KVWLTJJFC7MGXUA74P7UJUAAAAAOQCAQDAQCQMBYIBEFAWDANBYHRAEISCMKBKFQXDAMRUGY4DXFH6'));
    }
}

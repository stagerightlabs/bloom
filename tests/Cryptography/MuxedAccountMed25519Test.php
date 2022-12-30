<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Account;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\MuxedAccount;
use StageRightLabs\Bloom\Account\Signer;
use StageRightLabs\Bloom\Cryptography\ED25519;
use StageRightLabs\Bloom\Cryptography\MuxedAccountMed25519;
use StageRightLabs\Bloom\Cryptography\SignerKey;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Cryptography\MuxedAccountMed25519
 */
class MuxedAccountMed25519Test extends TestCase
{
    /**
     * @test
     * @covers ::fromMuxedAddress
     */
    public function it_can_be_instantiated_from_a_muxed_address()
    {
        $med25519 = MuxedAccountMed25519::fromMuxedAddress('MBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FEAAAAAAAAAAAAGPQ4');
        $this->assertInstanceOf(MuxedAccountMed25519::class, $med25519);
    }

    /**
     * @test
     * @covers ::fromMuxedAddress
     */
    public function it_can_be_instantiated_from_an_addressable_object()
    {
        $account = new SimpleMuxedAddressable('MBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FEAAAAAAAAAAAAGPQ4');
        $med25519 = MuxedAccountMed25519::fromMuxedAddress($account);
        $this->assertInstanceOf(MuxedAccountMed25519::class, $med25519);
    }

    /**
     * @test
     * @covers ::fromMuxedAddress
     */
    public function it_rejects_invalid_addresses()
    {
        $this->expectException(InvalidArgumentException::class);
        MuxedAccountMed25519::fromMuxedAddress('MBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VFINVALID');
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $med25519 = MuxedAccountMed25519::fromMuxedAddress('MBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FEAAAAAAAAAAAAGPQ4');
        $buffer = XDR::fresh()->write($med25519)->toBase64();
        $this->assertEquals('AAAAAAAAAAFVwB+HR928hqbQy42KJwdUutlnScF73mgSoHnf3ql6Ug==', $buffer);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $muxedAccountMed25519 = (new MuxedAccountMed25519())
            ->withEd25519(ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));
        XDR::fresh()->write($muxedAccountMed25519);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_ed25519_string_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $muxedAccountMed25519 = (new MuxedAccountMed25519())
            ->withId(UInt64::of(1));
        XDR::fresh()->write($muxedAccountMed25519);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $med25519 = XDR::fromBase64('AAAAAAAAAAFVwB+HR928hqbQy42KJwdUutlnScF73mgSoHnf3ql6Ug==')
            ->read(MuxedAccountMed25519::class);

        $this->assertInstanceOf(MuxedAccountMed25519::class, $med25519);
        $this->assertTrue($med25519->getId()->isEqualTo(1));
    }

    /**
     * @test
     * @covers ::getAddress
     */
    public function it_returns_the_address_string()
    {
        $med25519 = MuxedAccountMed25519::fromMuxedAddress('MBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FEAAAAAAAAAAAAGPQ4');
        $this->assertEquals(
            'MBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FEAAAAAAAAAAAAGPQ4',
            $med25519->getAddress()
        );
    }

    /**
     * @test
     * @covers ::withEd25519
     */
    public function it_accepts_an_ed25519_instance()
    {
        $first = MuxedAccountMed25519::fromMuxedAddress('MBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FEAAAAAAAAAAAAGPQ4');
        $ed25519 = ED25519::fromAddress('GAAYP5PGGRZLM5OQIPI4LJNENVJY4FFMKZW2U5IEKPAHS362B6G2XTG4');
        $second = $first->withEd25519($ed25519);

        $this->assertInstanceOf(MuxedAccountMed25519::class, $second);
        $this->assertNotEquals($first->getAddress(), $second->getAddress());
    }

    /**
     * @test
     * @covers ::getEd25519
     */
    public function it_returns_the_ed25519_value()
    {
        $med25519 = MuxedAccountMed25519::fromMuxedAddress('MBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FEAAAAAAAAAAAAGPQ4');
        $this->assertInstanceOf(ED25519::class, $med25519->getEd25519());
    }

    /**
     * @test
     * @covers ::withId
     */
    public function it_accepts_an_id()
    {
        $first = MuxedAccountMed25519::fromMuxedAddress('MBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FEAAAAAAAAAAAAGPQ4');
        $id = UInt64::of(2);
        $second = $first->withId($id);

        $this->assertInstanceOf(MuxedAccountMed25519::class, $second);
        $this->assertNotEquals($first->getAddress(), $second->getAddress());
    }

    /**
     * @test
     * @covers ::getId
     */
    public function it_returns_the_id()
    {
        $med25519 = MuxedAccountMed25519::fromMuxedAddress('MBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FEAAAAAAAAAAAAGPQ4');
        $this->assertInstanceOf(UInt64::class, $med25519->getId());
        $this->assertTrue($med25519->getId()->isEqualTo(1));
    }
}

class SimpleMuxedAddressable implements Addressable
{
    public function __construct(public string $address)
    {
        $this->address = $address;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getAccountId(): AccountId
    {
        return AccountId::fromAddressable($this->keypair->getAddress());
    }

    public function getMuxedAccount(): MuxedAccount
    {
        return MuxedAccount::fromAddressable($this->address);
    }

    /**
     * Convert this addressable into a weighted signer for account management.
     *
     * @param UInt32|integer $weight
     * @return Signer
     */
    public function getWeightedSigner(UInt32|int $weight): Signer
    {
        $signerKey = SignerKey::wrapEd25519(
            ED25519::fromAddress($this->getAddress())
        );

        return Signer::of($signerKey, $weight);
    }
}

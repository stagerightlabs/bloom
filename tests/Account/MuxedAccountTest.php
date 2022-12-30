<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Account;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\MuxedAccount;
use StageRightLabs\Bloom\Account\Signer;
use StageRightLabs\Bloom\Cryptography\ED25519;
use StageRightLabs\Bloom\Cryptography\MuxedAccountMed25519;
use StageRightLabs\Bloom\Keypair\CryptoKeyType;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Account\MuxedAccount
 */
class MuxedAccountTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(CryptoKeyType::class, MuxedAccount::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            CryptoKeyType::KEY_TYPE_ED25519       => ED25519::class,
            CryptoKeyType::KEY_TYPE_MUXED_ED25519 => MuxedAccountMed25519::class,
        ];

        $this->assertEquals($expected, MuxedAccount::arms());
    }

    /**
     * @test
     * @covers ::fromAddressable
     */
    public function it_can_be_instantiated_from_an_regular_address_string()
    {
        $muxedAccount = MuxedAccount::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $this->assertInstanceOf(MuxedAccount::class, $muxedAccount);
    }

    /**
     * @test
     * @covers ::fromAddressable
     */
    public function it_can_be_instantiated_from_a_muxed_address_string()
    {
        $muxedAccount = MuxedAccount::fromAddressable('MBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FEAAAAAAAAAAAAGPQ4');
        $this->assertInstanceOf(MuxedAccount::class, $muxedAccount);
    }

    /**
     * @test
     * @covers ::fromAddressable
     */
    public function it_can_be_instantiated_from_an_addressable_object()
    {
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $muxedAccount = MuxedAccount::fromAddressable($account);
        $this->assertInstanceOf(MuxedAccount::class, $muxedAccount);
    }

    /**
     * @test
     * @covers ::wrapEd25519
     */
    public function it_can_be_instantiated_from_an_ed25519_address_string()
    {
        $muxedAccount = MuxedAccount::wrapEd25519('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $this->assertInstanceOf(MuxedAccount::class, $muxedAccount);
    }

    /**
     * @test
     * @covers ::wrapEd25519
     */
    public function it_can_be_instantiated_from_an_ed25519_object()
    {
        $ed25519 = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $muxedAccount = MuxedAccount::wrapEd25519($ed25519);
        $this->assertInstanceOf(MuxedAccount::class, $muxedAccount);
    }

    /**
     * @test
     * @covers ::wrapMuxedEd25519
     */
    public function it_can_be_instantiated_from_a_muxed_ed25519_address_string()
    {
        $muxedAccount = MuxedAccount::wrapMuxedEd25519('MBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FEAAAAAAAAAAAAGPQ4');
        $this->assertInstanceOf(MuxedAccount::class, $muxedAccount);
    }

    /**
     * @test
     * @covers ::wrapMuxedEd25519
     */
    public function it_can_be_instantiated_from_a_muxed_account_med25519_object()
    {
        $muxedAccountMed25519 = MuxedAccountMed25519::fromMuxedAddress('MBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FEAAAAAAAAAAAAGPQ4');
        $muxedAccount = MuxedAccount::wrapMuxedEd25519($muxedAccountMed25519);
        $this->assertInstanceOf(MuxedAccount::class, $muxedAccount);
    }

    /**
     * @test
     * @covers ::unwrap
     */
    public function it_can_be_unwrapped()
    {
        $muxedAccount = MuxedAccount::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $this->assertInstanceOf(ED25519::class, $muxedAccount->unwrap());
    }

    /**
     * @test
     * @covers ::getAddress
     */
    public function it_returns_an_address_string()
    {
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $muxedAccount = MuxedAccount::fromAddressable($account);
        $this->assertEquals(
            'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            $muxedAccount->getAddress()
        );
    }

    /**
     * @test
     * @covers ::getAddress
     */
    public function it_returns_an_empty_string_if_no_address_is_set()
    {
        $this->assertEmpty((new MuxedAccount())->getAddress());
    }

    /**
     * @test
     * @covers ::getAccountId
     */
    public function it_returns_the_address_as_an_account_id()
    {
        $muxedAccount = MuxedAccount::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');

        $this->assertInstanceOf(AccountId::class, $muxedAccount->getAccountId());
        $this->assertEquals(
            'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            $muxedAccount->getAccountId()->getAddress()
        );
    }

    /**
     * @test
     * @covers ::getMuxedAccount
     */
    public function it_returns_a_cloned_muxed_account()
    {
        $muxedAccountA = MuxedAccount::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $muxedAccountB = $muxedAccountA->getMuxedAccount();

        $this->assertNotEquals(spl_object_id($muxedAccountA), spl_object_id($muxedAccountB));
        $this->assertInstanceOf(MuxedAccount::class, $muxedAccountB);
        $this->assertEquals(
            'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            $muxedAccountB->getAddress()
        );
    }

    /**
     * @test
     * @covers ::getWeightedSigner
     */
    public function it_returns_a_weighted_signer()
    {
        $muxedAccount = MuxedAccount::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');

        $this->assertInstanceOf(Signer::class, $muxedAccount->getWeightedSigner(10));
        $this->assertEquals(10, $muxedAccount->getWeightedSigner(10)->getWeight()->toNativeInt());
    }
}

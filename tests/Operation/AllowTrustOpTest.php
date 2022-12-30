<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Asset\AssetCode;
use StageRightLabs\Bloom\Asset\AssetCode4;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Operation\AllowTrustOp;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\AllowTrustOp
 */
class AllowTrustOpTest extends TestCase
{
    /**
     * @test
     * @covers ::operation
     */
    public function it_can_create_an_operation()
    {
        $operation = AllowTrustOp::operation(
            trustor: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            assetCode: 'ABCD',
            authorize: AllowTrustOp::AUTHORIZED_FLAG,
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(AllowTrustOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::isReady
     */
    public function it_knows_when_it_is_ready()
    {
        $allowTrustOp = new AllowTrustOp();
        $this->assertFalse($allowTrustOp->isReady());

        $allowTrustOp = $allowTrustOp->withTrustor(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));
        $this->assertFalse($allowTrustOp->isReady());

        $allowTrustOp = $allowTrustOp->withAssetCode('ABCD');
        $this->assertFalse($allowTrustOp->isReady());

        $allowTrustOp = $allowTrustOp->withAuthorizationFlag(AllowTrustOp::AUTHORIZED_FLAG);
        $this->assertTrue($allowTrustOp->isReady());
    }

    /**
     * @test
     * @covers ::getThreshold
     */
    public function it_has_a_threshold_category()
    {
        $this->assertEquals(Thresholds::CATEGORY_LOW, (new AllowTrustOp())->getThreshold());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $allowTrustOp = (new AllowTrustOp())
            ->withTrustor(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withAssetCode(AssetCode::wrapAssetCode4(AssetCode4::of('ABCD')))
            ->withAuthorizationFlag(AllowTrustOp::AUTHORIZED_FLAG);
        $buffer = XDR::fresh()->write($allowTrustOp);

        $this->assertEquals('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAUFCQ0QAAAAB', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_trustor_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $allowTrustOp = (new AllowTrustOp())
            ->withAssetCode(AssetCode::wrapAssetCode4(AssetCode4::of('ABCD')))
            ->withAuthorizationFlag(AllowTrustOp::AUTHORIZED_FLAG);
        XDR::fresh()->write($allowTrustOp);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_asset_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $allowTrustOp = (new AllowTrustOp())
            ->withTrustor(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withAuthorizationFlag(AllowTrustOp::AUTHORIZED_FLAG);
        XDR::fresh()->write($allowTrustOp);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_authorization_flag_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $allowTrustOp = (new AllowTrustOp())
            ->withAssetCode(AssetCode::wrapAssetCode4(AssetCode4::of('ABCD')))
            ->withTrustor(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));
        XDR::fresh()->write($allowTrustOp);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $allowTrustOp = XDR::fromBase64('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAUFCQ0QAAAAB')
            ->read(AllowTrustOp::class);

        $this->assertInstanceOf(AllowTrustOp::class, $allowTrustOp);
        $this->assertInstanceOf(AccountId::class, $allowTrustOp->getTrustor());
        $this->assertInstanceOf(AssetCode::class, $allowTrustOp->getAssetCode());
        $this->assertInstanceOf(UInt32::class, $allowTrustOp->getAuthorizationFlag());
    }

    /**
     * @test
     * @covers ::withTrustor
     * @covers ::getTrustor
     */
    public function it_accepts_an_account_id_as_a_trustor()
    {
        $allowTrustOp = (new AllowTrustOp())
            ->withTrustor(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(AllowTrustOp::class, $allowTrustOp);
        $this->assertInstanceOf(AccountId::class, $allowTrustOp->getTrustor());
    }

    /**
     * @test
     * @covers ::withTrustor
     * @covers ::getTrustor
     */
    public function it_accepts_a_string_as_a_destination()
    {
        $allowTrustOp = (new AllowTrustOp())
            ->withTrustor('GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN');

        $this->assertInstanceOf(AllowTrustOp::class, $allowTrustOp);
        $this->assertInstanceOf(AccountId::class, $allowTrustOp->getTrustor());
        ;
    }

    /**
     * @test
     * @covers ::withTrustor
     * @covers ::getTrustor
     */
    public function it_accepts_an_addressable_as_a_trustor()
    {
        $account = Account::fromAddress('GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN');
        $allowTrustOp = (new AllowTrustOp())->withTrustor($account);

        $this->assertInstanceOf(AllowTrustOp::class, $allowTrustOp);
        $this->assertInstanceOf(AccountId::class, $allowTrustOp->getTrustor());
    }

    /**
     * @test
     * @covers ::withAssetCode
     * @covers ::getAssetCode
     */
    public function it_accepts_an_asset_code()
    {
        $allowTrustOp = (new AllowTrustOp())
            ->withAssetCode(AssetCode::wrapAssetCode4(AssetCode4::of('ABCD')));

        $this->assertInstanceOf(AllowTrustOp::class, $allowTrustOp);
        $this->assertInstanceOf(AssetCode::class, $allowTrustOp->getAssetCode());
    }

    /**
     * @test
     * @covers ::withAssetCode
     * @covers ::getAssetCode
     */
    public function it_accepts_an_asset_code_string()
    {
        $allowTrustOp = (new AllowTrustOp())
            ->withAssetCode('ABCD');

        $this->assertInstanceOf(AllowTrustOp::class, $allowTrustOp);
        $this->assertInstanceOf(AssetCode::class, $allowTrustOp->getAssetCode());
    }

    /**
     * @test
     * @covers ::withAuthorizationFlag
     * @covers ::getAuthorizationFlag
     */
    public function it_accepts_an_uint_32_as_a_authorization_flag()
    {
        $allowTrustOp = (new AllowTrustOp())->withAuthorizationFlag(UInt32::of(1));

        $this->assertInstanceOf(AllowTrustOp::class, $allowTrustOp);
        $this->assertInstanceOf(UInt32::class, $allowTrustOp->getAuthorizationFlag());
    }

    /**
     * @test
     * @covers ::withAuthorizationFlag
     * @covers ::getAuthorizationFlag
     */
    public function it_accepts_a_native_int_as_a_authorization_flag()
    {
        $allowTrustOp = (new AllowTrustOp())->withAuthorizationFlag(1);

        $this->assertInstanceOf(AllowTrustOp::class, $allowTrustOp);
        $this->assertInstanceOf(UInt32::class, $allowTrustOp->getAuthorizationFlag());
    }

    /**
     * @test
     * @covers ::withAuthorizationFlag
     * @covers ::getAuthorizationFlag
     */
    public function invalid_authorization_flags_are_rejected()
    {
        $this->expectException(InvalidArgumentException::class);
        (new AllowTrustOp())->withAuthorizationFlag(5);
    }
}

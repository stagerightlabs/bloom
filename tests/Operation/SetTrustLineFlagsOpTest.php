<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Operation\SetTrustLineFlagsOp;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\SetTrustLineFlagsOp
 */
class SetTrustLineFlagsOpTest extends TestCase
{
    /**
     * @test
     * @covers ::operation
     */
    public function it_can_create_an_operation_with_all_flags_set_to_false()
    {
        $operation = SetTrustLineFlagsOp::operation(
            trustor: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
            asset: 'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            authorized: false,
            authorizedToMaintainLiabilities: false,
            clawbackEnabled: false,
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(SetTrustLineFlagsOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::operation
     */
    public function it_can_create_an_operation_with_all_flags_set_to_true()
    {
        $operation = SetTrustLineFlagsOp::operation(
            trustor: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
            asset: 'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            authorized: true,
            authorizedToMaintainLiabilities: true,
            clawbackEnabled: true,
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(SetTrustLineFlagsOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::isReady
     */
    public function it_knows_when_it_is_ready()
    {
        $setTrustLineFlagsOp = new SetTrustLineFlagsOp();
        $this->assertFalse($setTrustLineFlagsOp->isReady());

        $setTrustLineFlagsOp = $setTrustLineFlagsOp->withTrustor('GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN');
        $this->assertFalse($setTrustLineFlagsOp->isReady());

        $setTrustLineFlagsOp = $setTrustLineFlagsOp->withAsset('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
    }

    /**
     * @test
     * @covers ::getThreshold
     */
    public function it_has_a_threshold_category()
    {
        $this->assertEquals(Thresholds::CATEGORY_LOW, (new SetTrustLineFlagsOp())->getThreshold());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $trustor = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $setTrustLineFlagsOp = (new SetTrustLineFlagsOp())
            ->withTrustor($trustor)
            ->withAsset($asset);
        $buffer = XDR::fresh()->write($setTrustLineFlagsOp);

        $this->assertEquals('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAUFCQ0QAAAAAVcAfh0fdvIam0MuNiicHVLrZZ0nBe95oEqB5396pelIAAAAAAAAAAA==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_trustor_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $setTrustLineFlagsOp = (new SetTrustLineFlagsOp())
            ->withAsset($asset);
        XDR::fresh()->write($setTrustLineFlagsOp);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_asset_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $trustor = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $setTrustLineFlagsOp = (new SetTrustLineFlagsOp())
            ->withTrustor($trustor);
        XDR::fresh()->write($setTrustLineFlagsOp);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $setTrustLineFlagsOp = XDR::fromBase64('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAUFCQ0QAAAAAVcAfh0fdvIam0MuNiicHVLrZZ0nBe95oEqB5396pelIAAAAAAAAAAA==')
            ->read(SetTrustLineFlagsOp::class);

        $this->assertInstanceOf(SetTrustLineFlagsOp::class, $setTrustLineFlagsOp);
        $this->assertInstanceOf(AccountId::class, $setTrustLineFlagsOp->getTrustor());
        $this->assertInstanceOf(Asset::class, $setTrustLineFlagsOp->getAsset());
        $this->assertInstanceOf(UInt32::class, $setTrustLineFlagsOp->getClearFlags());
        $this->assertInstanceOf(UInt32::class, $setTrustLineFlagsOp->getSetFlags());
    }

    /**
     * @test
     * @covers ::withTrustor
     * @covers ::getTrustor
     */
    public function it_accepts_a_trustor()
    {
        $trustor = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $setTrustLineFlagsOp = (new SetTrustLineFlagsOp())
            ->withTrustor($trustor);

        $this->assertInstanceOf(SetTrustLineFlagsOp::class, $setTrustLineFlagsOp);
        $this->assertInstanceOf(AccountId::class, $setTrustLineFlagsOp->getTrustor());
    }

    /**
     * @test
     * @covers ::withAsset
     * @covers ::getAsset
     */
    public function it_accepts_an_asset()
    {
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $setTrustLineFlagsOp = (new SetTrustLineFlagsOp())
            ->withAsset($asset);

        $this->assertInstanceOf(SetTrustLineFlagsOp::class, $setTrustLineFlagsOp);
        $this->assertInstanceOf(Asset::class, $setTrustLineFlagsOp->getAsset());
    }

    /**
     * @test
     * @covers ::withAsset
     * @covers ::getAsset
     */
    public function it_accepts_an_asset_string()
    {
        $setTrustLineFlagsOp = (new SetTrustLineFlagsOp())
            ->withAsset('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');

        $this->assertInstanceOf(SetTrustLineFlagsOp::class, $setTrustLineFlagsOp);
        $this->assertInstanceOf(Asset::class, $setTrustLineFlagsOp->getAsset());
    }

    /**
     * @test
     * @covers ::withClearFlags
     * @covers ::getClearFlags
     */
    public function it_accepts_clear_flags()
    {
        $setTrustLineFlagsOp = (new SetTrustLineFlagsOp())->withClearFlags(UInt32::of(1));

        $this->assertInstanceOf(SetTrustLineFlagsOp::class, $setTrustLineFlagsOp);
        $this->assertInstanceOf(UInt32::class, $setTrustLineFlagsOp->getClearFlags());
    }

    /**
     * @test
     * @covers ::withSetFlags
     * @covers ::getSetFlags
     */
    public function it_accepts_set_flags()
    {
        $setTrustLineFlagsOp = (new SetTrustLineFlagsOp())->withSetFlags(UInt32::of(1));

        $this->assertInstanceOf(SetTrustLineFlagsOp::class, $setTrustLineFlagsOp);
        $this->assertInstanceOf(UInt32::class, $setTrustLineFlagsOp->getSetFlags());
    }
}

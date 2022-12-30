<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\MuxedAccount;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Operation\ClawbackOp;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\ClawbackOp
 */
class ClawbackOpTest extends TestCase
{
    /**
     * @test
     * @covers ::operation
     */
    public function it_can_create_an_operation()
    {
        $operation = ClawbackOp::operation(
            from: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
            asset: Asset::native(),
            amount: '10',
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(ClawbackOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::isReady
     */
    public function it_knows_when_it_is_ready()
    {
        $clawbackOp = new ClawbackOp();
        $this->assertFalse($clawbackOp->isReady());

        $clawbackOp = $clawbackOp->withTargetAccount('GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN');
        $this->assertFalse($clawbackOp->isReady());

        $clawbackOp = $clawbackOp->withAsset('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $this->assertFalse($clawbackOp->isReady());

        $clawbackOp = $clawbackOp->withAmount('10');
        $this->assertTrue($clawbackOp->isReady());
    }

    /**
     * @test
     * @covers ::getThreshold
     */
    public function it_has_a_threshold_category()
    {
        $this->assertEquals(Thresholds::CATEGORY_MEDIUM, (new ClawbackOp())->getThreshold());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $muxedAccount = MuxedAccount::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $clawbackOp = (new ClawbackOp())
            ->withAsset($asset)
            ->withTargetAccount($muxedAccount)
            ->withAmount(Int64::of(1));
        $buffer = XDR::fresh()->write($clawbackOp);

        $this->assertEquals('AAAAAUFCQ0QAAAAAVcAfh0fdvIam0MuNiicHVLrZZ0nBe95oEqB5396pelIAAAAAam1BxzlDU4CGaXl40EB4DWHFzms0sXAq4QQodjODne4AAAAAAAAAAQ==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_asset_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $muxedAccount = MuxedAccount::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $clawbackOp = (new ClawbackOp())
            ->withTargetAccount($muxedAccount)
            ->withAmount(Int64::of(1));
        XDR::fresh()->write($clawbackOp);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_target_account_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $clawbackOp = (new ClawbackOp())
            ->withAsset($asset)
            ->withAmount(Int64::of(1));
        XDR::fresh()->write($clawbackOp);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_amount_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $muxedAccount = MuxedAccount::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $clawbackOp = (new ClawbackOp())
            ->withAsset($asset)
            ->withTargetAccount($muxedAccount);
        XDR::fresh()->write($clawbackOp);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $clawbackOp = XDR::fromBase64('AAAAAUFCQ0QAAAAAVcAfh0fdvIam0MuNiicHVLrZZ0nBe95oEqB5396pelIAAAAAam1BxzlDU4CGaXl40EB4DWHFzms0sXAq4QQodjODne4AAAAAAAAAAQ==')
            ->read(ClawbackOp::class);

        $this->assertInstanceOf(ClawbackOp::class, $clawbackOp);
        $this->assertInstanceOf(Asset::class, $clawbackOp->getAsset());
        $this->assertInstanceOf(MuxedAccount::class, $clawbackOp->getTargetAccount());
        $this->assertInstanceOf(Int64::class, $clawbackOp->getAmount());
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
        $clawbackOp = (new ClawbackOp())->withAsset($asset);

        $this->assertInstanceOf(ClawbackOp::class, $clawbackOp);
        $this->assertInstanceOf(Asset::class, $clawbackOp->getAsset());
    }

    /**
     * @test
     * @covers ::withAsset
     * @covers ::getAsset
     */
    public function it_accepts_an_asset_string()
    {
        $clawbackOp = (new ClawbackOp())
            ->withAsset('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');

        $this->assertInstanceOf(ClawbackOp::class, $clawbackOp);
        $this->assertInstanceOf(Asset::class, $clawbackOp->getAsset());
    }

    /**
     * @test
     * @covers ::withTargetAccount
     * @covers ::getTargetAccount
     */
    public function it_accepts_a_target_account()
    {
        $muxedAccount = MuxedAccount::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $clawbackOp = (new ClawbackOp())->withTargetAccount($muxedAccount);

        $this->assertInstanceOf(ClawbackOp::class, $clawbackOp);
        $this->assertInstanceOf(MuxedAccount::class, $clawbackOp->getTargetAccount());
    }

    /**
     * @test
     * @covers ::withAmount
     * @covers ::getAmount
     */
    public function it_accepts_an_amount()
    {
        $clawbackOp = (new ClawbackOp())->withAmount(Int64::of(1));

        $this->assertInstanceOf(ClawbackOp::class, $clawbackOp);
        $this->assertInstanceOf(Int64::class, $clawbackOp->getAmount());
    }
}

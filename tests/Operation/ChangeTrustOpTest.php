<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\LiquidityPool\LiquidityPoolParameters;
use StageRightLabs\Bloom\Operation\ChangeTrustAsset;
use StageRightLabs\Bloom\Operation\ChangeTrustOp;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\ChangeTrustOp
 */
class ChangeTrustOpTest extends TestCase
{
    /**
     * @test
     * @covers ::operation
     */
    public function it_can_create_an_operation()
    {
        $operation = ChangeTrustOp::operation(
            asset: 'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            limit: 0,
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(ChangeTrustOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::isReady
     */
    public function it_knows_when_it_is_ready()
    {
        $changeTrustOp = new ChangeTrustOp();
        $this->assertFalse($changeTrustOp->isReady());

        $changeTrustOp = $changeTrustOp->withLine('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $this->assertTrue($changeTrustOp->isReady());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $changeTrustOp = (new ChangeTrustOp())
            ->withLine(ChangeTrustAsset::wrapNativeAsset());
        $buffer = XDR::fresh()->write($changeTrustOp);

        $this->assertEquals('AAAAAH//////////', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function asset_details_are_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new ChangeTrustOp());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $changeTrustOp = XDR::fromBase64('AAAAAH//////////')
            ->read(ChangeTrustOp::class);

        $this->assertInstanceOf(ChangeTrustOp::class, $changeTrustOp);
        $this->assertInstanceOf(ChangeTrustAsset::class, $changeTrustOp->getLine());
        $this->assertInstanceOf(Int64::class, $changeTrustOp->getLimit());
    }

    /**
     * @test
     * @covers ::withLine
     * @covers ::getLine
     */
    public function it_accepts_an_change_trust_asset_line()
    {
        $changeTrustOp = (new ChangeTrustOp())->withLine(ChangeTrustAsset::wrapNativeAsset());

        $this->assertInstanceOf(ChangeTrustOp::class, $changeTrustOp);
        $this->assertInstanceOf(ChangeTrustAsset::class, $changeTrustOp->getLine());
    }

    /**
     * @test
     * @covers ::withLine
     * @covers ::getLine
     */
    public function it_accepts_a_string_asset_line()
    {
        $changeTrustOp = (new ChangeTrustOp())->withLine('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');

        $this->assertInstanceOf(ChangeTrustOp::class, $changeTrustOp);
        $this->assertInstanceOf(ChangeTrustAsset::class, $changeTrustOp->getLine());
    }

    /**
     * @test
     * @covers ::withLine
     * @covers ::getLine
     */
    public function it_accepts_an_asset_line()
    {
        $changeTrustOp = (new ChangeTrustOp())->withLine(
            Asset::fromNativeString('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS')
        );

        $this->assertInstanceOf(ChangeTrustOp::class, $changeTrustOp);
        $this->assertInstanceOf(ChangeTrustAsset::class, $changeTrustOp->getLine());
    }

    /**
     * @test
     * @covers ::withLine
     * @covers ::getLine
     */
    public function it_accepts_a_liquidity_pool_asset_line()
    {
        $liquidityPoolParameters = LiquidityPoolParameters::build(
            assetA: 'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            assetB: 'ABCDEFGHIJKL:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            fee: 1,
        );
        $changeTrustOp = (new ChangeTrustOp())->withLine($liquidityPoolParameters);

        $this->assertInstanceOf(ChangeTrustOp::class, $changeTrustOp);
        $this->assertInstanceOf(ChangeTrustAsset::class, $changeTrustOp->getLine());
    }

    /**
     * @test
     * @covers ::withLimit
     * @covers ::getLimit
     */
    public function it_accepts_an_int_64_as_a_limit()
    {
        $changeTrustOp = (new ChangeTrustOp())->withLimit(Int64::of(10000000));

        $this->assertInstanceOf(ChangeTrustOp::class, $changeTrustOp);
        $this->assertInstanceOf(Int64::class, $changeTrustOp->getLimit());
    }

    /**
     * @test
     * @covers ::withLimit
     * @covers ::getLimit
     */
    public function it_accepts_a_scaled_amount_as_a_limit()
    {
        $changeTrustOp = (new ChangeTrustOp())->withLimit(ScaledAmount::of(1));

        $this->assertInstanceOf(ChangeTrustOp::class, $changeTrustOp);
        $this->assertInstanceOf(Int64::class, $changeTrustOp->getLimit());
        $this->assertEquals('10000000', $changeTrustOp->getLimit()->toNativeString());
    }

    /**
     * @test
     * @covers ::withLimit
     * @covers ::getLimit
     */
    public function it_accepts_a_null_limit()
    {
        $changeTrustOp = (new ChangeTrustOp())->withLimit();

        $this->assertInstanceOf(ChangeTrustOp::class, $changeTrustOp);
        $this->assertInstanceOf(Int64::class, $changeTrustOp->getLimit());
        $this->assertTrue($changeTrustOp->getLimit()->isEqualTo(Int64::max()));
    }

    /**
     * @test
     * @covers ::getThreshold
     */
    public function it_has_a_threshold_category()
    {
        $this->assertEquals(Thresholds::CATEGORY_MEDIUM, (new ChangeTrustOp())->getThreshold());
    }
}

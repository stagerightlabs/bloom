<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\Price;
use StageRightLabs\Bloom\Operation\CreatePassiveSellOfferOp;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\CreatePassiveSellOfferOp
 */
class CreatePassiveSellOfferOpTest extends TestCase
{
    /**
     * @test
     * @covers ::operation
     */
    public function it_can_create_an_operation()
    {
        $operation = CreatePassiveSellOfferOp::operation(
            sellingAsset: 'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            buyingAsset: 'ABCDEFG:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            price: '3.75',
            amount: '10',
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(CreatePassiveSellOfferOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::isReady
     */
    public function it_knows_when_it_is_ready()
    {
        $createPassiveSellOfferOp = new CreatePassiveSellOfferOp();
        $this->assertFalse($createPassiveSellOfferOp->isReady());

        $createPassiveSellOfferOp = $createPassiveSellOfferOp->withSellingAsset('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $this->assertFalse($createPassiveSellOfferOp->isReady());

        $createPassiveSellOfferOp = $createPassiveSellOfferOp->withBuyingAsset('ABCDEFG:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $this->assertFalse($createPassiveSellOfferOp->isReady());

        $createPassiveSellOfferOp = $createPassiveSellOfferOp->withAmount('10');
        $this->assertFalse($createPassiveSellOfferOp->isReady());

        $createPassiveSellOfferOp = $createPassiveSellOfferOp->withPrice(Price::of(1, 2));
        $this->assertTrue($createPassiveSellOfferOp->isReady());
    }

    /**
     * @test
     * @covers ::getThreshold
     */
    public function it_has_a_threshold_category()
    {
        $this->assertEquals(Thresholds::CATEGORY_MEDIUM, (new CreatePassiveSellOfferOp())->getThreshold());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $createPassiveSellOfferOp = (new CreatePassiveSellOfferOp())
            ->withSellingAsset($asset)
            ->withBuyingAsset($asset)
            ->withAmount(Int64::of(10000000))
            ->withPrice(Price::of(1, 2));
        $buffer = XDR::fresh()->write($createPassiveSellOfferOp);

        $this->assertEquals(
            'AAAAAUFCQ0QAAAAAVcAfh0fdvIam0MuNiicHVLrZZ0nBe95oEqB5396pelIAAAABQUJDRAAAAABVwB+HR928hqbQy42KJwdUutlnScF73mgSoHnf3ql6UgAAAAAAmJaAAAAAAQAAAAI=',
            $buffer->toBase64()
        );
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_selling_asset_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $createPassiveSellOfferOp = (new CreatePassiveSellOfferOp())
            ->withBuyingAsset($asset)
            ->withAmount(Int64::of(10000000))
            ->withPrice(Price::of(1, 2));
        XDR::fresh()->write($createPassiveSellOfferOp);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_buying_asset_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $createPassiveSellOfferOp = (new CreatePassiveSellOfferOp())
            ->withSellingAsset($asset)
            ->withAmount(Int64::of(10000000))
            ->withPrice(Price::of(1, 2));
        XDR::fresh()->write($createPassiveSellOfferOp);
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
        $createPassiveSellOfferOp = (new CreatePassiveSellOfferOp())
            ->withSellingAsset($asset)
            ->withBuyingAsset($asset)
            ->withPrice(Price::of(1, 2));
        XDR::fresh()->write($createPassiveSellOfferOp);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_price_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $createPassiveSellOfferOp = (new CreatePassiveSellOfferOp())
            ->withSellingAsset($asset)
            ->withBuyingAsset($asset)
            ->withAmount(Int64::of(10000000));
        XDR::fresh()->write($createPassiveSellOfferOp);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $createPassiveSellOfferOp = XDR::fromBase64('AAAAAUFCQ0QAAAAAVcAfh0fdvIam0MuNiicHVLrZZ0nBe95oEqB5396pelIAAAABQUJDRAAAAABVwB+HR928hqbQy42KJwdUutlnScF73mgSoHnf3ql6UgAAAAAAmJaAAAAAAQAAAAI=')
            ->read(CreatePassiveSellOfferOp::class);

        $this->assertInstanceOf(CreatePassiveSellOfferOp::class, $createPassiveSellOfferOp);
        $this->assertInstanceOf(Asset::class, $createPassiveSellOfferOp->getSellingAsset());
        $this->assertInstanceOf(Asset::class, $createPassiveSellOfferOp->getBuyingAsset());
        $this->assertInstanceOf(Int64::class, $createPassiveSellOfferOp->getAmount());
        $this->assertInstanceOf(Price::class, $createPassiveSellOfferOp->getPrice());
    }

    /**
     * @test
     * @covers ::withSellingAsset
     * @covers ::getSellingAsset
     */
    public function it_accepts_a_selling_asset()
    {
        $createPassiveSellOfferOp = (new CreatePassiveSellOfferOp())
            ->withSellingAsset(new Asset());

        $this->assertInstanceOf(CreatePassiveSellOfferOp::class, $createPassiveSellOfferOp);
        $this->assertInstanceOf(Asset::class, $createPassiveSellOfferOp->getSellingAsset());
    }

    /**
     * @test
     * @covers ::withSellingAsset
     * @covers ::getSellingAsset
     */
    public function it_accepts_a_selling_asset_string()
    {
        $createPassiveSellOfferOp = (new CreatePassiveSellOfferOp())
            ->withSellingAsset('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');

        $this->assertInstanceOf(CreatePassiveSellOfferOp::class, $createPassiveSellOfferOp);
        $this->assertInstanceOf(Asset::class, $createPassiveSellOfferOp->getSellingAsset());
    }

    /**
     * @test
     * @covers ::withBuyingAsset
     * @covers ::getBuyingAsset
     */
    public function it_accepts_a_buying_asset()
    {
        $createPassiveSellOfferOp = (new CreatePassiveSellOfferOp())
            ->withBuyingAsset(new Asset());

        $this->assertInstanceOf(CreatePassiveSellOfferOp::class, $createPassiveSellOfferOp);
        $this->assertInstanceOf(Asset::class, $createPassiveSellOfferOp->getBuyingAsset());
    }

    /**
     * @test
     * @covers ::withBuyingAsset
     * @covers ::getBuyingAsset
     */
    public function it_accepts_a_buying_asset_string()
    {
        $createPassiveSellOfferOp = (new CreatePassiveSellOfferOp())
            ->withBuyingAsset('ABCDEFG:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');

        $this->assertInstanceOf(CreatePassiveSellOfferOp::class, $createPassiveSellOfferOp);
        $this->assertInstanceOf(Asset::class, $createPassiveSellOfferOp->getBuyingAsset());
    }

    /**
     * @test
     * @covers ::withAmount
     * @covers ::getAmount
     */
    public function it_accepts_an_int_64_as_a_purchase_amount()
    {
        $createPassiveSellOfferOp = (new CreatePassiveSellOfferOp())
            ->withAmount(Int64::of(10000000));

        $this->assertInstanceOf(CreatePassiveSellOfferOp::class, $createPassiveSellOfferOp);
        $this->assertInstanceOf(Int64::class, $createPassiveSellOfferOp->getAmount());
    }

    /**
     * @test
     * @covers ::withAmount
     * @covers ::getAmount
     */
    public function it_accepts_a_scaled_amount_as_a_purchase_amount()
    {
        $createPassiveSellOfferOp = (new CreatePassiveSellOfferOp())
            ->withAmount(ScaledAmount::of(1));

        $this->assertInstanceOf(CreatePassiveSellOfferOp::class, $createPassiveSellOfferOp);
        $this->assertInstanceOf(Int64::class, $createPassiveSellOfferOp->getAmount());
    }

    /**
     * @test
     * @covers ::withPrice
     * @covers ::getPrice
     */
    public function it_accepts_a_price()
    {
        $createPassiveSellOfferOp = (new CreatePassiveSellOfferOp())
            ->withPrice(Price::of(1, 2));

        $this->assertInstanceOf(CreatePassiveSellOfferOp::class, $createPassiveSellOfferOp);
        $this->assertInstanceOf(Price::class, $createPassiveSellOfferOp->getPrice());
    }

    /**
     * @test
     * @covers ::withPrice
     * @covers ::getPrice
     */
    public function it_accepts_a_price_string()
    {
        $createPassiveSellOfferOp = (new CreatePassiveSellOfferOp())
            ->withPrice('3.75');

        $this->assertInstanceOf(CreatePassiveSellOfferOp::class, $createPassiveSellOfferOp);
        $this->assertInstanceOf(Price::class, $createPassiveSellOfferOp->getPrice());
    }
}

<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\Price;
use StageRightLabs\Bloom\Operation\ManageSellOfferOp;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\ManageSellOfferOp
 */
class ManageSellOfferOpTest extends TestCase
{
    /**
     * @test
     * @covers ::operation
     */
    public function it_can_create_an_operation()
    {
        $operation = ManageSellOfferOp::operation(
            sellingAsset: 'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            sellingAmount: '10',
            buyingAsset: 'ABCDEFG:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            price: '3.75',
            offerId: 0,
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(ManageSellOfferOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::isReady
     */
    public function it_knows_when_it_is_ready()
    {
        $manageSellOfferOp = new ManageSellOfferOp();
        $this->assertFalse($manageSellOfferOp->isReady());

        $manageSellOfferOp = $manageSellOfferOp->withSellingAsset('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $this->assertFalse($manageSellOfferOp->isReady());

        $manageSellOfferOp = $manageSellOfferOp->withSellingAmount('10');
        $this->assertFalse($manageSellOfferOp->isReady());

        $manageSellOfferOp = $manageSellOfferOp->withBuyingAsset('ABCDEFG:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $this->assertFalse($manageSellOfferOp->isReady());

        $manageSellOfferOp = $manageSellOfferOp->withPrice(Price::of(2, 1));
        $this->assertTrue($manageSellOfferOp->isReady());
    }

    /**
     * @test
     * @covers ::getThreshold
     */
    public function it_has_a_threshold_category()
    {
        $this->assertEquals(Thresholds::CATEGORY_MEDIUM, (new ManageSellOfferOp())->getThreshold());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $price = Price::of(1, 2);
        $manageSellOffer = (new ManageSellOfferOp())
            ->withSellingAsset($asset)
            ->withBuyingAsset($asset)
            ->withSellingAmount(Int64::of(10000000))
            ->withPrice($price);
        $buffer = XDR::fresh()->write($manageSellOffer);

        $this->assertEquals('AAAAAUFCQ0QAAAAAVcAfh0fdvIam0MuNiicHVLrZZ0nBe95oEqB5396pelIAAAABQUJDRAAAAABVwB+HR928hqbQy42KJwdUutlnScF73mgSoHnf3ql6UgAAAAAAmJaAAAAAAQAAAAIAAAAAAAAAAA==', $buffer->toBase64());
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
        $price = Price::of(1, 2);
        $manageSellOffer = (new ManageSellOfferOp())
            ->withBuyingAsset($asset)
            ->withSellingAmount(Int64::of(10000000))
            ->withPrice($price);
        XDR::fresh()->write($manageSellOffer);
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
        $price = Price::of(1, 2);
        $manageSellOffer = (new ManageSellOfferOp())
            ->withSellingAsset($asset)
            ->withSellingAmount(Int64::of(10000000))
            ->withPrice($price);
        XDR::fresh()->write($manageSellOffer);
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
        $price = Price::of(1, 2);
        $manageSellOffer = (new ManageSellOfferOp())
            ->withSellingAsset($asset)
            ->withBuyingAsset($asset)
            ->withPrice($price);
        XDR::fresh()->write($manageSellOffer);
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
        $manageSellOffer = (new ManageSellOfferOp())
            ->withSellingAsset($asset)
            ->withBuyingAsset($asset)
            ->withSellingAmount(Int64::of(10000000));
        XDR::fresh()->write($manageSellOffer);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $manageSellOffer = XDR::fromBase64('AAAAAUFCQ0QAAAAAVcAfh0fdvIam0MuNiicHVLrZZ0nBe95oEqB5396pelIAAAABQUJDRAAAAABVwB+HR928hqbQy42KJwdUutlnScF73mgSoHnf3ql6UgAAAAAAmJaAAAAAAQAAAAIAAAAAAAAAAA==')
            ->read(ManageSellOfferOp::class);

        $this->assertInstanceOf(ManageSellOfferOp::class, $manageSellOffer);
        $this->assertInstanceOf(Asset::class, $manageSellOffer->getSellingAsset());
        $this->assertInstanceOf(Asset::class, $manageSellOffer->getBuyingAsset());
        $this->assertInstanceOf(Int64::class, $manageSellOffer->getSellingAmount());
        $this->assertInstanceOf(Price::class, $manageSellOffer->getPrice());
        $this->assertInstanceOf(Int64::class, $manageSellOffer->getOfferId());
    }

    /**
     * @test
     * @covers ::withSellingAsset
     * @covers ::getSellingAsset
     */
    public function it_accepts_a_selling_asset()
    {
        $manageSellOffer = (new ManageSellOfferOp())
            ->withSellingAsset(new Asset());

        $this->assertInstanceOf(ManageSellOfferOp::class, $manageSellOffer);
        $this->assertInstanceOf(Asset::class, $manageSellOffer->getSellingAsset());
    }

    /**
     * @test
     * @covers ::withSellingAsset
     * @covers ::getSellingAsset
     */
    public function it_accepts_a_selling_asset_string()
    {
        $manageSellOffer = (new ManageSellOfferOp())
            ->withSellingAsset('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');

        $this->assertInstanceOf(ManageSellOfferOp::class, $manageSellOffer);
        $this->assertInstanceOf(Asset::class, $manageSellOffer->getSellingAsset());
    }

    /**
     * @test
     * @covers ::withBuyingAsset
     * @covers ::getBuyingAsset
     */
    public function it_accepts_a_buying_asset()
    {
        $manageSellOffer = (new ManageSellOfferOp())
            ->withBuyingAsset(new Asset());

        $this->assertInstanceOf(ManageSellOfferOp::class, $manageSellOffer);
        $this->assertInstanceOf(Asset::class, $manageSellOffer->getBuyingAsset());
    }

    /**
     * @test
     * @covers ::withBuyingAsset
     * @covers ::getBuyingAsset
     */
    public function it_accepts_a_buying_asset_string()
    {
        $manageSellOffer = (new ManageSellOfferOp())
            ->withBuyingAsset('ABCDEFG:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');

        $this->assertInstanceOf(ManageSellOfferOp::class, $manageSellOffer);
        $this->assertInstanceOf(Asset::class, $manageSellOffer->getBuyingAsset());
    }

    /**
     * @test
     * @covers ::withSellingAmount
     * @covers ::getSellingAmount
     */
    public function it_accepts_an_int_64_as_an_amount()
    {
        $manageSellOffer = (new ManageSellOfferOp())
            ->withSellingAmount(Int64::of(10000000));

        $this->assertInstanceOf(ManageSellOfferOp::class, $manageSellOffer);
        $this->assertInstanceOf(Int64::class, $manageSellOffer->getSellingAmount());
    }

    /**
     * @test
     * @covers ::withSellingAmount
     * @covers ::getSellingAmount
     */
    public function it_accepts_a_scaled_amount_as_an_amount()
    {
        $manageSellOffer = (new ManageSellOfferOp())
            ->withSellingAmount(ScaledAmount::of(1));

        $this->assertInstanceOf(ManageSellOfferOp::class, $manageSellOffer);
        $this->assertInstanceOf(Int64::class, $manageSellOffer->getSellingAmount());
        $this->assertEquals('10000000', $manageSellOffer->getSellingAmount()->toNativeString());
    }

    /**
     * @test
     * @covers ::withPrice
     * @covers ::getPrice
     */
    public function it_accepts_a_price()
    {
        $manageSellOffer = (new ManageSellOfferOp())->withPrice(new Price());

        $this->assertInstanceOf(ManageSellOfferOp::class, $manageSellOffer);
        $this->assertInstanceOf(Price::class, $manageSellOffer->getPrice());
    }

    /**
     * @test
     * @covers ::withPrice
     * @covers ::getPrice
     */
    public function it_accepts_a_price_string()
    {
        $manageSellOffer = (new ManageSellOfferOp())->withPrice('3.75');

        $this->assertInstanceOf(ManageSellOfferOp::class, $manageSellOffer);
        $this->assertInstanceOf(Price::class, $manageSellOffer->getPrice());
    }

    /**
     * @test
     * @covers ::withOfferId
     * @covers ::getOfferId
     */
    public function it_accepts_an_offer_id()
    {
        $manageSellOffer = (new ManageSellOfferOp())->withOfferId(Int64::of(1));

        $this->assertInstanceOf(ManageSellOfferOp::class, $manageSellOffer);
        $this->assertInstanceOf(Int64::class, $manageSellOffer->getOfferId());
    }

    /**
     * @test
     * @covers ::withOfferId
     * @covers ::getOfferId
     */
    public function it_accepts_a_null_offer_id()
    {
        $manageSellOffer = (new ManageSellOfferOp())->withOfferId(null);

        $this->assertInstanceOf(ManageSellOfferOp::class, $manageSellOffer);
        $this->assertInstanceOf(Int64::class, $manageSellOffer->getOfferId());
        $this->assertEquals(0, $manageSellOffer->getOfferId()->toNativeInt());
    }
}

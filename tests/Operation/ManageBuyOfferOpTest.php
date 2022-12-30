<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\Price;
use StageRightLabs\Bloom\Operation\ManageBuyOfferOp;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\ManageBuyOfferOp
 */
class ManageBuyOfferOpTest extends TestCase
{
    /**
     * @test
     * @covers ::operation
     */
    public function it_can_create_an_operation()
    {
        $operation = ManageBuyOfferOp::operation(
            sellingAsset: 'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            buyingAsset: 'ABCDEFG:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            buyingAmount: '10',
            price: '3.75',
            offerId: 0,
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(ManageBuyOfferOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::isReady
     */
    public function it_knows_when_it_is_ready()
    {
        $manageBuyOfferOp = new ManageBuyOfferOp();
        $this->assertFalse($manageBuyOfferOp->isReady());

        $manageBuyOfferOp = $manageBuyOfferOp->withSellingAsset('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $this->assertFalse($manageBuyOfferOp->isReady());

        $manageBuyOfferOp = $manageBuyOfferOp->withBuyingAsset('ABCDEFG:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $this->assertFalse($manageBuyOfferOp->isReady());

        $manageBuyOfferOp = $manageBuyOfferOp->withBuyingAmount('10');
        $this->assertFalse($manageBuyOfferOp->isReady());

        $manageBuyOfferOp = $manageBuyOfferOp->withPrice(Price::of(2, 1));
        $this->assertTrue($manageBuyOfferOp->isReady());
    }

    /**
     * @test
     * @covers ::getThreshold
     */
    public function it_has_a_threshold_category()
    {
        $this->assertEquals(Thresholds::CATEGORY_MEDIUM, (new ManageBuyOfferOp())->getThreshold());
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
        $manageBuyOffer = (new ManageBuyOfferOp())
            ->withSellingAsset($asset)
            ->withBuyingAsset($asset)
            ->withBuyingAmount(Int64::of(10000000))
            ->withPrice($price);
        $buffer = XDR::fresh()->write($manageBuyOffer);

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
        $manageBuyOffer = (new ManageBuyOfferOp())
            ->withBuyingAsset($asset)
            ->withBuyingAmount(Int64::of(10000000))
            ->withPrice($price);
        XDR::fresh()->write($manageBuyOffer);
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
        $manageBuyOffer = (new ManageBuyOfferOp())
            ->withSellingAsset($asset)
            ->withBuyingAmount(Int64::of(10000000))
            ->withPrice($price);
        XDR::fresh()->write($manageBuyOffer);
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
        $manageBuyOffer = (new ManageBuyOfferOp())
            ->withSellingAsset($asset)
            ->withBuyingAsset($asset)
            ->withPrice($price);
        XDR::fresh()->write($manageBuyOffer);
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
        $manageBuyOffer = (new ManageBuyOfferOp())
            ->withSellingAsset($asset)
            ->withBuyingAsset($asset)
            ->withBuyingAmount(Int64::of(10000000));
        XDR::fresh()->write($manageBuyOffer);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $manageBuyOffer = XDR::fromBase64('AAAAAUFCQ0QAAAAAVcAfh0fdvIam0MuNiicHVLrZZ0nBe95oEqB5396pelIAAAABQUJDRAAAAABVwB+HR928hqbQy42KJwdUutlnScF73mgSoHnf3ql6UgAAAAAAmJaAAAAAAQAAAAIAAAAAAAAAAA==')
            ->read(ManageBuyOfferOp::class);

        $this->assertInstanceOf(ManageBuyOfferOp::class, $manageBuyOffer);
        $this->assertInstanceOf(Asset::class, $manageBuyOffer->getSellingAsset());
        $this->assertInstanceOf(Asset::class, $manageBuyOffer->getBuyingAsset());
        $this->assertInstanceOf(Int64::class, $manageBuyOffer->getBuyingAmount());
        $this->assertInstanceOf(Price::class, $manageBuyOffer->getPrice());
        $this->assertInstanceOf(Int64::class, $manageBuyOffer->getOfferId());
    }

    /**
     * @test
     * @covers ::withSellingAsset
     * @covers ::getSellingAsset
     */
    public function it_accepts_a_selling_asset()
    {
        $manageBuyOffer = (new ManageBuyOfferOp())
            ->withSellingAsset(new Asset());

        $this->assertInstanceOf(ManageBuyOfferOp::class, $manageBuyOffer);
        $this->assertInstanceOf(Asset::class, $manageBuyOffer->getSellingAsset());
    }

    /**
     * @test
     * @covers ::withSellingAsset
     * @covers ::getSellingAsset
     */
    public function it_accepts_a_selling_asset_string()
    {
        $manageBuyOffer = (new ManageBuyOfferOp())
            ->withSellingAsset('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');

        $this->assertInstanceOf(ManageBuyOfferOp::class, $manageBuyOffer);
        $this->assertInstanceOf(Asset::class, $manageBuyOffer->getSellingAsset());
    }

    /**
     * @test
     * @covers ::withBuyingAsset
     * @covers ::getBuyingAsset
     */
    public function it_accepts_a_buying_asset()
    {
        $manageBuyOffer = (new ManageBuyOfferOp())
            ->withBuyingAsset(new Asset());

        $this->assertInstanceOf(ManageBuyOfferOp::class, $manageBuyOffer);
        $this->assertInstanceOf(Asset::class, $manageBuyOffer->getBuyingAsset());
    }

    /**
     * @test
     * @covers ::withBuyingAsset
     * @covers ::getBuyingAsset
     */
    public function it_accepts_a_buying_asset_string()
    {
        $manageBuyOffer = (new ManageBuyOfferOp())
            ->withBuyingAsset('ABCDEFG:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');

        $this->assertInstanceOf(ManageBuyOfferOp::class, $manageBuyOffer);
        $this->assertInstanceOf(Asset::class, $manageBuyOffer->getBuyingAsset());
    }

    /**
     * @test
     * @covers ::withBuyingAmount
     * @covers ::getBuyingAmount
     */
    public function it_accepts_an_int_64_as_an_amount()
    {
        $manageBuyOffer = (new ManageBuyOfferOp())
            ->withBuyingAmount(Int64::of(10000000));

        $this->assertInstanceOf(ManageBuyOfferOp::class, $manageBuyOffer);
        $this->assertInstanceOf(Int64::class, $manageBuyOffer->getBuyingAmount());
    }

    /**
     * @test
     * @covers ::withBuyingAmount
     * @covers ::getBuyingAmount
     */
    public function it_accepts_a_scaled_amount_as_an_amount()
    {
        $manageBuyOffer = (new ManageBuyOfferOp())
            ->withBuyingAmount(ScaledAmount::of(1));

        $this->assertInstanceOf(ManageBuyOfferOp::class, $manageBuyOffer);
        $this->assertInstanceOf(Int64::class, $manageBuyOffer->getBuyingAmount());
        $this->assertEquals('10000000', $manageBuyOffer->getBuyingAmount()->toNativeString());
    }

    /**
     * @test
     * @covers ::withPrice
     * @covers ::getPrice
     */
    public function it_accepts_a_price()
    {
        $manageBuyOffer = (new ManageBuyOfferOp())->withPrice(new Price());

        $this->assertInstanceOf(ManageBuyOfferOp::class, $manageBuyOffer);
        $this->assertInstanceOf(Price::class, $manageBuyOffer->getPrice());
    }

    /**
     * @test
     * @covers ::withPrice
     * @covers ::getPrice
     */
    public function it_accepts_a_price_string()
    {
        $manageBuyOffer = (new ManageBuyOfferOp())->withPrice('3.75');

        $this->assertInstanceOf(ManageBuyOfferOp::class, $manageBuyOffer);
        $this->assertInstanceOf(Price::class, $manageBuyOffer->getPrice());
    }

    /**
     * @test
     * @covers ::withOfferId
     * @covers ::getOfferId
     */
    public function it_accepts_an_offer_id()
    {
        $manageBuyOffer = (new ManageBuyOfferOp())->withOfferId(Int64::of(1));

        $this->assertInstanceOf(ManageBuyOfferOp::class, $manageBuyOffer);
        $this->assertInstanceOf(Int64::class, $manageBuyOffer->getOfferId());
    }

    /**
     * @test
     * @covers ::withOfferId
     * @covers ::getOfferId
     */
    public function it_accepts_a_null_offer_id()
    {
        $manageBuyOffer = (new ManageBuyOfferOp())->withOfferId(null);

        $this->assertInstanceOf(ManageBuyOfferOp::class, $manageBuyOffer);
        $this->assertInstanceOf(Int64::class, $manageBuyOffer->getOfferId());
        $this->assertEquals(0, $manageBuyOffer->getOfferId()->toNativeInt());
    }
}

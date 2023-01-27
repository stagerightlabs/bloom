<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\CreatePassiveSellOfferOperationResource;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\CreatePassiveSellOfferOperationResource
 */
class CreatePassiveSellOfferOperationResourceTest extends TestCase
{
    /**
     * @test
     * @covers ::getAmount
     */
    public function it_returns_the_amount()
    {
        $operation = CreatePassiveSellOfferOperationResource::wrap(
            Response::fake('create_passive_sell_offer_operation')->getBody()
        );

        $this->assertEquals('1.0000000', $operation->getAmount()->toNativeString());
        $this->assertNull((new CreatePassiveSellOfferOperationResource())->getAmount());
    }

    /**
     * @test
     * @covers ::getPrice
     */
    public function it_returns_the_price()
    {
        $operation = CreatePassiveSellOfferOperationResource::wrap(
            Response::fake('create_passive_sell_offer_operation')->getBody()
        );

        $this->assertEquals('1.0000000', $operation->getPrice());
    }

    /**
     * @test
     * @covers ::getPriceNumerator
     */
    public function it_returns_the_price_numerator()
    {
        $operation = CreatePassiveSellOfferOperationResource::wrap(
            Response::fake('create_passive_sell_offer_operation')->getBody()
        );

        $this->assertEquals(1, $operation->getPriceNumerator());
    }

    /**
     * @test
     * @covers ::getPriceDenominator
     */
    public function it_returns_the_price_denominator()
    {
        $operation = CreatePassiveSellOfferOperationResource::wrap(
            Response::fake('create_passive_sell_offer_operation')->getBody()
        );

        $this->assertEquals(1, $operation->getPriceDenominator());
    }

    /**
     * @test
     * @covers ::getBuyingAssetType
     */
    public function it_returns_the_buying_asset_type()
    {
        $operation = CreatePassiveSellOfferOperationResource::wrap(
            Response::fake('create_passive_sell_offer_operation')->getBody()
        );

        $this->assertEquals('credit_alphanum4', $operation->getBuyingAssetType());
    }

    /**
     * @test
     * @covers ::getBuyingAssetIssuerAddress
     */
    public function it_returns_the_buying_asset_issuer_address()
    {
        $operation = CreatePassiveSellOfferOperationResource::wrap(
            Response::fake('create_passive_sell_offer_operation')->getBody()
        );

        $this->assertEquals(
            'GBNLJIYH34UWO5YZFA3A3HD3N76R6DOI33N4JONUOHEEYZYCAYTEJ5AK',
            $operation->getBuyingAssetIssuerAddress()
        );
    }

    /**
     * @test
     * @covers ::getBuyingAssetCode
     */
    public function it_returns_the_buying_asset_code()
    {
        $operation = CreatePassiveSellOfferOperationResource::wrap(
            Response::fake('create_passive_sell_offer_operation')->getBody()
        );

        $this->assertEquals('USD', $operation->getBuyingAssetCode());
    }

    /**
     * @test
     * @covers ::getSellingAssetType
     */
    public function it_returns_the_selling_asset_type()
    {
        $operation = CreatePassiveSellOfferOperationResource::wrap(
            Response::fake('create_passive_sell_offer_operation')->getBody()
        );

        $this->assertEquals('credit_alphanum4', $operation->getSellingAssetType());
    }

    /**
     * @test
     * @covers ::getSellingAssetIssuerAddress
     */
    public function it_returns_the_selling_asset_issuer_address()
    {
        $operation = CreatePassiveSellOfferOperationResource::wrap(
            Response::fake('create_passive_sell_offer_operation')->getBody()
        );

        $this->assertEquals(
            'GDUKMGUGDZQK6YHYA5Z6AY2G4XDSZPSZ3SW5UN3ARVMO6QSRDWP5YLEX',
            $operation->getSellingAssetIssuerAddress()
        );
    }

    /**
     * @test
     * @covers ::getSellingAssetCode
     */
    public function it_returns_the_selling_asset_code()
    {
        $operation = CreatePassiveSellOfferOperationResource::wrap(
            Response::fake('create_passive_sell_offer_operation')->getBody()
        );

        $this->assertEquals('USD', $operation->getSellingAssetCode());
    }

    /**
     * @test
     * @covers ::getOfferId
     */
    public function it_returns_the_offer_id()
    {
        $operation = CreatePassiveSellOfferOperationResource::wrap(
            Response::fake('create_passive_sell_offer_operation')->getBody()
        );

        $this->assertEquals('0', $operation->getOfferId());
    }
}

<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\ManageBuyOfferOperationResource;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\ManageBuyOfferOperationResource
 */
class ManageBuyOfferOperationResourceTest extends TestCase
{
    /**
     * @test
     * @covers ::getAmount
     */
    public function it_returns_the_amount()
    {
        $operation = ManageBuyOfferOperationResource::wrap(
            Response::fake('manage_buy_offer_operation')->getBody()
        );

        $this->assertEquals('20.4521401', $operation->getAmount()->toNativeString());
        $this->assertNull((new ManageBuyOfferOperationResource())->getAmount());
    }

    /**
     * @test
     * @covers ::getPrice
     */
    public function it_returns_the_price()
    {
        $operation = ManageBuyOfferOperationResource::wrap(
            Response::fake('manage_buy_offer_operation')->getBody()
        );

        $this->assertEquals('0.0300003', $operation->getPrice());
    }

    /**
     * @test
     * @covers ::getPriceNumerator
     */
    public function it_returns_the_price_numerator()
    {
        $operation = ManageBuyOfferOperationResource::wrap(
            Response::fake('manage_buy_offer_operation')->getBody()
        );

        $this->assertEquals(9000190, $operation->getPriceNumerator());
    }

    /**
     * @test
     * @covers ::getPriceDenominator
     */
    public function it_returns_the_price_denominator()
    {
        $operation = ManageBuyOfferOperationResource::wrap(
            Response::fake('manage_buy_offer_operation')->getBody()
        );

        $this->assertEquals(300003333, $operation->getPriceDenominator());
    }

    /**
     * @test
     * @covers ::getBuyingAssetType
     */
    public function it_returns_the_buying_asset_type()
    {
        $operation = ManageBuyOfferOperationResource::wrap(
            Response::fake('manage_buy_offer_operation')->getBody()
        );

        $this->assertEquals('credit_alphanum4', $operation->getBuyingAssetType());
    }

    /**
     * @test
     * @covers ::getBuyingAssetIssuerAddress
     */
    public function it_returns_the_buying_asset_issuer_address()
    {
        $operation = ManageBuyOfferOperationResource::wrap(
            Response::fake('manage_buy_offer_operation')->getBody()
        );

        $this->assertEquals(
            'GDUKMGUGDZQK6YHYA5Z6AY2G4XDSZPSZ3SW5UN3ARVMO6QSRDWP5YLEX',
            $operation->getBuyingAssetIssuerAddress()
        );
    }

    /**
     * @test
     * @covers ::getBuyingAssetCode
     */
    public function it_returns_the_buying_asset_code()
    {
        $operation = ManageBuyOfferOperationResource::wrap(
            Response::fake('manage_buy_offer_operation')->getBody()
        );

        $this->assertEquals('USD', $operation->getBuyingAssetCode());
    }

    /**
     * @test
     * @covers ::getSellingAssetType
     */
    public function it_returns_the_selling_asset_type()
    {
        $operation = ManageBuyOfferOperationResource::wrap(
            Response::fake('manage_buy_offer_operation')->getBody()
        );

        $this->assertEquals('credit_alphanum4', $operation->getSellingAssetType());
    }

    /**
     * @test
     * @covers ::getSellingAssetIssuerAddress
     */
    public function it_returns_the_selling_asset_issuer_address()
    {
        $operation = ManageBuyOfferOperationResource::wrap(
            Response::fake('manage_buy_offer_operation')->getBody()
        );

        $this->assertEquals(
            'GAP5LETOV6YIE62YAM56STDANPRDO7ZFDBGSNHJQIYGGKSMOZAHOOS2S',
            $operation->getSellingAssetIssuerAddress()
        );
    }

    /**
     * @test
     * @covers ::getSellingAssetCode
     */
    public function it_returns_the_selling_asset_code()
    {
        $operation = ManageBuyOfferOperationResource::wrap(
            Response::fake('manage_buy_offer_operation')->getBody()
        );

        $this->assertEquals('EURT', $operation->getSellingAssetCode());
    }

    /**
     * @test
     * @covers ::getOfferId
     */
    public function it_returns_the_offer_id()
    {
        $operation = ManageBuyOfferOperationResource::wrap(
            Response::fake('manage_buy_offer_operation')->getBody()
        );

        $this->assertEquals('0', $operation->getOfferId());
    }
}

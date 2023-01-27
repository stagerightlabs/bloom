<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\PathPaymentStrictReceiveOperationResource;
use StageRightLabs\Bloom\Horizon\PaymentPathResource;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\PathPaymentStrictReceiveOperationResource
 */
class PathPaymentStrictReceiveOperationResourceTest extends TestCase
{
    /**
     * @test
     * @covers ::getAssetType
     */
    public function it_returns_the_asset_type()
    {
        $operation = PathPaymentStrictReceiveOperationResource::wrap(
            Response::fake('path_payment_strict_receive_operation')->getBody()
        );

        $this->assertEquals(
            'credit_alphanum4',
            $operation->getAssetType()
        );
    }

    /**
     * @test
     * @covers ::getAssetCode
     */
    public function it_returns_the_asset_code()
    {
        $operation = PathPaymentStrictReceiveOperationResource::wrap(
            Response::fake('path_payment_strict_receive_operation')->getBody()
        );

        $this->assertEquals(
            'BRL',
            $operation->getAssetCode()
        );
    }

    /**
     * @test
     * @covers ::getAssetIssuerAddress
     */
    public function it_returns_the_asset_issuer_address()
    {
        $operation = PathPaymentStrictReceiveOperationResource::wrap(
            Response::fake('path_payment_strict_receive_operation')->getBody()
        );

        $this->assertEquals(
            'GDVKY2GU2DRXWTBEYJJWSFXIGBZV6AZNBVVSUHEPZI54LIS6BA7DVVSP',
            $operation->getAssetIssuerAddress()
        );
    }

    /**
     * @test
     * @covers ::getFromAddress
     */
    public function it_returns_the_from_address()
    {
        $operation = PathPaymentStrictReceiveOperationResource::wrap(
            Response::fake('path_payment_strict_receive_operation')->getBody()
        );

        $this->assertEquals(
            'GBZH7S5NC57XNHKHJ75C5DGMI3SP6ZFJLIKW74K6OSMA5E5DFMYBDD2Z',
            $operation->getFromAddress()
        );
    }

    /**
     * @test
     * @covers ::getToAddress
     */
    public function it_returns_the_to_address()
    {
        $operation = PathPaymentStrictReceiveOperationResource::wrap(
            Response::fake('path_payment_strict_receive_operation')->getBody()
        );

        $this->assertEquals(
            'GBZH7S5NC57XNHKHJ75C5DGMI3SP6ZFJLIKW74K6OSMA5E5DFMYBDD2Z',
            $operation->getToAddress()
        );
    }

    /**
     * @test
     * @covers ::getAmount
     */
    public function it_returns_the_amount()
    {
        $operation = PathPaymentStrictReceiveOperationResource::wrap(
            Response::fake('path_payment_strict_receive_operation')->getBody()
        );

        $this->assertEquals('0.1000000', $operation->getAmount()->toNativeString());
        $this->assertNull((new PathPaymentStrictReceiveOperationResource())->getAmount());
    }

    /**
     * @test
     * @covers ::getPath
     */
    public function it_returns_the_payment_path()
    {
        $operation = PathPaymentStrictReceiveOperationResource::wrap(
            Response::fake('path_payment_strict_receive_operation')->getBody()
        );

        $path = $operation->getPath();
        $this->assertIsArray($path);
        foreach ($path as $hop) {
            $this->assertInstanceOf(PaymentPathResource::class, $hop);
        }
        $this->assertNull((new PathPaymentStrictReceiveOperationResource())->getPath());
    }

    /**
     * @test
     * @covers ::getSourceAmount
     */
    public function it_returns_the_source_amount()
    {
        $operation = PathPaymentStrictReceiveOperationResource::wrap(
            Response::fake('path_payment_strict_receive_operation')->getBody()
        );

        $this->assertEquals('0.0198773', $operation->getSourceAmount()->toNativeString());
        $this->assertNull((new PathPaymentStrictReceiveOperationResource())->getSourceAmount());
    }

    /**
     * @test
     * @covers ::getSourceMaximum
     */
    public function it_returns_the_source_maximum()
    {
        $operation = PathPaymentStrictReceiveOperationResource::wrap(
            Response::fake('path_payment_strict_receive_operation')->getBody()
        );

        $this->assertEquals('0.0198774', $operation->getSourceMaximum()->toNativeString());
        $this->assertNull((new PathPaymentStrictReceiveOperationResource())->getSourceMaximum());
    }

    /**
     * @test
     * @covers ::getSourceAssetType
     */
    public function it_returns_the_source_asset_type()
    {
        $operation = PathPaymentStrictReceiveOperationResource::wrap(
            Response::fake('path_payment_strict_receive_operation')->getBody()
        );

        $this->assertEquals('credit_alphanum4', $operation->getSourceAssetType());
    }

    /**
     * @test
     * @covers ::getSourceAssetCode
     */
    public function it_returns_the_source_asset_code()
    {
        $operation = PathPaymentStrictReceiveOperationResource::wrap(
            Response::fake('path_payment_strict_receive_operation')->getBody()
        );

        $this->assertEquals('USD', $operation->getSourceAssetCode());
    }

    /**
     * @test
     * @covers ::getSourceAssetIssuerAddress
     */
    public function it_returns_the_source_asset_issuer_address()
    {
        $operation = PathPaymentStrictReceiveOperationResource::wrap(
            Response::fake('path_payment_strict_receive_operation')->getBody()
        );

        $this->assertEquals(
            'GDUKMGUGDZQK6YHYA5Z6AY2G4XDSZPSZ3SW5UN3ARVMO6QSRDWP5YLEX',
            $operation->getSourceAssetIssuerAddress()
        );
    }
}

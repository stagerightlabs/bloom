<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\ChangeTrustOperationResource;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\ChangeTrustOperationResource
 */
class ChangeTrustOperationResourceTest extends TestCase
{
    /**
     * @test
     * @covers ::getAssetType
     */
    public function it_returns_the_asset_type()
    {
        $operation = ChangeTrustOperationResource::wrap(
            Response::fake('change_trust_operation')->getBody()
        );

        $this->assertEquals('credit_alphanum4', $operation->getAssetType());
    }

    /**
     * @test
     * @covers ::getAssetCode
     */
    public function it_returns_the_asset_code()
    {
        $operation = ChangeTrustOperationResource::wrap(
            Response::fake('change_trust_operation')->getBody()
        );

        $this->assertEquals('NGNT', $operation->getAssetCode());
    }

    /**
     * @test
     * @covers ::getAssetIssuerAddress
     */
    public function it_returns_the_asset_issuer_address()
    {
        $operation = ChangeTrustOperationResource::wrap(
            Response::fake('change_trust_operation')->getBody()
        );

        $this->assertEquals(
            'GAWODAROMJ33V5YDFY3NPYTHVYQG7MJXVJ2ND3AOGIHYRWINES6ACCPD',
            $operation->getAssetIssuerAddress()
        );
    }

    /**
     * @test
     * @covers ::getLimit
     */
    public function it_returns_the_limit()
    {
        $operation = ChangeTrustOperationResource::wrap(
            Response::fake('change_trust_operation')->getBody()
        );

        $this->assertEquals(
            '922337203685.4775807',
            $operation->getLimit()->toNativeString()
        );
        $this->assertNull((new ChangeTrustOperationResource())->getLimit());
    }

    /**
     * @test
     * @covers ::getTrusteeAddress
     */
    public function it_returns_the_trustee_address()
    {
        $operation = ChangeTrustOperationResource::wrap(
            Response::fake('change_trust_operation')->getBody()
        );

        $this->assertEquals(
            'GAWODAROMJ33V5YDFY3NPYTHVYQG7MJXVJ2ND3AOGIHYRWINES6ACCPD',
            $operation->getTrusteeAddress()
        );
    }

    /**
     * @test
     * @covers ::getTrustorAddress
     */
    public function it_returns_the_trustor_address()
    {
        $operation = ChangeTrustOperationResource::wrap(
            Response::fake('change_trust_operation')->getBody()
        );

        $this->assertEquals(
            'GAYOLLLUIZE4DZMBB2ZBKGBUBZLIOYU6XFLW37GBP2VZD3ABNXCW4BVA',
            $operation->getTrustorAddress()
        );
    }

    /**
     * @test
     * @covers ::getLiquidityPoolId
     */
    public function it_returns_the_liquidity_pool_id()
    {
        $operation = ChangeTrustOperationResource::wrap(
            Response::fake('change_trust_operation')->getBody()
        );

        $this->assertEquals(
            'liquidity_pool_id',
            $operation->getLiquidityPoolId()
        );
    }
}

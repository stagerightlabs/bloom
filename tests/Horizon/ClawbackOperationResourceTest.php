<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\ClawbackOperationResource;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\ClawbackOperationResource
 */
class ClawbackOperationResourceTest extends TestCase
{
    /**
     * @test
     * @covers ::getAssetType
     */
    public function it_returns_the_asset_type()
    {
        $operation = ClawbackOperationResource::wrap(
            Response::fake('clawback_operation')->getBody()
        );

        $this->assertEquals('credit_alphanum4', $operation->getAssetType());
    }

    /**
     * @test
     * @covers ::getAssetCode
     */
    public function it_returns_the_asset_code()
    {
        $operation = ClawbackOperationResource::wrap(
            Response::fake('clawback_operation')->getBody()
        );

        $this->assertEquals('NGNT', $operation->getAssetCode());
    }

    /**
     * @test
     * @covers ::getAssetIssuerAddress
     */
    public function it_returns_the_asset_issuer_address()
    {
        $operation = ClawbackOperationResource::wrap(
            Response::fake('clawback_operation')->getBody()
        );

        $this->assertEquals(
            'GAWODAROMJ33V5YDFY3NPYTHVYQG7MJXVJ2ND3AOGIHYRWINES6ACCPD',
            $operation->getAssetIssuerAddress()
        );
    }

    /**
     * @test
     * @covers ::getFromAddress
     */
    public function it_returns_the_from_address()
    {
        $operation = ClawbackOperationResource::wrap(
            Response::fake('clawback_operation')->getBody()
        );

        $this->assertEquals(
            'GCAXBKU3AKYJPLQ6PEJ6L47KOATCYCBJ2NFRGAK7FUUA2DCEUC265SU2',
            $operation->getFromAddress()
        );
    }

    /**
     * @test
     * @covers ::getAmount
     */
    public function it_returns_the_amount()
    {
        $operation = ClawbackOperationResource::wrap(
            Response::fake('clawback_operation')->getBody()
        );

        $this->assertEquals('5.0000000', $operation->getAmount()->toNativeString());
        $this->assertNull((new ClawbackOperationResource())->getAmount());
    }
}

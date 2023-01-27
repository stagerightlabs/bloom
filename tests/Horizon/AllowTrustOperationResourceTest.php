<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\AllowTrustOperationResource;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\AllowTrustOperationResource
 */
class AllowTrustOperationResourceTest extends TestCase
{
    /**
     * @test
     * @covers ::getAssetType
     */
    public function it_returns_the_asset_type()
    {
        $operation = AllowTrustOperationResource::wrap(
            Response::fake('allow_trust_operation')->getBody()
        );

        $this->assertEquals('credit_alphanum4', $operation->getAssetType());
    }

    /**
     * @test
     * @covers ::getAssetCode
     */
    public function it_returns_the_asset_code()
    {
        $operation = AllowTrustOperationResource::wrap(
            Response::fake('allow_trust_operation')->getBody()
        );

        $this->assertEquals('LSV1', $operation->getAssetCode());
    }

    /**
     * @test
     * @covers ::getAssetIssuerAddress
     */
    public function it_returns_the_asset_issuer_address()
    {
        $operation = AllowTrustOperationResource::wrap(
            Response::fake('allow_trust_operation')->getBody()
        );

        $this->assertEquals(
            'GCRZQVBBDAWVOCO5R2NI34YR55RO2GQXPTDUE5OZESXGZRRTAEQLKEKN',
            $operation->getAssetIssuerAddress()
        );
    }

    /**
     * @test
     * @covers ::getAuthorizedFlag
     */
    public function it_returns_the_authorized_flag()
    {
        $operation = AllowTrustOperationResource::wrap(
            Response::fake('allow_trust_operation')->getBody()
        );

        $this->assertEquals(1, $operation->getAuthorizedFlag());
    }

    /**
     * @test
     * @covers ::getTrusteeAddress
     */
    public function it_returns_the_trustee_address()
    {
        $operation = AllowTrustOperationResource::wrap(
            Response::fake('allow_trust_operation')->getBody()
        );

        $this->assertEquals(
            'GCRZQVBBDAWVOCO5R2NI34YR55RO2GQXPTDUE5OZESXGZRRTAEQLKEKN',
            $operation->getTrusteeAddress()
        );
    }

    /**
     * @test
     * @covers ::getTrustorAddress
     */
    public function it_returns_the_trustor_address()
    {
        $operation = AllowTrustOperationResource::wrap(
            Response::fake('allow_trust_operation')->getBody()
        );

        $this->assertEquals(
            'GDSYBYRG6NIBJWR7BLY72HYV7VM4A7WWHUJ45FI7H4Q2U2RPR3BB3CFR',
            $operation->getTrustorAddress()
        );
    }
}

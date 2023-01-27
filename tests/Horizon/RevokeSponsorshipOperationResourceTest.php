<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Horizon\RevokeSponsorshipOperationResource;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\RevokeSponsorshipOperationResource
 */
class RevokeSponsorshipOperationResourceTest extends TestCase
{
    /**
     * @test
     * @covers ::getAccountAddress
     */
    public function it_returns_the_account_address()
    {
        $operation = RevokeSponsorshipOperationResource::wrap(
            Response::fake('revoke_sponsorship_operation')->getBody()
        );

        $this->assertEquals(
            'GAYOLLLUIZE4DZMBB2ZBKGBUBZLIOYU6XFLW37GBP2VZD3ABNXCW4BVA',
            $operation->getAccountAddress()
        );
    }

    /**
     * @test
     * @covers ::getClaimableBalanceId
     */
    public function it_returns_the_claimable_balance_id()
    {
        $operation = RevokeSponsorshipOperationResource::wrap(
            Response::fake('revoke_sponsorship_operation')->getBody()
        );

        $this->assertEquals(
            'claimable_balance_id',
            $operation->getClaimableBalanceId()
        );
    }

    /**
     * @test
     * @covers ::getDataAccountAddress
     */
    public function it_returns_the_data_account_address()
    {
        $operation = RevokeSponsorshipOperationResource::wrap(
            Response::fake('revoke_sponsorship_operation')->getBody()
        );

        $this->assertEquals('data_account_id', $operation->getDataAccountAddress());
    }

    /**
     * @test
     * @covers ::getDataName
     */
    public function it_returns_the_data_name()
    {
        $operation = RevokeSponsorshipOperationResource::wrap(
            Response::fake('revoke_sponsorship_operation')->getBody()
        );

        $this->assertEquals('data_name', $operation->getDataName());
    }

    /**
     * @test
     * @covers ::getOfferId
     */
    public function it_returns_the_offer_id()
    {
        $operation = RevokeSponsorshipOperationResource::wrap(
            Response::fake('revoke_sponsorship_operation')->getBody()
        );

        $this->assertEquals('offer_id', $operation->getOfferId());
    }

    /**
     * @test
     * @covers ::getTrustlineAccountAddress
     */
    public function it_returns_the_trustline_account_address()
    {
        $operation = RevokeSponsorshipOperationResource::wrap(
            Response::fake('revoke_sponsorship_operation')->getBody()
        );

        $this->assertEquals('trustline_account_id', $operation->getTrustlineAccountAddress());
    }

    /**
     * @test
     * @covers ::getTrustlineAsset
     */
    public function it_returns_the_trustline_asset()
    {
        $operation = RevokeSponsorshipOperationResource::wrap(
            Response::fake('revoke_sponsorship_operation')->getBody()
        );

        $this->assertEquals('trustline_asset', $operation->getTrustlineAsset());
    }

    /**
     * @test
     * @covers ::getSignerAccountAddress
     */
    public function it_returns_the_signer_account_address()
    {
        $operation = RevokeSponsorshipOperationResource::wrap(
            Response::fake('revoke_sponsorship_operation')->getBody()
        );

        $this->assertEquals('signer_account_id', $operation->getSignerAccountAddress());
    }

    /**
     * @test
     * @covers ::getSignerKey
     */
    public function it_returns_the_signer_key()
    {
        $operation = RevokeSponsorshipOperationResource::wrap(
            Response::fake('revoke_sponsorship_operation')->getBody()
        );

        $this->assertEquals('signer_key', $operation->getSignerKey());
    }
}

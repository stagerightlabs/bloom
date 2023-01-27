<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Horizon\SetTrustLineFlagsOperationResource;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\SetTrustLineFlagsOperationResource
 */
class SetTrustLineFlagsOperationResourceTest extends TestCase
{
    /**
     * @test
     * @covers ::getAssetType
     */
    public function it_returns_the_asset_type()
    {
        $operation = SetTrustLineFlagsOperationResource::wrap(
            Response::fake('set_trustline_flags_operation')->getBody()
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
        $operation = SetTrustLineFlagsOperationResource::wrap(
            Response::fake('set_trustline_flags_operation')->getBody()
        );

        $this->assertEquals(
            'NGNT',
            $operation->getAssetCode()
        );
    }

    /**
     * @test
     * @covers ::getAssetIssuerAddress
     */
    public function it_returns_the_asset_issuer_address()
    {
        $operation = SetTrustLineFlagsOperationResource::wrap(
            Response::fake('set_trustline_flags_operation')->getBody()
        );

        $this->assertEquals(
            'GAWODAROMJ33V5YDFY3NPYTHVYQG7MJXVJ2ND3AOGIHYRWINES6ACCPD',
            $operation->getAssetIssuerAddress()
        );
    }

    /**
     * @test
     * @covers ::getTrustorAddress
     */
    public function it_returns_the_trustor_address()
    {
        $operation = SetTrustLineFlagsOperationResource::wrap(
            Response::fake('set_trustline_flags_operation')->getBody()
        );

        $this->assertEquals(
            'GDSYBYRG6NIBJWR7BLY72HYV7VM4A7WWHUJ45FI7H4Q2U2RPR3BB3CFR',
            $operation->getTrustorAddress()
        );
    }

    /**
     * @test
     * @covers ::getSetFlags
     */
    public function it_returns_the_set_flags_as_integers()
    {
        $operation = SetTrustLineFlagsOperationResource::wrap(
            Response::fake('set_trustline_flags_operation')->getBody()
        );

        $this->assertEquals([1, 2, 4], $operation->getSetFlags());
    }

    /**
     * @test
     * @covers ::getSetFlagsStrings
     */
    public function it_returns_the_set_flags_as_strings()
    {
        $operation = SetTrustLineFlagsOperationResource::wrap(
            Response::fake('set_trustline_flags_operation')->getBody()
        );
        $expected = [
            'AUTHORIZED_FLAG',
            'AUTHORIZED_TO_MAINTAIN_LIABILITIES_FLAG',
            'TRUSTLINE_CLAWBACK_ENABLED_FLAG'
        ];

        $this->assertEquals($expected, $operation->getSetFlagsStrings());
    }

    /**
     * @test
     * @covers ::getClearFlags
     */
    public function it_returns_the_clear_flags_as_integers()
    {
        $operation = SetTrustLineFlagsOperationResource::wrap(
            Response::fake('set_trustline_flags_operation')->getBody()
        );

        $this->assertEquals([1, 2, 4], $operation->getClearFlags());
    }

    /**
     * @test
     * @covers ::getClearFlagsStrings
     */
    public function it_returns_the_clear_flags_as_strings()
    {
        $operation = SetTrustLineFlagsOperationResource::wrap(
            Response::fake('set_trustline_flags_operation')->getBody()
        );
        $expected = [
            'AUTHORIZED_FLAG',
            'AUTHORIZED_TO_MAINTAIN_LIABILITIES_FLAG',
            'TRUSTLINE_CLAWBACK_ENABLED_FLAG'
        ];

        $this->assertEquals($expected, $operation->getClearFlagsStrings());
    }
}

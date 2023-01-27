<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\PaymentPathResource;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\PaymentPathResource
 */
class PaymentPathResourceTest extends TestCase
{
    public const EXAMPLE = [
        'asset_type'   => 'credit_alphanum4',
        'asset_code'   => 'USD',
        'asset_issuer' => 'GBUYUAI75XXWDZEKLY66CFYKQPET5JR4EENXZBUZ3YXZ7DS56Z4OKOFU',
    ];

    /**
     * @test
     * @covers ::getAssetType
     */
    public function it_returns_the_asset_type()
    {
        $resource = PaymentPathResource::wrap(self::EXAMPLE);
        $this->assertEquals('credit_alphanum4', $resource->getAssetType());
    }

    /**
     * @test
     * @covers ::getAssetCode
     */
    public function it_returns_the_asset_code()
    {
        $resource = PaymentPathResource::wrap(self::EXAMPLE);
        $this->assertEquals('USD', $resource->getAssetCode());
    }

    /**
     * @test
     * @covers ::getAssetIssuerAddress
     */
    public function it_returns_the_asset_issuer_address()
    {
        $resource = PaymentPathResource::wrap(self::EXAMPLE);
        $this->assertEquals(
            'GBUYUAI75XXWDZEKLY66CFYKQPET5JR4EENXZBUZ3YXZ7DS56Z4OKOFU',
            $resource->getAssetIssuerAddress()
        );
    }
}

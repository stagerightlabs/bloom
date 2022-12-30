<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\AccountBalanceResource;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\AccountBalanceResource
 */
class AccountBalanceResourceTest extends TestCase
{
    public const XLM_BALANCE_EXAMPLE = [
        'balance'             => '198.8944970',
        'buying_liabilities'  => '0.0000000',
        'selling_liabilities' => '0.0000000',
        'asset_type'          => 'native',
    ];

    public const PHP_BALANCE_EXAMPLE = [
        'balance'              => '0.0000000',
        'limit'                => '12345678.0000000',
        'buying_liabilities'   => '100.0000000',
        'selling_liabilities'  => '200.0000000',
        'last_modified_ledger' => 7877447,
        'is_authorized'        => false,
        'is_clawback_enabled'  => false,
        'asset_type'           => 'credit_alphanum4',
        'asset_code'           => 'PHP',
        'asset_issuer'         => 'GBUQWP3BOUZX34TOND2QV7QQ7K7VJTG6VSE7WMLBTMDJLLAW7YKGU6EP',
        'sponsor'              => 'GBUQWP3BOUZX34TOND2QV7QQ7K7VJTG6VSE7WMLBTMDJLLAW7YKGU6EP',
    ];

    /**
     * @test
     * @covers ::getBalance
     */
    public function it_returns_the_balance_as_a_string()
    {
        $resource = AccountBalanceResource::fromArray(self::XLM_BALANCE_EXAMPLE);
        $this->assertEquals('198.8944970', $resource->getBalance());
    }

    /**
     * @test
     * @covers ::getDescaledBalance
     */
    public function it_returns_the_balance_as_an_int64()
    {
        $resource = AccountBalanceResource::fromArray(self::XLM_BALANCE_EXAMPLE);
        $this->assertInstanceOf(Int64::class, $resource->getDescaledBalance());
        $this->assertTrue($resource->getDescaledBalance()->isEqualTo('1988944970'));
    }

    /**
     * @test
     * @covers ::getBalance
     * @covers ::getDescaledBalance
     */
    public function the_default_balance_is_zero()
    {
        $resource = AccountBalanceResource::fromArray([]);
        $this->assertEquals('0', $resource->getBalance());
        $this->assertTrue($resource->getDescaledBalance()->isEqualTo('0'));
    }

    /**
     * @test
     * @covers ::getBuyingLiabilities
     */
    public function it_returns_the_buying_liabilities_as_a_scaled_amount()
    {
        $resource = AccountBalanceResource::fromArray(self::PHP_BALANCE_EXAMPLE);
        $this->assertInstanceOf(ScaledAmount::class, $resource->getBuyingLiabilities());
        $this->assertEquals('100.0000000', $resource->getBuyingLiabilities()->toNativeString());
    }

    /**
     * @test
     * @covers ::getDescaledBuyingLiabilities
     */
    public function it_returns_the_buying_liabilities_as_an_int64()
    {
        $resource = AccountBalanceResource::fromArray(self::PHP_BALANCE_EXAMPLE);
        $this->assertInstanceOf(Int64::class, $resource->getDescaledBuyingLiabilities());
        $this->assertEquals('1000000000', $resource->getDescaledBuyingLiabilities()->toNativeString());
    }

    /**
     * @test
     * @covers ::getBuyingLiabilities
     * @covers ::getDescaledBuyingLiabilities
     */
    public function the_default_buying_liabilities_value_is_zero()
    {
        $resource = AccountBalanceResource::fromArray([]);
        $this->assertEquals('0', $resource->getBuyingLiabilities());
        $this->assertTrue($resource->getDescaledBuyingLiabilities()->isEqualTo('0'));
    }

    /**
     * @test
     * @covers ::getSellingLiabilities
     */
    public function it_returns_the_selling_liabilities_as_a_scaled_amount()
    {
        $resource = AccountBalanceResource::fromArray(self::PHP_BALANCE_EXAMPLE);
        $this->assertEquals('200.0000000', $resource->getSellingLiabilities());
    }

    /**
     * @test
     * @covers ::getDescaledSellingLiabilities
     */
    public function it_returns_the_descaled_selling_liabilities_as_an_int64()
    {
        $resource = AccountBalanceResource::fromArray(self::PHP_BALANCE_EXAMPLE);
        $this->assertInstanceOf(Int64::class, $resource->getDescaledSellingLiabilities());
        $this->assertEquals('2000000000', $resource->getDescaledSellingLiabilities()->toNativeString());
    }

    /**
     * @test
     * @covers ::getSellingLiabilities
     * @covers ::getDescaledSellingLiabilities
     */
    public function the_default_selling_liabilities_value_is_zero()
    {
        $resource = AccountBalanceResource::fromArray([]);
        $this->assertEquals('0.0000000', $resource->getSellingLiabilities()->toNativeString());
        $this->assertTrue($resource->getDescaledSellingLiabilities()->isEqualTo('0'));
    }

    /**
     * @test
     * @covers ::getLimit
     */
    public function it_returns_the_limit_as_a_scaled_amount()
    {
        $resource = AccountBalanceResource::fromArray(self::PHP_BALANCE_EXAMPLE);
        $this->assertInstanceOf(ScaledAmount::class, $resource->getLimit());
        $this->assertEquals('12345678.0000000', $resource->getLimit()->toNativeString());
    }

    /**
     * @test
     * @covers ::getDescaledLimit
     */
    public function it_returns_the_limit_as_an_int64()
    {
        $resource = AccountBalanceResource::fromArray(self::PHP_BALANCE_EXAMPLE);
        $this->assertInstanceOf(Int64::class, $resource->getDescaledLimit());
        $this->assertEquals('123456780000000', $resource->getDescaledLimit()->toNativeString());
    }

    /**
     * @test
     * @covers ::getLimit
     * @covers ::getDescaledLimit
     */
    public function it_provides_a_default_limit()
    {
        $resource = AccountBalanceResource::fromArray([]);
        $this->assertEquals('922337203685.4775807', $resource->getLimit()->toNativeString());
        $this->assertEquals('9223372036854775807', $resource->getDescaledLimit()->toNativeString());
    }

    /**
     * @test
     * @covers ::getAssetType
     */
    public function it_returns_the_asset_type()
    {
        $resourceA = AccountBalanceResource::fromArray(self::PHP_BALANCE_EXAMPLE);
        $resourceB = AccountBalanceResource::fromArray(self::XLM_BALANCE_EXAMPLE);
        $resourceC = AccountBalanceResource::fromArray([]);

        $this->assertEquals('credit_alphanum4', $resourceA->getAssetType());
        $this->assertEquals('native', $resourceB->getAssetType());
        $this->assertEquals('', $resourceC->getAssetType());
    }

    /**
     * @test
     * @covers ::isNativeAsset
     */
    public function it_knows_if_it_represents_the_native_asset()
    {
        $resourceA = AccountBalanceResource::fromArray(self::PHP_BALANCE_EXAMPLE);
        $resourceB = AccountBalanceResource::fromArray(self::XLM_BALANCE_EXAMPLE);

        $this->assertFalse($resourceA->isNativeAsset());
        $this->assertTrue($resourceB->isNativeAsset());
    }

    /**
     * @test
     * @covers ::getAssetCode
     */
    public function it_returns_the_asset_code_if_present()
    {
        $resourceA = AccountBalanceResource::fromArray(self::PHP_BALANCE_EXAMPLE);
        $resourceB = AccountBalanceResource::fromArray(self::XLM_BALANCE_EXAMPLE);
        $resourceC = AccountBalanceResource::fromArray([]);

        $this->assertEquals('PHP', $resourceA->getAssetCode());
        $this->assertEquals('XLM', $resourceB->getAssetCode());
        $this->assertNull($resourceC->getAssetCode());
    }

    /**
     * @test
     * @covers ::getAssetIssuer
     */
    public function it_returns_the_asset_issuer_if_present()
    {
        $resourceA = AccountBalanceResource::fromArray(self::PHP_BALANCE_EXAMPLE);
        $resourceB = AccountBalanceResource::fromArray(self::XLM_BALANCE_EXAMPLE);
        $resourceC = AccountBalanceResource::fromArray([]);

        $this->assertEquals('GBUQWP3BOUZX34TOND2QV7QQ7K7VJTG6VSE7WMLBTMDJLLAW7YKGU6EP', $resourceA->getAssetIssuer());
        $this->assertNull($resourceB->getAssetIssuer());
        $this->assertNull($resourceC->getAssetIssuer());
    }

    /**
     * @test
     * @covers ::getSponsor
     */
    public function it_returns_the_sponsor_if_present()
    {
        $resourceA = AccountBalanceResource::fromArray(self::PHP_BALANCE_EXAMPLE);
        $resourceB = AccountBalanceResource::fromArray(self::XLM_BALANCE_EXAMPLE);
        $resourceC = AccountBalanceResource::fromArray([]);

        $this->assertEquals('GBUQWP3BOUZX34TOND2QV7QQ7K7VJTG6VSE7WMLBTMDJLLAW7YKGU6EP', $resourceA->getSponsor());
        $this->assertNull($resourceB->getSponsor());
        $this->assertNull($resourceC->getSponsor());
    }

    /**
     * @test
     * @covers ::getCanonicalAssetName
     */
    public function it_returns_an_asset_identifier()
    {
        $resourceA = AccountBalanceResource::fromArray(self::PHP_BALANCE_EXAMPLE);
        $resourceB = AccountBalanceResource::fromArray(self::XLM_BALANCE_EXAMPLE);
        $resourceC = AccountBalanceResource::fromArray([]);

        $this->assertEquals('PHP:GBUQWP3BOUZX34TOND2QV7QQ7K7VJTG6VSE7WMLBTMDJLLAW7YKGU6EP', $resourceA->getCanonicalAssetName());
        $this->assertEquals('native', $resourceB->getCanonicalAssetName());
        $this->assertEquals('', $resourceC->getCanonicalAssetName());
    }
}

<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\TrustLineAsset;
use StageRightLabs\Bloom\ClaimableBalance\ClaimableBalanceId;
use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Ledger\LedgerEntryType;
use StageRightLabs\Bloom\Ledger\LedgerKey;
use StageRightLabs\Bloom\Ledger\LedgerKeyAccount;
use StageRightLabs\Bloom\Ledger\LedgerKeyClaimableBalance;
use StageRightLabs\Bloom\Ledger\LedgerKeyData;
use StageRightLabs\Bloom\Ledger\LedgerKeyLiquidityPool;
use StageRightLabs\Bloom\Ledger\LedgerKeyOffer;
use StageRightLabs\Bloom\Ledger\LedgerKeyTrustLine;
use StageRightLabs\Bloom\LiquidityPool\PoolId;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\String64;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LedgerKey
 */
class LedgerKeyTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(LedgerEntryType::class, LedgerKey::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            LedgerEntryType::ACCOUNT           => LedgerKeyAccount::class,
            LedgerEntryType::TRUSTLINE         => LedgerKeyTrustLine::class,
            LedgerEntryType::OFFER             => LedgerKeyOffer::class,
            LedgerEntryType::DATA              => LedgerKeyData::class,
            LedgerEntryType::CLAIMABLE_BALANCE => LedgerKeyClaimableBalance::class,
            LedgerEntryType::LIQUIDITY_POOL    => LedgerKeyLiquidityPool::class,
        ];

        $this->assertEquals($expected, LedgerKey::arms());
    }

    /**
     * @test
     * @covers ::wrapLedgerKeyAccount
     * @covers ::unwrap
     */
    public function it_can_wrap_a_ledger_key_account()
    {
        $accountId = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $ledgerKeyAccount = (new LedgerKeyAccount())->withAccountId($accountId);
        $ledgerKey = LedgerKey::wrapLedgerKeyAccount($ledgerKeyAccount);

        $this->assertInstanceOf(LedgerKey::class, $ledgerKey);
        $this->assertInstanceOf(LedgerKeyAccount::class, $ledgerKey->unwrap());
    }

    /**
     * @test
     * @covers ::wrapLedgerKeyTrustLine
     * @covers ::unwrap
     */
    public function it_can_wrap_a_ledger_key_trust_line()
    {
        $ledgerKeyTrustLine = (new LedgerKeyTrustLine())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withAsset(TrustLineAsset::native());
        $ledgerKey = LedgerKey::wrapLedgerKeyTrustLine($ledgerKeyTrustLine);

        $this->assertInstanceOf(LedgerKey::class, $ledgerKey);
        $this->assertInstanceOf(LedgerKeyTrustLine::class, $ledgerKey->unwrap());
    }

    /**
     * @test
     * @covers ::wrapLedgerKeyOffer
     * @covers ::unwrap
     */
    public function it_can_wrap_a_ledger_key_offer()
    {
        $ledgerKeyOffer = (new LedgerKeyOffer())
            ->withSellerId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withOfferId(Int64::of(1));
        $ledgerKey = LedgerKey::wrapLedgerKeyOffer($ledgerKeyOffer);

        $this->assertInstanceOf(LedgerKey::class, $ledgerKey);
        $this->assertInstanceOf(LedgerKeyOffer::class, $ledgerKey->unwrap());
    }

    /**
     * @test
     * @covers ::wrapLedgerKeyData
     * @covers ::unwrap
     */
    public function it_can_wrap_a_ledger_key_data()
    {
        $ledgerKeyData = (new LedgerKeyData())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withDataName(String64::of('example'));
        $ledgerKey = LedgerKey::wrapLedgerKeyData($ledgerKeyData);

        $this->assertInstanceOf(LedgerKey::class, $ledgerKey);
        $this->assertInstanceOf(LedgerKeyData::class, $ledgerKey->unwrap());
    }

    /**
     * @test
     * @covers ::wrapLedgerKeyClaimableBalance
     * @covers ::unwrap
     */
    public function it_can_wrap_a_ledger_key_claimable_balance()
    {
        $ledgerKeyClaimableBalance = (new LedgerKeyClaimableBalance())
            ->withClaimableBalanceId(ClaimableBalanceId::wrapHash(Hash::make('1')));
        $ledgerKey = LedgerKey::wrapLedgerKeyClaimableBalance($ledgerKeyClaimableBalance);

        $this->assertInstanceOf(LedgerKey::class, $ledgerKey);
        $this->assertInstanceOf(LedgerKeyClaimableBalance::class, $ledgerKey->unwrap());
    }

    /**
     * @test
     * @covers ::wrapLedgerKeyLiquidityPool
     * @covers ::unwrap
     */
    public function it_can_wrap_a_ledger_key_liquidity_pool()
    {
        $ledgerKeyLiquidityPool = (new LedgerKeyLiquidityPool())
            ->withLiquidityPoolId(PoolId::make('1'));
        $ledgerKey = LedgerKey::wrapLedgerKeyLiquidityPool($ledgerKeyLiquidityPool);

        $this->assertInstanceOf(LedgerKey::class, $ledgerKey);
        $this->assertInstanceOf(LedgerKeyLiquidityPool::class, $ledgerKey->unwrap());
    }

    /**
     * @test
     * @covers ::account
     */
    public function it_can_be_instantiated_as_an_account_ledger_key()
    {
        $ledgerKey = LedgerKey::account('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');

        $this->assertInstanceOf(LedgerKey::class, $ledgerKey);
        $this->assertInstanceOf(LedgerKeyAccount::class, $ledgerKey->unwrap());
    }

    /**
     * @test
     * @covers ::trustLine
     */
    public function it_can_be_instantiated_as_a_trust_line_ledger_key()
    {
        $ledgerKey = LedgerKey::trustLine(
            accountId: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            asset: 'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
        );

        $this->assertInstanceOf(LedgerKey::class, $ledgerKey);
        $this->assertInstanceOf(LedgerKeyTrustLine::class, $ledgerKey->unwrap());
    }

    /**
     * @test
     * @covers ::offer
     */
    public function it_can_be_instantiated_as_an_offer_ledger_key()
    {
        $ledgerKey = LedgerKey::offer(
            sellerId: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            offerId: 1,
        );

        $this->assertInstanceOf(LedgerKey::class, $ledgerKey);
        $this->assertInstanceOf(LedgerKeyOffer::class, $ledgerKey->unwrap());
    }

    /**
     * @test
     * @covers ::data
     */
    public function it_can_be_instantiated_as_a_data_ledger_key()
    {
        $ledgerKey = LedgerKey::data(
            accountId: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            dataName: 'FooBar',
        );

        $this->assertInstanceOf(LedgerKey::class, $ledgerKey);
        $this->assertInstanceOf(LedgerKeyData::class, $ledgerKey->unwrap());
    }

    /**
     * @test
     * @covers ::claimableBalance
     */
    public function it_can_be_instantiated_as_a_claimable_balance_ledger_key()
    {
        $ledgerKey = LedgerKey::claimableBalance(
            balanceId: '0000000017496e54b6e193d074e50c47bad7ec8c411bd06ce984e6a9b4cd109e65569a4e'
        );

        $this->assertInstanceOf(LedgerKey::class, $ledgerKey);
        $this->assertInstanceOf(LedgerKeyClaimableBalance::class, $ledgerKey->unwrap());
    }

    /**
     * @test
     * @covers ::liquidityPool
     */
    public function it_can_be_instantiated_as_a_liquidity_pool_ledger_key()
    {
        $ledgerKey = LedgerKey::liquidityPool(
            poolId: PoolId::make('1')
        );

        $this->assertInstanceOf(LedgerKey::class, $ledgerKey);
        $this->assertInstanceOf(LedgerKeyLiquidityPool::class, $ledgerKey->unwrap());
    }
}

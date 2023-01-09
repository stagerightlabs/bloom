<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Horizon\AccountBalanceResource;
use StageRightLabs\Bloom\Horizon\AccountResource;
use StageRightLabs\Bloom\Horizon\AccountSignerResource;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\SequenceNumber;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\AccountResource
 */
class AccountResourceTest extends TestCase
{
    /**
     * @test
     * @covers ::getLinks
     */
    public function it_returns_the_links_array()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );
        $links = $account->getLinks();
        $expected = [
            'self'         => 'https://horizon-testnet.stellar.org/accounts/[address]',
            'transactions' => 'https://horizon-testnet.stellar.org/accounts/[address]/transactions{?cursor,limit,order}',
            'operations'   => 'https://horizon-testnet.stellar.org/accounts/[address]/operations{?cursor,limit,order}',
            'payments'     => 'https://horizon-testnet.stellar.org/accounts/[address]/payments{?cursor,limit,order}',
            'effects'      => 'https://horizon-testnet.stellar.org/accounts/[address]/effects{?cursor,limit,order}',
            'offers'       => 'https://horizon-testnet.stellar.org/accounts/[address]/offers{?cursor,limit,order}',
            'trades'       => 'https://horizon-testnet.stellar.org/accounts/[address]/trades{?cursor,limit,order}',
            'data'         => 'https://horizon-testnet.stellar.org/accounts/[address]/data/{key}',
        ];

        $this->assertEquals($expected, $links);
    }

    /**
     * @test
     * @covers ::getLink
     */
    public function it_returns_a_single_link()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );

        $this->assertEquals('https://horizon-testnet.stellar.org/accounts/[address]', $account->getLink('self'));
    }

    /**
     * @test
     * @covers ::getSelfLink
     */
    public function it_returns_the_self_link()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );

        $this->assertEquals('https://horizon-testnet.stellar.org/accounts/[address]', $account->getSelfLink());
    }

    /**
     * @test
     * @covers ::getTransactionsLink
     */
    public function it_returns_the_transactions_link()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );

        $this->assertEquals('https://horizon-testnet.stellar.org/accounts/[address]/transactions{?cursor,limit,order}', $account->getTransactionsLink());
    }

    /**
     * @test
     * @covers ::getOperationsLink
     */
    public function it_returns_the_operations_link()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );

        $this->assertEquals('https://horizon-testnet.stellar.org/accounts/[address]/operations{?cursor,limit,order}', $account->getOperationsLink());
    }

    /**
     * @test
     * @covers ::getPaymentsLink
     */
    public function it_returns_the_payments_link()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );

        $this->assertEquals('https://horizon-testnet.stellar.org/accounts/[address]/payments{?cursor,limit,order}', $account->getPaymentsLink());
    }

    /**
     * @test
     * @covers ::getEffectsLink
     */
    public function it_returns_the_effects_link()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );

        $this->assertEquals('https://horizon-testnet.stellar.org/accounts/[address]/effects{?cursor,limit,order}', $account->getEffectsLink());
    }

    /**
     * @test
     * @covers ::getOffersLink
     */
    public function it_returns_the_offers_link()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );

        $this->assertEquals('https://horizon-testnet.stellar.org/accounts/[address]/offers{?cursor,limit,order}', $account->getOffersLink());
    }

    /**
     * @test
     * @covers ::getTradesLink
     */
    public function it_returns_the_trades_link()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );

        $this->assertEquals('https://horizon-testnet.stellar.org/accounts/[address]/trades{?cursor,limit,order}', $account->getTradesLink());
    }

    /**
     * @test
     * @covers ::getDataLink
     */
    public function it_returns_the_data_link()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );
        $this->assertEquals('https://horizon-testnet.stellar.org/accounts/[address]/data/{key}', $account->getDataLink());
    }

    /**
     * @test
     * @covers ::getId
     */
    public function it_returns_the_account_identifier()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );

        $this->assertEquals('[address]', $account->getId());
    }

    /**
     * @test
     * @covers ::getAccountId
     */
    public function it_returns_an_account_id()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account', [
                'address' => 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'
            ])->getBody()
        );

        $this->assertInstanceOf(AccountId::class, $account->getAccountId());
        $this->assertEquals(
            'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            $account->getAccountId()->getAddress()
        );
    }

    /**
     * @test
     * @covers ::getAccountId
     */
    public function it_returns_null_for_missing_account_id()
    {
        $account = AccountResource::wrap(
            Response::fake('account_without_data', [
                'address' => 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'
            ])->getBody()
        );

        $this->assertNull($account->getAccountId());
    }

    /**
     * @test
     * @covers ::getSequenceNumber
     */
    public function it_returns_the_sequence_number()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );

        $this->assertInstanceOf(SequenceNumber::class, $account->getSequenceNumber());
        $this->assertEquals('5607590906036224', $account->getSequenceNumber()->toNativeString());
    }

    /**
     * @test
     * @covers ::getSequenceNumber
     */
    public function it_returns_null_for_a_missing_sequence_number()
    {
        $account = AccountResource::wrap(
            Response::fake('account_without_data')->getBody()
        );

        $this->assertNull($account->getSequenceNumber());
    }

    /**
     * @test
     * @covers ::getSequenceLedger
     */
    public function it_returns_the_sequence_ledger()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );

        $this->assertInstanceOf(UInt32::class, $account->getSequenceLedger());
        $this->assertEquals(50, $account->getSequenceLedger()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getSequenceLedger
     */
    public function it_returns_null_for_a_missing_sequence_ledger()
    {
        $account = AccountResource::wrap(
            Response::fake('account_without_data')->getBody()
        );

        $this->assertNull($account->getSequenceLedger());
    }

    /**
     * @test
     * @covers ::getSequenceTime
     */
    public function it_returns_the_sequence_time()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );

        $this->assertInstanceOf(UInt64::class, $account->getSequenceTime());
        $this->assertEquals('100', $account->getSequenceTime()->toNativeString());
    }

    /**
     * @test
     * @covers ::getSequenceTime
     */
    public function it_returns_null_for_missing_sequence_time()
    {
        $account = AccountResource::wrap(
            Response::fake('account_without_data')->getBody()
        );

        $this->assertNull($account->getSequenceTime());
    }

    /**
     * @test
     * @covers ::getSubEntryCount
     */
    public function it_returns_the_sub_entry_count()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );

        $this->assertInstanceOf(UInt32::class, $account->getSubEntryCount());
        $this->assertEquals(10, $account->getSubEntryCount()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getSubEntryCount
     */
    public function it_returns_null_for_a_missing_sub_entry_count()
    {
        $account = AccountResource::wrap(
            Response::fake('account_without_data')->getBody()
        );

        $this->assertNull($account->getSubEntryCount());
    }

    /**
     * @test
     * @covers ::getHomeDomain
     */
    public function it_returns_the_home_domain()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );

        $this->assertEquals('[domain]', $account->getHomeDomain());
    }

    /**
     * @test
     * @covers ::getHomeDomain
     */
    public function it_returns_null_for_a_missing_home_domain()
    {
        $account = AccountResource::wrap(
            Response::fake('account_without_data')->getBody()
        );

        $this->assertNull($account->getHomeDomain());
    }

    /**
     * @test
     * @covers ::getLastModifiedLedgerSequence
     */
    public function it_returns_the_last_modified_ledger_sequence()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );

        $this->assertInstanceOf(UInt32::class, $account->getLastModifiedLedgerSequence());
        $this->assertEquals(1305619, $account->getLastModifiedLedgerSequence()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getLastModifiedLedgerSequence
     */
    public function it_returns_null_for_missing_last_modified_ledger_sequence()
    {
        $account = AccountResource::wrap(
            Response::fake('account_without_data')->getBody()
        );

        $this->assertNull($account->getLastModifiedLedgerSequence());
    }

    /**
     * @test
     * @covers ::getReservesSponsoringCount
     */
    public function it_returns_the_reserves_sponsoring_count()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );

        $this->assertInstanceOf(UInt32::class, $account->getReservesSponsoringCount());
        $this->assertEquals(1, $account->getReservesSponsoringCount()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getReservesSponsoringCount
     */
    public function it_returns_null_for_missing_reserves_sponsoring_count()
    {
        $account = AccountResource::wrap(
            Response::fake('account_without_data')->getBody()
        );

        $this->assertNull($account->getReservesSponsoringCount());
    }

    /**
     * @test
     * @covers ::getReservesSponsoredCount
     */
    public function it_returns_the_reserves_sponsored_count()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );

        $this->assertInstanceOf(UInt32::class, $account->getReservesSponsoredCount());
        $this->assertEquals(2, $account->getReservesSponsoredCount()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getReservesSponsoredCount
     */
    public function it_returns_null_for_missing_reserves_sponsored_count()
    {
        $account = AccountResource::wrap(
            Response::fake('account_without_data')->getBody()
        );

        $this->assertNull($account->getReservesSponsoredCount());
    }

    /**
     * @test
     * @covers ::getSponsorId
     */
    public function it_returns_the_sponsor_id()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );

        $this->assertEquals('[sponsor]', $account->getSponsorId());
    }

    /**
     * @test
     * @covers ::getSponsorId
     */
    public function it_returns_null_for_missing_sponsor_id()
    {
        $account = AccountResource::wrap(
            Response::fake('account_without_data')->getBody()
        );

        $this->assertNull($account->getSponsorId());
    }

    /**
     * @test
     * @covers ::getLowThreshold
     */
    public function it_returns_the_low_threshold()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );

        $this->assertInstanceOf(UInt32::class, $account->getLowThreshold());
        $this->assertEquals(1, $account->getLowThreshold()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getLowThreshold
     */
    public function it_returns_zero_for_missing_low_threshold()
    {
        $account = AccountResource::wrap(
            Response::fake('account_without_data')->getBody()
        );

        $this->assertEquals(0, $account->getLowThreshold()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getMediumThreshold
     */
    public function it_returns_the_medium_threshold()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );

        $this->assertInstanceOf(UInt32::class, $account->getMediumThreshold());
        $this->assertEquals(2, $account->getMediumThreshold()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getMediumThreshold
     */
    public function it_returns_zero_for_missing_medium_threshold()
    {
        $account = AccountResource::wrap(
            Response::fake('account_without_data')->getBody()
        );

        $this->assertEquals(0, $account->getMediumThreshold()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getHighThreshold
     */
    public function it_returns_the_high_threshold()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );

        $this->assertInstanceOf(UInt32::class, $account->getHighThreshold());
        $this->assertEquals(3, $account->getHighThreshold()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getHighThreshold
     */
    public function it_returns_zero_for_missing_high_threshold()
    {
        $account = AccountResource::wrap(
            Response::fake('account_without_data')->getBody()
        );

        $this->assertEquals(0, $account->getHighThreshold()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getFlags
     */
    public function it_returns_the_flags()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );
        $expected = [
            'auth_required'         => true,
            'auth_revocable'        => true,
            'auth_immutable'        => true,
            'auth_clawback_enabled' => true,
        ];

        $this->assertEquals($expected, $account->getFlags());
    }

    /**
     * @test
     * @covers ::getFlags
     */
    public function it_returns_an_empty_array_for_missing_flags()
    {
        $account = AccountResource::wrap(
            Response::fake('account_without_data')->getBody()
        );

        $this->assertEquals([], $account->getFlags());
    }

    /**
     * @test
     * @covers ::getAuthImmutableFlag
     */
    public function it_returns_the_auth_immutable_flag()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );

        $this->assertTrue($account->getAuthImmutableFlag());
    }

    /**
     * @test
     * @covers ::getAuthImmutableFlag
     */
    public function it_returns_null_for_missing_auth_immutable_flag()
    {
        $account = AccountResource::wrap(
            Response::fake('account_without_data')->getBody()
        );

        $this->assertNull($account->getAuthImmutableFlag());
    }

    /**
     * @test
     * @covers ::getAuthRequiredFlag
     */
    public function it_returns_the_auth_required_flag()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );

        $this->assertTrue($account->getAuthRequiredFlag());
    }

    /**
     * @test
     * @covers ::getAuthRequiredFlag
     */
    public function it_returns_null_for_missing_auth_required_flag()
    {
        $account = AccountResource::wrap(
            Response::fake('account_without_data')->getBody()
        );

        $this->assertNull($account->getAuthRequiredFlag());
    }

    /**
     * @test
     * @covers ::getAuthRevocableFlag
     */
    public function it_returns_the_auth_revocable_flag()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );

        $this->assertTrue($account->getAuthRevocableFlag());
    }

    /**
     * @test
     * @covers ::getAuthRevocableFlag
     */
    public function it_returns_null_for_missing_auth_revocable_flag()
    {
        $account = AccountResource::wrap(
            Response::fake('account_without_data')->getBody()
        );

        $this->assertNull($account->getAuthRevocableFlag());
    }

    /**
     * @test
     * @covers ::getAuthClawbackEnabledFlag
     */
    public function it_returns_the_auth_clawback_enabled_flag()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );

        $this->assertTrue($account->getAuthClawbackEnabledFlag());
    }

    /**
     * @test
     * @covers ::getAuthClawbackEnabledFlag
     */
    public function it_returns_null_for_missing_auth_clawback_enabled_flag()
    {
        $account = AccountResource::wrap(
            Response::fake('account_without_data')->getBody()
        );

        $this->assertNull($account->getAuthClawbackEnabledFlag());
    }

    /**
     * @test
     * @covers ::getBalances
     */
    public function it_returns_the_account_balances()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );
        $expected = [
            'balance'             => '10000.0000000',
            'buying_liabilities'  => '0.0000000',
            'selling_liabilities' => '0.0000000',
            'asset_type'          => 'native',
        ];

        $this->assertInstanceOf(AccountBalanceResource::class, $account->getBalances()[1]);
        $this->assertEquals($expected, $account->getBalances()[1]->toArray());
    }

    /**
     * @test
     * @covers ::getBalances
     */
    public function it_returns_an_empty_array_for_missing_balances()
    {
        $account = AccountResource::wrap(
            Response::fake('account_without_data')->getBody()
        );

        $this->assertEquals([], $account->getBalances());
    }

    /**
     * @test
     * @covers ::getBalanceForAsset
     */
    public function it_returns_a_balance_for_a_given_asset_if_it_is_present()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );
        $balance = $account->getBalanceForAsset('USDC:GBBD47IF6LWK7P7MDEVSCWR7DPUWV3NY3DTQEVFL4NAT4AQH3ZLLFLA5');

        $this->assertInstanceOf(AccountBalanceResource::class, $balance);
        $this->assertEquals('USDC', $balance->getAssetCode());
        $this->assertEquals('GBBD47IF6LWK7P7MDEVSCWR7DPUWV3NY3DTQEVFL4NAT4AQH3ZLLFLA5', $balance->getAssetIssuer());
    }

    /**
     * @test
     * @covers ::getBalanceForAsset
     */
    public function it_returns_a_balance_for_the_native_asset()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );
        $balance = $account->getBalanceForAsset('XLM');

        $this->assertInstanceOf(AccountBalanceResource::class, $balance);
        $this->assertEquals('XLM', $balance->getAssetCode());
        $this->assertNull($balance->getAssetIssuer());
    }

    /**
     * @test
     * @covers ::getBalanceForAsset
     */
    public function it_returns_null_for_a_missing_asset()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );

        $this->assertNull($account->getBalanceForAsset('FooBar:GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));
    }

    /**
     * @test
     * @covers ::getSigners
     */
    public function it_returns_the_signers()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );
        $expected = [
            'weight' => 1,
            'key'    => '[address]',
            'type'   => 'ed25519_public_key',
        ];

        $this->assertInstanceOf(AccountSignerResource::class, $account->getSigners()[0]);
        $this->assertEquals($expected, $account->getSigners()[0]->toArray());
    }

    /**
     * @test
     * @covers ::getSigners
     */
    public function it_returns_an_empty_array_for_missing_signers()
    {
        $account = AccountResource::wrap(
            Response::fake('account_without_data')->getBody()
        );

        $this->assertEquals([], $account->getSigners());
    }

    /**
     * @test
     * @covers ::getData
     */
    public function it_returns_the_data_fields_array()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );
        $expected = [
            'foo' => 'bar',
        ];

        $this->assertEquals($expected, $account->getData());
    }

    /**
     * @test
     * @covers ::getData
     */
    public function it_returns_an_empty_array_for_missing_data_fields()
    {
        $account = AccountResource::wrap(
            Response::fake('account_without_data')->getBody()
        );

        $this->assertEquals([], $account->getData());
    }

    /**
     * @test
     * @covers ::getData
     */
    public function it_can_return_a_specific_data_key_value()
    {
        $account = AccountResource::wrap(
            Response::fake('retrieve_account')->getBody()
        );

        $this->assertEquals('bar', $account->getData('foo'));
    }
}

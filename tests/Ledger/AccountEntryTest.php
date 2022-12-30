<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\SignerList;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\AccountEntry;
use StageRightLabs\Bloom\Ledger\AccountEntryExt;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Primitives\String32;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\SequenceNumber;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\AccountEntry
 */
class AccountEntryTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $accountEntry = (new AccountEntry())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withBalance(Int64::of(100))
            ->withSequenceNumber(SequenceNumber::of(1))
            ->withNumSubEntries(UInt32::of(2))
            ->withFlags(UInt32::of(1))
            ->withHomeDomain(String32::of('example.com'))
            ->withThresholds(Thresholds::of(1, 0, 0, 0))
            ->withSigners(SignerList::empty());
        $buffer = XDR::fresh()->write($accountEntry);

        $this->assertEquals('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAAAAAGQAAAAAAAAAAQAAAAIAAAAAAAAAAQAAAAtleGFtcGxlLmNvbQABAAAAAAAAAAAAAAA=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_account_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $accountEntry = (new AccountEntry())
            ->withBalance(Int64::of(100))
            ->withSequenceNumber(SequenceNumber::of(1))
            ->withNumSubEntries(UInt32::of(2))
            ->withFlags(UInt32::of(1))
            ->withHomeDomain(String32::of('example.com'))
            ->withThresholds(Thresholds::of(1, 0, 0, 0))
            ->withSigners(SignerList::empty());
        XDR::fresh()->write($accountEntry);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_balance_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $accountEntry = (new AccountEntry())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withSequenceNumber(SequenceNumber::of(1))
            ->withNumSubEntries(UInt32::of(2))
            ->withFlags(UInt32::of(1))
            ->withHomeDomain(String32::of('example.com'))
            ->withThresholds(Thresholds::of(1, 0, 0, 0))
            ->withSigners(SignerList::empty());
        XDR::fresh()->write($accountEntry);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_sequence_number_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $accountEntry = (new AccountEntry())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withBalance(Int64::of(100))
            ->withNumSubEntries(UInt32::of(2))
            ->withFlags(UInt32::of(1))
            ->withHomeDomain(String32::of('example.com'))
            ->withThresholds(Thresholds::of(1, 0, 0, 0))
            ->withSigners(SignerList::empty());
        XDR::fresh()->write($accountEntry);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_number_of_sub_entries_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $accountEntry = (new AccountEntry())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withBalance(Int64::of(100))
            ->withSequenceNumber(SequenceNumber::of(1))
            ->withFlags(UInt32::of(1))
            ->withHomeDomain(String32::of('example.com'))
            ->withThresholds(Thresholds::of(1, 0, 0, 0))
            ->withSigners(SignerList::empty());
        XDR::fresh()->write($accountEntry);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function flags_are_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $accountEntry = (new AccountEntry())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withBalance(Int64::of(100))
            ->withSequenceNumber(SequenceNumber::of(1))
            ->withNumSubEntries(UInt32::of(2))
            ->withHomeDomain(String32::of('example.com'))
            ->withThresholds(Thresholds::of(1, 0, 0, 0))
            ->withSigners(SignerList::empty());
        XDR::fresh()->write($accountEntry);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_home_domain_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $accountEntry = (new AccountEntry())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withBalance(Int64::of(100))
            ->withSequenceNumber(SequenceNumber::of(1))
            ->withNumSubEntries(UInt32::of(2))
            ->withFlags(UInt32::of(1))
            ->withThresholds(Thresholds::of(1, 0, 0, 0))
            ->withSigners(SignerList::empty());
        XDR::fresh()->write($accountEntry);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function thresholds_are_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $accountEntry = (new AccountEntry())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withBalance(Int64::of(100))
            ->withSequenceNumber(SequenceNumber::of(1))
            ->withNumSubEntries(UInt32::of(2))
            ->withFlags(UInt32::of(1))
            ->withHomeDomain(String32::of('example.com'))
            ->withSigners(SignerList::empty());
        XDR::fresh()->write($accountEntry);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_list_of_signers_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $accountEntry = (new AccountEntry())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withBalance(Int64::of(100))
            ->withSequenceNumber(SequenceNumber::of(1))
            ->withNumSubEntries(UInt32::of(2))
            ->withFlags(UInt32::of(1))
            ->withHomeDomain(String32::of('example.com'))
            ->withThresholds(Thresholds::of(1, 0, 0, 0));
        XDR::fresh()->write($accountEntry);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $accountEntry = XDR::fromBase64('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAAAAAGQAAAAAAAAAAQAAAAIAAAAAAAAAAQAAAAtleGFtcGxlLmNvbQABAAAAAAAAAAAAAAA=')
            ->read(AccountEntry::class);

        $this->assertInstanceOf(AccountEntry::class, $accountEntry);
        $this->assertInstanceOf(AccountId::class, $accountEntry->getAccountId());
        $this->assertInstanceOf(Int64::class, $accountEntry->getBalance());
        $this->assertInstanceOf(SequenceNumber::class, $accountEntry->getSequenceNumber());
        $this->assertInstanceOf(UInt32::class, $accountEntry->getNumSubEntries());
        $this->assertNull($accountEntry->getInflationDestination());
        $this->assertInstanceOf(UInt32::class, $accountEntry->getFlags());
        $this->assertInstanceOf(String32::class, $accountEntry->getHomeDomain());
        $this->assertInstanceOf(Thresholds::class, $accountEntry->getThresholds());
        $this->assertInstanceOf(SignerList::class, $accountEntry->getSigners());
        $this->assertInstanceOf(AccountEntryExt::class, $accountEntry->getExtension());
    }

    /**
     * @test
     * @covers ::withAccountId
     * @covers ::getAccountId
     */
    public function it_accepts_an_account_id()
    {
        $accountEntry = (new AccountEntry())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(AccountId::class, $accountEntry->getAccountId());
    }

    /**
     * @test
     * @covers ::withAccountId
     * @covers ::getAccountId
     */
    public function it_accepts_a_string_as_an_account_id()
    {
        $accountEntry = (new AccountEntry())
            ->withAccountId('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');

        $this->assertInstanceOf(AccountId::class, $accountEntry->getAccountId());
    }

    /**
     * @test
     * @covers ::withAccountId
     * @covers ::getAccountId
     */
    public function it_accepts_an_addressable_as_an_account_id()
    {
        $accountEntry = (new AccountEntry())
            ->withAccountId(Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(AccountId::class, $accountEntry->getAccountId());
    }

    /**
     * @test
     * @covers ::withBalance
     * @covers ::getBalance
     */
    public function it_accepts_an_int64_balance()
    {
        $accountEntry = (new AccountEntry())->withBalance(Int64::of(100));
        $this->assertInstanceOf(Int64::class, $accountEntry->getBalance());
    }

    /**
     * @test
     * @covers ::withBalance
     * @covers ::getBalance
     */
    public function it_accepts_a_scaled_amount_balance()
    {
        $accountEntry = (new AccountEntry())->withBalance(ScaledAmount::of('0.000100'));
        $this->assertInstanceOf(Int64::class, $accountEntry->getBalance());
    }

    /**
     * @test
     * @covers ::withSequenceNumber
     * @covers ::getSequenceNumber
     */
    public function it_accepts_a_sequence_number()
    {
        $accountEntry = (new AccountEntry())->withSequenceNumber(SequenceNumber::of(1));
        $this->assertInstanceOf(SequenceNumber::class, $accountEntry->getSequenceNumber());
    }

    /**
     * @test
     * @covers ::withNumSubEntries
     * @covers ::getNumSubEntries
     */
    public function it_accepts_a_quantity_of_sub_entries()
    {
        $accountEntry = (new AccountEntry())->withNumSubEntries(UInt32::of(2));
        $this->assertInstanceOf(UInt32::class, $accountEntry->getNumSubEntries());
    }

    /**
     * @test
     * @covers ::withInflationDestination
     * @covers ::getInflationDestination
     */
    public function it_accepts_an_inflation_destination()
    {
        $accountEntry = (new AccountEntry())->withInflationDestination(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));
        $this->assertInstanceOf(AccountId::class, $accountEntry->getInflationDestination());
    }

    /**
     * @test
     * @covers ::withFlags
     * @covers ::getFlags
     */
    public function it_accepts_a_set_of_flags()
    {
        $accountEntry = (new AccountEntry())->withFlags(UInt32::of(1));
        $this->assertInstanceOf(UInt32::class, $accountEntry->getFlags());
    }

    /**
     * @test
     * @covers ::withHomeDomain
     * @covers ::getHomeDomain
     */
    public function it_accepts_a_string32_as_a_home_domain()
    {
        $accountEntry = (new AccountEntry())->withHomeDomain(String32::of('example.com'));
        $this->assertInstanceOf(String32::class, $accountEntry->getHomeDomain());
    }

    /**
     * @test
     * @covers ::withHomeDomain
     * @covers ::getHomeDomain
     */
    public function it_accepts_a_string_as_a_home_domain()
    {
        $accountEntry = (new AccountEntry())->withHomeDomain('example.com');
        $this->assertInstanceOf(String32::class, $accountEntry->getHomeDomain());
    }

    /**
     * @test
     * @covers ::withThresholds
     * @covers ::getThresholds
     */
    public function it_accepts_a_set_of_thresholds()
    {
        $accountEntry = (new AccountEntry())->withThresholds(Thresholds::of(1, 0, 0, 0));
        $this->assertInstanceOf(Thresholds::class, $accountEntry->getThresholds());
    }

    /**
     * @test
     * @covers ::withSigners
     * @covers ::getSigners
     */
    public function it_accepts_a_list_of_signers()
    {
        $accountEntry = (new AccountEntry())->withSigners(SignerList::empty());
        $this->assertInstanceOf(SignerList::class, $accountEntry->getSigners());
    }

    /**
     * @test
     * @covers ::withExtension
     * @covers ::getExtension
     */
    public function it_accepts_an_extension()
    {
        $accountEntry = (new AccountEntry())->withExtension(AccountEntryExt::empty());
        $this->assertInstanceOf(AccountEntryExt::class, $accountEntry->getExtension());
    }
}

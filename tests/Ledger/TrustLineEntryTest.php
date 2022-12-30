<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\TrustLineAsset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\TrustLineEntry;
use StageRightLabs\Bloom\Ledger\TrustLineEntryExt;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\TrustLineEntry
 */
class TrustLineEntryTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $trustLineEntry = (new TrustLineEntry())
            ->withAccountId('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW')
            ->withAsset(TrustLineAsset::native())
            ->withBalance(Int64::of(100))
            ->withFlags(UInt32::of(1));
        $buffer = XDR::fresh()->write($trustLineEntry);

        $this->assertEquals('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAAAAAAAAAABkf/////////8AAAABAAAAAA==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_account_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $trustLineEntry = (new TrustLineEntry())
            ->withAsset(TrustLineAsset::native())
            ->withBalance(Int64::of(100))
            ->withFlags(UInt32::of(1));
        XDR::fresh()->write($trustLineEntry);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_asset_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $trustLineEntry = (new TrustLineEntry())
            ->withAccountId('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW')
            ->withBalance(Int64::of(100))
            ->withFlags(UInt32::of(1));
        XDR::fresh()->write($trustLineEntry);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_balance_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $trustLineEntry = (new TrustLineEntry())
            ->withAccountId('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW')
            ->withAsset(TrustLineAsset::native())
            ->withFlags(UInt32::of(1));
        XDR::fresh()->write($trustLineEntry);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function flags_are_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $trustLineEntry = (new TrustLineEntry())
            ->withAccountId('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW')
            ->withAsset(TrustLineAsset::native())
            ->withBalance(Int64::of(100));
        XDR::fresh()->write($trustLineEntry);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $trustLineEntry = XDR::fromBase64('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAAAAAAAAAABkf/////////8AAAABAAAAAA==')
            ->read(TrustLineEntry::class);

        $this->assertInstanceOf(TrustLineEntry::class, $trustLineEntry);
        $this->assertInstanceOf(AccountId::class, $trustLineEntry->getAccountId());
        $this->assertInstanceOf(TrustLineAsset::class, $trustLineEntry->getAsset());
        $this->assertInstanceOf(Int64::class, $trustLineEntry->getBalance());
        $this->assertInstanceOf(Int64::class, $trustLineEntry->getLimit());
        $this->assertInstanceOf(UInt32::class, $trustLineEntry->getFlags());
        $this->assertInstanceOf(TrustLineEntryExt::class, $trustLineEntry->getExtension());
    }

    /**
     * @test
     * @covers ::withAccountId
     * @covers ::getAccountId
     */
    public function it_accepts_an_account_id()
    {
        $trustLineEntry = (new TrustLineEntry())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(AccountId::class, $trustLineEntry->getAccountId());
    }

    /**
     * @test
     * @covers ::withAccountId
     * @covers ::getAccountId
     */
    public function it_accepts_a_string_as_an_account_id()
    {
        $trustLineEntry = (new TrustLineEntry())
            ->withAccountId('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');

        $this->assertInstanceOf(AccountId::class, $trustLineEntry->getAccountId());
    }

    /**
     * @test
     * @covers ::withAccountId
     * @covers ::getAccountId
     */
    public function it_accepts_an_addressable_as_an_account_id()
    {
        $trustLineEntry = (new TrustLineEntry())
            ->withAccountId(Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(AccountId::class, $trustLineEntry->getAccountId());
    }

    /**
     * @test
     * @covers ::withAsset
     * @covers ::getAsset
     */
    public function it_accepts_an_asset()
    {
        $trustLineEntry = (new TrustLineEntry())
            ->withAsset(TrustLineAsset::native());

        $this->assertInstanceOf(TrustLineAsset::class, $trustLineEntry->getAsset());
    }

    /**
     * @test
     * @covers ::withBalance
     * @covers ::getBalance
     */
    public function it_accepts_an_int64_balance()
    {
        $trustLineEntry = (new TrustLineEntry())
            ->withBalance(Int64::of(100));

        $this->assertInstanceOf(Int64::class, $trustLineEntry->getBalance());
    }

    /**
     * @test
     * @covers ::withBalance
     * @covers ::getBalance
     */
    public function it_accepts_a_scaled_amount_balance()
    {
        $trustLineEntry = (new TrustLineEntry())
            ->withBalance(ScaledAmount::of(100));

        $this->assertInstanceOf(Int64::class, $trustLineEntry->getBalance());
        $this->assertEquals('1000000000', $trustLineEntry->getBalance()->toNativeString());
    }

    /**
     * @test
     * @covers ::withLimit
     * @covers ::getLimit
     */
    public function it_accepts_an_int64_limit()
    {
        $trustLineEntry = (new TrustLineEntry())
            ->withLimit(Int64::of(100));

        $this->assertInstanceOf(Int64::class, $trustLineEntry->getLimit());
    }

    /**
     * @test
     * @covers ::withLimit
     * @covers ::getLimit
     */
    public function it_accepts_a_scaled_amount_limit()
    {
        $trustLineEntry = (new TrustLineEntry())
            ->withLimit(ScaledAmount::of(100));

        $this->assertInstanceOf(Int64::class, $trustLineEntry->getLimit());
        $this->assertEquals('1000000000', $trustLineEntry->getLimit()->toNativeString());
    }

    /**
     * @test
     * @covers ::withFlags
     * @covers ::getFlags
     */
    public function it_accepts_flags()
    {
        $trustLineEntry = (new TrustLineEntry())
            ->withFlags(UInt32::of(1));

        $this->assertInstanceOf(UInt32::class, $trustLineEntry->getFlags());
    }

    /**
     * @test
     * @covers ::withExtension
     * @covers ::getExtension
     */
    public function it_accepts_an_extension()
    {
        $trustLineEntry = (new TrustLineEntry())
            ->withExtension(TrustLineEntryExt::empty());

        $this->assertInstanceOf(TrustLineEntryExt::class, $trustLineEntry->getExtension());
    }
}

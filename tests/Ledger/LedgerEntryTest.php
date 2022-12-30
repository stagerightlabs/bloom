<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\DataEntry;
use StageRightLabs\Bloom\Ledger\LedgerEntry;
use StageRightLabs\Bloom\Ledger\LedgerEntryData;
use StageRightLabs\Bloom\Ledger\LedgerEntryExt;
use StageRightLabs\Bloom\Primitives\DataValue;
use StageRightLabs\Bloom\Primitives\String64;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LedgerEntry
 */
class LedgerEntryTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $dataEntry = (new DataEntry())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withDataName(String64::of('name'))
            ->withDataValue(DataValue::of('value'));
        $ledgerEntryData = LedgerEntryData::wrapDataEntry($dataEntry);
        $ledgerEntry = (new LedgerEntry())
            ->withLastModifiedLedgerSeq(UInt32::of(1))
            ->withData($ledgerEntryData);
        $buffer = XDR::fresh()->write($ledgerEntry);

        $this->assertEquals('AAAAAQAAAAMAAAAAam1BxzlDU4CGaXl40EB4DWHFzms0sXAq4QQodjODne4AAAAEbmFtZQAAAAV2YWx1ZQAAAAAAAAAAAAAA', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_last_modified_sequence_number_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $dataEntry = (new DataEntry())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withDataName(String64::of('name'))
            ->withDataValue(DataValue::of('value'));
        $ledgerEntryData = LedgerEntryData::wrapDataEntry($dataEntry);
        $ledgerEntry = (new LedgerEntry())
            ->withData($ledgerEntryData);
        XDR::fresh()->write($ledgerEntry);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function entry_data_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $ledgerEntry = (new LedgerEntry())
            ->withLastModifiedLedgerSeq(UInt32::of(1));
        XDR::fresh()->write($ledgerEntry);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $ledgerEntry = XDR::fromBase64('AAAAAQAAAAMAAAAAam1BxzlDU4CGaXl40EB4DWHFzms0sXAq4QQodjODne4AAAAEbmFtZQAAAAV2YWx1ZQAAAAAAAAAAAAAA')
            ->read(LedgerEntry::class);

        $this->assertInstanceOf(LedgerEntry::class, $ledgerEntry);
        $this->assertInstanceOf(UInt32::class, $ledgerEntry->getLastModifiedLedgerSeq());
        $this->assertInstanceOf(LedgerEntryData::class, $ledgerEntry->getData());
        $this->assertInstanceOf(LedgerEntryExt::class, $ledgerEntry->getExtension());
    }

    /**
     * @test
     * @covers ::withLastModifiedLedgerSeq
     * @covers ::getLastModifiedLedgerSeq
     */
    public function it_accepts_a_last_modified_ledger_seq()
    {
        $ledgerEntry = (new LedgerEntry())->withLastModifiedLedgerSeq(UInt32::of(1));

        $this->assertInstanceOf(LedgerEntry::class, $ledgerEntry);
        $this->assertInstanceOf(UInt32::class, $ledgerEntry->getLastModifiedLedgerSeq());
    }

    /**
     * @test
     * @covers ::withData
     * @covers ::getData
     */
    public function it_accepts_data()
    {
        $ledgerEntry = (new LedgerEntry())->withData(new LedgerEntryData());

        $this->assertInstanceOf(LedgerEntry::class, $ledgerEntry);
        $this->assertInstanceOf(LedgerEntryData::class, $ledgerEntry->getData());
    }

    /**
     * @test
     * @covers ::withExtension
     * @covers ::getExtension
     */
    public function it_accepts_an_extension()
    {
        $ledgerEntry = (new LedgerEntry())->withExtension(LedgerEntryExt::empty());

        $this->assertInstanceOf(LedgerEntry::class, $ledgerEntry);
        $this->assertInstanceOf(LedgerEntryExt::class, $ledgerEntry->getExtension());
    }
}

<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Envelope\TransactionEnvelopeList;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\LedgerCloseMetaV0;
use StageRightLabs\Bloom\Ledger\LedgerHeader;
use StageRightLabs\Bloom\Ledger\LedgerHeaderHistoryEntry;
use StageRightLabs\Bloom\Ledger\ScpHistoryEntryList;
use StageRightLabs\Bloom\Ledger\SkipList;
use StageRightLabs\Bloom\Ledger\UpgradeEntryMetaList;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\SCP\StellarValue;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\TimePoint;
use StageRightLabs\Bloom\Transaction\TransactionResultMetaList;
use StageRightLabs\Bloom\Transaction\TransactionSet;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LedgerCloseMetaV0
 */
class LedgerCloseMetaV0Test extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $stellarValue = (new StellarValue())
            ->withTxSetHash(Hash::make('1'))
            ->withCloseTime(TimePoint::fromNativeString('2022-01-01'));
        $skipList = SkipList::make([
            Hash::make('1'),
            Hash::make('2'),
            Hash::make('3'),
            Hash::make('4'),
        ]);
        $ledgerHeader = (new LedgerHeader())
            ->withLedgerVersion(UInt32::of(1))
            ->withPreviousLedgerHash(Hash::make('2'))
            ->withSCPValue($stellarValue)
            ->withTxSetResultHash(Hash::make('3'))
            ->withBucketListHash(Hash::make('4'))
            ->withLedgerSeq(UInt32::of(5))
            ->withTotalCoins(Int64::of(6))
            ->withFeePool(Int64::of(7))
            ->withInflationSeq(UInt32::of(8))
            ->withIdPool(UInt64::of(9))
            ->withBaseFee(UInt32::of(10))
            ->withBaseReserve(UInt32::of(11))
            ->withMaxTxSetSize(UInt32::of(12))
            ->withSkipList($skipList);
        $ledgerHeaderHistoryEntry = (new LedgerHeaderHistoryEntry())
            ->withHash(Hash::make('1'))
            ->withHeader($ledgerHeader);
        $transactionSet = (new TransactionSet())
            ->withPreviousLedgerHash(Hash::make('1'))
            ->withTransactions(TransactionEnvelopeList::empty());
        $ledgerCloseMetaV0 = (new LedgerCloseMetaV0())
            ->withLedgerHeader($ledgerHeaderHistoryEntry)
            ->withTransactionSet($transactionSet)
            ->withTransactionProcessing(TransactionResultMetaList::empty());
        $buffer = XDR::fresh()->write($ledgerCloseMetaV0);

        $this->assertEquals(
            'a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAB1HNeOiZeFu7gP1lxi5tdAwGcB9i2xR+Q2jpmbuwTqzVrhrJz/zT84Z1rgE7/Wj9XR62k6qIvHUnAHlLdt4dbSwAAAABhz5mAAAAAAAAAAABOB0CFYr7bi2DOBcHez+OtFrciMJZ94B9kC35HKbSfzksid3fU3R/GHG+IT0hkHQK00SHT/TKMsItVMfys2r+KAAAABQAAAAAAAAAGAAAAAAAAAAcAAAAIAAAAAAAAAAkAAAAKAAAACwAAAAxrhrJz/zT84Z1rgE7/Wj9XR62k6qIvHUnAHlLdt4dbS9RzXjomXhbu4D9ZcYubXQMBnAfYtsUfkNo6Zm7sE6s1TgdAhWK+24tgzgXB3s/jrRa3IjCWfeAfZAt+Rym0n85LInd31N0fxhxviE9IZB0CtNEh0/0yjLCLVTH8rNq/igAAAAAAAAAAa4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAAAAAAAAAAAAAAAAAA',
            $buffer->toBase64()
        );
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_ledger_header_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $transactionSet = (new TransactionSet())
            ->withPreviousLedgerHash(Hash::make('1'))
            ->withTransactions(TransactionEnvelopeList::empty());
        $ledgerCloseMetaV0 = (new LedgerCloseMetaV0())
            ->withTransactionSet($transactionSet)
            ->withTransactionProcessing(TransactionResultMetaList::empty());
        XDR::fresh()->write($ledgerCloseMetaV0);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_transaction_set_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $stellarValue = (new StellarValue())
            ->withTxSetHash(Hash::make('1'))
            ->withCloseTime(TimePoint::fromNativeString('2022-01-01'));
        $skipList = SkipList::make([
            Hash::make('1'),
            Hash::make('2'),
            Hash::make('3'),
            Hash::make('4'),
        ]);
        $ledgerHeader = (new LedgerHeader())
            ->withLedgerVersion(UInt32::of(1))
            ->withPreviousLedgerHash(Hash::make('2'))
            ->withSCPValue($stellarValue)
            ->withTxSetResultHash(Hash::make('3'))
            ->withBucketListHash(Hash::make('4'))
            ->withLedgerSeq(UInt32::of(5))
            ->withTotalCoins(Int64::of(6))
            ->withFeePool(Int64::of(7))
            ->withInflationSeq(UInt32::of(8))
            ->withIdPool(UInt64::of(9))
            ->withBaseFee(UInt32::of(10))
            ->withBaseReserve(UInt32::of(11))
            ->withMaxTxSetSize(UInt32::of(12))
            ->withSkipList($skipList);
        $ledgerHeaderHistoryEntry = (new LedgerHeaderHistoryEntry())
            ->withHash(Hash::make('1'))
            ->withHeader($ledgerHeader);
        $ledgerCloseMetaV0 = (new LedgerCloseMetaV0())
            ->withLedgerHeader($ledgerHeaderHistoryEntry)
            ->withTransactionProcessing(TransactionResultMetaList::empty());
        XDR::fresh()->write($ledgerCloseMetaV0);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_transaction_processing_set_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $stellarValue = (new StellarValue())
            ->withTxSetHash(Hash::make('1'))
            ->withCloseTime(TimePoint::fromNativeString('2022-01-01'));
        $skipList = SkipList::make([
            Hash::make('1'),
            Hash::make('2'),
            Hash::make('3'),
            Hash::make('4'),
        ]);
        $ledgerHeader = (new LedgerHeader())
            ->withLedgerVersion(UInt32::of(1))
            ->withPreviousLedgerHash(Hash::make('2'))
            ->withSCPValue($stellarValue)
            ->withTxSetResultHash(Hash::make('3'))
            ->withBucketListHash(Hash::make('4'))
            ->withLedgerSeq(UInt32::of(5))
            ->withTotalCoins(Int64::of(6))
            ->withFeePool(Int64::of(7))
            ->withInflationSeq(UInt32::of(8))
            ->withIdPool(UInt64::of(9))
            ->withBaseFee(UInt32::of(10))
            ->withBaseReserve(UInt32::of(11))
            ->withMaxTxSetSize(UInt32::of(12))
            ->withSkipList($skipList);
        $ledgerHeaderHistoryEntry = (new LedgerHeaderHistoryEntry())
            ->withHash(Hash::make('1'))
            ->withHeader($ledgerHeader);
        $transactionSet = (new TransactionSet())
            ->withPreviousLedgerHash(Hash::make('1'))
            ->withTransactions(TransactionEnvelopeList::empty());
        $ledgerCloseMetaV0 = (new LedgerCloseMetaV0())
            ->withLedgerHeader($ledgerHeaderHistoryEntry)
            ->withTransactionSet($transactionSet);
        XDR::fresh()->write($ledgerCloseMetaV0);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $ledgerCloseMetaV0 = XDR::fromBase64('a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAB1HNeOiZeFu7gP1lxi5tdAwGcB9i2xR+Q2jpmbuwTqzVrhrJz/zT84Z1rgE7/Wj9XR62k6qIvHUnAHlLdt4dbSwAAAABhz5mAAAAAAAAAAABOB0CFYr7bi2DOBcHez+OtFrciMJZ94B9kC35HKbSfzksid3fU3R/GHG+IT0hkHQK00SHT/TKMsItVMfys2r+KAAAABQAAAAAAAAAGAAAAAAAAAAcAAAAIAAAAAAAAAAkAAAAKAAAACwAAAAxrhrJz/zT84Z1rgE7/Wj9XR62k6qIvHUnAHlLdt4dbS9RzXjomXhbu4D9ZcYubXQMBnAfYtsUfkNo6Zm7sE6s1TgdAhWK+24tgzgXB3s/jrRa3IjCWfeAfZAt+Rym0n85LInd31N0fxhxviE9IZB0CtNEh0/0yjLCLVTH8rNq/igAAAAAAAAAAa4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAAAAAAAAAAAAAAAAAA')
            ->read(LedgerCloseMetaV0::class);

        $this->assertInstanceOf(LedgerCloseMetaV0::class, $ledgerCloseMetaV0);
        $this->assertInstanceOf(LedgerHeaderHistoryEntry::class, $ledgerCloseMetaV0->getLedgerHeader());
        $this->assertInstanceOf(TransactionSet::class, $ledgerCloseMetaV0->getTransactionSet());
        $this->assertInstanceOf(TransactionResultMetaList::class, $ledgerCloseMetaV0->getTransactionProcessing());
        $this->assertInstanceOf(UpgradeEntryMetaList::class, $ledgerCloseMetaV0->getUpgradesProcessing());
        $this->assertInstanceOf(ScpHistoryEntryList::class, $ledgerCloseMetaV0->getScpInfo());
    }

    /**
     * @test
     * @covers ::withLedgerHeader
     * @covers ::getLedgerHeader
     */
    public function it_accepts_a_ledger_header_history_entry()
    {
        $stellarValue = (new StellarValue())
            ->withTxSetHash(Hash::make('1'))
            ->withCloseTime(TimePoint::fromNativeString('2022-01-01'));
        $skipList = SkipList::make([
            Hash::make('1'),
            Hash::make('2'),
            Hash::make('3'),
            Hash::make('4'),
        ]);
        $ledgerHeader = (new LedgerHeader())
            ->withLedgerVersion(UInt32::of(1))
            ->withPreviousLedgerHash(Hash::make('2'))
            ->withSCPValue($stellarValue)
            ->withTxSetResultHash(Hash::make('3'))
            ->withBucketListHash(Hash::make('4'))
            ->withLedgerSeq(UInt32::of(5))
            ->withTotalCoins(Int64::of(6))
            ->withFeePool(Int64::of(7))
            ->withInflationSeq(UInt32::of(8))
            ->withIdPool(UInt64::of(9))
            ->withBaseFee(UInt32::of(10))
            ->withBaseReserve(UInt32::of(11))
            ->withMaxTxSetSize(UInt32::of(12))
            ->withSkipList($skipList);
        $ledgerHeaderHistoryEntry = (new LedgerHeaderHistoryEntry())
            ->withHash(Hash::make('1'))
            ->withHeader($ledgerHeader);
        $ledgerCloseMetaV0 = (new LedgerCloseMetaV0())
            ->withLedgerHeader($ledgerHeaderHistoryEntry);

        $this->assertInstanceOf(LedgerCloseMetaV0::class, $ledgerCloseMetaV0);
        $this->assertInstanceOf(LedgerHeaderHistoryEntry::class, $ledgerCloseMetaV0->getLedgerHeader());
    }

    /**
     * @test
     * @covers ::withTransactionSet
     * @covers ::getTransactionSet
     */
    public function it_accepts_a_transaction_set()
    {
        $transactionSet = (new TransactionSet())
            ->withPreviousLedgerHash(Hash::make('1'))
            ->withTransactions(TransactionEnvelopeList::empty());
        $ledgerCloseMetaV0 = (new LedgerCloseMetaV0())
            ->withTransactionSet($transactionSet);

        $this->assertInstanceOf(LedgerCloseMetaV0::class, $ledgerCloseMetaV0);
        $this->assertInstanceOf(TransactionSet::class, $ledgerCloseMetaV0->getTransactionSet());
    }

    /**
     * @test
     * @covers ::withTransactionProcessing
     * @covers ::getTransactionProcessing
     */
    public function it_accepts_transaction_processing_information()
    {
        $ledgerCloseMetaV0 = (new LedgerCloseMetaV0())
            ->withTransactionProcessing(TransactionResultMetaList::empty());

        $this->assertInstanceOf(LedgerCloseMetaV0::class, $ledgerCloseMetaV0);
        $this->assertInstanceOf(TransactionResultMetaList::class, $ledgerCloseMetaV0->getTransactionProcessing());
    }

    /**
     * @test
     * @covers ::withUpgradesProcessing
     * @covers ::getUpgradesProcessing
     */
    public function it_accepts_upgrades_processing_information()
    {
        $ledgerCloseMetaV0 = (new LedgerCloseMetaV0())
            ->withUpgradesProcessing(UpgradeEntryMetaList::empty());

        $this->assertInstanceOf(LedgerCloseMetaV0::class, $ledgerCloseMetaV0);
        $this->assertInstanceOf(UpgradeEntryMetaList::class, $ledgerCloseMetaV0->getUpgradesProcessing());
    }

    /**
     * @test
     * @covers ::withScpInfo
     * @covers ::getScpInfo
     */
    public function it_accepts_scp_info()
    {
        $ledgerCloseMetaV0 = (new LedgerCloseMetaV0())
            ->withScpInfo(ScpHistoryEntryList::empty());

        $this->assertInstanceOf(LedgerCloseMetaV0::class, $ledgerCloseMetaV0);
        $this->assertInstanceOf(ScpHistoryEntryList::class, $ledgerCloseMetaV0->getScpInfo());
    }
}

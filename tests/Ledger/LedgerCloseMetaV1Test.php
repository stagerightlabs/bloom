<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\LedgerCloseMetaV1;
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
use StageRightLabs\Bloom\Transaction\GeneralizedTransactionSet;
use StageRightLabs\Bloom\Transaction\TimePoint;
use StageRightLabs\Bloom\Transaction\TransactionResultMetaList;
use StageRightLabs\Bloom\Transaction\TransactionSetV1;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LedgerCloseMetaV1
 */
class LedgerCloseMetaV1Test extends TestCase
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
        $transactionSetV1 = (new TransactionSetV1())
            ->withPreviousLedgerHash(Hash::make('1'));
        $generalizedTransactionSet = GeneralizedTransactionSet::wrapTransactionSetV1($transactionSetV1);
        $ledgerCloseMetaV1 = (new LedgerCloseMetaV1())
            ->withLedgerHeader($ledgerHeaderHistoryEntry)
            ->withTransactionSet($generalizedTransactionSet);
        $buffer = XDR::fresh()->write($ledgerCloseMetaV1);

        $this->assertEquals(
            'a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAB1HNeOiZeFu7gP1lxi5tdAwGcB9i2xR+Q2jpmbuwTqzVrhrJz/zT84Z1rgE7/Wj9XR62k6qIvHUnAHlLdt4dbSwAAAABhz5mAAAAAAAAAAABOB0CFYr7bi2DOBcHez+OtFrciMJZ94B9kC35HKbSfzksid3fU3R/GHG+IT0hkHQK00SHT/TKMsItVMfys2r+KAAAABQAAAAAAAAAGAAAAAAAAAAcAAAAIAAAAAAAAAAkAAAAKAAAACwAAAAxrhrJz/zT84Z1rgE7/Wj9XR62k6qIvHUnAHlLdt4dbS9RzXjomXhbu4D9ZcYubXQMBnAfYtsUfkNo6Zm7sE6s1TgdAhWK+24tgzgXB3s/jrRa3IjCWfeAfZAt+Rym0n85LInd31N0fxhxviE9IZB0CtNEh0/0yjLCLVTH8rNq/igAAAAAAAAAAAAAAAWuGsnP/NPzhnWuATv9aP1dHraTqoi8dScAeUt23h1tLAAAAAAAAAAAAAAAAAAAAAA==',
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
        $transactionSetV1 = (new TransactionSetV1())
            ->withPreviousLedgerHash(Hash::make('1'));
        $generalizedTransactionSet = GeneralizedTransactionSet::wrapTransactionSetV1($transactionSetV1);
        $ledgerCloseMetaV1 = (new LedgerCloseMetaV1())
            ->withTransactionSet($generalizedTransactionSet);
        XDR::fresh()->write($ledgerCloseMetaV1);
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
        $ledgerCloseMetaV1 = (new LedgerCloseMetaV1())
            ->withLedgerHeader($ledgerHeaderHistoryEntry);
        XDR::fresh()->write($ledgerCloseMetaV1);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $ledgerCloseMetaV1 = XDR::fromBase64(
            'a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAB1HNeOiZeFu7gP1lxi5tdAwGcB9i2xR+Q2jpmbuwTqzVrhrJz/zT84Z1rgE7/Wj9XR62k6qIvHUnAHlLdt4dbSwAAAABhz5mAAAAAAAAAAABOB0CFYr7bi2DOBcHez+OtFrciMJZ94B9kC35HKbSfzksid3fU3R/GHG+IT0hkHQK00SHT/TKMsItVMfys2r+KAAAABQAAAAAAAAAGAAAAAAAAAAcAAAAIAAAAAAAAAAkAAAAKAAAACwAAAAxrhrJz/zT84Z1rgE7/Wj9XR62k6qIvHUnAHlLdt4dbS9RzXjomXhbu4D9ZcYubXQMBnAfYtsUfkNo6Zm7sE6s1TgdAhWK+24tgzgXB3s/jrRa3IjCWfeAfZAt+Rym0n85LInd31N0fxhxviE9IZB0CtNEh0/0yjLCLVTH8rNq/igAAAAAAAAAAAAAAAWuGsnP/NPzhnWuATv9aP1dHraTqoi8dScAeUt23h1tLAAAAAAAAAAAAAAAAAAAAAA=='
        )->read(LedgerCloseMetaV1::class);

        $this->assertInstanceOf(LedgerCloseMetaV1::class, $ledgerCloseMetaV1);
        $this->assertInstanceOf(LedgerHeaderHistoryEntry::class, $ledgerCloseMetaV1->getLedgerHeader());
        $this->assertInstanceOf(GeneralizedTransactionSet::class, $ledgerCloseMetaV1->getTransactionSet());
        $this->assertInstanceOf(TransactionResultMetaList::class, $ledgerCloseMetaV1->getTransactionsProcessing());
        $this->assertInstanceOf(UpgradeEntryMetaList::class, $ledgerCloseMetaV1->getUpgradesProcessing());
        $this->assertInstanceOf(ScpHistoryEntryList::class, $ledgerCloseMetaV1->getScpInfo());
    }

    /**
     * @test
     * @covers ::withLedgerHeader
     * @covers ::getLedgerHeader
     */
    public function it_accepts_a_ledger_header()
    {
        $ledgerCloseMetaV1 = (new LedgerCloseMetaV1())
            ->withLedgerHeader(new LedgerHeaderHistoryEntry());

        $this->assertInstanceOf(LedgerCloseMetaV1::class, $ledgerCloseMetaV1);
        $this->assertInstanceOf(LedgerHeaderHistoryEntry::class, $ledgerCloseMetaV1->getLedgerHeader());
    }

    /**
     * @test
     * @covers ::withTransactionSet
     * @covers ::getTransactionSet
     */
    public function it_accepts_a_transaction_set()
    {
        $ledgerCloseMetaV1 = (new LedgerCloseMetaV1())
            ->withTransactionSet(new GeneralizedTransactionSet());

        $this->assertInstanceOf(LedgerCloseMetaV1::class, $ledgerCloseMetaV1);
        $this->assertInstanceOf(GeneralizedTransactionSet::class, $ledgerCloseMetaV1->getTransactionSet());
    }

    /**
     * @test
     * @covers ::withTransactionsProcessing
     * @covers ::getTransactionsProcessing
     */
    public function it_accepts_a_list_of_processing_transactions()
    {
        $ledgerCloseMetaV1 = (new LedgerCloseMetaV1())
            ->withTransactionsProcessing(TransactionResultMetaList::empty());

        $this->assertInstanceOf(LedgerCloseMetaV1::class, $ledgerCloseMetaV1);
        $this->assertInstanceOf(TransactionResultMetaList::class, $ledgerCloseMetaV1->getTransactionsProcessing());
    }

    /**
     * @test
     * @covers ::withUpgradesProcessing
     * @covers ::getUpgradesProcessing
     */
    public function it_accepts_a_list_of_processing_upgrades()
    {
        $ledgerCloseMetaV1 = (new LedgerCloseMetaV1())
            ->withUpgradesProcessing(UpgradeEntryMetaList::empty());

        $this->assertInstanceOf(LedgerCloseMetaV1::class, $ledgerCloseMetaV1);
        $this->assertInstanceOf(UpgradeEntryMetaList::class, $ledgerCloseMetaV1->getUpgradesProcessing());
    }

    /**
     * @test
     * @covers ::withScpInfo
     * @covers ::getScpInfo
     */
    public function it_accepts_a_list_of_miscellaneous_scp_information()
    {
        $ledgerCloseMetaV1 = (new LedgerCloseMetaV1())
            ->withScpInfo(ScpHistoryEntryList::empty());

        $this->assertInstanceOf(LedgerCloseMetaV1::class, $ledgerCloseMetaV1);
        $this->assertInstanceOf(ScpHistoryEntryList::class, $ledgerCloseMetaV1->getScpInfo());
    }
}

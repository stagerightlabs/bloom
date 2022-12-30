<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\LedgerHeader;
use StageRightLabs\Bloom\Ledger\LedgerHeaderHistoryEntry;
use StageRightLabs\Bloom\Ledger\LedgerHeaderHistoryEntryExt;
use StageRightLabs\Bloom\Ledger\SkipList;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\SCP\StellarValue;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\TimePoint;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LedgerHeaderHistoryEntry
 */
class LedgerHeaderHistoryEntryTest extends TestCase
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
        $buffer = XDR::fresh()->write($ledgerHeaderHistoryEntry);

        $this->assertEquals(
            'a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAB1HNeOiZeFu7gP1lxi5tdAwGcB9i2xR+Q2jpmbuwTqzVrhrJz/zT84Z1rgE7/Wj9XR62k6qIvHUnAHlLdt4dbSwAAAABhz5mAAAAAAAAAAABOB0CFYr7bi2DOBcHez+OtFrciMJZ94B9kC35HKbSfzksid3fU3R/GHG+IT0hkHQK00SHT/TKMsItVMfys2r+KAAAABQAAAAAAAAAGAAAAAAAAAAcAAAAIAAAAAAAAAAkAAAAKAAAACwAAAAxrhrJz/zT84Z1rgE7/Wj9XR62k6qIvHUnAHlLdt4dbS9RzXjomXhbu4D9ZcYubXQMBnAfYtsUfkNo6Zm7sE6s1TgdAhWK+24tgzgXB3s/jrRa3IjCWfeAfZAt+Rym0n85LInd31N0fxhxviE9IZB0CtNEh0/0yjLCLVTH8rNq/igAAAAAAAAAA',
            $buffer->toBase64()
        );
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_hash_is_required_for_xdr_conversion()
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
            ->withHeader($ledgerHeader);
        XDR::fresh()->write($ledgerHeaderHistoryEntry);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_ledger_header_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $ledgerHeaderHistoryEntry = (new LedgerHeaderHistoryEntry())
            ->withHash(Hash::make('1'));
        XDR::fresh()->write($ledgerHeaderHistoryEntry);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $ledgerHeaderHistoryEntry = XDR::fromBase64('a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAB1HNeOiZeFu7gP1lxi5tdAwGcB9i2xR+Q2jpmbuwTqzVrhrJz/zT84Z1rgE7/Wj9XR62k6qIvHUnAHlLdt4dbSwAAAABhz5mAAAAAAAAAAABOB0CFYr7bi2DOBcHez+OtFrciMJZ94B9kC35HKbSfzksid3fU3R/GHG+IT0hkHQK00SHT/TKMsItVMfys2r+KAAAABQAAAAAAAAAGAAAAAAAAAAcAAAAIAAAAAAAAAAkAAAAKAAAACwAAAAxrhrJz/zT84Z1rgE7/Wj9XR62k6qIvHUnAHlLdt4dbS9RzXjomXhbu4D9ZcYubXQMBnAfYtsUfkNo6Zm7sE6s1TgdAhWK+24tgzgXB3s/jrRa3IjCWfeAfZAt+Rym0n85LInd31N0fxhxviE9IZB0CtNEh0/0yjLCLVTH8rNq/igAAAAAAAAAA')
            ->read(LedgerHeaderHistoryEntry::class);

        $this->assertInstanceOf(LedgerHeaderHistoryEntry::class, $ledgerHeaderHistoryEntry);
        $this->assertInstanceOf(Hash::class, $ledgerHeaderHistoryEntry->getHash());
        $this->assertInstanceOf(LedgerHeader::class, $ledgerHeaderHistoryEntry->getHeader());
        $this->assertInstanceOf(LedgerHeaderHistoryEntryExt::class, $ledgerHeaderHistoryEntry->getExtension());
    }

    /**
     * @test
     * @covers ::withHash
     * @covers ::getHash
     */
    public function it_accepts_a_hash()
    {
        $ledgerHeaderHistoryEntry = (new LedgerHeaderHistoryEntry())
            ->withHash(Hash::make('1'));

        $this->assertInstanceOf(LedgerHeaderHistoryEntry::class, $ledgerHeaderHistoryEntry);
        $this->assertInstanceOf(Hash::class, $ledgerHeaderHistoryEntry->getHash());
    }

    /**
     * @test
     * @covers ::withHeader
     * @covers ::getHeader
     */
    public function it_accepts_a_ledger_header()
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
            ->withHeader($ledgerHeader);

        $this->assertInstanceOf(LedgerHeaderHistoryEntry::class, $ledgerHeaderHistoryEntry);
        $this->assertInstanceOf(LedgerHeader::class, $ledgerHeaderHistoryEntry->getHeader());
    }

    /**
     * @test
     * @covers ::withExtension
     * @covers ::getExtension
     */
    public function it_accepts_an_extension()
    {
        $ledgerHeaderHistoryEntry = (new LedgerHeaderHistoryEntry())
            ->withExtension(LedgerHeaderHistoryEntryExt::empty());

        $this->assertInstanceOf(LedgerHeaderHistoryEntry::class, $ledgerHeaderHistoryEntry);
        $this->assertInstanceOf(LedgerHeaderHistoryEntryExt::class, $ledgerHeaderHistoryEntry->getExtension());
    }
}

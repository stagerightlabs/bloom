<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\LedgerHeader;
use StageRightLabs\Bloom\Ledger\LedgerHeaderExt;
use StageRightLabs\Bloom\Ledger\SkipList;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\SCP\StellarValue;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\TimePoint;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LedgerHeader
 */
class LedgerHeaderTest extends TestCase
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
        $buffer = XDR::fresh()->write($ledgerHeader);

        $this->assertEquals(
            'AAAAAdRzXjomXhbu4D9ZcYubXQMBnAfYtsUfkNo6Zm7sE6s1a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAAYc+ZgAAAAAAAAAAATgdAhWK+24tgzgXB3s/jrRa3IjCWfeAfZAt+Rym0n85LInd31N0fxhxviE9IZB0CtNEh0/0yjLCLVTH8rNq/igAAAAUAAAAAAAAABgAAAAAAAAAHAAAACAAAAAAAAAAJAAAACgAAAAsAAAAMa4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0vUc146Jl4W7uA/WXGLm10DAZwH2LbFH5DaOmZu7BOrNU4HQIVivtuLYM4Fwd7P460WtyIwln3gH2QLfkcptJ/OSyJ3d9TdH8Ycb4hPSGQdArTRIdP9Moywi1Ux/Kzav4oAAAAA',
            $buffer->toBase64()
        );
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_ledger_version_is_required_for_xdr_conversion()
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
        XDR::fresh()->write($ledgerHeader);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_previous_ledger_hash_is_required_for_xdr_conversion()
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
        XDR::fresh()->write($ledgerHeader);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_scp_value_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $skipList = SkipList::make([
            Hash::make('1'),
            Hash::make('2'),
            Hash::make('3'),
            Hash::make('4'),
        ]);
        $ledgerHeader = (new LedgerHeader())
            ->withLedgerVersion(UInt32::of(1))
            ->withPreviousLedgerHash(Hash::make('2'))
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
        XDR::fresh()->write($ledgerHeader);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_tx_set_result_hash_is_required_for_xdr_conversion()
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
        XDR::fresh()->write($ledgerHeader);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_bucket_list_hash_is_required_for_xdr_conversion()
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
            ->withLedgerSeq(UInt32::of(5))
            ->withTotalCoins(Int64::of(6))
            ->withFeePool(Int64::of(7))
            ->withInflationSeq(UInt32::of(8))
            ->withIdPool(UInt64::of(9))
            ->withBaseFee(UInt32::of(10))
            ->withBaseReserve(UInt32::of(11))
            ->withMaxTxSetSize(UInt32::of(12))
            ->withSkipList($skipList);
        XDR::fresh()->write($ledgerHeader);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_ledger_seq_is_required_for_xdr_conversion()
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
            ->withTotalCoins(Int64::of(6))
            ->withFeePool(Int64::of(7))
            ->withInflationSeq(UInt32::of(8))
            ->withIdPool(UInt64::of(9))
            ->withBaseFee(UInt32::of(10))
            ->withBaseReserve(UInt32::of(11))
            ->withMaxTxSetSize(UInt32::of(12))
            ->withSkipList($skipList);
        XDR::fresh()->write($ledgerHeader);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_total_coins_count_is_required_for_xdr_conversion()
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
            ->withFeePool(Int64::of(7))
            ->withInflationSeq(UInt32::of(8))
            ->withIdPool(UInt64::of(9))
            ->withBaseFee(UInt32::of(10))
            ->withBaseReserve(UInt32::of(11))
            ->withMaxTxSetSize(UInt32::of(12))
            ->withSkipList($skipList);
        XDR::fresh()->write($ledgerHeader);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_fee_pool_count_is_required_for_xdr_conversion()
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
            ->withInflationSeq(UInt32::of(8))
            ->withIdPool(UInt64::of(9))
            ->withBaseFee(UInt32::of(10))
            ->withBaseReserve(UInt32::of(11))
            ->withMaxTxSetSize(UInt32::of(12))
            ->withSkipList($skipList);
        XDR::fresh()->write($ledgerHeader);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_inflation_seq_is_required_for_xdr_conversion()
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
            ->withIdPool(UInt64::of(9))
            ->withBaseFee(UInt32::of(10))
            ->withBaseReserve(UInt32::of(11))
            ->withMaxTxSetSize(UInt32::of(12))
            ->withSkipList($skipList);
        XDR::fresh()->write($ledgerHeader);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_id_pool_is_required_for_xdr_conversion()
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
            ->withBaseFee(UInt32::of(10))
            ->withBaseReserve(UInt32::of(11))
            ->withMaxTxSetSize(UInt32::of(12))
            ->withSkipList($skipList);
        XDR::fresh()->write($ledgerHeader);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_base_fee_is_required_for_xdr_conversion()
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
            ->withBaseReserve(UInt32::of(11))
            ->withMaxTxSetSize(UInt32::of(12))
            ->withSkipList($skipList);
        XDR::fresh()->write($ledgerHeader);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_base_reserve_is_required_for_xdr_conversion()
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
            ->withMaxTxSetSize(UInt32::of(12))
            ->withSkipList($skipList);
        XDR::fresh()->write($ledgerHeader);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_max_tx_set_size_is_required_for_xdr_conversion()
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
            ->withSkipList($skipList);
        XDR::fresh()->write($ledgerHeader);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_skip_list_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $stellarValue = (new StellarValue())
            ->withTxSetHash(Hash::make('1'))
            ->withCloseTime(TimePoint::fromNativeString('2022-01-01'));
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
            ->withMaxTxSetSize(UInt32::of(12));
        XDR::fresh()->write($ledgerHeader);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $ledgerHeader = XDR::fromBase64('AAAAAdRzXjomXhbu4D9ZcYubXQMBnAfYtsUfkNo6Zm7sE6s1a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAAYc+ZgAAAAAAAAAAATgdAhWK+24tgzgXB3s/jrRa3IjCWfeAfZAt+Rym0n85LInd31N0fxhxviE9IZB0CtNEh0/0yjLCLVTH8rNq/igAAAAUAAAAAAAAABgAAAAAAAAAHAAAACAAAAAAAAAAJAAAACgAAAAsAAAAMa4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0vUc146Jl4W7uA/WXGLm10DAZwH2LbFH5DaOmZu7BOrNU4HQIVivtuLYM4Fwd7P460WtyIwln3gH2QLfkcptJ/OSyJ3d9TdH8Ycb4hPSGQdArTRIdP9Moywi1Ux/Kzav4oAAAAA')
            ->read(LedgerHeader::class);

        $this->assertInstanceOf(LedgerHeader::class, $ledgerHeader);
        $this->assertInstanceOf(UInt32::class, $ledgerHeader->getLedgerVersion());
        $this->assertInstanceOf(Hash::class, $ledgerHeader->getPreviousLedgerHash());
        $this->assertInstanceOf(StellarValue::class, $ledgerHeader->getSCPValue());
        $this->assertInstanceOf(Hash::class, $ledgerHeader->getTxSetResultHash());
        $this->assertInstanceOf(Hash::class, $ledgerHeader->getBucketListHash());
        $this->assertInstanceOf(UInt32::class, $ledgerHeader->getLedgerSeq());
        $this->assertInstanceOf(Int64::class, $ledgerHeader->getTotalCoins());
        $this->assertInstanceOf(Int64::class, $ledgerHeader->getFeePool());
        $this->assertInstanceOf(UInt32::class, $ledgerHeader->getInflationSeq());
        $this->assertInstanceOf(UInt64::class, $ledgerHeader->getIdPool());
        $this->assertInstanceOf(UInt32::class, $ledgerHeader->getBaseFee());
        $this->assertInstanceOf(UInt32::class, $ledgerHeader->getBaseReserve());
        $this->assertInstanceOf(UInt32::class, $ledgerHeader->getMaxTxSetSize());
        $this->assertInstanceOf(SkipList::class, $ledgerHeader->getSkipList());
        $this->assertInstanceOf(LedgerHeaderExt::class, $ledgerHeader->getExtension());
    }

    /**
     * @test
     * @covers ::withLedgerVersion
     * @covers ::getLedgerVersion
     */
    public function it_accepts_a_uint32_as_a_ledger_version()
    {
        $ledgerHeader = (new LedgerHeader())->withLedgerVersion(UInt32::of(1));

        $this->assertInstanceOf(LedgerHeader::class, $ledgerHeader);
        $this->assertInstanceOf(UInt32::class, $ledgerHeader->getLedgerVersion());
    }

    /**
     * @test
     * @covers ::withLedgerVersion
     * @covers ::getLedgerVersion
     */
    public function it_accepts_a_native_int_as_a_ledger_version()
    {
        $ledgerHeader = (new LedgerHeader())->withLedgerVersion(1);

        $this->assertInstanceOf(LedgerHeader::class, $ledgerHeader);
        $this->assertInstanceOf(UInt32::class, $ledgerHeader->getLedgerVersion());
    }

    /**
     * @test
     * @covers ::withPreviousLedgerHash
     * @covers ::getPreviousLedgerHash
     */
    public function it_accepts_a_previous_ledger_hash()
    {
        $ledgerHeader = (new LedgerHeader())->withPreviousLedgerHash(Hash::make('2'));

        $this->assertInstanceOf(LedgerHeader::class, $ledgerHeader);
        $this->assertInstanceOf(Hash::class, $ledgerHeader->getPreviousLedgerHash());
    }

    /**
     * @test
     * @covers ::withSCPValue
     * @covers ::getSCPValue
     */
    public function it_accepts_a_scp_value()
    {
        $stellarValue = (new StellarValue())
            ->withTxSetHash(Hash::make('1'))
            ->withCloseTime(TimePoint::fromNativeString('2022-01-01'));
        $ledgerHeader = (new LedgerHeader())->withSCPValue($stellarValue);

        $this->assertInstanceOf(LedgerHeader::class, $ledgerHeader);
        $this->assertInstanceOf(StellarValue::class, $ledgerHeader->getSCPValue());
    }

    /**
     * @test
     * @covers ::withTxSetResultHash
     * @covers ::getTxSetResultHash
     */
    public function it_accepts_a_tx_set_result_hash()
    {
        $ledgerHeader = (new LedgerHeader())->withTxSetResultHash(Hash::make('3'));

        $this->assertInstanceOf(LedgerHeader::class, $ledgerHeader);
        $this->assertInstanceOf(Hash::class, $ledgerHeader->getTxSetResultHash());
    }

    /**
     * @test
     * @covers ::withBucketListHash
     * @covers ::getBucketListHash
     */
    public function it_accepts_a_bucket_list_hash()
    {
        $ledgerHeader = (new LedgerHeader())->withBucketListHash(Hash::make('4'));

        $this->assertInstanceOf(LedgerHeader::class, $ledgerHeader);
        $this->assertInstanceOf(Hash::class, $ledgerHeader->getBucketListHash());
    }

    /**
     * @test
     * @covers ::withLedgerSeq
     * @covers ::getLedgerSeq
     */
    public function it_accepts_a_uint32_as_a_ledger_seq()
    {
        $ledgerHeader = (new LedgerHeader())->withLedgerSeq(UInt32::of(5));

        $this->assertInstanceOf(LedgerHeader::class, $ledgerHeader);
        $this->assertInstanceOf(UInt32::class, $ledgerHeader->getLedgerSeq());
    }

    /**
     * @test
     * @covers ::withLedgerSeq
     * @covers ::getLedgerSeq
     */
    public function it_accepts_a_native_int_as_a_ledger_seq()
    {
        $ledgerHeader = (new LedgerHeader())->withLedgerSeq(5);

        $this->assertInstanceOf(LedgerHeader::class, $ledgerHeader);
        $this->assertInstanceOf(UInt32::class, $ledgerHeader->getLedgerSeq());
    }

    /**
     * @test
     * @covers ::withTotalCoins
     * @covers ::getTotalCoins
     */
    public function it_accepts_a_total_coins_count()
    {
        $ledgerHeader = (new LedgerHeader())->withTotalCoins(Int64::of(6));

        $this->assertInstanceOf(LedgerHeader::class, $ledgerHeader);
        $this->assertInstanceOf(Int64::class, $ledgerHeader->getTotalCoins());
    }

    /**
     * @test
     * @covers ::withFeePool
     * @covers ::getFeePool
     */
    public function it_accepts_a_fee_pool()
    {
        $ledgerHeader = (new LedgerHeader())->withFeePool(Int64::of(7));

        $this->assertInstanceOf(LedgerHeader::class, $ledgerHeader);
        $this->assertInstanceOf(Int64::class, $ledgerHeader->getFeePool());
    }

    /**
     * @test
     * @covers ::withInflationSeq
     * @covers ::getInflationSeq
     */
    public function it_accepts_a_uint32_as_an_inflation_seq()
    {
        $ledgerHeader = (new LedgerHeader())->withInflationSeq(UInt32::of(8));

        $this->assertInstanceOf(LedgerHeader::class, $ledgerHeader);
        $this->assertInstanceOf(UInt32::class, $ledgerHeader->getInflationSeq());
    }

    /**
     * @test
     * @covers ::withInflationSeq
     * @covers ::getInflationSeq
     */
    public function it_accepts_a_native_int_as_an_inflation_seq()
    {
        $ledgerHeader = (new LedgerHeader())->withInflationSeq(8);

        $this->assertInstanceOf(LedgerHeader::class, $ledgerHeader);
        $this->assertInstanceOf(UInt32::class, $ledgerHeader->getInflationSeq());
    }

    /**
     * @test
     * @covers ::withIdPool
     * @covers ::getIdPool
     */
    public function it_accepts_an_id_pool()
    {
        $ledgerHeader = (new LedgerHeader())->withIdPool(UInt64::of(9));

        $this->assertInstanceOf(LedgerHeader::class, $ledgerHeader);
        $this->assertInstanceOf(UInt64::class, $ledgerHeader->getIdPool());
    }

    /**
     * @test
     * @covers ::withBaseFee
     * @covers ::getBaseFee
     */
    public function it_accepts_a_uint32_as_a_base_fee()
    {
        $ledgerHeader = (new LedgerHeader())->withBaseFee(UInt32::of(10));

        $this->assertInstanceOf(LedgerHeader::class, $ledgerHeader);
        $this->assertInstanceOf(UInt32::class, $ledgerHeader->getBaseFee());
    }

    /**
     * @test
     * @covers ::withBaseFee
     * @covers ::getBaseFee
     */
    public function it_accepts_a_native_int_as_a_base_fee()
    {
        $ledgerHeader = (new LedgerHeader())->withBaseFee(10);

        $this->assertInstanceOf(LedgerHeader::class, $ledgerHeader);
        $this->assertInstanceOf(UInt32::class, $ledgerHeader->getBaseFee());
    }

    /**
     * @test
     * @covers ::withBaseReserve
     * @covers ::getBaseReserve
     */
    public function it_accepts_a_uint32_as_a_base_reserve()
    {
        $ledgerHeader = (new LedgerHeader())->withBaseReserve(UInt32::of(11));

        $this->assertInstanceOf(LedgerHeader::class, $ledgerHeader);
        $this->assertInstanceOf(UInt32::class, $ledgerHeader->getBaseReserve());
    }

    /**
     * @test
     * @covers ::withBaseReserve
     * @covers ::getBaseReserve
     */
    public function it_accepts_a_native_as_a_base_reserve()
    {
        $ledgerHeader = (new LedgerHeader())->withBaseReserve(11);

        $this->assertInstanceOf(LedgerHeader::class, $ledgerHeader);
        $this->assertInstanceOf(UInt32::class, $ledgerHeader->getBaseReserve());
    }

    /**
     * @test
     * @covers ::withMaxTxSetSize
     * @covers ::getMaxTxSetSize
     */
    public function it_accepts_a_uint32_as_a_max_tx_set_size()
    {
        $ledgerHeader = (new LedgerHeader())->withMaxTxSetSize(UInt32::of(12));

        $this->assertInstanceOf(LedgerHeader::class, $ledgerHeader);
        $this->assertInstanceOf(UInt32::class, $ledgerHeader->getMaxTxSetSize());
    }

    /**
     * @test
     * @covers ::withMaxTxSetSize
     * @covers ::getMaxTxSetSize
     */
    public function it_accepts_a_native_int_as_a_max_tx_set_size()
    {
        $ledgerHeader = (new LedgerHeader())->withMaxTxSetSize(12);

        $this->assertInstanceOf(LedgerHeader::class, $ledgerHeader);
        $this->assertInstanceOf(UInt32::class, $ledgerHeader->getMaxTxSetSize());
    }

    /**
     * @test
     * @covers ::withSkipList
     * @covers ::getSkipList
     */
    public function it_accepts_a_skip_list()
    {
        $skipList = SkipList::make([
            Hash::make('1'),
            Hash::make('2'),
            Hash::make('3'),
            Hash::make('4'),
        ]);
        $ledgerHeader = (new LedgerHeader())->withSkipList($skipList);

        $this->assertInstanceOf(LedgerHeader::class, $ledgerHeader);
        $this->assertInstanceOf(SkipList::class, $ledgerHeader->getSkipList());
    }

    /**
     * @test
     * @covers ::withExtension
     * @covers ::getExtension
     */
    public function it_accepts_an_extension()
    {
        $ledgerHeader = (new LedgerHeader())->withExtension(LedgerHeaderExt::empty());

        $this->assertInstanceOf(LedgerHeader::class, $ledgerHeader);
        $this->assertInstanceOf(LedgerHeaderExt::class, $ledgerHeader->getExtension());
    }
}

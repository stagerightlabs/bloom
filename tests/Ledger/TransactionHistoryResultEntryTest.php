<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\TransactionHistoryResultEntry;
use StageRightLabs\Bloom\Ledger\TransactionHistoryResultEntryExt;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\TransactionResultSet;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\TransactionHistoryResultEntry
 */
class TransactionHistoryResultEntryTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $transactionHistoryResultEntry = (new TransactionHistoryResultEntry())
            ->withLedgerSeq(UInt32::of(1))
            ->withTransactionResultSet(TransactionResultSet::empty());
        $buffer = XDR::fresh()->write($transactionHistoryResultEntry);

        $this->assertEquals('AAAAAQAAAAAAAAAA', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_ledger_seq_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $transactionHistoryResultEntry = (new TransactionHistoryResultEntry())
            ->withTransactionResultSet(TransactionResultSet::empty());
        XDR::fresh()->write($transactionHistoryResultEntry);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_transaction_result_set_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $transactionHistoryResultEntry = (new TransactionHistoryResultEntry())
            ->withLedgerSeq(UInt32::of(1));
        XDR::fresh()->write($transactionHistoryResultEntry);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $transactionHistoryResultEntry = XDR::fromBase64('AAAAAQAAAAAAAAAA')
            ->read(TransactionHistoryResultEntry::class);

        $this->assertInstanceOf(TransactionHistoryResultEntry::class, $transactionHistoryResultEntry);
        $this->assertInstanceOf(UInt32::class, $transactionHistoryResultEntry->getLedgerSeq());
        $this->assertInstanceOf(TransactionResultSet::class, $transactionHistoryResultEntry->getTransactionResultSet());
        $this->assertInstanceOf(TransactionHistoryResultEntryExt::class, $transactionHistoryResultEntry->getExtension());
    }

    /**
     * @test
     * @covers ::withLedgerSeq
     * @covers ::getLedgerSeq
     */
    public function it_accepts_a_uint32_as_a_ledger_seq()
    {
        $transactionHistoryResultEntry = (new TransactionHistoryResultEntry())
            ->withLedgerSeq(UInt32::of(1));

        $this->assertInstanceOf(TransactionHistoryResultEntry::class, $transactionHistoryResultEntry);
        $this->assertInstanceOf(UInt32::class, $transactionHistoryResultEntry->getLedgerSeq());
    }

    /**
     * @test
     * @covers ::withLedgerSeq
     * @covers ::getLedgerSeq
     */
    public function it_accepts_a_native_int_as_a_ledger_seq()
    {
        $transactionHistoryResultEntry = (new TransactionHistoryResultEntry())
            ->withLedgerSeq(1);

        $this->assertInstanceOf(TransactionHistoryResultEntry::class, $transactionHistoryResultEntry);
        $this->assertInstanceOf(UInt32::class, $transactionHistoryResultEntry->getLedgerSeq());
    }

    /**
     * @test
     * @covers ::withTransactionResultSet
     * @covers ::getTransactionResultSet
     */
    public function it_accepts_a_transaction_result_set()
    {
        $transactionHistoryResultEntry = (new TransactionHistoryResultEntry())
            ->withTransactionResultSet(TransactionResultSet::empty());

        $this->assertInstanceOf(TransactionHistoryResultEntry::class, $transactionHistoryResultEntry);
        $this->assertInstanceOf(TransactionResultSet::class, $transactionHistoryResultEntry->getTransactionResultSet());
    }

    /**
     * @test
     * @covers ::withExtension
     * @covers ::getExtension
     */
    public function it_accepts_an_extension()
    {
        $transactionHistoryResultEntry = (new TransactionHistoryResultEntry())
            ->withExtension(TransactionHistoryResultEntryExt::empty());

        $this->assertInstanceOf(TransactionHistoryResultEntry::class, $transactionHistoryResultEntry);
        $this->assertInstanceOf(TransactionHistoryResultEntryExt::class, $transactionHistoryResultEntry->getExtension());
    }
}

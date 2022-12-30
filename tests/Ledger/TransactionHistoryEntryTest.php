<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\TransactionHistoryEntry;
use StageRightLabs\Bloom\Ledger\TransactionHistoryEntryExt;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\TransactionSet;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\TransactionHistoryEntry
 */
class TransactionHistoryEntryTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $transactionSet = (new TransactionSet())->withPreviousLedgerHash(Hash::make('1'));
        $transactionHistoryEntry = (new TransactionHistoryEntry())
            ->withLedgerSeq(UInt32::of(2))
            ->withTransactionSet($transactionSet);
        $buffer = XDR::fresh()->write($transactionHistoryEntry);

        $this->assertEquals('AAAAAmuGsnP/NPzhnWuATv9aP1dHraTqoi8dScAeUt23h1tLAAAAAAAAAAA=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_ledger_seq_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $transactionSet = (new TransactionSet())->withPreviousLedgerHash(Hash::make('1'));
        $transactionHistoryEntry = (new TransactionHistoryEntry())
            ->withTransactionSet($transactionSet);
        XDR::fresh()->write($transactionHistoryEntry);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_transaction_set_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $transactionHistoryEntry = (new TransactionHistoryEntry())
            ->withLedgerSeq(UInt32::of(2));
        XDR::fresh()->write($transactionHistoryEntry);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $transactionHistoryEntry = XDR::fromBase64('AAAAAmuGsnP/NPzhnWuATv9aP1dHraTqoi8dScAeUt23h1tLAAAAAAAAAAA=')
            ->read(TransactionHistoryEntry::class);

        $this->assertInstanceOf(TransactionHistoryEntry::class, $transactionHistoryEntry);
        $this->assertInstanceOf(UInt32::class, $transactionHistoryEntry->getLedgerSeq());
        $this->assertInstanceOf(TransactionSet::class, $transactionHistoryEntry->getTransactionSet());
        $this->assertInstanceOf(TransactionHistoryEntryExt::class, $transactionHistoryEntry->getExtension());
    }

    /**
     * @test
     * @covers ::withLedgerSeq
     * @covers ::getLedgerSeq
     */
    public function it_accepts_a_uint32_as_a_ledger_seq()
    {
        $transactionHistoryEntry = (new TransactionHistoryEntry())
            ->withLedgerSeq(UInt32::of(2));

        $this->assertInstanceOf(TransactionHistoryEntry::class, $transactionHistoryEntry);
        $this->assertInstanceOf(UInt32::class, $transactionHistoryEntry->getLedgerSeq());
    }

    /**
     * @test
     * @covers ::withLedgerSeq
     * @covers ::getLedgerSeq
     */
    public function it_accepts_a_native_as_a_ledger_seq()
    {
        $transactionHistoryEntry = (new TransactionHistoryEntry())
            ->withLedgerSeq(2);

        $this->assertInstanceOf(TransactionHistoryEntry::class, $transactionHistoryEntry);
        $this->assertInstanceOf(UInt32::class, $transactionHistoryEntry->getLedgerSeq());
    }

    /**
     * @test
     * @covers ::withTransactionSet
     * @covers ::getTransactionSet
     */
    public function it_accepts_a_transaction_set()
    {
        $transactionSet = (new TransactionSet())->withPreviousLedgerHash(Hash::make('1'));
        $transactionHistoryEntry = (new TransactionHistoryEntry())
            ->withTransactionSet($transactionSet);

        $this->assertInstanceOf(TransactionHistoryEntry::class, $transactionHistoryEntry);
        $this->assertInstanceOf(TransactionSet::class, $transactionHistoryEntry->getTransactionSet());
    }

    /**
     * @test
     * @covers ::withExtension
     * @covers ::getExtension
     */
    public function it_accepts_an_extension()
    {
        $transactionHistoryEntry = (new TransactionHistoryEntry())
            ->withExtension(TransactionHistoryEntryExt::empty());

        $this->assertInstanceOf(TransactionHistoryEntry::class, $transactionHistoryEntry);
        $this->assertInstanceOf(TransactionHistoryEntryExt::class, $transactionHistoryEntry->getExtension());
    }
}

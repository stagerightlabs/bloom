<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\LedgerEntryChanges;
use StageRightLabs\Bloom\Operation\OperationMetaList;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\TransactionMetaV2;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\TransactionMetaV2
 */
class TransactionMetaV2Test extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $transactionMetaV2 = (new TransactionMetaV2())
            ->withOperations(OperationMetaList::empty());
        $buffer = XDR::fresh()->write($transactionMetaV2);

        $this->assertEquals('AAAAAAAAAAAAAAAA', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_list_of_operation_meta_data_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new TransactionMetaV2());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $transactionMetaV2 = XDR::fromBase64('AAAAAAAAAAAAAAAA')
            ->read(TransactionMetaV2::class);

        $this->assertInstanceOf(TransactionMetaV2::class, $transactionMetaV2);
        $this->assertInstanceOf(LedgerEntryChanges::class, $transactionMetaV2->getTxChangesBefore());
        $this->assertInstanceOf(OperationMetaList::class, $transactionMetaV2->getOperations());
        $this->assertInstanceOf(LedgerEntryChanges::class, $transactionMetaV2->getTxChangesAfter());
    }

    /**
     * @test
     * @covers ::withTxChangesBefore
     * @covers ::getTxChangesBefore
     */
    public function it_accepts_a_list_of_transaction_changes_before()
    {
        $transactionMetaV2 = (new TransactionMetaV2())
            ->withTxChangesBefore(LedgerEntryChanges::empty());

        $this->assertInstanceOf(TransactionMetaV2::class, $transactionMetaV2);
        $this->assertInstanceOf(LedgerEntryChanges::class, $transactionMetaV2->getTxChangesBefore());
    }

    /**
     * @test
     * @covers ::withOperations
     * @covers ::getOperations
     */
    public function it_accepts_a_list_of_operation_meta_data()
    {
        $transactionMetaV2 = (new TransactionMetaV2())
            ->withOperations(OperationMetaList::empty());

        $this->assertInstanceOf(TransactionMetaV2::class, $transactionMetaV2);
        $this->assertInstanceOf(OperationMetaList::class, $transactionMetaV2->getOperations());
    }

    /**
     * @test
     * @covers ::withTxChangesAfter
     * @covers ::getTxChangesAfter
     */
    public function it_accepts_a_list_of_transaction_changes_after()
    {
        $transactionMetaV2 = (new TransactionMetaV2())
            ->withTxChangesAfter(LedgerEntryChanges::empty());

        $this->assertInstanceOf(TransactionMetaV2::class, $transactionMetaV2);
        $this->assertInstanceOf(LedgerEntryChanges::class, $transactionMetaV2->getTxChangesAfter());
    }
}

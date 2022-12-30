<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\LedgerEntryChanges;
use StageRightLabs\Bloom\Operation\OperationMetaList;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\TransactionMetaV1;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\TransactionMetaV1
 */
class TransactionMetaV1Test extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $transactionMetaV1 = (new TransactionMetaV1())
            ->withOperations(OperationMetaList::empty());
        $buffer = XDR::fresh()->write($transactionMetaV1);

        $this->assertEquals('AAAAAAAAAAA=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_operation_meta_list_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new TransactionMetaV1());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $transactionMetaV1 = XDR::fromBase64('AAAAAAAAAAA=')
            ->read(TransactionMetaV1::class);

        $this->assertInstanceOf(TransactionMetaV1::class, $transactionMetaV1);
        $this->assertInstanceOf(LedgerEntryChanges::class, $transactionMetaV1->getTxChanges());
        $this->assertInstanceOf(OperationMetaList::class, $transactionMetaV1->getOperations());
    }

    /**
     * @test
     * @covers ::withTxChanges
     * @covers ::getTxChanges
     */
    public function it_accepts_a_list_of_transaction_changes()
    {
        $transactionMetaV1 = (new TransactionMetaV1())
            ->withTxChanges(LedgerEntryChanges::empty());

        $this->assertInstanceOf(TransactionMetaV1::class, $transactionMetaV1);
        $this->assertInstanceOf(LedgerEntryChanges::class, $transactionMetaV1->getTxChanges());
    }

    /**
     * @test
     * @covers ::withOperations
     * @covers ::getOperations
     */
    public function it_accepts_a_list_of_operation_meta_data()
    {
        $transactionMetaV1 = (new TransactionMetaV1())
            ->withOperations(OperationMetaList::empty());

        $this->assertInstanceOf(TransactionMetaV1::class, $transactionMetaV1);
        $this->assertInstanceOf(OperationMetaList::class, $transactionMetaV1->getOperations());
    }
}

<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Ledger\LedgerEntryChanges;
use StageRightLabs\Bloom\Operation\OperationMetaList;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\TransactionMeta;
use StageRightLabs\Bloom\Transaction\TransactionMetaV1;
use StageRightLabs\Bloom\Transaction\TransactionMetaV2;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\TransactionMeta
 */
class TransactionMetaTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(XDR::INT, TransactionMeta::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            0 => OperationMetaList::class,
            1 => TransactionMetaV1::class,
            2 => TransactionMetaV2::class,
        ];

        $this->assertEquals($expected, TransactionMeta::arms());
    }

    /**
     * @test
     * @covers ::wrapOperationMetaList
     * @covers ::unwrap
     */
    public function it_can_wrap_an_operation_meta_list()
    {
        $transactionMeta = TransactionMeta::wrapOperationMetaList(OperationMetaList::empty());

        $this->assertInstanceOf(TransactionMeta::class, $transactionMeta);
        $this->assertInstanceOf(OperationMetaList::class, $transactionMeta->unwrap());
    }

    /**
     * @test
     * @covers ::wrapTransactionMetaV1
     * @covers ::unwrap
     */
    public function it_can_wrap_a_transaction_meta_v1()
    {
        $transactionMetaV1 = (new TransactionMetaV1())
            ->withOperations(OperationMetaList::empty());
        $transactionMeta = TransactionMeta::wrapTransactionMetaV1($transactionMetaV1);

        $this->assertInstanceOf(TransactionMeta::class, $transactionMeta);
        $this->assertInstanceOf(TransactionMetaV1::class, $transactionMeta->unwrap());
    }

    /**
     * @test
     * @covers ::wrapTransactionMetaV2
     * @covers ::unwrap
     */
    public function it_can_wrap_a_transaction_meta_v2()
    {
        $transactionMetaV2 = (new TransactionMetaV2())
            ->withTxChangesBefore(LedgerEntryChanges::empty());
        $transactionMeta = TransactionMeta::wrapTransactionMetaV2($transactionMetaV2);

        $this->assertInstanceOf(TransactionMeta::class, $transactionMeta);
        $this->assertInstanceOf(TransactionMetaV2::class, $transactionMeta->unwrap());
    }
}

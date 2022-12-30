<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Ledger\TransactionHistoryEntryExt;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\GeneralizedTransactionSet;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\TransactionHistoryEntryExt
 */
class TransactionHistoryEntryExtTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(XDR::INT, TransactionHistoryEntryExt::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            0 => XDR::VOID,
            1 => GeneralizedTransactionSet::class,
        ];

        $this->assertEquals($expected, TransactionHistoryEntryExt::arms());
    }

    /**
     * @test
     * @covers ::empty
     * @covers ::unwrap
     */
    public function it_can_be_created_as_an_empty_union()
    {
        $transactionHistoryEntryExt = TransactionHistoryEntryExt::empty();

        $this->assertInstanceOf(TransactionHistoryEntryExt::class, $transactionHistoryEntryExt);
        $this->assertNull($transactionHistoryEntryExt->unwrap());
    }

    /**
     * @test
     * @covers ::wrapGeneralizedTransactionSet
     * @covers ::unwrap
     */
    public function it_can_wrap_a_generalized_transaction_set()
    {
        $transactionHistoryEntryExt = TransactionHistoryEntryExt::wrapGeneralizedTransactionSet(new GeneralizedTransactionSet());

        $this->assertInstanceOf(TransactionHistoryEntryExt::class, $transactionHistoryEntryExt);
        $this->assertInstanceOf(GeneralizedTransactionSet::class, $transactionHistoryEntryExt->unwrap());
    }
}

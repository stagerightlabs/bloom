<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\TransactionPhase;
use StageRightLabs\Bloom\Transaction\TxSetComponentList;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\TransactionPhase
 */
class TransactionPhaseTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(XDR::INT, TransactionPhase::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            0 => TxSetComponentList::class,
        ];

        $this->assertEquals($expected, TransactionPhase::arms());
    }

    /**
     * @test
     * @covers ::wrapTxSetComponentList
     * @covers ::unwrap
     */
    public function it_can_wrap_a_tx_set_component_list()
    {
        $transactionPhase = TransactionPhase::wrapTxSetComponentList(TxSetComponentList::empty());

        $this->assertInstanceOf(TransactionPhase::class, $transactionPhase);
        $this->assertInstanceOf(TxSetComponentList::class, $transactionPhase->unwrap());
    }
}

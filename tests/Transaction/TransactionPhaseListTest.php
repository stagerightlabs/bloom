<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\TransactionPhase;
use StageRightLabs\Bloom\Transaction\TransactionPhaseList;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\TransactionPhaseList
 */
class TransactionPhaseListTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrType
     */
    public function it_defines_an_xdr_type()
    {
        $this->assertEquals(TransactionPhase::class, TransactionPhaseList::getXdrType());
    }

    /**
     * @test
     * @covers ::getMaxLength
     */
    public function it_defines_a_max_length()
    {
        $this->assertEquals(TransactionPhaseList::MAX_LENGTH, TransactionPhaseList::getMaxLength());
    }

    /**
     * @test
     * @covers ::empty
     */
    public function it_can_be_instantiated_as_an_empty_list()
    {
        $transactionPhaseList = TransactionPhaseList::empty();

        $this->assertInstanceOf(TransactionPhaseList::class, $transactionPhaseList);
        $this->assertEmpty($transactionPhaseList);
    }
}

<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\TransactionResultMeta;
use StageRightLabs\Bloom\Transaction\TransactionResultMetaList;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\TransactionResultMetaList
 */
class TransactionResultMetaListTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrType
     */
    public function it_defines_an_xdr_type()
    {
        $this->assertEquals(TransactionResultMeta::class, TransactionResultMetaList::getXdrType());
    }

    /**
     * @test
     * @covers ::getMaxLength
     */
    public function it_defines_a_max_length()
    {
        $this->assertEquals(TransactionResultMetaList::MAX_LENGTH, TransactionResultMetaList::getMaxLength());
    }

    /**
     * @test
     * @covers ::empty
     */
    public function it_can_be_instantiated_as_an_empty_list()
    {
        $transactionResultMetaList = TransactionResultMetaList::empty();

        $this->assertInstanceOf(TransactionResultMetaList::class, $transactionResultMetaList);
        $this->assertEmpty($transactionResultMetaList);
    }
}

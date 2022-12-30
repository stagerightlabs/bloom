<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\TransactionResultPair;
use StageRightLabs\Bloom\Transaction\TransactionResultSet;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\TransactionResultSet
 */
class TransactionResultSetTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrType
     */
    public function it_defines_an_xdr_type()
    {
        $this->assertEquals(TransactionResultPair::class, TransactionResultSet::getXdrType());
    }

    /**
     * @test
     * @covers ::getMaxLength
     */
    public function it_defines_a_max_length()
    {
        $this->assertEquals(TransactionResultSet::MAX_LENGTH, TransactionResultSet::getMaxLength());
    }

    /**
     * @test
     * @covers ::empty
     */
    public function it_can_be_instantiated_as_an_empty_list()
    {
        $this->assertEmpty(TransactionResultSet::empty());
    }
}

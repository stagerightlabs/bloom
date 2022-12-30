<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Envelope;

use StageRightLabs\Bloom\Envelope\TransactionEnvelope;
use StageRightLabs\Bloom\Envelope\TransactionEnvelopeList;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Envelope\TransactionEnvelopeList
 */
class TransactionEnvelopeListTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrType
     */
    public function it_defines_an_xdr_type()
    {
        $this->assertEquals(TransactionEnvelope::class, TransactionEnvelopeList::getXdrType());
    }

    /**
     * @test
     * @covers ::getMaxLength
     */
    public function it_defines_a_max_length()
    {
        $this->assertEquals(TransactionEnvelopeList::MAX_LENGTH, TransactionEnvelopeList::getMaxLength());
    }

    /**
     * @test
     * @covers ::empty
     */
    public function it_can_be_instantiated_as_an_empty_list()
    {
        $transactions = TransactionEnvelopeList::empty();

        $this->assertInstanceOf(TransactionEnvelopeList::class, $transactions);
        $this->assertEmpty($transactions);
    }
}

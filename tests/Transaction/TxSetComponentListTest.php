<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\TxSetComponent;
use StageRightLabs\Bloom\Transaction\TxSetComponentList;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\TxSetComponentList
 */
class TxSetComponentListTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrType
     */
    public function it_defines_an_xdr_type()
    {
        $this->assertEquals(TxSetComponent::class, TxSetComponentList::getXdrType());
    }

    /**
     * @test
     * @covers ::getMaxLength
     */
    public function it_defines_a_max_length()
    {
        $this->assertEquals(TxSetComponentList::MAX_LENGTH, TxSetComponentList::getMaxLength());
    }

    /**
     * @test
     * @covers ::empty
     */
    public function it_can_be_instantiated_as_an_empty_list()
    {
        $transactions = TxSetComponentList::empty();

        $this->assertInstanceOf(TxSetComponentList::class, $transactions);
        $this->assertEmpty($transactions);
    }
}

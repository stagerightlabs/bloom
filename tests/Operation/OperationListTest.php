<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Operation\OperationList;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\OperationList
 */
class OperationListTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrType
     */
    public function it_defines_an_xdr_type()
    {
        $this->assertEquals(Operation::class, OperationList::getXdrType());
    }

    /**
     * @test
     * @covers ::getMaxLength
     */
    public function it_limits_the_number_of_total_operations_as_defined_in_the_spec()
    {
        $this->assertEquals(Bloom::MAX_OPS_PER_TX, OperationList::getMaxLength());
    }

    /**
     * @test
     * @covers ::empty
     */
    public function it_can_be_instantiated_as_an_empty_list()
    {
        $this->assertEmpty(OperationList::empty());
    }
}

<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\OperationMeta;
use StageRightLabs\Bloom\Operation\OperationMetaList;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\OperationMetaList
 */
class OperationMetaListTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrType
     */
    public function it_defines_an_xdr_type()
    {
        $this->assertEquals(OperationMeta::class, OperationMetaList::getXdrType());
    }

    /**
     * @test
     * @covers ::getMaxLength
     */
    public function it_defines_a_max_length()
    {
        $this->assertEquals(OperationMetaList::MAX_LENGTH, OperationMetaList::getMaxLength());
    }

    /**
     * @test
     * @covers ::empty
     */
    public function it_can_be_instantiated_as_an_empty_list()
    {
        $operationMetaList = OperationMetaList::empty();

        $this->assertInstanceOf(OperationMetaList::class, $operationMetaList);
        $this->assertEmpty($operationMetaList);
    }
}

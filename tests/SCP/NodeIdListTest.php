<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\SCP\NodeId;
use StageRightLabs\Bloom\SCP\NodeIdList;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\NodeIdList
 */
class NodeIdListTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrType
     */
    public function it_defines_an_xdr_type()
    {
        $this->assertEquals(NodeId::class, NodeIdList::getXdrType());
    }

    /**
     * @test
     * @covers ::getMaxLength
     */
    public function it_defines_a_max_length()
    {
        $this->assertEquals(NodeIdList::MAX_LENGTH, NodeIdList::getMaxLength());
    }

    /**
     * @test
     * @covers ::empty
     */
    public function it_can_be_instantiated_as_an_empty_list()
    {
        $nodeIdList = NodeIdList::empty();

        $this->assertInstanceOf(NodeIdList::class, $nodeIdList);
        $this->assertEmpty($nodeIdList);
    }
}

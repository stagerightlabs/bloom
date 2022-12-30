<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\SCP\ScpQuorumSet;
use StageRightLabs\Bloom\SCP\ScpQuorumSetList;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\ScpQuorumSetList
 */
class ScpQuorumSetListTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrType
     */
    public function it_defines_an_xdr_type()
    {
        $this->assertEquals(ScpQuorumSet::class, ScpQuorumSetList::getXdrType());
    }

    /**
     * @test
     * @covers ::getMaxLength
     */
    public function it_defines_a_max_length()
    {
        $this->assertEquals(ScpQuorumSetList::MAX_LENGTH, ScpQuorumSetList::getMaxLength());
    }

    /**
     * @test
     * @covers ::empty
     */
    public function it_can_be_instantiated_as_an_empty_list()
    {
        $scpQuorumSetList = ScpQuorumSetList::empty();

        $this->assertInstanceOf(ScpQuorumSetList::class, $scpQuorumSetList);
        $this->assertEmpty($scpQuorumSetList);
    }
}

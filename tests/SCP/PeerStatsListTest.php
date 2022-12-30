<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\SCP\PeerStats;
use StageRightLabs\Bloom\SCP\PeerStatsList;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\PeerStatsList
 */
class PeerStatsListTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrType
     */
    public function it_defines_an_xdr_type()
    {
        $this->assertEquals(PeerStats::class, PeerStatsList::getXdrType());
    }

    /**
     * @test
     * @covers ::getMaxLength
     */
    public function it_defines_a_max_length()
    {
        $this->assertEquals(PeerStatsList::MAX_LENGTH, PeerStatsList::getMaxLength());
    }

    /**
     * @test
     * @covers ::empty
     */
    public function it_can_be_instantiated_as_an_empty_list()
    {
        $peerStatsList = PeerStatsList::empty();

        $this->assertInstanceOf(PeerStatsList::class, $peerStatsList);
        $this->assertEmpty($peerStatsList);
    }
}

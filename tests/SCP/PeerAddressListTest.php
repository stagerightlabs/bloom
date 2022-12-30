<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\SCP\PeerAddress;
use StageRightLabs\Bloom\SCP\PeerAddressList;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\PeerAddressList
 */
class PeerAddressListTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrType
     */
    public function it_defines_an_xdr_type()
    {
        $this->assertEquals(PeerAddress::class, PeerAddressList::getXdrType());
    }

    /**
     * @test
     * @covers ::getMaxLength
     */
    public function it_defines_a_max_length()
    {
        $this->assertEquals(PeerAddressList::MAX_LENGTH, PeerAddressList::getMaxLength());
    }

    /**
     * @test
     * @covers ::empty
     */
    public function it_can_be_instantiated_as_an_empty_list()
    {
        $peerAddressList = PeerAddressList::empty();

        $this->assertInstanceOf(PeerAddressList::class, $peerAddressList);
        $this->assertEmpty($peerAddressList);
    }
}

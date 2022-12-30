<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\SCP\ScpEnvelope;
use StageRightLabs\Bloom\SCP\ScpEnvelopeList;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\ScpEnvelopeList
 */
class ScpEnvelopeListTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrType
     */
    public function it_defines_an_xdr_type()
    {
        $this->assertEquals(ScpEnvelope::class, ScpEnvelopeList::getXdrType());
    }

    /**
     * @test
     * @covers ::getMaxLength
     */
    public function it_defines_a_max_length()
    {
        $this->assertEquals(ScpEnvelopeList::MAX_LENGTH, ScpEnvelopeList::getMaxLength());
    }

    /**
     * @test
     * @covers ::empty
     */
    public function it_can_be_instantiated_as_an_empty_list()
    {
        $scpEnvelopeList = ScpEnvelopeList::empty();

        $this->assertInstanceOf(ScpEnvelopeList::class, $scpEnvelopeList);
        $this->assertEmpty($scpEnvelopeList);
    }
}

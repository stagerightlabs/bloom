<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Offer;

use StageRightLabs\Bloom\Offer\ClaimAtom;
use StageRightLabs\Bloom\Offer\ClaimAtomList;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Offer\ClaimAtomList
 */
class ClaimAtomListTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrType
     */
    public function it_defines_an_xdr_type()
    {
        $this->assertEquals(ClaimAtom::class, ClaimAtomList::getXdrType());
    }

    /**
     * @test
     * @covers ::getMaxLength
     */
    public function it_defines_a_max_length()
    {
        $this->assertEquals(ClaimAtomList::MAX_LENGTH, ClaimAtomList::getMaxLength());
    }

    /**
     * @test
     * @covers ::empty
     */
    public function it_can_be_instantiated_as_an_empty_list()
    {
        $this->assertEmpty(ClaimAtomList::empty());
    }
}

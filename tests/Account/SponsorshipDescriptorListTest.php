<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Account;

use StageRightLabs\Bloom\Account\SponsorshipDescriptor;
use StageRightLabs\Bloom\Account\SponsorshipDescriptorList;
use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Account\SponsorshipDescriptorList
 */
class SponsorshipDescriptorListTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrType
     */
    public function it_defines_an_xdr_type()
    {
        $this->assertEquals(SponsorshipDescriptor::class, SponsorshipDescriptorList::getXdrType());
    }

    /**
     * @test
     * @covers ::getMaxLength
     */
    public function it_defines_a_max_length()
    {
        $this->assertEquals(Bloom::MAX_SIGNERS, SponsorshipDescriptorList::getMaxLength());
    }

    /**
     * @test
     * @covers ::empty
     */
    public function it_can_be_instantiated_as_an_empty_list()
    {
        $this->assertEmpty(SponsorshipDescriptorList::empty());
    }
}

<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\SCP\UpgradeType;
use StageRightLabs\Bloom\SCP\UpgradeTypeList;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\UpgradeTypeList
 */
class UpgradeTypeListTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrType
     */
    public function it_defines_an_xdr_type()
    {
        $this->assertEquals(UpgradeType::class, UpgradeTypeList::getXdrType());
    }

    /**
     * @test
     * @covers ::getMaxLength
     */
    public function it_defines_a_max_length()
    {
        $this->assertEquals(UpgradeTypeList::MAX_LENGTH, UpgradeTypeList::getMaxLength());
    }

    /**
     * @test
     * @covers ::empty
     */
    public function it_can_be_instantiated_as_an_empty_list()
    {
        $upgradeTypeList = UpgradeTypeList::empty();

        $this->assertInstanceOf(UpgradeTypeList::class, $upgradeTypeList);
        $this->assertEmpty($upgradeTypeList);
    }
}

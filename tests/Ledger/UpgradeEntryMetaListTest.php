<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Ledger\UpgradeEntryMeta;
use StageRightLabs\Bloom\Ledger\UpgradeEntryMetaList;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\UpgradeEntryMetaList
 */
class UpgradeEntryMetaListTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrType
     */
    public function it_defines_an_xdr_type()
    {
        $this->assertEquals(UpgradeEntryMeta::class, UpgradeEntryMetaList::getXdrType());
    }

    /**
     * @test
     * @covers ::getMaxLength
     */
    public function it_defines_a_max_length()
    {
        $this->assertEquals(UpgradeEntryMetaList::MAX_LENGTH, UpgradeEntryMetaList::getMaxLength());
    }

    /**
     * @test
     * @covers ::empty
     */
    public function it_can_be_instantiated_as_an_empty_list()
    {
        $upgradeEntryMetaList = UpgradeEntryMetaList::empty();

        $this->assertInstanceOf(UpgradeEntryMetaList::class, $upgradeEntryMetaList);
        $this->assertEmpty($upgradeEntryMetaList);
    }
}

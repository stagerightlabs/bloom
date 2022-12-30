<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Ledger\ScpHistoryEntry;
use StageRightLabs\Bloom\Ledger\ScpHistoryEntryList;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\ScpHistoryEntryList
 */
class ScpHistoryEntryListTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrType
     */
    public function it_defines_an_xdr_type()
    {
        $this->assertEquals(ScpHistoryEntry::class, ScpHistoryEntryList::getXdrType());
    }

    /**
     * @test
     * @covers ::getMaxLength
     */
    public function it_defines_a_max_length()
    {
        $this->assertEquals(ScpHistoryEntryList::MAX_LENGTH, ScpHistoryEntryList::getMaxLength());
    }

    /**
     * @test
     * @covers ::empty
     */
    public function it_can_be_instantiated_as_an_empty_list()
    {
        $scpHistoryEntryList = ScpHistoryEntryList::empty();

        $this->assertInstanceOf(ScpHistoryEntryList::class, $scpHistoryEntryList);
        $this->assertEmpty($scpHistoryEntryList);
    }
}

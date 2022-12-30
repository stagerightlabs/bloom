<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Ledger\LedgerEntryChange;
use StageRightLabs\Bloom\Ledger\LedgerEntryChanges;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LedgerEntryChanges
 */
class LedgerEntryChangesTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrType
     */
    public function it_defines_an_xdr_type()
    {
        $this->assertEquals(LedgerEntryChange::class, LedgerEntryChanges::getXdrType());
    }

    /**
     * @test
     * @covers ::getMaxLength
     */
    public function it_defines_a_max_length()
    {
        $this->assertEquals(LedgerEntryChanges::MAX_LENGTH, LedgerEntryChanges::getMaxLength());
    }

    /**
     * @test
     * @covers ::empty
     */
    public function it_can_be_instantiated_as_an_empty_list()
    {
        $ledgerEntryChanges = LedgerEntryChanges::empty();

        $this->assertInstanceOf(LedgerEntryChanges::class, $ledgerEntryChanges);
        $this->assertEmpty($ledgerEntryChanges);
    }
}

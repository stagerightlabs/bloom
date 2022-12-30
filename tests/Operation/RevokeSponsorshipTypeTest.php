<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\RevokeSponsorshipType;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\RevokeSponsorshipType
 */
class RevokeSponsorshipTypeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0 => RevokeSponsorshipType::REVOKE_SPONSORSHIP_LEDGER_ENTRY,
            1 => RevokeSponsorshipType::REVOKE_SPONSORSHIP_SIGNER,
        ];
        $revokeSponsorshipType = new RevokeSponsorshipType();

        $this->assertEquals($expected, $revokeSponsorshipType->getOptions());
    }

    /**
     * @test
     * @covers ::revokeSponsorshipLedgerEntry
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_revoke_sponsorship_ledger_entry_type()
    {
        $revokeSponsorshipType = RevokeSponsorshipType::revokeSponsorshipLedgerEntry();

        $this->assertInstanceOf(RevokeSponsorshipType::class, $revokeSponsorshipType);
        $this->assertEquals(RevokeSponsorshipType::REVOKE_SPONSORSHIP_LEDGER_ENTRY, $revokeSponsorshipType->getType());
    }

    /**
     * @test
     * @covers ::revokeSponsorshipSigner
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_revoke_sponsorship_signer_type()
    {
        $revokeSponsorshipType = RevokeSponsorshipType::revokeSponsorshipSigner();

        $this->assertInstanceOf(RevokeSponsorshipType::class, $revokeSponsorshipType);
        $this->assertEquals(RevokeSponsorshipType::REVOKE_SPONSORSHIP_SIGNER, $revokeSponsorshipType->getType());
    }
}

<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\LedgerEntryChanges;
use StageRightLabs\Bloom\Ledger\LedgerUpgrade;
use StageRightLabs\Bloom\Ledger\UpgradeEntryMeta;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\UpgradeEntryMeta
 */
class UpgradeEntryMetaTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $upgradeEntryMeta = (new UpgradeEntryMeta())
            ->withUpgrade(LedgerUpgrade::wrapVersion(1));
        $buffer = XDR::fresh()->write($upgradeEntryMeta);

        $this->assertEquals('AAAAAQAAAAEAAAAA', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_upgrade_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new UpgradeEntryMeta());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $upgradeEntryMeta = XDR::fromBase64('AAAAAQAAAAEAAAAA')
            ->read(UpgradeEntryMeta::class);

        $this->assertInstanceOf(UpgradeEntryMeta::class, $upgradeEntryMeta);
        $this->assertInstanceOf(LedgerUpgrade::class, $upgradeEntryMeta->getUpgrade());
        $this->assertInstanceOf(LedgerEntryChanges::class, $upgradeEntryMeta->getChanges());
    }

    /**
     * @test
     * @covers ::withUpgrade
     * @covers ::getUpgrade
     */
    public function it_accepts_an_upgrade()
    {
        $upgradeEntryMeta = (new UpgradeEntryMeta())
            ->withUpgrade(LedgerUpgrade::wrapVersion(1));

        $this->assertInstanceOf(UpgradeEntryMeta::class, $upgradeEntryMeta);
        $this->assertInstanceOf(LedgerUpgrade::class, $upgradeEntryMeta->getUpgrade());
    }

    /**
     * @test
     * @covers ::withChanges
     * @covers ::getChanges
     */
    public function it_accepts_a_list_of_changes()
    {
        $upgradeEntryMeta = (new UpgradeEntryMeta())
            ->withChanges(LedgerEntryChanges::empty());

        $this->assertInstanceOf(UpgradeEntryMeta::class, $upgradeEntryMeta);
        $this->assertInstanceOf(LedgerEntryChanges::class, $upgradeEntryMeta->getChanges());
    }
}

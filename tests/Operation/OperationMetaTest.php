<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Ledger\LedgerEntryChanges;
use StageRightLabs\Bloom\Operation\OperationMeta;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\OperationMeta
 */
class OperationMetaTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $buffer = XDR::fresh()->write(new OperationMeta());
        $this->assertEquals('AAAAAA==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $operationMeta = XDR::fromBase64('AAAAAA==')->read(OperationMeta::class);

        $this->assertInstanceOf(OperationMeta::class, $operationMeta);
        $this->assertInstanceOf(LedgerEntryChanges::class, $operationMeta->getChanges());
    }

    /**
     * @test
     * @covers ::withChanges
     * @covers ::getChanges
     */
    public function it_accepts_a_list_of_ledger_entry_changes()
    {
        $operationMeta = (new OperationMeta())
            ->withChanges(LedgerEntryChanges::empty());

        $this->assertInstanceOf(OperationMeta::class, $operationMeta);
        $this->assertInstanceOf(LedgerEntryChanges::class, $operationMeta->getChanges());
    }
}

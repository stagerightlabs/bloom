<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\SCP\NodeId;
use StageRightLabs\Bloom\SCP\ScpNomination;
use StageRightLabs\Bloom\SCP\ScpStatement;
use StageRightLabs\Bloom\SCP\ScpStatementPledges;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\ScpStatement
 */
class ScpStatementTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $scpNomination = (new ScpNomination())
            ->withQuorumSetHash(Hash::make('1'));
        $scpStatementPledges = ScpStatementPledges::wrapNomination($scpNomination);
        $scpStatement = (new ScpStatement())
            ->withNodeId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withSlotIndex(UInt64::of(1))
            ->withPledges($scpStatementPledges);
        $buffer = XDR::fresh()->write($scpStatement);

        $this->assertEquals('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAAAAAAEAAAADa4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAAAAAAAA==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_node_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $scpNomination = (new ScpNomination())
            ->withQuorumSetHash(Hash::make('1'));
        $scpStatementPledges = ScpStatementPledges::wrapNomination($scpNomination);
        $scpStatement = (new ScpStatement())
            ->withSlotIndex(UInt64::of(1))
            ->withPledges($scpStatementPledges);
        XDR::fresh()->write($scpStatement);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_slot_index_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $scpNomination = (new ScpNomination())
            ->withQuorumSetHash(Hash::make('1'));
        $scpStatementPledges = ScpStatementPledges::wrapNomination($scpNomination);
        $scpStatement = (new ScpStatement())
            ->withNodeId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withPledges($scpStatementPledges);
        XDR::fresh()->write($scpStatement);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function statement_pledges_are_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $scpStatement = (new ScpStatement())
            ->withNodeId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withSlotIndex(UInt64::of(1));
        XDR::fresh()->write($scpStatement);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $scpStatement = XDR::fromBase64('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAAAAAAEAAAADa4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAAAAAAAA==')
            ->read(ScpStatement::class);

        $this->assertInstanceOf(ScpStatement::class, $scpStatement);
        $this->assertInstanceOf(NodeId::class, $scpStatement->getNodeId());
        $this->assertInstanceOf(UInt64::class, $scpStatement->getSlotIndex());
        $this->assertInstanceOf(ScpStatementPledges::class, $scpStatement->getPledges());
    }

    /**
     * @test
     * @covers ::withNodeId
     * @covers ::getNodeId
     */
    public function it_accepts_a_node_id()
    {
        $scpStatement = (new ScpStatement())
            ->withNodeId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(ScpStatement::class, $scpStatement);
        $this->assertInstanceOf(NodeId::class, $scpStatement->getNodeId());
    }

    /**
     * @test
     * @covers ::withSlotIndex
     * @covers ::getSlotIndex
     */
    public function it_accepts_a_slot_index()
    {
        $scpStatement = (new ScpStatement())
            ->withSlotIndex(UInt64::of(1));

        $this->assertInstanceOf(ScpStatement::class, $scpStatement);
        $this->assertInstanceOf(UInt64::class, $scpStatement->getSlotIndex());
    }

    /**
     * @test
     * @covers ::withPledges
     * @covers ::getPledges
     */
    public function it_accepts_pledgees()
    {
        $scpNomination = (new ScpNomination())
            ->withQuorumSetHash(Hash::make('1'));
        $scpStatementPledges = ScpStatementPledges::wrapNomination($scpNomination);
        $scpStatement = (new ScpStatement())
            ->withPledges($scpStatementPledges);

        $this->assertInstanceOf(ScpStatement::class, $scpStatement);
        $this->assertInstanceOf(ScpStatementPledges::class, $scpStatement->getPledges());
    }
}

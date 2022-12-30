<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Cryptography\Signature;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\SCP\NodeId;
use StageRightLabs\Bloom\SCP\ScpEnvelope;
use StageRightLabs\Bloom\SCP\ScpNomination;
use StageRightLabs\Bloom\SCP\ScpStatement;
use StageRightLabs\Bloom\SCP\ScpStatementPledges;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\ScpEnvelope
 */
class ScpEnvelopeTest extends TestCase
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
        $signature = Signature::of('abcd');
        $scpEnvelope = (new ScpEnvelope())
            ->withStatement($scpStatement)
            ->withSignature($signature);
        $buffer = XDR::fresh()->write($scpEnvelope);

        $this->assertEquals('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAAAAAAEAAAADa4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAAAAAAAAAAAARhYmNk', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_statement_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $signature = Signature::of('abcd');
        $scpEnvelope = (new ScpEnvelope())
            ->withSignature($signature);
        XDR::fresh()->write($scpEnvelope);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_signature_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $scpNomination = (new ScpNomination())
            ->withQuorumSetHash(Hash::make('1'));
        $scpStatementPledges = ScpStatementPledges::wrapNomination($scpNomination);
        $scpStatement = (new ScpStatement())
            ->withNodeId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withSlotIndex(UInt64::of(1))
            ->withPledges($scpStatementPledges);
        $scpEnvelope = (new ScpEnvelope())
            ->withStatement($scpStatement);
        XDR::fresh()->write($scpEnvelope);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $scpEnvelope = XDR::fromBase64('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAAAAAAEAAAADa4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAAAAAAAAAAAARhYmNk')
            ->read(ScpEnvelope::class);

        $this->assertInstanceOf(ScpEnvelope::class, $scpEnvelope);
        $this->assertInstanceOf(ScpStatement::class, $scpEnvelope->getStatement());
        $this->assertInstanceOf(Signature::class, $scpEnvelope->getSignature());
    }

    /**
     * @test
     * @covers ::withStatement
     * @covers ::getStatement
     */
    public function it_accepts_a_statement()
    {
        $scpNomination = (new ScpNomination())
            ->withQuorumSetHash(Hash::make('1'));
        $scpStatementPledges = ScpStatementPledges::wrapNomination($scpNomination);
        $scpStatement = (new ScpStatement())
            ->withNodeId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withSlotIndex(UInt64::of(1))
            ->withPledges($scpStatementPledges);
        $scpEnvelope = (new ScpEnvelope())
            ->withStatement($scpStatement);

        $this->assertInstanceOf(ScpEnvelope::class, $scpEnvelope);
        $this->assertInstanceOf(ScpStatement::class, $scpEnvelope->getStatement());
    }

    /**
     * @test
     * @covers ::withSignature
     * @covers ::getSignature
     */
    public function it_accepts_a_signature()
    {
        $signature = Signature::of('abcd');
        $scpEnvelope = (new ScpEnvelope())->withSignature($signature);

        $this->assertInstanceOf(ScpEnvelope::class, $scpEnvelope);
        $this->assertInstanceOf(Signature::class, $scpEnvelope->getSignature());
    }
}

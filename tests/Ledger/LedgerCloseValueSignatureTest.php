<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Cryptography\Signature;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\LedgerCloseValueSignature;
use StageRightLabs\Bloom\SCP\NodeId;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LedgerCloseValueSignature
 */
class LedgerCloseValueSignatureTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $ledgerCloseValueSignature = (new LedgerCloseValueSignature())
            ->withNodeId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withSignature(Signature::of('abcd'));
        $buffer = XDR::fresh()->write($ledgerCloseValueSignature);

        $this->assertEquals('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAABGFiY2Q=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_node_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $ledgerCloseValueSignature = (new LedgerCloseValueSignature())
            ->withSignature(Signature::of('abcd'));
        XDR::fresh()->write($ledgerCloseValueSignature);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_signature_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $ledgerCloseValueSignature = (new LedgerCloseValueSignature())
            ->withNodeId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));
        XDR::fresh()->write($ledgerCloseValueSignature);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $ledgerCloseValueSignature = XDR::fromBase64('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAABGFiY2Q=')
            ->read(LedgerCloseValueSignature::class);

        $this->assertInstanceOf(LedgerCloseValueSignature::class, $ledgerCloseValueSignature);
        $this->assertInstanceOf(NodeId::class, $ledgerCloseValueSignature->getNodeId());
        $this->assertInstanceOf(Signature::class, $ledgerCloseValueSignature->getSignature());
    }

    /**
     * @test
     * @covers ::withNodeId
     * @covers ::getNodeId
     */
    public function it_accepts_a_node_id()
    {
        $ledgerCloseValueSignature = (new LedgerCloseValueSignature())
            ->withNodeId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(LedgerCloseValueSignature::class, $ledgerCloseValueSignature);
        $this->assertInstanceOf(NodeId::class, $ledgerCloseValueSignature->getNodeId());
    }

    /**
     * @test
     * @covers ::withSignature
     * @covers ::getSignature
     */
    public function it_accepts_a_signature()
    {
        $ledgerCloseValueSignature = (new LedgerCloseValueSignature())
            ->withSignature(Signature::of('abcd'));

        $this->assertInstanceOf(LedgerCloseValueSignature::class, $ledgerCloseValueSignature);
        $this->assertInstanceOf(Signature::class, $ledgerCloseValueSignature->getSignature());
    }
}

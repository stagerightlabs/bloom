<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Operation\BumpSequenceOp;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\SequenceNumber;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\BumpSequenceOp
 */
class BumpSequenceOpTest extends TestCase
{
    /**
     * @test
     * @covers ::operation
     */
    public function it_can_create_an_operation()
    {
        $operation = BumpSequenceOp::operation(
            bumpTo: 256,
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(BumpSequenceOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::isReady
     */
    public function it_knows_when_it_is_ready()
    {
        $bumpSequenceOp = new BumpSequenceOp();
        $this->assertFalse($bumpSequenceOp->isReady());

        $bumpSequenceOp = $bumpSequenceOp->withBumpTo(256);
        $this->assertTrue($bumpSequenceOp->isReady());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $bumpSequenceOp = (new BumpSequenceOp())
            ->withBumpTo(SequenceNumber::of(1));
        $buffer = XDR::fresh()->write($bumpSequenceOp);

        $this->assertEquals('AAAAAAAAAAE=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_target_sequence_number_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new BumpSequenceOp());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $bumpSequenceOp = XDR::fromBase64('AAAAAAAAAAE=')
            ->read(BumpSequenceOp::class);

        $this->assertInstanceOf(BumpSequenceOp::class, $bumpSequenceOp);
        $this->assertInstanceOf(SequenceNumber::class, $bumpSequenceOp->getBumpTo());
    }

    /**
     * @test
     * @covers ::withBumpTo
     * @covers ::getBumpTo
     */
    public function it_accepts_a_target_sequence_number()
    {
        $bumpSequenceOp = (new BumpSequenceOp())
            ->withBumpTo(SequenceNumber::of(1));

        $this->assertInstanceOf(BumpSequenceOp::class, $bumpSequenceOp);
        $this->assertInstanceOf(SequenceNumber::class, $bumpSequenceOp->getBumpTo());
    }

    /**
     * @test
     * @covers ::withBumpTo
     * @covers ::getBumpTo
     */
    public function it_accepts_an_int64_target_sequence_number()
    {
        $bumpSequenceOp = (new BumpSequenceOp())
            ->withBumpTo(Int64::of(2));

        $this->assertInstanceOf(BumpSequenceOp::class, $bumpSequenceOp);
        $this->assertInstanceOf(SequenceNumber::class, $bumpSequenceOp->getBumpTo());
    }

    /**
     * @test
     * @covers ::withBumpTo
     * @covers ::getBumpTo
     */
    public function it_accepts_a_native_integer_target_sequence_number()
    {
        $bumpSequenceOp = (new BumpSequenceOp())
            ->withBumpTo(3);

        $this->assertInstanceOf(BumpSequenceOp::class, $bumpSequenceOp);
        $this->assertInstanceOf(SequenceNumber::class, $bumpSequenceOp->getBumpTo());
    }

    /**
     * @test
     * @covers ::getThreshold
     */
    public function it_has_a_threshold_category()
    {
        $this->assertEquals(Thresholds::CATEGORY_MEDIUM, (new BumpSequenceOp())->getThreshold());
    }
}

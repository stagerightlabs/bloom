<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Cryptography\ED25519;
use StageRightLabs\Bloom\Cryptography\ExtraSigners;
use StageRightLabs\Bloom\Cryptography\SignerKey;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\Duration;
use StageRightLabs\Bloom\Transaction\LedgerBounds;
use StageRightLabs\Bloom\Transaction\PreconditionsV2;
use StageRightLabs\Bloom\Transaction\SequenceNumber;
use StageRightLabs\Bloom\Transaction\TimeBounds;
use StageRightLabs\Bloom\Transaction\TimePoint;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\PreconditionsV2
 */
class PreconditionsV2Test extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $ed25519 = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $signerKey = SignerKey::wrapEd25519($ed25519);

        $preconditionsV2 = (new PreconditionsV2())
            ->withTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31'))
            ->withLedgerBounds(LedgerBounds::between(1, 2))
            ->withMinimumSequenceNumber(SequenceNumber::of(1))
            ->withMinimumSequenceAge(Duration::of(2))
            ->withMinimumSequenceLedgerGap(UInt32::of(3))
            ->withExtraSigners(ExtraSigners::of($signerKey));
        $buffer = XDR::fresh()->write($preconditionsV2);

        $this->assertEquals('AAAAAQAAAABf7mYAAAAAAGHOSAAAAAABAAAAAQAAAAIAAAABAAAAAAAAAAEAAAAAAAAAAgAAAAMAAAABAAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53u', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr_with_defaults()
    {
        $preconditionsV2 = new PreconditionsV2();
        $buffer = XDR::fresh()->write($preconditionsV2);

        $this->assertEquals('AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $preconditionsV2 = XDR::fromBase64('AAAAAQAAAABf7mYAAAAAAGHOSAAAAAABAAAAAQAAAAIAAAABAAAAAAAAAAEAAAAAAAAAAgAAAAMAAAABAAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53u')
            ->read(PreconditionsV2::class);

        $this->assertInstanceOf(PreconditionsV2::class, $preconditionsV2);
        $this->assertEquals(1609459200, $preconditionsV2->getTimeBounds()->getMinTime()->toNativeInt());
        $this->assertEquals(1640908800, $preconditionsV2->getTimeBounds()->getMaxTime()->toNativeInt());
        $this->assertEquals(1, $preconditionsV2->getLedgerBounds()->getMinLedgerOffset()->toNativeInt());
        $this->assertEquals(2, $preconditionsV2->getLedgerBounds()->getMaxLedgerOffset()->toNativeInt());
        $this->assertEquals(1, $preconditionsV2->getMinimumSequenceNumber()->toNativeInt());
        $this->assertEquals(2, $preconditionsV2->getMinimumSequenceAge()->toNativeInt());
        $this->assertEquals(3, $preconditionsV2->getMinimumSequenceLedgerGap()->toNativeInt());
        $this->assertInstanceOf(ED25519::class, $preconditionsV2->getExtraSigners()->get(0)->unwrap());
    }

    /**
     * @test
     * @covers ::default
     */
    public function it_provides_a_default_preconditions_set()
    {
        $preconditions = PreconditionsV2::default();

        $this->assertInstanceOf(TimeBounds::class, $preconditions->getTimeBounds());
        $this->assertNull($preconditions->getLedgerBounds());
        $this->assertNull($preconditions->getMinimumSequenceNumber());
        $this->assertEquals(0, $preconditions->getMinimumSequenceAge()->toNativeInt());
        $this->assertEquals(0, $preconditions->getMinimumSequenceLedgerGap()->toNativeInt());
        $this->assertEmpty($preconditions->getExtraSigners()->toArray());
    }

    /**
     * @test
     * @covers ::getTimeBounds
     * @covers ::withTimeBounds
     */
    public function it_accepts_time_bounds()
    {
        $preconditionsV2A = new PreconditionsV2();
        $preconditionsV2B = $preconditionsV2A->withTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31'));

        $this->assertNull($preconditionsV2A->getTimeBounds());
        $this->assertInstanceOf(TimeBounds::class, $preconditionsV2B->getTimeBounds());
    }

    /**
     * @test
     * @covers ::withMinimumTimePoint
     */
    public function it_accepts_a_minimum_time_point()
    {
        $preconditionsV2A = new PreconditionsV2();
        $preconditionsV2B = $preconditionsV2A->withMinimumTimePoint(TimePoint::fromNativeString('2021-01-01'));

        $this->assertNull($preconditionsV2A->getTimeBounds());
        $this->assertEquals(1609459200, $preconditionsV2B->getTimeBounds()->getMinTime()->toNativeInt());
    }

    /**
     * @test
     * @covers ::withMinimumTimePoint
     */
    public function it_accepts_a_minimum_time_point_and_overwrites_existing()
    {
        $preconditionsV2A = (new PreconditionsV2())->withTimeBounds(TimeBounds::between('2022-01-01', '2022-02-01'));
        $preconditionsV2B = $preconditionsV2A->withMinimumTimePoint(TimePoint::fromNativeString('2021-01-01'));

        $this->assertEquals(1609459200, $preconditionsV2B->getTimeBounds()->getMinTime()->toNativeInt());
    }

    /**
     * @test
     * @covers ::withMaximumTimePoint
     */
    public function it_accepts_a_maximum_time_point()
    {
        $preconditionsV2A = new PreconditionsV2();
        $preconditionsV2B = $preconditionsV2A->withMaximumTimePoint(TimePoint::fromNativeString('2021-01-01'));

        $this->assertNull($preconditionsV2A->getTimeBounds());
        $this->assertEquals(1609459200, $preconditionsV2B->getTimeBounds()->getMaxTime()->toNativeInt());
    }

    /**
     * @test
     * @covers ::withMaximumTimePoint
     */
    public function it_accepts_a_maximum_time_point_and_overwrites_existing()
    {
        $preconditionsV2A = (new PreconditionsV2())->withTimeBounds(TimeBounds::between('2022-01-01', '2022-02-01'));
        $preconditionsV2B = $preconditionsV2A->withMaximumTimePoint(TimePoint::fromNativeString('2021-01-01'));

        $this->assertEquals(1609459200, $preconditionsV2B->getTimeBounds()->getMaxTime()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getLedgerBounds
     * @covers ::withLedgerBounds
     */
    public function it_accepts_ledger_bounds()
    {
        $preconditionsV2A = new PreconditionsV2();
        $preconditionsV2B = $preconditionsV2A->withLedgerBounds(LedgerBounds::between(1, 2));

        $this->assertNull($preconditionsV2A->getLedgerBounds());
        $this->assertInstanceOf(LedgerBounds::class, $preconditionsV2B->getLedgerBounds());
    }

    /**
     * @test
     * @covers ::withMinimumLedgerOffset
     */
    public function it_accepts_a_minimum_ledger_offset()
    {
        $preconditionsV2A = new PreconditionsV2();
        $preconditionsV2B = $preconditionsV2A->withMinimumLedgerOffset(UInt32::of(1));

        $this->assertNull($preconditionsV2A->getLedgerBounds());
        $this->assertEquals(1, $preconditionsV2B->getLedgerBounds()->getMinLedgerOffset()->toNativeInt());
    }

    /**
     * @test
     * @covers ::withMinimumLedgerOffset
     */
    public function it_accepts_a_minimum_ledger_offset_and_overwrites_existing()
    {
        $preconditionsV2A = (new PreconditionsV2())->withLedgerBounds(LedgerBounds::between(1, 2));
        $preconditionsV2B = $preconditionsV2A->withMinimumLedgerOffset(3);

        $this->assertEquals(3, $preconditionsV2B->getLedgerBounds()->getMinLedgerOffset()->toNativeInt());
    }

    /**
     * @test
     * @covers ::withMaximumLedgerOffset
     */
    public function it_accepts_a_maximum_ledger_offset()
    {
        $preconditionsV2A = new PreconditionsV2();
        $preconditionsV2B = $preconditionsV2A->withMaximumLedgerOffset(UInt32::of(10));

        $this->assertNull($preconditionsV2A->getLedgerBounds());
        $this->assertEquals(10, $preconditionsV2B->getLedgerBounds()->getMaxLedgerOffset()->toNativeInt());
    }

    /**
     * @test
     * @covers ::withMaximumLedgerOffset
     */
    public function it_accepts_a_maximum_ledger_offset_and_overwrites_existing()
    {
        $preconditionsV2A = (new PreconditionsV2())->withLedgerBounds(LedgerBounds::between(1, 2));
        $preconditionsV2B = $preconditionsV2A->withMaximumLedgerOffset(3);

        $this->assertEquals(3, $preconditionsV2B->getLedgerBounds()->getMaxLedgerOffset()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getMinimumSequenceNumber
     * @covers ::withMinimumSequenceNumber
     */
    public function it_accepts_a_minimum_sequence_number()
    {
        $preconditionsV2A = new PreconditionsV2();
        $preconditionsV2B = $preconditionsV2A->withMinimumSequenceNumber(SequenceNumber::of(1));

        $this->assertNull($preconditionsV2A->getMinimumSequenceNumber());
        $this->assertInstanceOf(SequenceNumber::class, $preconditionsV2B->getMinimumSequenceNumber());
    }

    /**
     * @test
     * @covers ::getMinimumSequenceAge
     * @covers ::withMinimumSequenceAge
     */
    public function it_accepts_a_minimum_sequence_age()
    {
        $preconditionsV2A = new PreconditionsV2();
        $preconditionsV2B = $preconditionsV2A->withMinimumSequenceAge(Duration::of('1'));

        $this->assertTrue($preconditionsV2A->getMinimumSequenceAge()->isEqualTo(0));
        $this->assertTrue($preconditionsV2B->getMinimumSequenceAge()->isEqualTo(1));
    }

    /**
     * @test
     * @covers ::getMinimumSequenceLedgerGap
     * @covers ::withMinimumSequenceLedgerGap
     */
    public function it_accepts_a_minimum_sequence_ledger_gap_as_uint32()
    {
        $preconditionsV2A = new PreconditionsV2();
        $preconditionsV2B = $preconditionsV2A->withMinimumSequenceLedgerGap(UInt32::of(1));

        $this->assertTrue($preconditionsV2A->getMinimumSequenceLedgerGap()->isEqualTo(0));
        $this->assertTrue($preconditionsV2B->getMinimumSequenceLedgerGap()->isEqualTo(1));
    }

    /**
     * @test
     * @covers ::getMinimumSequenceLedgerGap
     * @covers ::withMinimumSequenceLedgerGap
     */
    public function it_accepts_a_minimum_sequence_ledger_gap_as_int()
    {
        $preconditionsV2A = new PreconditionsV2();
        $preconditionsV2B = $preconditionsV2A->withMinimumSequenceLedgerGap(1);

        $this->assertTrue($preconditionsV2A->getMinimumSequenceLedgerGap()->isEqualTo(0));
        $this->assertTrue($preconditionsV2B->getMinimumSequenceLedgerGap()->isEqualTo(1));
    }

    /**
     * @test
     * @covers ::getExtraSigners
     * @covers ::withExtraSigners
     */
    public function it_accepts_extra_signers()
    {
        $ed25519 = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $signerKey = SignerKey::wrapEd25519($ed25519);
        $preconditionsV2A = new PreconditionsV2();
        $preconditionsV2B = $preconditionsV2A->withExtraSigners(ExtraSigners::of($signerKey));

        $this->assertTrue($preconditionsV2A->getExtraSigners()->isEmpty());
        $this->assertEquals(1, $preconditionsV2B->getExtraSigners()->count());
    }
}

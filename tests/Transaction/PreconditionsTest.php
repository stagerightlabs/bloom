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
use StageRightLabs\Bloom\Transaction\OptionalTimeBounds;
use StageRightLabs\Bloom\Transaction\Preconditions;
use StageRightLabs\Bloom\Transaction\PreconditionsV2;
use StageRightLabs\Bloom\Transaction\PreconditionType;
use StageRightLabs\Bloom\Transaction\SequenceNumber;
use StageRightLabs\Bloom\Transaction\TimeBounds;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\Preconditions
 */
class PreconditionsTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(PreconditionType::class, Preconditions::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            PreconditionType::PRECONDITION_NONE => XDR::VOID,
            PreconditionType::PRECONDITION_TIME => TimeBounds::class,
            PreconditionType::PRECONDITION_V2   => PreconditionsV2::class,
        ];

        $this->assertEquals($expected, Preconditions::arms());
    }

    /**
     * @test
     * @covers ::default
     */
    public function it_provides_a_default_preconditions_set()
    {
        $preconditions = Preconditions::default();

        $this->assertInstanceOf(TimeBounds::class, $preconditions->getTimeBounds());
        $this->assertNull($preconditions->getLedgerBounds());
        $this->assertNull($preconditions->getMinimumSequenceNumber());
        $this->assertEquals(0, $preconditions->getMinimumSequenceAge()->toNativeInt());
        $this->assertEquals(0, $preconditions->getMinimumSequenceLedgerGap()->toNativeInt());
        $this->assertEmpty($preconditions->getExtraSigners()->toArray());
    }

    /**
     * @test
     * @covers ::none
     */
    public function it_can_be_instantiated_as_an_empty_preconditions_set()
    {
        $preconditions = Preconditions::none();
        $this->assertEquals(XDR::VOID, $preconditions->unwrap());
    }

    /**
     * @test
     * @covers ::wrapPreconditionsV2
     * @covers ::unwrap
     */
    public function it_can_be_instantiated_as_a_v2_preconditions_set()
    {
        $preconditions = Preconditions::wrapPreconditionsV2();
        $this->assertInstanceOf(PreconditionsV2::class, $preconditions->unwrap());
    }

    /**
     * @test
     * @covers ::wrapTimeBounds
     */
    public function it_can_be_instantiated_as_a_time_bounds_preconditions_set_using_time_bounds()
    {
        $preconditions = Preconditions::wrapTimeBounds(TimeBounds::oneYear());
        $this->assertInstanceOf(TimeBounds::class, $preconditions->unwrap());
    }

    /**
     * @test
     * @covers ::wrapTimeBounds
     */
    public function it_can_be_instantiated_as_a_time_bounds_preconditions_set_using_optional_time_bounds()
    {
        $timeBounds = OptionalTimeBounds::some(TimeBounds::oneYear());
        $preconditions = Preconditions::wrapTimeBounds($timeBounds);

        $this->assertInstanceOf(TimeBounds::class, $preconditions->unwrap());
    }

    /**
     * @test
     * @covers ::wrapTimeBounds
     */
    public function it_can_be_instantiated_as_a_time_bounds_preconditions_set_using_optional_time_bounds_with_no_value()
    {
        $timeBounds = OptionalTimeBounds::none();
        $preconditions = Preconditions::wrapTimeBounds($timeBounds);

        $this->assertEquals(XDR::VOID, $preconditions->unwrap());
    }

    /**
     * @test
     * @covers ::getTimeBounds
     */
    public function it_returns_the_time_bounds_when_stored_as_a_time_precondition_set()
    {
        $preconditions = Preconditions::wrapTimeBounds(TimeBounds::oneYear());
        $this->assertInstanceOf(TimeBounds::class, $preconditions->getTimeBounds());
    }

    /**
     * @test
     * @covers ::getTimeBounds
     */
    public function it_returns_the_time_bounds_when_stored_as_a_v2_precondition_set()
    {
        $timeBounds = TimeBounds::oneYear();
        $preconditionsV2 = (new PreconditionsV2())->withTimeBounds($timeBounds);
        $preconditions = Preconditions::wrapPreconditionsV2($preconditionsV2);

        $this->assertInstanceOf(TimeBounds::class, $preconditions->getTimeBounds());
    }

    /**
     * @test
     * @covers ::getTimeBounds
     */
    public function it_returns_null_for_time_bounds_when_no_time_bounds_are_present()
    {
        $preconditions = Preconditions::none();
        $this->assertNull($preconditions->getTimeBounds());
    }

    /**
     * @test
     * @covers ::getLedgerBounds
     */
    public function it_returns_the_ledger_bounds_when_present()
    {
        $ledgerBounds = LedgerBounds::between(1, 2);
        $preconditionsV2 = (new PreconditionsV2())->withLedgerBounds($ledgerBounds);
        $preconditions = Preconditions::wrapPreconditionsV2($preconditionsV2);

        $this->assertInstanceOf(LedgerBounds::class, $preconditions->getLedgerBounds());
    }

    /**
     * @test
     * @covers ::getLedgerBounds
     */
    public function it_returns_null_for_ledger_bounds_when_none_are_present()
    {
        $preconditions = Preconditions::none();
        $this->assertNull($preconditions->getLedgerBounds());
    }

    /**
     * @test
     * @covers ::getMinimumSequenceNumber
     */
    public function it_returns_the_minimum_sequence_number_when_present()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $preconditionsV2 = (new PreconditionsV2())->withMinimumSequenceNumber($sequenceNumber);
        $preconditions = Preconditions::wrapPreconditionsV2($preconditionsV2);

        $this->assertInstanceOf(SequenceNumber::class, $preconditions->getMinimumSequenceNumber());
    }

    /**
     * @test
     * @covers ::getMinimumSequenceNumber
     */
    public function it_returns_null_for_minimum_sequence_number_when_not_present()
    {
        $preconditions = Preconditions::none();
        $this->assertNull($preconditions->getMinimumSequenceNumber());
    }

    /**
     * @test
     * @covers ::getMinimumSequenceAge
     */
    public function it_returns_the_minimum_sequence_age_when_present()
    {
        $minimumSequenceAge = Duration::of(1);
        $preconditionsV2 = (new PreconditionsV2())->withMinimumSequenceAge($minimumSequenceAge);
        $preconditions = Preconditions::wrapPreconditionsV2($preconditionsV2);

        $this->assertInstanceOf(Duration::class, $preconditions->getMinimumSequenceAge());
    }

    /**
     * @test
     * @covers ::getMinimumSequenceAge
     */
    public function it_returns_null_for_minimum_sequence_age_when_not_present()
    {
        $preconditions = Preconditions::none();
        $this->assertNull($preconditions->getMinimumSequenceAge());
    }

    /**
     * @test
     * @covers ::getMinimumSequenceLedgerGap
     */
    public function it_returns_the_minimum_sequence_ledger_gap_when_present()
    {
        $minimumSequenceLedgerGap = UInt32::of(1);
        $preconditionsV2 = (new PreconditionsV2())->withMinimumSequenceLedgerGap($minimumSequenceLedgerGap);
        $preconditions = Preconditions::wrapPreconditionsV2($preconditionsV2);

        $this->assertInstanceOf(UInt32::class, $preconditions->getMinimumSequenceLedgerGap());
    }

    /**
     * @test
     * @covers ::getMinimumSequenceLedgerGap
     */
    public function it_returns_null_for_minimum_sequence_ledger_gap_when_not_present()
    {
        $preconditions = Preconditions::none();
        $this->assertNull($preconditions->getMinimumSequenceLedgerGap());
    }

    /**
     * @test
     * @covers ::getExtraSigners
     */
    public function it_returns_the_extra_signers_when_present()
    {
        $ed25519 = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $signerKey = SignerKey::wrapEd25519($ed25519);
        $preconditionsA = Preconditions::wrapPreconditionsV2(
            (new PreconditionsV2())->withExtraSigners(ExtraSigners::of($signerKey))
        );
        $preconditionsB = Preconditions::none();

        $this->assertInstanceOf(ExtraSigners::class, $preconditionsA->getExtraSigners());
        $this->assertNull($preconditionsB->getExtraSigners());
    }
}

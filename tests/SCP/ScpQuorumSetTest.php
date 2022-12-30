<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\SCP\NodeIdList;
use StageRightLabs\Bloom\SCP\ScpQuorumSet;
use StageRightLabs\Bloom\SCP\ScpQuorumSetList;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\ScpQuorumSet
 */
class ScpQuorumSetTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $scpQuorumSet = (new ScpQuorumSet())
            ->withThreshold(UInt32::of(1))
            ->withValidators(NodeIdList::empty());
        $buffer = XDR::fresh()->write($scpQuorumSet);

        $this->assertEquals('AAAAAQAAAAAAAAAA', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_threshold_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $scpQuorumSet = (new ScpQuorumSet())
            ->withValidators(NodeIdList::empty());
        XDR::fresh()->write($scpQuorumSet);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_list_of_validators_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $scpQuorumSet = (new ScpQuorumSet())
            ->withThreshold(UInt32::of(1));
        XDR::fresh()->write($scpQuorumSet);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $scpQuorumSet = XDR::fromBase64('AAAAAQAAAAAAAAAA')
            ->read(ScpQuorumSet::class);

        $this->assertInstanceOf(ScpQuorumSet::class, $scpQuorumSet);
        $this->assertInstanceOf(UInt32::class, $scpQuorumSet->getThreshold());
        $this->assertInstanceOf(NodeIdList::class, $scpQuorumSet->getValidators());
        $this->assertInstanceOf(ScpQuorumSetList::class, $scpQuorumSet->getInnerSets());
    }

    /**
     * @test
     * @covers ::withThreshold
     * @covers ::getThreshold
     */
    public function it_accepts_a_uint32_as_a_threshold()
    {
        $scpQuorumSet = (new ScpQuorumSet())->withThreshold(UInt32::of(1));

        $this->assertInstanceOf(ScpQuorumSet::class, $scpQuorumSet);
        $this->assertInstanceOf(UInt32::class, $scpQuorumSet->getThreshold());
    }

    /**
     * @test
     * @covers ::withThreshold
     * @covers ::getThreshold
     */
    public function it_accepts_a_native_int_as_a_threshold()
    {
        $scpQuorumSet = (new ScpQuorumSet())->withThreshold(1);

        $this->assertInstanceOf(ScpQuorumSet::class, $scpQuorumSet);
        $this->assertInstanceOf(UInt32::class, $scpQuorumSet->getThreshold());
    }

    /**
     * @test
     * @covers ::withValidators
     * @covers ::getValidators
     */
    public function it_accepts_a_list_of_validators()
    {
        $scpQuorumSet = (new ScpQuorumSet())
            ->withValidators(NodeIdList::empty());

        $this->assertInstanceOf(ScpQuorumSet::class, $scpQuorumSet);
        $this->assertInstanceOf(NodeIdList::class, $scpQuorumSet->getValidators());
    }

    /**
     * @test
     * @covers ::withInnerSets
     * @covers ::getInnerSets
     */
    public function it_accepts_a_list_of_inner_sets()
    {
        $scpQuorumSet = (new ScpQuorumSet())
            ->withInnerSets(ScpQuorumSetList::empty());

        $this->assertInstanceOf(ScpQuorumSet::class, $scpQuorumSet);
        $this->assertInstanceOf(ScpQuorumSetList::class, $scpQuorumSet->getInnerSets());
    }
}

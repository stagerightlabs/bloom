<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\ValueList;
use StageRightLabs\Bloom\SCP\ScpNomination;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\ScpNomination
 */
class ScpNominationTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $scpNomination = (new ScpNomination())
            ->withQuorumSetHash(Hash::make('1'));
        $buffer = XDR::fresh()->write($scpNomination);

        $this->assertEquals('a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAAAAAAAA==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_quorum_set_hash_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $scpNomination = (new ScpNomination())
            ->withVotes(ValueList::empty())
            ->withAccepted(ValueList::empty());
        XDR::fresh()->write($scpNomination);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $scpNomination = XDR::fromBase64('a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAAAAAAAA==')
            ->read(ScpNomination::class);

        $this->assertInstanceOf(ScpNomination::class, $scpNomination);
        $this->assertInstanceOf(Hash::class, $scpNomination->getQuorumSetHash());
        $this->assertInstanceOf(ValueList::class, $scpNomination->getVotes());
        $this->assertInstanceOf(ValueList::class, $scpNomination->getAccepted());
    }

    /**
     * @test
     * @covers ::withQuorumSetHash
     * @covers ::getQuorumSetHash
     */
    public function it_accepts_a_quorum_set_hash()
    {
        $scpNomination = (new ScpNomination())
            ->withQuorumSetHash(Hash::make('1'));

        $this->assertInstanceOf(ScpNomination::class, $scpNomination);
        $this->assertInstanceOf(Hash::class, $scpNomination->getQuorumSetHash());
    }

    /**
     * @test
     * @covers ::withVotes
     * @covers ::getVotes
     */
    public function it_accepts_a_list_of_votes()
    {
        $scpNomination = (new ScpNomination())
            ->withVotes(ValueList::empty());

        $this->assertInstanceOf(ScpNomination::class, $scpNomination);
        $this->assertInstanceOf(ValueList::class, $scpNomination->getVotes());
    }

    /**
     * @test
     * @covers ::withAccepted
     * @covers ::getAccepted
     */
    public function it_accepts_an_accepted_list()
    {
        $scpNomination = (new ScpNomination())
            ->withAccepted(ValueList::empty());

        $this->assertInstanceOf(ScpNomination::class, $scpNomination);
        $this->assertInstanceOf(ValueList::class, $scpNomination->getAccepted());
    }
}

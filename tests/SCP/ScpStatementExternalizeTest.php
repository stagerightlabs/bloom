<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Primitives\Value;
use StageRightLabs\Bloom\SCP\ScpBallot;
use StageRightLabs\Bloom\SCP\ScpStatementExternalize;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\ScpStatementExternalize
 */
class ScpStatementExternalizeTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $scpBallot = (new ScpBallot())
            ->withCounter(UInt32::of(1))
            ->withValue(Value::of('example'));
        $scpStatementExternalize = (new ScpStatementExternalize())
            ->withCommit($scpBallot)
            ->withNH(UInt32::of(1))
            ->withCommitQuorumSetHash(Hash::make('1'));
        $buffer = XDR::fresh()->write($scpStatementExternalize);

        $this->assertEquals('AAAAAQAAAAdleGFtcGxlAAAAAAFrhrJz/zT84Z1rgE7/Wj9XR62k6qIvHUnAHlLdt4dbSw==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_commit_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $scpStatementExternalize = (new ScpStatementExternalize())
            ->withNH(UInt32::of(1))
            ->withCommitQuorumSetHash(Hash::make('1'));
        XDR::fresh()->write($scpStatementExternalize);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_nh_value_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $scpBallot = (new ScpBallot())
            ->withCounter(UInt32::of(1))
            ->withValue(Value::of('example'));
        $scpStatementExternalize = (new ScpStatementExternalize())
            ->withCommit($scpBallot)
            ->withCommitQuorumSetHash(Hash::make('1'));
        XDR::fresh()->write($scpStatementExternalize);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_commit_quorum_set_hash_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $scpBallot = (new ScpBallot())
            ->withCounter(UInt32::of(1))
            ->withValue(Value::of('example'));
        $scpStatementExternalize = (new ScpStatementExternalize())
            ->withCommit($scpBallot)
            ->withNH(UInt32::of(1));
        XDR::fresh()->write($scpStatementExternalize);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $scpStatementExternalize = XDR::fromBase64('AAAAAQAAAAdleGFtcGxlAAAAAAFrhrJz/zT84Z1rgE7/Wj9XR62k6qIvHUnAHlLdt4dbSw==')
            ->read(ScpStatementExternalize::class);

        $this->assertInstanceOf(ScpStatementExternalize::class, $scpStatementExternalize);
        $this->assertInstanceOf(ScpBallot::class, $scpStatementExternalize->getCommit());
        $this->assertInstanceOf(UInt32::class, $scpStatementExternalize->getNH());
        $this->assertInstanceOf(Hash::class, $scpStatementExternalize->getCommitQuorumSetHash());
    }

    /**
     * @test
     * @covers ::withCommit
     * @covers ::getCommit
     */
    public function it_accepts_a_commit_ballot()
    {
        $scpBallot = (new ScpBallot())
            ->withCounter(UInt32::of(1))
            ->withValue(Value::of('example'));
        $scpStatementExternalize = (new ScpStatementExternalize())
            ->withCommit($scpBallot);

        $this->assertInstanceOf(ScpStatementExternalize::class, $scpStatementExternalize);
        $this->assertInstanceOf(ScpBallot::class, $scpStatementExternalize->getCommit());
    }

    /**
     * @test
     * @covers ::withNH
     * @covers ::getNH
     */
    public function it_accepts_a_uint32_as_an_nh_value()
    {
        $scpStatementExternalize = (new ScpStatementExternalize())
            ->withNH(UInt32::of(1));

        $this->assertInstanceOf(ScpStatementExternalize::class, $scpStatementExternalize);
        $this->assertInstanceOf(UInt32::class, $scpStatementExternalize->getNH());
    }

    /**
     * @test
     * @covers ::withNH
     * @covers ::getNH
     */
    public function it_accepts_a_native_int_as_an_nh_value()
    {
        $scpStatementExternalize = (new ScpStatementExternalize())
            ->withNH(1);

        $this->assertInstanceOf(ScpStatementExternalize::class, $scpStatementExternalize);
        $this->assertInstanceOf(UInt32::class, $scpStatementExternalize->getNH());
    }

    /**
     * @test
     * @covers ::withCommitQuorumSetHash
     * @covers ::getCommitQuorumSetHash
     */
    public function it_accepts_a_commit_quorum_set_hash()
    {
        $scpStatementExternalize = (new ScpStatementExternalize())
            ->withCommitQuorumSetHash(Hash::make('1'));

        $this->assertInstanceOf(ScpStatementExternalize::class, $scpStatementExternalize);
        $this->assertInstanceOf(Hash::class, $scpStatementExternalize->getCommitQuorumSetHash());
    }
}

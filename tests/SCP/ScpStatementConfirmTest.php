<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Primitives\Value;
use StageRightLabs\Bloom\SCP\ScpBallot;
use StageRightLabs\Bloom\SCP\ScpStatementConfirm;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\ScpStatementConfirm
 */
class ScpStatementConfirmTest extends TestCase
{
    /**
     * @test
     * @covers ::toXDR
     */
    public function it_can_be_converted_to_xdr()
    {
        $scpBallot = (new ScpBallot())
            ->withCounter(UInt32::of(1))
            ->withValue(Value::of('example'));
        $scpStatementConfirm = (new ScpStatementConfirm())
            ->withBallot($scpBallot)
            ->withNPrepared(UInt32::of(1))
            ->withNCommit(UInt32::of(2))
            ->withNH(UInt32::of(3))
            ->withQuorumSetHash(Hash::make('1'));
        $buffer = XDR::fresh()->write($scpStatementConfirm);

        $this->assertEquals('AAAAAQAAAAdleGFtcGxlAAAAAAEAAAACAAAAA2uGsnP/NPzhnWuATv9aP1dHraTqoi8dScAeUt23h1tL', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_ballot_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $scpStatementConfirm = (new ScpStatementConfirm())
            ->withNPrepared(UInt32::of(1))
            ->withNCommit(UInt32::of(2))
            ->withNH(UInt32::of(3))
            ->withQuorumSetHash(Hash::make('1'));
        XDR::fresh()->write($scpStatementConfirm);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_n_prepared_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $scpBallot = (new ScpBallot())
            ->withCounter(UInt32::of(1))
            ->withValue(Value::of('example'));
        $scpStatementConfirm = (new ScpStatementConfirm())
            ->withBallot($scpBallot)
            ->withNCommit(UInt32::of(2))
            ->withNH(UInt32::of(3))
            ->withQuorumSetHash(Hash::make('1'));
        XDR::fresh()->write($scpStatementConfirm);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_n_commit_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $scpBallot = (new ScpBallot())
            ->withCounter(UInt32::of(1))
            ->withValue(Value::of('example'));
        $scpStatementConfirm = (new ScpStatementConfirm())
            ->withBallot($scpBallot)
            ->withNPrepared(UInt32::of(1))
            ->withNH(UInt32::of(3))
            ->withQuorumSetHash(Hash::make('1'));
        XDR::fresh()->write($scpStatementConfirm);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_nh_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $scpBallot = (new ScpBallot())
            ->withCounter(UInt32::of(1))
            ->withValue(Value::of('example'));
        $scpStatementConfirm = (new ScpStatementConfirm())
            ->withBallot($scpBallot)
            ->withNPrepared(UInt32::of(1))
            ->withNCommit(UInt32::of(2))
            ->withQuorumSetHash(Hash::make('1'));
        XDR::fresh()->write($scpStatementConfirm);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_quorum_set_hash_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $scpBallot = (new ScpBallot())
            ->withCounter(UInt32::of(1))
            ->withValue(Value::of('example'));
        $scpStatementConfirm = (new ScpStatementConfirm())
            ->withBallot($scpBallot)
            ->withNPrepared(UInt32::of(1))
            ->withNCommit(UInt32::of(2))
            ->withNH(UInt32::of(3));
        XDR::fresh()->write($scpStatementConfirm);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $scpStatementConfirm = XDR::fromBase64('AAAAAQAAAAdleGFtcGxlAAAAAAEAAAACAAAAA2uGsnP/NPzhnWuATv9aP1dHraTqoi8dScAeUt23h1tL')
            ->read(ScpStatementConfirm::class);

        $this->assertInstanceOf(ScpStatementConfirm::class, $scpStatementConfirm);
        $this->assertInstanceOf(ScpBallot::class, $scpStatementConfirm->getBallot());
        $this->assertInstanceOf(UInt32::class, $scpStatementConfirm->getNPrepared());
        $this->assertInstanceOf(UInt32::class, $scpStatementConfirm->getNCommit());
        $this->assertInstanceOf(UInt32::class, $scpStatementConfirm->getNH());
        $this->assertInstanceOf(Hash::class, $scpStatementConfirm->getQuorumSetHash());
    }

    /**
     * @test
     * @covers ::withBallot
     * @covers ::getBallot
     */
    public function it_accepts_a_ballot()
    {
        $scpBallot = (new ScpBallot())
            ->withCounter(UInt32::of(1))
            ->withValue(Value::of('example'));
        $scpStatementConfirm = (new ScpStatementConfirm())
            ->withBallot($scpBallot);

        $this->assertInstanceOf(ScpStatementConfirm::class, $scpStatementConfirm);
        $this->assertInstanceOf(ScpBallot::class, $scpStatementConfirm->getBallot());
    }

    /**
     * @test
     * @covers ::withNPrepared
     * @covers ::getNPrepared
     */
    public function it_accepts_a_uint32_as_an_n_prepared_value()
    {
        $scpStatementConfirm = (new ScpStatementConfirm())
            ->withNPrepared(UInt32::of(1));

        $this->assertInstanceOf(ScpStatementConfirm::class, $scpStatementConfirm);
        $this->assertInstanceOf(UInt32::class, $scpStatementConfirm->getNPrepared());
    }

    /**
     * @test
     * @covers ::withNPrepared
     * @covers ::getNPrepared
     */
    public function it_accepts_a_native_int_as_an_n_prepared_value()
    {
        $scpStatementConfirm = (new ScpStatementConfirm())
            ->withNPrepared(1);

        $this->assertInstanceOf(ScpStatementConfirm::class, $scpStatementConfirm);
        $this->assertInstanceOf(UInt32::class, $scpStatementConfirm->getNPrepared());
    }

    /**
     * @test
     * @covers ::withNCommit
     * @covers ::getNCommit
     */
    public function it_accepts_a_uint32_as_an_n_commit_value()
    {
        $scpStatementConfirm = (new ScpStatementConfirm())
            ->withNCommit(UInt32::of(2));

        $this->assertInstanceOf(ScpStatementConfirm::class, $scpStatementConfirm);
        $this->assertInstanceOf(UInt32::class, $scpStatementConfirm->getNCommit());
    }

    /**
     * @test
     * @covers ::withNCommit
     * @covers ::getNCommit
     */
    public function it_accepts_a_native_int_as_an_n_commit_value()
    {
        $scpStatementConfirm = (new ScpStatementConfirm())
            ->withNCommit(2);

        $this->assertInstanceOf(ScpStatementConfirm::class, $scpStatementConfirm);
        $this->assertInstanceOf(UInt32::class, $scpStatementConfirm->getNCommit());
    }

    /**
     * @test
     * @covers ::withNH
     * @covers ::getNH
     */
    public function it_accepts_a_uint32_as_an_nh_value()
    {
        $scpStatementConfirm = (new ScpStatementConfirm())
            ->withNH(UInt32::of(3));

        $this->assertInstanceOf(ScpStatementConfirm::class, $scpStatementConfirm);
        $this->assertInstanceOf(UInt32::class, $scpStatementConfirm->getNH());
    }

    /**
     * @test
     * @covers ::withNH
     * @covers ::getNH
     */
    public function it_accepts_a_native_int_as_an_nh_value()
    {
        $scpStatementConfirm = (new ScpStatementConfirm())
            ->withNH(3);

        $this->assertInstanceOf(ScpStatementConfirm::class, $scpStatementConfirm);
        $this->assertInstanceOf(UInt32::class, $scpStatementConfirm->getNH());
    }

    /**
     * @test
     * @covers ::withQuorumSetHash
     * @covers ::getQuorumSetHash
     */
    public function it_accepts_a_quorum_set_hash()
    {
        $scpStatementConfirm = (new ScpStatementConfirm())
            ->withQuorumSetHash(Hash::make('1'));

        $this->assertInstanceOf(ScpStatementConfirm::class, $scpStatementConfirm);
        $this->assertInstanceOf(Hash::class, $scpStatementConfirm->getQuorumSetHash());
    }
}

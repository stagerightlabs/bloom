<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Primitives\Value;
use StageRightLabs\Bloom\SCP\ScpBallot;
use StageRightLabs\Bloom\SCP\ScpStatementPrepare;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\ScpStatementPrepare
 */
class ScpStatementPrepareTest extends TestCase
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
        $scpStatementPrepare = (new ScpStatementPrepare())
            ->withQuorumSetHash(Hash::make('1'))
            ->withBallot($scpBallot)
            ->withPrepared($scpBallot)
            ->withPreparedPrime($scpBallot)
            ->withNC(UInt32::of(1))
            ->withNH(UInt32::of(2));
        $buffer = XDR::fresh()->write($scpStatementPrepare);

        $this->assertEquals('a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAABAAAAB2V4YW1wbGUAAAAAAQAAAAEAAAAHZXhhbXBsZQAAAAABAAAAAQAAAAdleGFtcGxlAAAAAAEAAAAC', $buffer->toBase64());
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
        $scpStatementPrepare = (new ScpStatementPrepare())
            ->withBallot($scpBallot)
            ->withPrepared($scpBallot)
            ->withPreparedPrime($scpBallot)
            ->withNC(UInt32::of(1))
            ->withNH(UInt32::of(2));
        XDR::fresh()->write($scpStatementPrepare);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_ballot_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $scpBallot = (new ScpBallot())
            ->withCounter(UInt32::of(1))
            ->withValue(Value::of('example'));
        $scpStatementPrepare = (new ScpStatementPrepare())
            ->withQuorumSetHash(Hash::make('1'))
            ->withPrepared($scpBallot)
            ->withPreparedPrime($scpBallot)
            ->withNC(UInt32::of(1))
            ->withNH(UInt32::of(2));
        XDR::fresh()->write($scpStatementPrepare);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_prepared_ballot_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $scpBallot = (new ScpBallot())
            ->withCounter(UInt32::of(1))
            ->withValue(Value::of('example'));
        $scpStatementPrepare = (new ScpStatementPrepare())
            ->withQuorumSetHash(Hash::make('1'))
            ->withBallot($scpBallot)
            ->withPreparedPrime($scpBallot)
            ->withNC(UInt32::of(1))
            ->withNH(UInt32::of(2));
        XDR::fresh()->write($scpStatementPrepare);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_prime_prepared_ballot_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $scpBallot = (new ScpBallot())
            ->withCounter(UInt32::of(1))
            ->withValue(Value::of('example'));
        $scpStatementPrepare = (new ScpStatementPrepare())
            ->withQuorumSetHash(Hash::make('1'))
            ->withBallot($scpBallot)
            ->withPrepared($scpBallot)
            ->withNC(UInt32::of(1))
            ->withNH(UInt32::of(2));
        XDR::fresh()->write($scpStatementPrepare);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_nc_value_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $scpBallot = (new ScpBallot())
            ->withCounter(UInt32::of(1))
            ->withValue(Value::of('example'));
        $scpStatementPrepare = (new ScpStatementPrepare())
            ->withQuorumSetHash(Hash::make('1'))
            ->withBallot($scpBallot)
            ->withPrepared($scpBallot)
            ->withPreparedPrime($scpBallot)
            ->withNH(UInt32::of(2));
        XDR::fresh()->write($scpStatementPrepare);
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
        $scpStatementPrepare = (new ScpStatementPrepare())
            ->withQuorumSetHash(Hash::make('1'))
            ->withBallot($scpBallot)
            ->withPrepared($scpBallot)
            ->withPreparedPrime($scpBallot)
            ->withNC(UInt32::of(1));
        XDR::fresh()->write($scpStatementPrepare);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $scpStatementPrepare = XDR::fromBase64('a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAABAAAAB2V4YW1wbGUAAAAAAQAAAAEAAAAHZXhhbXBsZQAAAAABAAAAAQAAAAdleGFtcGxlAAAAAAEAAAAC')
            ->read(ScpStatementPrepare::class);

        $this->assertInstanceOf(ScpStatementPrepare::class, $scpStatementPrepare);
        $this->assertInstanceOf(Hash::class, $scpStatementPrepare->getQuorumSetHash());
        $this->assertInstanceOf(ScpBallot::class, $scpStatementPrepare->getBallot());
        $this->assertInstanceOf(ScpBallot::class, $scpStatementPrepare->getPrepared());
        $this->assertInstanceOf(ScpBallot::class, $scpStatementPrepare->getPreparedPrime());
        $this->assertInstanceOf(UInt32::class, $scpStatementPrepare->getNC());
        $this->assertInstanceOf(UInt32::class, $scpStatementPrepare->getNH());
    }

    /**
     * @test
     * @covers ::withQuorumSetHash
     * @covers ::getQuorumSetHash
     */
    public function it_accepts_a_quorum_set_hash()
    {
        $scpStatementPrepare = (new ScpStatementPrepare())
            ->withQuorumSetHash(Hash::make('1'));

        $this->assertInstanceOf(ScpStatementPrepare::class, $scpStatementPrepare);
        $this->assertInstanceOf(Hash::class, $scpStatementPrepare->getQuorumSetHash());
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
        $scpStatementPrepare = (new ScpStatementPrepare())
            ->withBallot($scpBallot);

        $this->assertInstanceOf(ScpStatementPrepare::class, $scpStatementPrepare);
        $this->assertInstanceOf(ScpBallot::class, $scpStatementPrepare->getBallot());
    }

    /**
     * @test
     * @covers ::withPrepared
     * @covers ::getPrepared
     */
    public function it_accepts_a_prepared_ballot()
    {
        $scpBallot = (new ScpBallot())
            ->withCounter(UInt32::of(1))
            ->withValue(Value::of('example'));
        $scpStatementPrepareA = (new ScpStatementPrepare())
            ->withPrepared($scpBallot);
        $scpStatementPrepareB = new ScpStatementPrepare();

        $this->assertInstanceOf(ScpStatementPrepare::class, $scpStatementPrepareA);
        $this->assertInstanceOf(ScpBallot::class, $scpStatementPrepareA->getPrepared());
        $this->assertNull($scpStatementPrepareB->getPrepared());
    }

    /**
     * @test
     * @covers ::withPreparedPrime
     * @covers ::getPreparedPrime
     */
    public function it_accepts_a_prime_prepared_ballot()
    {
        $scpBallot = (new ScpBallot())
            ->withCounter(UInt32::of(1))
            ->withValue(Value::of('example'));
        $scpStatementPrepareA = (new ScpStatementPrepare())
            ->withPreparedPrime($scpBallot);
        $scpStatementPrepareB = new ScpStatementPrepare();

        $this->assertInstanceOf(ScpStatementPrepare::class, $scpStatementPrepareA);
        $this->assertInstanceOf(ScpBallot::class, $scpStatementPrepareA->getPreparedPrime());
        $this->assertNull($scpStatementPrepareB->getPreparedPrime());
    }

    /**
     * @test
     * @covers ::withNC
     * @covers ::getNC
     */
    public function it_accepts_a_uint32_as_a_nc_value()
    {
        $scpStatementPrepare = (new ScpStatementPrepare())
            ->withNC(UInt32::of(1));

        $this->assertInstanceOf(ScpStatementPrepare::class, $scpStatementPrepare);
        $this->assertInstanceOf(UInt32::class, $scpStatementPrepare->getNC());
    }

    /**
     * @test
     * @covers ::withNC
     * @covers ::getNC
     */
    public function it_accepts_a_native_int_as_a_nc_value()
    {
        $scpStatementPrepare = (new ScpStatementPrepare())
            ->withNC(1);

        $this->assertInstanceOf(ScpStatementPrepare::class, $scpStatementPrepare);
        $this->assertInstanceOf(UInt32::class, $scpStatementPrepare->getNC());
    }

    /**
     * @test
     * @covers ::withNH
     * @covers ::getNH
     */
    public function it_accepts_a_uint32_as_a_nh_value()
    {
        $scpStatementPrepare = (new ScpStatementPrepare())
            ->withNH(UInt32::of(2));

        $this->assertInstanceOf(ScpStatementPrepare::class, $scpStatementPrepare);
        $this->assertInstanceOf(UInt32::class, $scpStatementPrepare->getNH());
    }

    /**
     * @test
     * @covers ::withNH
     * @covers ::getNH
     */
    public function it_accepts_a_native_int_as_a_nh_value()
    {
        $scpStatementPrepare = (new ScpStatementPrepare())
            ->withNH(2);

        $this->assertInstanceOf(ScpStatementPrepare::class, $scpStatementPrepare);
        $this->assertInstanceOf(UInt32::class, $scpStatementPrepare->getNH());
    }
}

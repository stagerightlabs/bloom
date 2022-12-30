<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Primitives\Value;
use StageRightLabs\Bloom\SCP\ScpBallot;
use StageRightLabs\Bloom\SCP\ScpNomination;
use StageRightLabs\Bloom\SCP\ScpStatementConfirm;
use StageRightLabs\Bloom\SCP\ScpStatementExternalize;
use StageRightLabs\Bloom\SCP\ScpStatementPledges;
use StageRightLabs\Bloom\SCP\ScpStatementPrepare;
use StageRightLabs\Bloom\SCP\ScpStatementType;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\ScpStatementPledges
 */
class ScpStatementPledgesTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(ScpStatementType::class, ScpStatementPledges::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            ScpStatementType::SCP_ST_PREPARE     => ScpStatementPrepare::class,
            ScpStatementType::SCP_ST_CONFIRM     => ScpStatementConfirm::class,
            ScpStatementType::SCP_ST_EXTERNALIZE => ScpStatementExternalize::class,
            ScpStatementType::SCP_ST_NOMINATE    => ScpNomination::class,
        ];

        $this->assertEquals($expected, ScpStatementPledges::arms());
    }

    /**
     * @test
     * @covers ::wrapPrepareStatement
     * @covers ::unwrap
     */
    public function it_can_wrap_a_scp_statement_prepare()
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
        $scpStatementPledges = ScpStatementPledges::wrapPrepareStatement($scpStatementPrepare);

        $this->assertInstanceOf(ScpStatementPledges::class, $scpStatementPledges);
        $this->assertInstanceOf(ScpStatementPrepare::class, $scpStatementPledges->unwrap());
    }

    /**
     * @test
     * @covers ::wrapConfirmStatement
     * @covers ::unwrap
     */
    public function it_can_wrap_a_scp_statement_confirm()
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
        $scpStatementPledges = ScpStatementPledges::wrapConfirmStatement($scpStatementConfirm);

        $this->assertInstanceOf(ScpStatementPledges::class, $scpStatementPledges);
        $this->assertInstanceOf(ScpStatementConfirm::class, $scpStatementPledges->unwrap());
    }

    /**
     * @test
     * @covers ::wrapExternalizeStatement
     * @covers ::unwrap
     */
    public function it_can_wrap_a_scp_statement_externalize()
    {
        $scpBallot = (new ScpBallot())
            ->withCounter(UInt32::of(1))
            ->withValue(Value::of('example'));
        $scpStatementExternalize = (new ScpStatementExternalize())
            ->withCommit($scpBallot)
            ->withNH(UInt32::of(1))
            ->withCommitQuorumSetHash(Hash::make('1'));
        $scpStatementPledges = ScpStatementPledges::wrapExternalizeStatement($scpStatementExternalize);

        $this->assertInstanceOf(ScpStatementPledges::class, $scpStatementPledges);
        $this->assertInstanceOf(ScpStatementExternalize::class, $scpStatementPledges->unwrap());
    }

    /**
     * @test
     * @covers ::wrapNomination
     * @covers ::unwrap
     */
    public function it_can_wrap_a_scp_nomination()
    {
        $scpNomination = (new ScpNomination())
            ->withQuorumSetHash(Hash::make('1'));
        $scpStatementPledges = ScpStatementPledges::wrapNomination($scpNomination);

        $this->assertInstanceOf(ScpStatementPledges::class, $scpStatementPledges);
        $this->assertInstanceOf(ScpNomination::class, $scpStatementPledges->unwrap());
    }
}

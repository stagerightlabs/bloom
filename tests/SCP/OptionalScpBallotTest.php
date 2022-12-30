<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Primitives\Value;
use StageRightLabs\Bloom\SCP\OptionalScpBallot;
use StageRightLabs\Bloom\SCP\ScpBallot;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\OptionalScpBallot
 */
class OptionalScpBallotTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrValueType
     */
    public function it_defines_an_xdr_value_type()
    {
        $this->assertEquals(ScpBallot::class, OptionalScpBallot::getXdrValueType());
    }

    /**
     * @test
     * @covers ::some
     */
    public function it_can_be_instantiated_from_an_scp_ballot()
    {
        $scpBallot = (new ScpBallot())->withValue(Value::of('example'));
        $optional = OptionalScpBallot::some($scpBallot);

        $this->assertInstanceOf(OptionalScpBallot::class, $optional);
    }

    /**
     * @test
     * @covers ::unwrap
     */
    public function it_returns_the_scp_ballot()
    {
        $scpBallot = (new ScpBallot())->withValue(Value::of('example'));
        $optional = OptionalScpBallot::some($scpBallot);

        $this->assertInstanceOf(ScpBallot::class, $optional->unwrap());
    }
}

<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\AllowTrustResultCode;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\AllowTrustResultCode
 */
class AllowTrustResultCodeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0  => AllowTrustResultCode::ALLOW_TRUST_SUCCESS,
            -1 => AllowTrustResultCode::ALLOW_TRUST_MALFORMED,
            -2 => AllowTrustResultCode::ALLOW_TRUST_NO_TRUST_LINE,
            -3 => AllowTrustResultCode::ALLOW_TRUST_TRUST_NOT_REQUIRED,
            -4 => AllowTrustResultCode::ALLOW_TRUST_CANT_REVOKE,
            -5 => AllowTrustResultCode::ALLOW_TRUST_SELF_NOT_ALLOWED,
            -6 => AllowTrustResultCode::ALLOW_TRUST_LOW_RESERVE,
        ];
        $allowTrustResultCode = new AllowTrustResultCode();

        $this->assertEquals($expected, $allowTrustResultCode->getOptions());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_type()
    {
        $allowTrustResultCode = AllowTrustResultCode::success();

        $this->assertInstanceOf(AllowTrustResultCode::class, $allowTrustResultCode);
        $this->assertEquals(AllowTrustResultCode::ALLOW_TRUST_SUCCESS, $allowTrustResultCode->getType());
    }

    /**
     * @test
     * @covers ::malformed
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_malformed_type()
    {
        $allowTrustResultCode = AllowTrustResultCode::malformed();

        $this->assertInstanceOf(AllowTrustResultCode::class, $allowTrustResultCode);
        $this->assertEquals(AllowTrustResultCode::ALLOW_TRUST_MALFORMED, $allowTrustResultCode->getType());
    }

    /**
     * @test
     * @covers ::noTrustLine
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_no_trustline_type()
    {
        $allowTrustResultCode = AllowTrustResultCode::noTrustLine();

        $this->assertInstanceOf(AllowTrustResultCode::class, $allowTrustResultCode);
        $this->assertEquals(AllowTrustResultCode::ALLOW_TRUST_NO_TRUST_LINE, $allowTrustResultCode->getType());
    }

    /**
     * @test
     * @covers ::trustNotRequired
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_trust_not_required_type()
    {
        $allowTrustResultCode = AllowTrustResultCode::trustNotRequired();

        $this->assertInstanceOf(AllowTrustResultCode::class, $allowTrustResultCode);
        $this->assertEquals(AllowTrustResultCode::ALLOW_TRUST_TRUST_NOT_REQUIRED, $allowTrustResultCode->getType());
    }

    /**
     * @test
     * @covers ::cantRevoke
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_cant_revoke_type()
    {
        $allowTrustResultCode = AllowTrustResultCode::cantRevoke();

        $this->assertInstanceOf(AllowTrustResultCode::class, $allowTrustResultCode);
        $this->assertEquals(AllowTrustResultCode::ALLOW_TRUST_CANT_REVOKE, $allowTrustResultCode->getType());
    }

    /**
     * @test
     * @covers ::selfNotAllowed
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_self_not_allowed_type()
    {
        $allowTrustResultCode = AllowTrustResultCode::selfNotAllowed();

        $this->assertInstanceOf(AllowTrustResultCode::class, $allowTrustResultCode);
        $this->assertEquals(AllowTrustResultCode::ALLOW_TRUST_SELF_NOT_ALLOWED, $allowTrustResultCode->getType());
    }

    /**
     * @test
     * @covers ::lowReserve
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_low_reserve_type()
    {
        $allowTrustResultCode = AllowTrustResultCode::lowReserve();

        $this->assertInstanceOf(AllowTrustResultCode::class, $allowTrustResultCode);
        $this->assertEquals(AllowTrustResultCode::ALLOW_TRUST_LOW_RESERVE, $allowTrustResultCode->getType());
    }
}

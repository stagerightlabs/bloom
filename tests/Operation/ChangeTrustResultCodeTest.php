<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\ChangeTrustResultCode;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\ChangeTrustResultCode
 */
class ChangeTrustResultCodeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0  => ChangeTrustResultCode::CHANGE_TRUST_SUCCESS,
            -1 => ChangeTrustResultCode::CHANGE_TRUST_MALFORMED,
            -2 => ChangeTrustResultCode::CHANGE_TRUST_NO_ISSUER,
            -3 => ChangeTrustResultCode::CHANGE_TRUST_INVALID_LIMIT,
            -4 => ChangeTrustResultCode::CHANGE_TRUST_LOW_RESERVE,
            -5 => ChangeTrustResultCode::CHANGE_TRUST_SELF_NOT_ALLOWED,
            -6 => ChangeTrustResultCode::CHANGE_TRUST_TRUST_LINE_MISSING,
            -7 => ChangeTrustResultCode::CHANGE_TRUST_CANNOT_DELETE,
            -8 => ChangeTrustResultCode::CHANGE_TRUST_NOT_AUTH_MAINTAIN_LIABILITIES,
        ];
        $changeTrustResultCode = new ChangeTrustResultCode();

        $this->assertEquals($expected, $changeTrustResultCode->getOptions());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_type()
    {
        $changeTrustResultCode = ChangeTrustResultCode::success();

        $this->assertInstanceOf(ChangeTrustResultCode::class, $changeTrustResultCode);
        $this->assertEquals(ChangeTrustResultCode::CHANGE_TRUST_SUCCESS, $changeTrustResultCode->getType());
    }

    /**
     * @test
     * @covers ::malformed
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_malformed_type()
    {
        $changeTrustResultCode = ChangeTrustResultCode::malformed();

        $this->assertInstanceOf(ChangeTrustResultCode::class, $changeTrustResultCode);
        $this->assertEquals(ChangeTrustResultCode::CHANGE_TRUST_MALFORMED, $changeTrustResultCode->getType());
    }

    /**
     * @test
     * @covers ::noIssuer
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_no_issuer_type()
    {
        $changeTrustResultCode = ChangeTrustResultCode::noIssuer();

        $this->assertInstanceOf(ChangeTrustResultCode::class, $changeTrustResultCode);
        $this->assertEquals(ChangeTrustResultCode::CHANGE_TRUST_NO_ISSUER, $changeTrustResultCode->getType());
    }

    /**
     * @test
     * @covers ::invalidLimit
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_invalid_limit_type()
    {
        $changeTrustResultCode = ChangeTrustResultCode::invalidLimit();

        $this->assertInstanceOf(ChangeTrustResultCode::class, $changeTrustResultCode);
        $this->assertEquals(ChangeTrustResultCode::CHANGE_TRUST_INVALID_LIMIT, $changeTrustResultCode->getType());
    }

    /**
     * @test
     * @covers ::lowReserve
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_low_reserve_type()
    {
        $changeTrustResultCode = ChangeTrustResultCode::lowReserve();

        $this->assertInstanceOf(ChangeTrustResultCode::class, $changeTrustResultCode);
        $this->assertEquals(ChangeTrustResultCode::CHANGE_TRUST_LOW_RESERVE, $changeTrustResultCode->getType());
    }

    /**
     * @test
     * @covers ::selfNotAllowed
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_self_hot_allowed_type()
    {
        $changeTrustResultCode = ChangeTrustResultCode::selfNotAllowed();

        $this->assertInstanceOf(ChangeTrustResultCode::class, $changeTrustResultCode);
        $this->assertEquals(ChangeTrustResultCode::CHANGE_TRUST_SELF_NOT_ALLOWED, $changeTrustResultCode->getType());
    }

    /**
     * @test
     * @covers ::trustlineMissing
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_trustline_missing_type()
    {
        $changeTrustResultCode = ChangeTrustResultCode::trustlineMissing();

        $this->assertInstanceOf(ChangeTrustResultCode::class, $changeTrustResultCode);
        $this->assertEquals(ChangeTrustResultCode::CHANGE_TRUST_TRUST_LINE_MISSING, $changeTrustResultCode->getType());
    }

    /**
     * @test
     * @covers ::cannotDelete
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_cannot_delete_type()
    {
        $changeTrustResultCode = ChangeTrustResultCode::cannotDelete();

        $this->assertInstanceOf(ChangeTrustResultCode::class, $changeTrustResultCode);
        $this->assertEquals(ChangeTrustResultCode::CHANGE_TRUST_CANNOT_DELETE, $changeTrustResultCode->getType());
    }

    /**
     * @test
     * @covers ::notAuthMaintainLiabilities
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_not_auth_maintain_liabilities_type()
    {
        $changeTrustResultCode = ChangeTrustResultCode::notAuthMaintainLiabilities();

        $this->assertInstanceOf(ChangeTrustResultCode::class, $changeTrustResultCode);
        $this->assertEquals(ChangeTrustResultCode::CHANGE_TRUST_NOT_AUTH_MAINTAIN_LIABILITIES, $changeTrustResultCode->getType());
    }
}

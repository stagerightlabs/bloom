<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\ChangeTrustResult;
use StageRightLabs\Bloom\Operation\ChangeTrustResultCode;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\ChangeTrustResult
 */
class ChangeTrustResultTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(ChangeTrustResultCode::class, ChangeTrustResult::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            ChangeTrustResultCode::CHANGE_TRUST_SUCCESS                       => XDR::VOID,
            ChangeTrustResultCode::CHANGE_TRUST_MALFORMED                     => XDR::VOID,
            ChangeTrustResultCode::CHANGE_TRUST_NO_ISSUER                     => XDR::VOID,
            ChangeTrustResultCode::CHANGE_TRUST_INVALID_LIMIT                 => XDR::VOID,
            ChangeTrustResultCode::CHANGE_TRUST_LOW_RESERVE                   => XDR::VOID,
            ChangeTrustResultCode::CHANGE_TRUST_SELF_NOT_ALLOWED              => XDR::VOID,
            ChangeTrustResultCode::CHANGE_TRUST_TRUST_LINE_MISSING            => XDR::VOID,
            ChangeTrustResultCode::CHANGE_TRUST_CANNOT_DELETE                 => XDR::VOID,
            ChangeTrustResultCode::CHANGE_TRUST_NOT_AUTH_MAINTAIN_LIABILITIES => XDR::VOID,
        ];

        $this->assertEquals($expected, ChangeTrustResult::arms());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_union()
    {
        $changeTrustResult = ChangeTrustResult::success();
        $this->assertEquals(ChangeTrustResultCode::CHANGE_TRUST_SUCCESS, $changeTrustResult->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_null_if_no_value_is_set()
    {
        $this->assertNull((new ChangeTrustResult())->getType());
    }

    /**
     * @test
     * @covers ::simulate
     */
    public function it_can_simulate_an_error_result()
    {
        $changeTrustResult = ChangeTrustResult::simulate(ChangeTrustResultCode::lowReserve());

        $this->assertInstanceOf(ChangeTrustResult::class, $changeTrustResult);
        $this->assertEquals(ChangeTrustResultCode::CHANGE_TRUST_LOW_RESERVE, $changeTrustResult->getType());
    }

    /**
     * @test
     * @covers ::wasSuccessful
     * @covers ::wasNotSuccessful
     */
    public function it_knows_if_it_was_successful()
    {
        $changeTrustResultA = ChangeTrustResult::success();
        $changeTrustResultB = new ChangeTrustResult();

        $this->assertTrue($changeTrustResultA->wasSuccessful());
        $this->assertFalse($changeTrustResultA->wasNotSuccessful());
        $this->assertTrue($changeTrustResultB->wasNotSuccessful());
        $this->assertFalse($changeTrustResultB->wasSuccessful());
    }

    /**
     * @test
     * @covers ::getErrorMessage
     * @covers ::getErrorCode
     */
    public function it_returns_an_error_message_and_code()
    {
        $changeTrustResult = ChangeTrustResult::simulate(ChangeTrustResultCode::lowReserve());

        $this->assertNotEmpty($changeTrustResult->getErrorMessage());
        $this->assertEquals('change_trust_low_reserve', $changeTrustResult->getErrorCode());
        $this->assertNull((new ChangeTrustResult())->getErrorMessage());
        $this->assertNull((new ChangeTrustResult())->getErrorCode());
    }
}

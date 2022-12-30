<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\RevokeSponsorshipResult;
use StageRightLabs\Bloom\Operation\RevokeSponsorshipResultCode;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\RevokeSponsorshipResult
 */
class RevokeSponsorshipResultTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(RevokeSponsorshipResultCode::class, RevokeSponsorshipResult::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_SUCCESS           => XDR::VOID,
            RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_DOES_NOT_EXIST    => XDR::VOID,
            RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_NOT_SPONSOR       => XDR::VOID,
            RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_LOW_RESERVE       => XDR::VOID,
            RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_ONLY_TRANSFERABLE => XDR::VOID,
            RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_MALFORMED         => XDR::VOID,
        ];

        $this->assertEquals($expected, RevokeSponsorshipResult::arms());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_union()
    {
        $revokeSponsorshipResult = RevokeSponsorshipResult::success();
        $this->assertEquals(RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_SUCCESS, $revokeSponsorshipResult->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_null_if_no_value_is_set()
    {
        $this->assertNull((new RevokeSponsorshipResult())->getType());
    }

    /**
     * @test
     * @covers ::simulate
     */
    public function it_can_simulate_an_error_result()
    {
        $revokeSponsorshipResult = RevokeSponsorshipResult::simulate(RevokeSponsorshipResultCode::lowReserve());

        $this->assertInstanceOf(RevokeSponsorshipResult::class, $revokeSponsorshipResult);
        $this->assertEquals(RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_LOW_RESERVE, $revokeSponsorshipResult->getType());
    }

    /**
     * @test
     * @covers ::wasSuccessful
     * @covers ::wasNotSuccessful
     */
    public function it_knows_if_it_was_successful()
    {
        $revokeSponsorshipResultA = RevokeSponsorshipResult::success();
        $revokeSponsorshipResultB = new RevokeSponsorshipResult();

        $this->assertTrue($revokeSponsorshipResultA->wasSuccessful());
        $this->assertFalse($revokeSponsorshipResultA->wasNotSuccessful());
        $this->assertTrue($revokeSponsorshipResultB->wasNotSuccessful());
        $this->assertFalse($revokeSponsorshipResultB->wasSuccessful());
    }

    /**
     * @test
     * @covers ::getErrorMessage
     * @covers ::getErrorCode
     */
    public function it_returns_an_error_message_and_code()
    {
        $revokeSponsorshipResult = RevokeSponsorshipResult::simulate(RevokeSponsorshipResultCode::lowReserve());

        $this->assertNotEmpty($revokeSponsorshipResult->getErrorMessage());
        $this->assertEquals('revoke_sponsorship_low_reserve', $revokeSponsorshipResult->getErrorCode());
        $this->assertNull((new RevokeSponsorshipResult())->getErrorMessage());
        $this->assertNull((new RevokeSponsorshipResult())->getErrorCode());
    }
}

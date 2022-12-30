<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\RevokeSponsorshipResultCode;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\RevokeSponsorshipResultCode
 */
class RevokeSponsorshipResultCodeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0  => RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_SUCCESS,
            -1 => RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_DOES_NOT_EXIST,
            -2 => RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_NOT_SPONSOR,
            -3 => RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_LOW_RESERVE,
            -4 => RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_ONLY_TRANSFERABLE,
            -5 => RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_MALFORMED,
        ];
        $revokeSponsorshipResultCode = new RevokeSponsorshipResultCode();

        $this->assertEquals($expected, $revokeSponsorshipResultCode->getOptions());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_type()
    {
        $revokeSponsorshipResultCode = RevokeSponsorshipResultCode::success();

        $this->assertInstanceOf(RevokeSponsorshipResultCode::class, $revokeSponsorshipResultCode);
        $this->assertEquals(RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_SUCCESS, $revokeSponsorshipResultCode->getType());
    }

    /**
     * @test
     * @covers ::doesNotExist
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_does_not_exist_type()
    {
        $revokeSponsorshipResultCode = RevokeSponsorshipResultCode::doesNotExist();

        $this->assertInstanceOf(RevokeSponsorshipResultCode::class, $revokeSponsorshipResultCode);
        $this->assertEquals(RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_DOES_NOT_EXIST, $revokeSponsorshipResultCode->getType());
    }

    /**
     * @test
     * @covers ::lowReserve
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_low_reserve_type()
    {
        $revokeSponsorshipResultCode = RevokeSponsorshipResultCode::lowReserve();

        $this->assertInstanceOf(RevokeSponsorshipResultCode::class, $revokeSponsorshipResultCode);
        $this->assertEquals(RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_LOW_RESERVE, $revokeSponsorshipResultCode->getType());
    }

    /**
     * @test
     * @covers ::onlyTransferable
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_only_transferable_type()
    {
        $revokeSponsorshipResultCode = RevokeSponsorshipResultCode::onlyTransferable();

        $this->assertInstanceOf(RevokeSponsorshipResultCode::class, $revokeSponsorshipResultCode);
        $this->assertEquals(RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_ONLY_TRANSFERABLE, $revokeSponsorshipResultCode->getType());
    }

    /**
     * @test
     * @covers ::malformed
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_malformed_type()
    {
        $revokeSponsorshipResultCode = RevokeSponsorshipResultCode::malformed();

        $this->assertInstanceOf(RevokeSponsorshipResultCode::class, $revokeSponsorshipResultCode);
        $this->assertEquals(RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_MALFORMED, $revokeSponsorshipResultCode->getType());
    }
}

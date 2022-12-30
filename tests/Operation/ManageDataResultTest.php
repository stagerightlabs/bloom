<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\ManageDataResult;
use StageRightLabs\Bloom\Operation\ManageDataResultCode;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\ManageDataResult
 */
class ManageDataResultTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(ManageDataResultCode::class, ManageDataResult::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            ManageDataResultCode::MANAGE_DATA_SUCCESS           => XDR::VOID,
            ManageDataResultCode::MANAGE_DATA_NOT_SUPPORTED_YET => XDR::VOID,
            ManageDataResultCode::MANAGE_DATA_NAME_NOT_FOUND    => XDR::VOID,
            ManageDataResultCode::MANAGE_DATA_LOW_RESERVE       => XDR::VOID,
            ManageDataResultCode::MANAGE_DATA_INVALID_NAME      => XDR::VOID,
        ];

        $this->assertEquals($expected, ManageDataResult::arms());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_union()
    {
        $manageDataResult = ManageDataResult::success();
        $this->assertEquals(ManageDataResultCode::MANAGE_DATA_SUCCESS, $manageDataResult->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_null_if_no_type_is_set()
    {
        $this->assertNull((new ManageDataResult())->getType());
    }

    /**
     * @test
     * @covers ::simulate
     */
    public function it_can_simulate_an_error_result()
    {
        $manageDataResult = ManageDataResult::simulate(ManageDataResultCode::lowReserve());

        $this->assertInstanceOf(ManageDataResult::class, $manageDataResult);
        $this->assertEquals(ManageDataResultCode::MANAGE_DATA_LOW_RESERVE, $manageDataResult->getType());
    }

    /**
     * @test
     * @covers ::wasSuccessful
     * @covers ::wasNotSuccessful
     */
    public function it_knows_if_it_was_successful()
    {
        $manageDataResultA = ManageDataResult::success();
        $manageDataResultB = new ManageDataResult();

        $this->assertTrue($manageDataResultA->wasSuccessful());
        $this->assertFalse($manageDataResultA->wasNotSuccessful());
        $this->assertTrue($manageDataResultB->wasNotSuccessful());
        $this->assertFalse($manageDataResultB->wasSuccessful());
    }

    /**
     * @test
     * @covers ::getErrorMessage
     * @covers ::getErrorCode
     */
    public function it_returns_an_error_message_and_code()
    {
        $manageDataResult = ManageDataResult::simulate(ManageDataResultCode::lowReserve());

        $this->assertNotEmpty($manageDataResult->getErrorMessage());
        $this->assertEquals('manage_data_low_reserve', $manageDataResult->getErrorCode());
        $this->assertNull((new ManageDataResult())->getErrorMessage());
        $this->assertNull((new ManageDataResult())->getErrorCode());
    }
}

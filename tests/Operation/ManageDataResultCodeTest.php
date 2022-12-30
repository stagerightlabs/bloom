<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\ManageDataResultCode;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\ManageDataResultCode
 */
class ManageDataResultCodeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0  => ManageDataResultCode::MANAGE_DATA_SUCCESS,
            -1 => ManageDataResultCode::MANAGE_DATA_NOT_SUPPORTED_YET,
            -2 => ManageDataResultCode::MANAGE_DATA_NAME_NOT_FOUND,
            -3 => ManageDataResultCode::MANAGE_DATA_LOW_RESERVE,
            -4 => ManageDataResultCode::MANAGE_DATA_INVALID_NAME,
        ];
        $manageDataResultCode = new ManageDataResultCode();

        $this->assertEquals($expected, $manageDataResultCode->getOptions());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_type()
    {
        $manageDataResultCode = ManageDataResultCode::success();

        $this->assertInstanceOf(ManageDataResultCode::class, $manageDataResultCode);
        $this->assertEquals(ManageDataResultCode::MANAGE_DATA_SUCCESS, $manageDataResultCode->getType());
    }

    /**
     * @test
     * @covers ::notSupportedYet
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_not_supported_yet_type()
    {
        $manageDataResultCode = ManageDataResultCode::notSupportedYet();

        $this->assertInstanceOf(ManageDataResultCode::class, $manageDataResultCode);
        $this->assertEquals(ManageDataResultCode::MANAGE_DATA_NOT_SUPPORTED_YET, $manageDataResultCode->getType());
    }

    /**
     * @test
     * @covers ::nameNotFound
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_name_not_found_type()
    {
        $manageDataResultCode = ManageDataResultCode::nameNotFound();

        $this->assertInstanceOf(ManageDataResultCode::class, $manageDataResultCode);
        $this->assertEquals(ManageDataResultCode::MANAGE_DATA_NAME_NOT_FOUND, $manageDataResultCode->getType());
    }

    /**
     * @test
     * @covers ::lowReserve
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_low_reserve_type()
    {
        $manageDataResultCode = ManageDataResultCode::lowReserve();

        $this->assertInstanceOf(ManageDataResultCode::class, $manageDataResultCode);
        $this->assertEquals(ManageDataResultCode::MANAGE_DATA_LOW_RESERVE, $manageDataResultCode->getType());
    }

    /**
     * @test
     * @covers ::invalidName
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_invalid_name_type()
    {
        $manageDataResultCode = ManageDataResultCode::invalidName();

        $this->assertInstanceOf(ManageDataResultCode::class, $manageDataResultCode);
        $this->assertEquals(ManageDataResultCode::MANAGE_DATA_INVALID_NAME, $manageDataResultCode->getType());
    }
}

<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\CreateAccountResultCode;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\CreateAccountResultCode
 */
class CreateAccountResultCodeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0  => CreateAccountResultCode::CREATE_ACCOUNT_SUCCESS,
            -1 => CreateAccountResultCode::CREATE_ACCOUNT_MALFORMED,
            -2 => CreateAccountResultCode::CREATE_ACCOUNT_UNDERFUNDED,
            -3 => CreateAccountResultCode::CREATE_ACCOUNT_LOW_RESERVE,
            -4 => CreateAccountResultCode::CREATE_ACCOUNT_ALREADY_EXISTS,
        ];
        $memoType = new CreateAccountResultCode();

        $this->assertEquals($expected, $memoType->getOptions());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_the_selected_type()
    {
        $this->assertEquals(CreateAccountResultCode::CREATE_ACCOUNT_SUCCESS, CreateAccountResultCode::success()->getType());
    }

    /**
     * @test
     * @covers ::success
     */
    public function it_can_be_instantiated_as_a_success_type()
    {
        $createAccountResultCodeType = CreateAccountResultCode::success();

        $this->assertInstanceOf(CreateAccountResultCode::class, $createAccountResultCodeType);
        $this->assertEquals(CreateAccountResultCode::CREATE_ACCOUNT_SUCCESS, $createAccountResultCodeType->getType());
    }

    /**
     * @test
     * @covers ::malformed
     */
    public function it_can_be_instantiated_as_a_malformed_type()
    {
        $createAccountResultCodeType = CreateAccountResultCode::malformed();

        $this->assertInstanceOf(CreateAccountResultCode::class, $createAccountResultCodeType);
        $this->assertEquals(CreateAccountResultCode::CREATE_ACCOUNT_MALFORMED, $createAccountResultCodeType->getType());
    }

    /**
     * @test
     * @covers ::underfunded
     */
    public function it_can_be_instantiated_as_a_underfunded_type()
    {
        $createAccountResultCodeType = CreateAccountResultCode::underfunded();

        $this->assertInstanceOf(CreateAccountResultCode::class, $createAccountResultCodeType);
        $this->assertEquals(CreateAccountResultCode::CREATE_ACCOUNT_UNDERFUNDED, $createAccountResultCodeType->getType());
    }

    /**
     * @test
     * @covers ::lowReserve
     */
    public function it_can_be_instantiated_as_a_low_reserve_type()
    {
        $createAccountResultCodeType = CreateAccountResultCode::lowReserve();

        $this->assertInstanceOf(CreateAccountResultCode::class, $createAccountResultCodeType);
        $this->assertEquals(CreateAccountResultCode::CREATE_ACCOUNT_LOW_RESERVE, $createAccountResultCodeType->getType());
    }

    /**
     * @test
     * @covers ::exists
     */
    public function it_can_be_instantiated_as_a_exists_type()
    {
        $createAccountResultCodeType = CreateAccountResultCode::exists();

        $this->assertInstanceOf(CreateAccountResultCode::class, $createAccountResultCodeType);
        $this->assertEquals(CreateAccountResultCode::CREATE_ACCOUNT_ALREADY_EXISTS, $createAccountResultCodeType->getType());
    }
}

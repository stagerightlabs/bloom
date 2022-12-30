<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\CreateAccountResult;
use StageRightLabs\Bloom\Operation\CreateAccountResultCode;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\CreateAccountResult
 */
class CreateAccountResultTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(CreateAccountResultCode::class, CreateAccountResult::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            CreateAccountResultCode::CREATE_ACCOUNT_SUCCESS        => XDR::VOID,
            CreateAccountResultCode::CREATE_ACCOUNT_MALFORMED      => XDR::VOID,
            CreateAccountResultCode::CREATE_ACCOUNT_UNDERFUNDED    => XDR::VOID,
            CreateAccountResultCode::CREATE_ACCOUNT_LOW_RESERVE    => XDR::VOID,
            CreateAccountResultCode::CREATE_ACCOUNT_ALREADY_EXISTS => XDR::VOID,
        ];

        $this->assertEquals($expected, CreateAccountResult::arms());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_result()
    {
        $createAccountResult = CreateAccountResult::success();
        $this->assertNull($createAccountResult->unwrap());
        $this->assertEquals(CreateAccountResultCode::CREATE_ACCOUNT_SUCCESS, $createAccountResult->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_null_if_no_value_is_set()
    {
        $this->assertNull((new CreateAccountResult())->getType());
    }

    /**
     * @test
     * @covers ::simulate
     */
    public function it_can_simulate_an_error_result()
    {
        $createAccountResult = CreateAccountResult::simulate(CreateAccountResultCode::malformed());

        $this->assertInstanceOf(CreateAccountResult::class, $createAccountResult);
        $this->assertEquals(CreateAccountResultCode::CREATE_ACCOUNT_MALFORMED, $createAccountResult->getType());
    }

    /**
     * @test
     * @covers ::wasSuccessful
     * @covers ::wasNotSuccessful
     */
    public function it_knows_if_it_was_successful()
    {
        $createAccountResultA = CreateAccountResult::success();
        $createAccountResultB = new CreateAccountResult();


        $this->assertTrue($createAccountResultA->wasSuccessful());
        $this->assertFalse($createAccountResultA->wasNotSuccessful());
        $this->assertTrue($createAccountResultB->wasNotSuccessful());
        $this->assertFalse($createAccountResultB->wasSuccessful());
    }

    /**
     * @test
     * @covers ::getErrorMessage
     * @covers ::getErrorCode
     */
    public function it_returns_an_error_message_and_code()
    {
        $createAccountResult = CreateAccountResult::simulate(CreateAccountResultCode::malformed());

        $this->assertNotEmpty($createAccountResult->getErrorMessage());
        $this->assertEquals('create_account_malformed', $createAccountResult->getErrorCode());
        $this->assertNull((new CreateAccountResult())->getErrorMessage());
        $this->assertNull((new CreateAccountResult())->getErrorCode());
    }
}

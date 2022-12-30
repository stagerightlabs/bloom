<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\OperationResultCode;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\OperationResultCode
 */
class OperationResultCodeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0  => OperationResultCode::INNER,
            -1 => OperationResultCode::BAD_AUTH,
            -2 => OperationResultCode::NO_ACCOUNT,
            -3 => OperationResultCode::NOT_SUPPORTED,
            -4 => OperationResultCode::TOO_MANY_SUBENTRIES,
            -5 => OperationResultCode::EXCEEDED_WORK_LIMIT,
            -6 => OperationResultCode::TOO_MANY_SPONSORING,
        ];
        $operationResultCode = new OperationResultCode();

        $this->assertEquals($expected, $operationResultCode->getOptions());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_the_selected_code()
    {
        $operationResultCode = OperationResultCode::inner();
        $this->assertEquals(OperationResultCode::INNER, $operationResultCode->getType());
    }

    /**
     * @test
     * @covers ::inner
     */
    public function it_can_be_instantiated_as_an_inner_type()
    {
        $operationResultCode = OperationResultCode::inner();

        $this->assertInstanceOf(OperationResultCode::class, $operationResultCode);
        $this->assertEquals(OperationResultCode::INNER, $operationResultCode->getType());
    }

    /**
     * @test
     * @covers ::badAuth
     */
    public function it_can_be_instantiated_as_a_bad_auth_type()
    {
        $operationResultCode = OperationResultCode::badAuth();

        $this->assertInstanceOf(OperationResultCode::class, $operationResultCode);
        $this->assertEquals(OperationResultCode::BAD_AUTH, $operationResultCode->getType());
    }

    /**
     * @test
     * @covers ::noAccount
     */
    public function it_can_be_instantiated_as_a_no_account_type()
    {
        $operationResultCode = OperationResultCode::noAccount();

        $this->assertInstanceOf(OperationResultCode::class, $operationResultCode);
        $this->assertEquals(OperationResultCode::NO_ACCOUNT, $operationResultCode->getType());
    }

    /**
     * @test
     * @covers ::notSupported
     */
    public function it_can_be_instantiated_as_a_not_supported_type()
    {
        $operationResultCode = OperationResultCode::notSupported();

        $this->assertInstanceOf(OperationResultCode::class, $operationResultCode);
        $this->assertEquals(OperationResultCode::NOT_SUPPORTED, $operationResultCode->getType());
    }

    /**
     * @test
     * @covers ::tooManySubentries
     */
    public function it_can_be_instantiated_as_a_too_many_subentries_type()
    {
        $operationResultCode = OperationResultCode::tooManySubentries();

        $this->assertInstanceOf(OperationResultCode::class, $operationResultCode);
        $this->assertEquals(OperationResultCode::TOO_MANY_SUBENTRIES, $operationResultCode->getType());
    }

    /**
     * @test
     * @covers ::exceededWorkLimit
     */
    public function it_can_be_instantiated_as_a_exceeded_work_limit_type()
    {
        $operationResultCode = OperationResultCode::exceededWorkLimit();

        $this->assertInstanceOf(OperationResultCode::class, $operationResultCode);
        $this->assertEquals(OperationResultCode::EXCEEDED_WORK_LIMIT, $operationResultCode->getType());
    }

    /**
     * @test
     * @covers ::tooManySponsoring
     */
    public function it_can_be_instantiated_as_a_too_many_sponsoring_type()
    {
        $operationResultCode = OperationResultCode::tooManySponsoring();

        $this->assertInstanceOf(OperationResultCode::class, $operationResultCode);
        $this->assertEquals(OperationResultCode::TOO_MANY_SPONSORING, $operationResultCode->getType());
    }
}

<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\AccountMergeResult;
use StageRightLabs\Bloom\Operation\AccountMergeResultCode;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\AccountMergeResult
 */
class AccountMergeResultTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(AccountMergeResultCode::class, AccountMergeResult::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            AccountMergeResultCode::ACCOUNT_MERGE_SUCCESS         => Int64::class,
            AccountMergeResultCode::ACCOUNT_MERGE_MALFORMED       => XDR::VOID,
            AccountMergeResultCode::ACCOUNT_MERGE_NO_ACCOUNT      => XDR::VOID,
            AccountMergeResultCode::ACCOUNT_MERGE_IMMUTABLE_SET   => XDR::VOID,
            AccountMergeResultCode::ACCOUNT_MERGE_HAS_SUB_ENTRIES => XDR::VOID,
            AccountMergeResultCode::ACCOUNT_MERGE_SEQNUM_TOO_FAR  => XDR::VOID,
            AccountMergeResultCode::ACCOUNT_MERGE_DEST_FULL       => XDR::VOID,
            AccountMergeResultCode::ACCOUNT_MERGE_IS_SPONSOR      => XDR::VOID,
        ];

        $this->assertEquals($expected, AccountMergeResult::arms());
    }

    /**
     * @test
     * @covers ::wrapInt64
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_union()
    {
        $accountMergeResult = AccountMergeResult::wrapInt64(Int64::of(1));
        $this->assertInstanceOf(AccountMergeResult::class, $accountMergeResult);
        $this->assertInstanceOf(Int64::class, $accountMergeResult->unwrap());
        $this->assertEquals(AccountMergeResultCode::ACCOUNT_MERGE_SUCCESS, $accountMergeResult->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_null_if_no_value_is_set()
    {
        $this->assertNull((new AccountMergeResult())->getType());
    }

    /**
     * @test
     * @covers ::simulate
     */
    public function it_can_simulate_an_error_result()
    {
        $accountMergeResult = AccountMergeResult::simulate(AccountMergeResultCode::malformed());

        $this->assertInstanceOf(AccountMergeResult::class, $accountMergeResult);
        $this->assertEquals(AccountMergeResultCode::ACCOUNT_MERGE_MALFORMED, $accountMergeResult->getType());
    }

    /**
     * @test
     * @covers ::wasSuccessful
     * @covers ::wasNotSuccessful
     */
    public function it_knows_if_it_was_successful()
    {
        $accountMergeResultA = AccountMergeResult::wrapInt64(Int64::of(1));
        $accountMergeResultB = new AccountMergeResult();

        $this->assertTrue($accountMergeResultA->wasSuccessful());
        $this->assertFalse($accountMergeResultA->wasNotSuccessful());
        $this->assertTrue($accountMergeResultB->wasNotSuccessful());
        $this->assertFalse($accountMergeResultB->wasSuccessful());
    }

    /**
     * @test
     * @covers ::getErrorMessage
     * @covers ::getErrorCode
     */
    public function it_returns_an_error_message_and_code()
    {
        $accountMergeResult = AccountMergeResult::simulate(AccountMergeResultCode::malformed());

        $this->assertNotEmpty($accountMergeResult->getErrorMessage());
        $this->assertEquals('account_merge_malformed', $accountMergeResult->getErrorCode());
        $this->assertNull((new AccountMergeResult())->getErrorMessage());
        $this->assertNull((new AccountMergeResult())->getErrorCode());
    }
}

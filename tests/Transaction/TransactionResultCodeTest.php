<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\TransactionResultCode;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\TransactionResultCode
 */
class TransactionResultCodeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            1   => TransactionResultCode::FEE_BUMP_INNER_SUCCESS,
            0   => TransactionResultCode::SUCCESS,
            -1  => TransactionResultCode::FAILED,
            -2  => TransactionResultCode::TOO_EARLY,
            -3  => TransactionResultCode::TOO_LATE,
            -4  => TransactionResultCode::MISSING_OPERATION,
            -5  => TransactionResultCode::BAD_SEQ,
            -6  => TransactionResultCode::BAD_AUTH,
            -7  => TransactionResultCode::INSUFFICIENT_BALANCE,
            -8  => TransactionResultCode::NO_ACCOUNT,
            -9  => TransactionResultCode::INSUFFICIENT_FEE,
            -10 => TransactionResultCode::BAD_AUTH_EXTRA,
            -11 => TransactionResultCode::INTERNAL_ERROR,
            -12 => TransactionResultCode::NOT_SUPPORTED,
            -13 => TransactionResultCode::FEE_BUMP_INNER_FAILED,
            -14 => TransactionResultCode::BAD_SPONSORSHIP,
            -15 => TransactionResultCode::BAD_MIN_SEQ_AGE_OR_GAP,
            -16 => TransactionResultCode::MALFORMED,
        ];
        $transactionResultCode = new TransactionResultCode();

        $this->assertEquals($expected, $transactionResultCode->getOptions());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_the_selected_type()
    {
        $transactionResultCode = TransactionResultCode::success();
        $this->assertEquals(TransactionResultCode::SUCCESS, $transactionResultCode->getType());
    }

    /**
     * @test
     * @covers ::feeBumpInnerSuccess
     */
    public function it_can_be_instantiated_as_a_fee_bump_inner_success_code()
    {
        $transactionResultCode = TransactionResultCode::feeBumpInnerSuccess();

        $this->assertInstanceOf(TransactionResultCode::class, $transactionResultCode);
        $this->assertEquals(TransactionResultCode::FEE_BUMP_INNER_SUCCESS, $transactionResultCode->getType());
    }

    /**
     * @test
     * @covers ::success
     */
    public function it_can_be_instantiated_as_a_success_code()
    {
        $transactionResultCode = TransactionResultCode::success();

        $this->assertInstanceOf(TransactionResultCode::class, $transactionResultCode);
        $this->assertEquals(TransactionResultCode::SUCCESS, $transactionResultCode->getType());
    }

    /**
     * @test
     * @covers ::failed
     */
    public function it_can_be_instantiated_as_a_failed_code()
    {
        $transactionResultCode = TransactionResultCode::failed();

        $this->assertInstanceOf(TransactionResultCode::class, $transactionResultCode);
        $this->assertEquals(TransactionResultCode::FAILED, $transactionResultCode->getType());
    }

    /**
     * @test
     * @covers ::tooEarly
     */
    public function it_can_be_instantiated_as_a_too_early_code()
    {
        $transactionResultCode = TransactionResultCode::tooEarly();

        $this->assertInstanceOf(TransactionResultCode::class, $transactionResultCode);
        $this->assertEquals(TransactionResultCode::TOO_EARLY, $transactionResultCode->getType());
    }

    /**
     * @test
     * @covers ::tooLate
     */
    public function it_can_be_instantiated_as_a_too_late_code()
    {
        $transactionResultCode = TransactionResultCode::tooLate();

        $this->assertInstanceOf(TransactionResultCode::class, $transactionResultCode);
        $this->assertEquals(TransactionResultCode::TOO_LATE, $transactionResultCode->getType());
    }

    /**
     * @test
     * @covers ::missingOperation
     */
    public function it_can_be_instantiated_as_a_missing_operation_code()
    {
        $transactionResultCode = TransactionResultCode::missingOperation();

        $this->assertInstanceOf(TransactionResultCode::class, $transactionResultCode);
        $this->assertEquals(TransactionResultCode::MISSING_OPERATION, $transactionResultCode->getType());
    }

    /**
     * @test
     * @covers ::badSeq
     */
    public function it_can_be_instantiated_as_a_bad_seq_code()
    {
        $transactionResultCode = TransactionResultCode::badSeq();

        $this->assertInstanceOf(TransactionResultCode::class, $transactionResultCode);
        $this->assertEquals(TransactionResultCode::BAD_SEQ, $transactionResultCode->getType());
    }

    /**
     * @test
     * @covers ::badAuth
     */
    public function it_can_be_instantiated_as_a_bad_auth_code()
    {
        $transactionResultCode = TransactionResultCode::badAuth();

        $this->assertInstanceOf(TransactionResultCode::class, $transactionResultCode);
        $this->assertEquals(TransactionResultCode::BAD_AUTH, $transactionResultCode->getType());
    }

    /**
     * @test
     * @covers ::insufficientBalance
     */
    public function it_can_be_instantiated_as_a_insufficient_Balance_code()
    {
        $transactionResultCode = TransactionResultCode::insufficientBalance();

        $this->assertInstanceOf(TransactionResultCode::class, $transactionResultCode);
        $this->assertEquals(TransactionResultCode::INSUFFICIENT_BALANCE, $transactionResultCode->getType());
    }

    /**
     * @test
     * @covers ::noAccount
     */
    public function it_can_be_instantiated_as_a_no_account_code()
    {
        $transactionResultCode = TransactionResultCode::noAccount();

        $this->assertInstanceOf(TransactionResultCode::class, $transactionResultCode);
        $this->assertEquals(TransactionResultCode::NO_ACCOUNT, $transactionResultCode->getType());
    }

    /**
     * @test
     * @covers ::insufficientFee
     */
    public function it_can_be_instantiated_as_a_insufficient_fee_code()
    {
        $transactionResultCode = TransactionResultCode::insufficientFee();

        $this->assertInstanceOf(TransactionResultCode::class, $transactionResultCode);
        $this->assertEquals(TransactionResultCode::INSUFFICIENT_FEE, $transactionResultCode->getType());
    }

    /**
     * @test
     * @covers ::badAuthExtra
     */
    public function it_can_be_instantiated_as_a_bad_auth_extra_code()
    {
        $transactionResultCode = TransactionResultCode::badAuthExtra();

        $this->assertInstanceOf(TransactionResultCode::class, $transactionResultCode);
        $this->assertEquals(TransactionResultCode::BAD_AUTH_EXTRA, $transactionResultCode->getType());
    }

    /**
     * @test
     * @covers ::internalError
     */
    public function it_can_be_instantiated_as_a_internal_error_code()
    {
        $transactionResultCode = TransactionResultCode::internalError();

        $this->assertInstanceOf(TransactionResultCode::class, $transactionResultCode);
        $this->assertEquals(TransactionResultCode::INTERNAL_ERROR, $transactionResultCode->getType());
    }

    /**
     * @test
     * @covers ::notSupported
     */
    public function it_can_be_instantiated_as_a_not_supported_code()
    {
        $transactionResultCode = TransactionResultCode::notSupported();

        $this->assertInstanceOf(TransactionResultCode::class, $transactionResultCode);
        $this->assertEquals(TransactionResultCode::NOT_SUPPORTED, $transactionResultCode->getType());
    }

    /**
     * @test
     * @covers ::feeBumpInnerFailed
     */
    public function it_can_be_instantiated_as_a_fee_bump_inner_failed_code()
    {
        $transactionResultCode = TransactionResultCode::feeBumpInnerFailed();

        $this->assertInstanceOf(TransactionResultCode::class, $transactionResultCode);
        $this->assertEquals(TransactionResultCode::FEE_BUMP_INNER_FAILED, $transactionResultCode->getType());
    }

    /**
     * @test
     * @covers ::badSponsorship
     */
    public function it_can_be_instantiated_as_a_bad_sponsorship_code()
    {
        $transactionResultCode = TransactionResultCode::badSponsorship();

        $this->assertInstanceOf(TransactionResultCode::class, $transactionResultCode);
        $this->assertEquals(TransactionResultCode::BAD_SPONSORSHIP, $transactionResultCode->getType());
    }

    /**
     * @test
     * @covers ::badMinSeqAgeOrGap
     */
    public function it_can_be_instantiated_as_a_bad_min_seq_age_or_gap_code()
    {
        $transactionResultCode = TransactionResultCode::badMinSeqAgeOrGap();

        $this->assertInstanceOf(TransactionResultCode::class, $transactionResultCode);
        $this->assertEquals(TransactionResultCode::BAD_MIN_SEQ_AGE_OR_GAP, $transactionResultCode->getType());
    }

    /**
     * @test
     * @covers ::malformed
     */
    public function it_can_be_instantiated_as_a_malformed_code()
    {
        $transactionResultCode = TransactionResultCode::malformed();

        $this->assertInstanceOf(TransactionResultCode::class, $transactionResultCode);
        $this->assertEquals(TransactionResultCode::MALFORMED, $transactionResultCode->getType());
    }
}

<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\AccountMergeResultCode;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\AccountMergeResultCode
 */
class AccountMergeResultCodeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0  => AccountMergeResultCode::ACCOUNT_MERGE_SUCCESS,
            -1 => AccountMergeResultCode::ACCOUNT_MERGE_MALFORMED,
            -2 => AccountMergeResultCode::ACCOUNT_MERGE_NO_ACCOUNT,
            -3 => AccountMergeResultCode::ACCOUNT_MERGE_IMMUTABLE_SET,
            -4 => AccountMergeResultCode::ACCOUNT_MERGE_HAS_SUB_ENTRIES,
            -5 => AccountMergeResultCode::ACCOUNT_MERGE_SEQNUM_TOO_FAR,
            -6 => AccountMergeResultCode::ACCOUNT_MERGE_DEST_FULL,
            -7 => AccountMergeResultCode::ACCOUNT_MERGE_IS_SPONSOR,
        ];
        $accountMErgeResultCode = new AccountMergeResultCode();

        $this->assertEquals($expected, $accountMErgeResultCode->getOptions());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_type()
    {
        $accountMErgeResultCode = AccountMergeResultCode::success();

        $this->assertInstanceOf(AccountMergeResultCode::class, $accountMErgeResultCode);
        $this->assertEquals(AccountMergeResultCode::ACCOUNT_MERGE_SUCCESS, $accountMErgeResultCode->getType());
    }

    /**
     * @test
     * @covers ::malformed
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_malformed_type()
    {
        $accountMErgeResultCode = AccountMergeResultCode::malformed();

        $this->assertInstanceOf(AccountMergeResultCode::class, $accountMErgeResultCode);
        $this->assertEquals(AccountMergeResultCode::ACCOUNT_MERGE_MALFORMED, $accountMErgeResultCode->getType());
    }

    /**
     * @test
     * @covers ::noAccount
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_no_account_type()
    {
        $accountMErgeResultCode = AccountMergeResultCode::noAccount();

        $this->assertInstanceOf(AccountMergeResultCode::class, $accountMErgeResultCode);
        $this->assertEquals(AccountMergeResultCode::ACCOUNT_MERGE_NO_ACCOUNT, $accountMErgeResultCode->getType());
    }

    /**
     * @test
     * @covers ::immutableSet
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_immutable_set_type()
    {
        $accountMErgeResultCode = AccountMergeResultCode::immutableSet();

        $this->assertInstanceOf(AccountMergeResultCode::class, $accountMErgeResultCode);
        $this->assertEquals(AccountMergeResultCode::ACCOUNT_MERGE_IMMUTABLE_SET, $accountMErgeResultCode->getType());
    }

    /**
     * @test
     * @covers ::hasSubEntries
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_has_sub_entries_type()
    {
        $accountMErgeResultCode = AccountMergeResultCode::hasSubEntries();

        $this->assertInstanceOf(AccountMergeResultCode::class, $accountMErgeResultCode);
        $this->assertEquals(AccountMergeResultCode::ACCOUNT_MERGE_HAS_SUB_ENTRIES, $accountMErgeResultCode->getType());
    }

    /**
     * @test
     * @covers ::sequenceNumberTooFar
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_sequence_number_too_far_type()
    {
        $accountMErgeResultCode = AccountMergeResultCode::sequenceNumberTooFar();

        $this->assertInstanceOf(AccountMergeResultCode::class, $accountMErgeResultCode);
        $this->assertEquals(AccountMergeResultCode::ACCOUNT_MERGE_SEQNUM_TOO_FAR, $accountMErgeResultCode->getType());
    }

    /**
     * @test
     * @covers ::destinationFull
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_destination_full_type()
    {
        $accountMErgeResultCode = AccountMergeResultCode::destinationFull();

        $this->assertInstanceOf(AccountMergeResultCode::class, $accountMErgeResultCode);
        $this->assertEquals(AccountMergeResultCode::ACCOUNT_MERGE_DEST_FULL, $accountMErgeResultCode->getType());
    }

    /**
     * @test
     * @covers ::isSponsor
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_is_sponsor_type()
    {
        $accountMErgeResultCode = AccountMergeResultCode::isSponsor();

        $this->assertInstanceOf(AccountMergeResultCode::class, $accountMErgeResultCode);
        $this->assertEquals(AccountMergeResultCode::ACCOUNT_MERGE_IS_SPONSOR, $accountMErgeResultCode->getType());
    }
}

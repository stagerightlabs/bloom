<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Operation\AccountMergeResult;
use StageRightLabs\Bloom\Operation\AllowTrustResult;
use StageRightLabs\Bloom\Operation\BeginSponsoringFutureReservesResult;
use StageRightLabs\Bloom\Operation\BumpSequenceResult;
use StageRightLabs\Bloom\Operation\ChangeTrustResult;
use StageRightLabs\Bloom\Operation\ClaimClaimableBalanceResult;
use StageRightLabs\Bloom\Operation\ClawbackClaimableBalanceResult;
use StageRightLabs\Bloom\Operation\ClawbackResult;
use StageRightLabs\Bloom\Operation\CreateAccountResult;
use StageRightLabs\Bloom\Operation\CreateClaimableBalanceResult;
use StageRightLabs\Bloom\Operation\EndSponsoringFutureReservesResult;
use StageRightLabs\Bloom\Operation\InflationResult;
use StageRightLabs\Bloom\Operation\LiquidityPoolDepositResult;
use StageRightLabs\Bloom\Operation\LiquidityPoolWithdrawResult;
use StageRightLabs\Bloom\Operation\ManageBuyOfferResult;
use StageRightLabs\Bloom\Operation\ManageDataResult;
use StageRightLabs\Bloom\Operation\ManageSellOfferResult;
use StageRightLabs\Bloom\Operation\OperationResultTr;
use StageRightLabs\Bloom\Operation\OperationType;
use StageRightLabs\Bloom\Operation\PathPaymentStrictReceiveResult;
use StageRightLabs\Bloom\Operation\PathPaymentStrictSendResult;
use StageRightLabs\Bloom\Operation\PaymentResult;
use StageRightLabs\Bloom\Operation\RevokeSponsorshipResult;
use StageRightLabs\Bloom\Operation\SetOptionsResult;
use StageRightLabs\Bloom\Operation\SetTrustLineFlagsResult;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\OperationResultTr
 */
class OperationResultTrTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(OperationType::class, OperationResultTr::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            OperationType::CREATE_ACCOUNT                   => CreateAccountResult::class,
            OperationType::PAYMENT                          => PaymentResult::class,
            OperationType::PATH_PAYMENT_STRICT_RECEIVE      => PathPaymentStrictReceiveResult::class,
            OperationType::MANAGE_SELL_OFFER                => ManageSellOfferResult::class,
            OperationType::CREATE_PASSIVE_SELL_OFFER        => ManageSellOfferResult::class,
            OperationType::SET_OPTIONS                      => SetOptionsResult::class,
            OperationType::CHANGE_TRUST                     => ChangeTrustResult::class,
            OperationType::ALLOW_TRUST                      => AllowTrustResult::class,
            OperationType::ACCOUNT_MERGE                    => AccountMergeResult::class,
            OperationType::INFLATION                        => InflationResult::class,
            OperationType::MANAGE_DATA                      => ManageDataResult::class,
            OperationType::BUMP_SEQUENCE                    => BumpSequenceResult::class,
            OperationType::MANAGE_BUY_OFFER                 => ManageBuyOfferResult::class,
            OperationType::PATH_PAYMENT_STRICT_SEND         => PathPaymentStrictSendResult::class,
            OperationType::CREATE_CLAIMABLE_BALANCE         => CreateClaimableBalanceResult::class,
            OperationType::CLAIM_CLAIMABLE_BALANCE          => ClaimClaimableBalanceResult::class,
            OperationType::BEGIN_SPONSORING_FUTURE_RESERVES => BeginSponsoringFutureReservesResult::class,
            OperationType::END_SPONSORING_FUTURE_RESERVES   => EndSponsoringFutureReservesResult::class,
            OperationType::REVOKE_SPONSORSHIP               => RevokeSponsorshipResult::class,
            OperationType::CLAWBACK                         => ClawbackResult::class,
            OperationType::CLAWBACK_CLAIMABLE_BALANCE       => ClawbackClaimableBalanceResult::class,
            OperationType::SET_TRUSTLINE_FLAGS              => SetTrustLineFlagsResult::class,
            OperationType::LIQUIDITY_POOL_DEPOSIT           => LiquidityPoolDepositResult::class,
            OperationType::LIQUIDITY_POOL_WITHDRAW          => LiquidityPoolWithdrawResult::class,
        ];

        $this->assertEquals($expected, OperationResultTr::arms());
    }

    /**
     * @test
     * @covers ::wrapCreateAccountResult
     * @covers ::unwrap
     */
    public function it_can_wrap_a_create_account_result()
    {
        $createAccountResult = CreateAccountResult::success();
        $operationResultTr = OperationResultTr::wrapCreateAccountResult($createAccountResult);

        $this->assertInstanceOf(OperationResultTr::class, $operationResultTr);
        $this->assertInstanceOf(CreateAccountResult::class, $operationResultTr->unwrap());
        $this->assertEquals(OperationType::CREATE_ACCOUNT, $operationResultTr->getType());
    }

    /**
     * @test
     * @covers ::wrapPaymentResult
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_payment_result()
    {
        $paymentResult = PaymentResult::success();
        $operationResultTr = OperationResultTr::wrapPaymentResult($paymentResult);

        $this->assertInstanceOf(OperationResultTr::class, $operationResultTr);
        $this->assertInstanceOf(PaymentResult::class, $operationResultTr->unwrap());
        $this->assertEquals(OperationType::PAYMENT, $operationResultTr->getType());
    }

    /**
     * @test
     * @covers ::wrapPathPaymentStrictReceiveResult
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_path_payment_strict_receive_result()
    {
        $asset = Asset::wrapAlphaNum4(AlphaNum4::of('ABCD', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ'));
        $pathPaymentStrictReceiveResult = PathPaymentStrictReceiveResult::wrapNoIssuerAsset($asset);
        $operationResultTr = OperationResultTr::wrapPathPaymentStrictReceiveResult($pathPaymentStrictReceiveResult);

        $this->assertInstanceOf(OperationResultTr::class, $operationResultTr);
        $this->assertInstanceOf(PathPaymentStrictReceiveResult::class, $operationResultTr->unwrap());
        $this->assertEquals(OperationType::PATH_PAYMENT_STRICT_RECEIVE, $operationResultTr->getType());
    }

    /**
     * @test
     * @covers ::wrapManageSellOfferResult
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_manage_sell_offer_result()
    {
        $operationResultTr = OperationResultTr::wrapManageSellOfferResult(new ManageSellOfferResult());

        $this->assertInstanceOf(OperationResultTr::class, $operationResultTr);
        $this->assertInstanceOf(ManageSellOfferResult::class, $operationResultTr->unwrap());
        $this->assertEquals(OperationType::MANAGE_SELL_OFFER, $operationResultTr->getType());
    }

    /**
     * @test
     * @covers ::wrapCreatePassiveSellOfferResult
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_create_passive_sell_offer_result()
    {
        $operationResultTr = OperationResultTr::wrapCreatePassiveSellOfferResult(new ManageSellOfferResult());

        $this->assertInstanceOf(OperationResultTr::class, $operationResultTr);
        $this->assertInstanceOf(ManageSellOfferResult::class, $operationResultTr->unwrap());
        $this->assertEquals(OperationType::CREATE_PASSIVE_SELL_OFFER, $operationResultTr->getType());
    }

    /**
     * @test
     * @covers ::wrapSetOptionsResult
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_set_options_result()
    {
        $operationResultTr = OperationResultTr::wrapSetOptionsResult(new SetOptionsResult());

        $this->assertInstanceOf(OperationResultTr::class, $operationResultTr);
        $this->assertInstanceOf(SetOptionsResult::class, $operationResultTr->unwrap());
        $this->assertEquals(OperationType::SET_OPTIONS, $operationResultTr->getType());
    }

    /**
     * @test
     * @covers ::wrapChangeTrustResult
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_change_trust_result()
    {
        $operationResultTr = OperationResultTr::wrapChangeTrustResult(new ChangeTrustResult());

        $this->assertInstanceOf(OperationResultTr::class, $operationResultTr);
        $this->assertInstanceOf(ChangeTrustResult::class, $operationResultTr->unwrap());
        $this->assertEquals(OperationType::CHANGE_TRUST, $operationResultTr->getType());
    }

    /**
     * @test
     * @covers ::wrapAllowTrustResult
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_an_allow_trust_result()
    {
        $operationResultTr = OperationResultTr::wrapAllowTrustResult(new AllowTrustResult());

        $this->assertInstanceOf(OperationResultTr::class, $operationResultTr);
        $this->assertInstanceOf(AllowTrustResult::class, $operationResultTr->unwrap());
        $this->assertEquals(OperationType::ALLOW_TRUST, $operationResultTr->getType());
    }

    /**
     * @test
     * @covers ::wrapAccountMergeResult
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_an_account_merge_result()
    {
        $operationResultTr = OperationResultTr::wrapAccountMergeResult(new AccountMergeResult());

        $this->assertInstanceOf(OperationResultTr::class, $operationResultTr);
        $this->assertInstanceOf(AccountMergeResult::class, $operationResultTr->unwrap());
        $this->assertEquals(OperationType::ACCOUNT_MERGE, $operationResultTr->getType());
    }

    /**
     * @test
     * @covers ::wrapInflationResult
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_an_inflation_result()
    {
        $operationResultTr = OperationResultTr::wrapInflationResult(new InflationResult());

        $this->assertInstanceOf(OperationResultTr::class, $operationResultTr);
        $this->assertInstanceOf(InflationResult::class, $operationResultTr->unwrap());
        $this->assertEquals(OperationType::INFLATION, $operationResultTr->getType());
    }

    /**
     * @test
     * @covers ::wrapManageDataResult
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_manage_data_result()
    {
        $operationResultTr = OperationResultTr::wrapManageDataResult(new ManageDataResult());

        $this->assertInstanceOf(OperationResultTr::class, $operationResultTr);
        $this->assertInstanceOf(ManageDataResult::class, $operationResultTr->unwrap());
        $this->assertEquals(OperationType::MANAGE_DATA, $operationResultTr->getType());
    }

    /**
     * @test
     * @covers ::wrapBumpSequenceResult
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_bump_sequence_result()
    {
        $operationResultTr = OperationResultTr::wrapBumpSequenceResult(new BumpSequenceResult());

        $this->assertInstanceOf(OperationResultTr::class, $operationResultTr);
        $this->assertInstanceOf(BumpSequenceResult::class, $operationResultTr->unwrap());
        $this->assertEquals(OperationType::BUMP_SEQUENCE, $operationResultTr->getType());
    }

    /**
     * @test
     * @covers ::wrapManageBuyOfferResult
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_manage_buy_offer_result()
    {
        $operationResultTr = OperationResultTr::wrapManageBuyOfferResult(new ManageBuyOfferResult());

        $this->assertInstanceOf(OperationResultTr::class, $operationResultTr);
        $this->assertInstanceOf(ManageBuyOfferResult::class, $operationResultTr->unwrap());
        $this->assertEquals(OperationType::MANAGE_BUY_OFFER, $operationResultTr->getType());
    }

    /**
     * @test
     * @covers ::wrapPathPaymentStrictSendResult
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_path_payment_strict_send_result()
    {
        $operationResultTr = OperationResultTr::wrapPathPaymentStrictSendResult(new PathPaymentStrictSendResult());

        $this->assertInstanceOf(OperationResultTr::class, $operationResultTr);
        $this->assertInstanceOf(PathPaymentStrictSendResult::class, $operationResultTr->unwrap());
        $this->assertEquals(OperationType::PATH_PAYMENT_STRICT_SEND, $operationResultTr->getType());
    }

    /**
     * @test
     * @covers ::wrapCreateClaimableBalanceResult
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_create_claimable_balance_result()
    {
        $operationResultTr = OperationResultTr::wrapCreateClaimableBalanceResult(new CreateClaimableBalanceResult());

        $this->assertInstanceOf(OperationResultTr::class, $operationResultTr);
        $this->assertInstanceOf(CreateClaimableBalanceResult::class, $operationResultTr->unwrap());
        $this->assertEquals(OperationType::CREATE_CLAIMABLE_BALANCE, $operationResultTr->getType());
    }

    /**
     * @test
     * @covers ::wrapClaimClaimableBalanceResult
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_claim_claimable_balance_result()
    {
        $operationResultTr = OperationResultTr::wrapClaimClaimableBalanceResult(new ClaimClaimableBalanceResult());

        $this->assertInstanceOf(OperationResultTr::class, $operationResultTr);
        $this->assertInstanceOf(ClaimClaimableBalanceResult::class, $operationResultTr->unwrap());
        $this->assertEquals(OperationType::CLAIM_CLAIMABLE_BALANCE, $operationResultTr->getType());
    }

    /**
     * @test
     * @covers ::wrapBeginSponsoringFutureReservesResult
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_begin_sponsoring_future_reserves_result()
    {
        $operationResultTr = OperationResultTr::wrapBeginSponsoringFutureReservesResult(new BeginSponsoringFutureReservesResult());

        $this->assertInstanceOf(OperationResultTr::class, $operationResultTr);
        $this->assertInstanceOf(BeginSponsoringFutureReservesResult::class, $operationResultTr->unwrap());
        $this->assertEquals(OperationType::BEGIN_SPONSORING_FUTURE_RESERVES, $operationResultTr->getType());
    }

    /**
     * @test
     * @covers ::wrapEndSponsoringFutureReservesResult
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_an_end_sponsoring_future_reserves_result()
    {
        $operationResultTr = OperationResultTr::wrapEndSponsoringFutureReservesResult(new EndSponsoringFutureReservesResult());

        $this->assertInstanceOf(OperationResultTr::class, $operationResultTr);
        $this->assertInstanceOf(EndSponsoringFutureReservesResult::class, $operationResultTr->unwrap());
        $this->assertEquals(OperationType::END_SPONSORING_FUTURE_RESERVES, $operationResultTr->getType());
    }

    /**
     * @test
     * @covers ::wrapRevokeSponsorshipResult
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_revoke_sponsorship_result()
    {
        $operationResultTr = OperationResultTr::wrapRevokeSponsorshipResult(new RevokeSponsorshipResult());

        $this->assertInstanceOf(OperationResultTr::class, $operationResultTr);
        $this->assertInstanceOf(RevokeSponsorshipResult::class, $operationResultTr->unwrap());
        $this->assertEquals(OperationType::REVOKE_SPONSORSHIP, $operationResultTr->getType());
    }

    /**
     * @test
     * @covers ::wrapClawbackResult
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_clawback_result()
    {
        $operationResultTr = OperationResultTr::wrapClawbackResult(new ClawbackResult());

        $this->assertInstanceOf(OperationResultTr::class, $operationResultTr);
        $this->assertInstanceOf(ClawbackResult::class, $operationResultTr->unwrap());
        $this->assertEquals(OperationType::CLAWBACK, $operationResultTr->getType());
    }

    /**
     * @test
     * @covers ::wrapClawbackClaimableBalanceResult
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_clawback_claimable_balance_result()
    {
        $operationResultTr = OperationResultTr::wrapClawbackClaimableBalanceResult(new ClawbackClaimableBalanceResult());

        $this->assertInstanceOf(OperationResultTr::class, $operationResultTr);
        $this->assertInstanceOf(ClawbackClaimableBalanceResult::class, $operationResultTr->unwrap());
        $this->assertEquals(OperationType::CLAWBACK_CLAIMABLE_BALANCE, $operationResultTr->getType());
    }

    /**
     * @test
     * @covers ::wrapSetTrustLineFlagsResult
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_set_trust_line_flag_result()
    {
        $operationResultTr = OperationResultTr::wrapSetTrustLineFlagsResult(new SetTrustLineFlagsResult());

        $this->assertInstanceOf(OperationResultTr::class, $operationResultTr);
        $this->assertInstanceOf(SetTrustLineFlagsResult::class, $operationResultTr->unwrap());
        $this->assertEquals(OperationType::SET_TRUSTLINE_FLAGS, $operationResultTr->getType());
    }

    /**
     * @test
     * @covers ::wrapLiquidityPoolDepositResult
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_liquidity_pool_deposit_result()
    {
        $operationResultTr = OperationResultTr::wrapLiquidityPoolDepositResult(new LiquidityPoolDepositResult());

        $this->assertInstanceOf(OperationResultTr::class, $operationResultTr);
        $this->assertInstanceOf(LiquidityPoolDepositResult::class, $operationResultTr->unwrap());
        $this->assertEquals(OperationType::LIQUIDITY_POOL_DEPOSIT, $operationResultTr->getType());
    }

    /**
     * @test
     * @covers ::wrapLiquidityPoolWithdrawResult
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_liquidity_pool_withdraw_result()
    {
        $operationResultTr = OperationResultTr::wrapLiquidityPoolWithdrawResult(new LiquidityPoolWithdrawResult());

        $this->assertInstanceOf(OperationResultTr::class, $operationResultTr);
        $this->assertInstanceOf(LiquidityPoolWithdrawResult::class, $operationResultTr->unwrap());
        $this->assertEquals(OperationType::LIQUIDITY_POOL_WITHDRAW, $operationResultTr->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_null_when_no_type_is_set()
    {
        $this->assertNull((new OperationResultTr())->getType());
    }
}

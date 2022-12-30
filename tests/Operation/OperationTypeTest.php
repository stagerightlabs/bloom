<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\OperationType;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\OperationType
 */
class OperationTypeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_has_options_defined_by_the_stellar_interface_definition_files()
    {
        $expected = [
            0  => OperationType::CREATE_ACCOUNT,
            1  => OperationType::PAYMENT,
            2  => OperationType::PATH_PAYMENT_STRICT_RECEIVE,
            3  => OperationType::MANAGE_SELL_OFFER,
            4  => OperationType::CREATE_PASSIVE_SELL_OFFER,
            5  => OperationType::SET_OPTIONS,
            6  => OperationType::CHANGE_TRUST,
            7  => OperationType::ALLOW_TRUST,
            8  => OperationType::ACCOUNT_MERGE,
            9  => OperationType::INFLATION,
            10 => OperationType::MANAGE_DATA,
            11 => OperationType::BUMP_SEQUENCE,
            12 => OperationType::MANAGE_BUY_OFFER,
            13 => OperationType::PATH_PAYMENT_STRICT_SEND,
            14 => OperationType::CREATE_CLAIMABLE_BALANCE,
            15 => OperationType::CLAIM_CLAIMABLE_BALANCE,
            16 => OperationType::BEGIN_SPONSORING_FUTURE_RESERVES,
            17 => OperationType::END_SPONSORING_FUTURE_RESERVES,
            18 => OperationType::REVOKE_SPONSORSHIP,
            19 => OperationType::CLAWBACK,
            20 => OperationType::CLAWBACK_CLAIMABLE_BALANCE,
            21 => OperationType::SET_TRUSTLINE_FLAGS,
            22 => OperationType::LIQUIDITY_POOL_DEPOSIT,
            23 => OperationType::LIQUIDITY_POOL_WITHDRAW,
        ];
        $this->assertEquals($expected, OperationType::getOptions());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_the_selected_type()
    {
        $operationType = OperationType::createAccount();
        $this->assertEquals(OperationType::CREATE_ACCOUNT, $operationType->getType());
    }

    /**
     * @test
     * @covers ::withType
     */
    public function it_accepts_a_type_selection()
    {
        $operationType = (new OperationType())->withType(OperationType::CREATE_ACCOUNT);
        $this->assertEquals(OperationType::CREATE_ACCOUNT, $operationType->getType());
    }

    /**
     * @test
     * @covers ::createAccount
     */
    public function it_can_be_instantiated_as_a_create_account_type()
    {
        $operationType = OperationType::createAccount();
        $this->assertEquals(OperationType::CREATE_ACCOUNT, strval($operationType));
    }

    /**
     * @test
     * @covers ::payment
     */
    public function it_can_be_instantiated_as_a_payment_type()
    {
        $operationType = OperationType::payment();
        $this->assertEquals(OperationType::PAYMENT, strval($operationType));
    }

    /**
     * @test
     * @covers ::pathPaymentStrictReceive
     */
    public function it_can_be_instantiated_as_a_path_payment_strict_receive_type()
    {
        $operationType = OperationType::pathPaymentStrictReceive();
        $this->assertEquals(OperationType::PATH_PAYMENT_STRICT_RECEIVE, strval($operationType));
    }

    /**
     * @test
     * @covers ::manageSellOffer
     */
    public function it_can_be_instantiated_as_a_path_manage_sell_offer_type()
    {
        $operationType = OperationType::manageSellOffer();
        $this->assertEquals(OperationType::MANAGE_SELL_OFFER, strval($operationType));
    }

    /**
     * @test
     * @covers ::createPassiveSellOffer
     */
    public function it_can_be_instantiated_as_a_create_passive_sell_offer_type()
    {
        $operationType = OperationType::createPassiveSellOffer();
        $this->assertEquals(OperationType::CREATE_PASSIVE_SELL_OFFER, strval($operationType));
    }

    /**
     * @test
     * @covers ::setOptions
     */
    public function it_can_be_instantiated_as_a_set_options_type()
    {
        $operationType = OperationType::setOptions();
        $this->assertEquals(OperationType::SET_OPTIONS, strval($operationType));
    }

    /**
     * @test
     * @covers ::changeTrust
     */
    public function it_can_be_instantiated_as_a_change_trust_type()
    {
        $operationType = OperationType::changeTrust();
        $this->assertEquals(OperationType::CHANGE_TRUST, strval($operationType));
    }

    /**
     * @test
     * @covers ::allowTrust
     */
    public function it_can_be_instantiated_as_an_allow_trust_type()
    {
        $operationType = OperationType::allowTrust();
        $this->assertEquals(OperationType::ALLOW_TRUST, strval($operationType));
    }

    /**
     * @test
     * @covers ::accountMerge
     */
    public function it_can_be_instantiated_as_an_account_merge_type()
    {
        $operationType = OperationType::accountMerge();
        $this->assertEquals(OperationType::ACCOUNT_MERGE, strval($operationType));
    }

    /**
     * @test
     * @covers ::inflation
     */
    public function it_can_be_instantiated_as_an_inflation_type()
    {
        $operationType = OperationType::inflation();
        $this->assertEquals(OperationType::INFLATION, strval($operationType));
    }

    /**
     * @test
     * @covers ::manageData
     */
    public function it_can_be_instantiated_as_a_manage_data_type()
    {
        $operationType = OperationType::manageData();
        $this->assertEquals(OperationType::MANAGE_DATA, strval($operationType));
    }

    /**
     * @test
     * @covers ::bumpSequence
     */
    public function it_can_be_instantiated_as_a_bump_sequence_type()
    {
        $operationType = OperationType::bumpSequence();
        $this->assertEquals(OperationType::BUMP_SEQUENCE, strval($operationType));
    }

    /**
     * @test
     * @covers ::manageBuyOffer
     */
    public function it_can_be_instantiated_as_a_manage_buy_offer_type()
    {
        $operationType = OperationType::manageBuyOffer();
        $this->assertEquals(OperationType::MANAGE_BUY_OFFER, strval($operationType));
    }

    /**
     * @test
     * @covers ::pathPaymentStrictSend
     */
    public function it_can_be_instantiated_as_a_path_payment_strict_send_type()
    {
        $operationType = OperationType::pathPaymentStrictSend();
        $this->assertEquals(OperationType::PATH_PAYMENT_STRICT_SEND, strval($operationType));
    }

    /**
     * @test
     * @covers ::createClaimableBalance
     */
    public function it_can_be_instantiated_as_a_create_claimable_balance_type()
    {
        $operationType = OperationType::createClaimableBalance();
        $this->assertEquals(OperationType::CREATE_CLAIMABLE_BALANCE, strval($operationType));
    }

    /**
     * @test
     * @covers ::claimClaimableBalance
     */
    public function it_can_be_instantiated_as_a_claim_claimable_balance_type()
    {
        $operationType = OperationType::claimClaimableBalance();
        $this->assertEquals(OperationType::CLAIM_CLAIMABLE_BALANCE, strval($operationType));
    }

    /**
     * @test
     * @covers ::beginSponsoringFutureReserves
     */
    public function it_can_be_instantiated_as_a_begin_sponsoring_future_reserves_type()
    {
        $operationType = OperationType::beginSponsoringFutureReserves();
        $this->assertEquals(OperationType::BEGIN_SPONSORING_FUTURE_RESERVES, strval($operationType));
    }

    /**
     * @test
     * @covers ::endSponsoringFutureReserves
     */
    public function it_can_be_instantiated_as_a_end_sponsoring_future_reserves_type()
    {
        $operationType = OperationType::endSponsoringFutureReserves();
        $this->assertEquals(OperationType::END_SPONSORING_FUTURE_RESERVES, strval($operationType));
    }

    /**
     * @test
     * @covers ::revokeSponsorship
     */
    public function it_can_be_instantiated_as_a_revoke_sponsorship_type()
    {
        $operationType = OperationType::revokeSponsorship();
        $this->assertEquals(OperationType::REVOKE_SPONSORSHIP, strval($operationType));
    }

    /**
     * @test
     * @covers ::clawback
     */
    public function it_can_be_instantiated_as_a_clawback_type()
    {
        $operationType = OperationType::clawback();
        $this->assertEquals(OperationType::CLAWBACK, strval($operationType));
    }

    /**
     * @test
     * @covers ::clawbackClaimableBalance
     */
    public function it_can_be_instantiated_as_a_clawback_claimable_balance_type()
    {
        $operationType = OperationType::clawbackClaimableBalance();
        $this->assertEquals(OperationType::CLAWBACK_CLAIMABLE_BALANCE, strval($operationType));
    }

    /**
     * @test
     * @covers ::setTrustlineFlags
     */
    public function it_can_be_instantiated_as_a_set_trustline_flags_type()
    {
        $operationType = OperationType::setTrustlineFlags();
        $this->assertEquals(OperationType::SET_TRUSTLINE_FLAGS, strval($operationType));
    }

    /**
     * @test
     * @covers ::liquidityPoolDeposit
     */
    public function it_can_be_instantiated_as_a_liquidity_pool_deposit_type()
    {
        $operationType = OperationType::liquidityPoolDeposit();
        $this->assertEquals(OperationType::LIQUIDITY_POOL_DEPOSIT, strval($operationType));
    }

    /**
     * @test
     * @covers ::liquidityPoolWithdraw
     */
    public function it_can_be_instantiated_as_a_liquidity_pool_withdraw_type()
    {
        $operationType = OperationType::liquidityPoolWithdraw();
        $this->assertEquals(OperationType::LIQUIDITY_POOL_WITHDRAW, strval($operationType));
    }
}

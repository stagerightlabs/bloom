<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\MuxedAccount;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Operation\AllowTrustOp;
use StageRightLabs\Bloom\Operation\BeginSponsoringFutureReservesOp;
use StageRightLabs\Bloom\Operation\BumpSequenceOp;
use StageRightLabs\Bloom\Operation\ChangeTrustOp;
use StageRightLabs\Bloom\Operation\ClaimClaimableBalanceOp;
use StageRightLabs\Bloom\Operation\ClawbackClaimableBalanceOp;
use StageRightLabs\Bloom\Operation\ClawbackOp;
use StageRightLabs\Bloom\Operation\CreateAccountOp;
use StageRightLabs\Bloom\Operation\CreateClaimableBalanceOp;
use StageRightLabs\Bloom\Operation\CreatePassiveSellOfferOp;
use StageRightLabs\Bloom\Operation\LiquidityPoolDepositOp;
use StageRightLabs\Bloom\Operation\LiquidityPoolWithdrawOp;
use StageRightLabs\Bloom\Operation\ManageBuyOfferOp;
use StageRightLabs\Bloom\Operation\ManageDataOp;
use StageRightLabs\Bloom\Operation\ManageSellOfferOp;
use StageRightLabs\Bloom\Operation\OperationBody;
use StageRightLabs\Bloom\Operation\OperationType;
use StageRightLabs\Bloom\Operation\PathPaymentStrictReceiveOp;
use StageRightLabs\Bloom\Operation\PathPaymentStrictSendOp;
use StageRightLabs\Bloom\Operation\PaymentOp;
use StageRightLabs\Bloom\Operation\RevokeSponsorshipOp;
use StageRightLabs\Bloom\Operation\SetTrustLineFlagsOp;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\OperationBody
 */
class OperationBodyTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(OperationType::class, OperationBody::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            OperationType::CREATE_ACCOUNT                   => CreateAccountOp::class,
            OperationType::PAYMENT                          => PaymentOp::class,
            OperationType::PATH_PAYMENT_STRICT_RECEIVE      => PathPaymentStrictReceiveOp::class,
            OperationType::MANAGE_SELL_OFFER                => ManageSellOfferOp::class,
            OperationType::CREATE_PASSIVE_SELL_OFFER        => CreatePassiveSellOfferOp::class,
            OperationType::SET_OPTIONS                      => CreatePassiveSellOfferOp::class,
            OperationType::CHANGE_TRUST                     => ChangeTrustOp::class,
            OperationType::ALLOW_TRUST                      => AllowTrustOp::class,
            OperationType::ACCOUNT_MERGE                    => MuxedAccount::class,
            OperationType::INFLATION                        => XDR::VOID,
            OperationType::MANAGE_DATA                      => ManageDataOp::class,
            OperationType::BUMP_SEQUENCE                    => BumpSequenceOp::class,
            OperationType::MANAGE_BUY_OFFER                 => ManageBuyOfferOp::class,
            OperationType::PATH_PAYMENT_STRICT_SEND         => PathPaymentStrictSendOp::class,
            OperationType::CREATE_CLAIMABLE_BALANCE         => CreateClaimableBalanceOp::class,
            OperationType::CLAIM_CLAIMABLE_BALANCE          => ClaimClaimableBalanceOp::class,
            OperationType::BEGIN_SPONSORING_FUTURE_RESERVES => BeginSponsoringFutureReservesOp::class,
            OperationType::END_SPONSORING_FUTURE_RESERVES   => XDR::VOID,
            OperationType::REVOKE_SPONSORSHIP               => RevokeSponsorshipOp::class,
            OperationType::CLAWBACK                         => ClawbackOp::class,
            OperationType::CLAWBACK_CLAIMABLE_BALANCE       => ClawbackClaimableBalanceOp::class,
            OperationType::SET_TRUSTLINE_FLAGS              => SetTrustLineFlagsOp::class,
            OperationType::LIQUIDITY_POOL_DEPOSIT           => LiquidityPoolDepositOp::class,
            OperationType::LIQUIDITY_POOL_WITHDRAW          => LiquidityPoolWithdrawOp::class,
        ];
        $this->assertEquals($expected, OperationBody::arms());
    }

    /**
     * @test
     * @covers ::make
     */
    public function it_can_be_instantiated_from_an_operation_type()
    {
        $operationBody = OperationBody::make(OperationType::CREATE_ACCOUNT);
        $this->assertInstanceOf(OperationBody::class, $operationBody);
    }

    /**
     * @test
     * @covers ::make
     */
    public function it_cannot_be_instantiated_from_an_invalid_operation_type()
    {
        $this->expectException(InvalidArgumentException::class);
        OperationBody::make('invalid');
    }

    /**
     * @test
     * @covers ::unwrap
     */
    public function it_returns_the_underlying_operation_variety()
    {
        $operationBody = OperationBody::make(OperationType::CREATE_ACCOUNT, new CreateAccountOp());
        $this->assertInstanceOf(CreateAccountOp::class, $operationBody->unwrap());
    }

    /**
     * @test
     * @covers ::unwrap
     */
    public function it_does_not_return_a_variety_when_none_has_been_set()
    {
        $operationBody = new OperationBody();
        $this->assertNull($operationBody->unwrap());
    }
}

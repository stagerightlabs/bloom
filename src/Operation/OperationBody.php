<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Account\MuxedAccount;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

class OperationBody extends Union implements XdrUnion
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return OperationType::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
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
    }

    /**
     * Create a new instance of the OperationBody class.
     *
     * @param string $operation
     * @param OperationVariety|MuxedAccount|null $op
     * @throws InvalidArgumentException
     * @return static
     */
    public static function make(string $operation, OperationVariety|MuxedAccount $op = null): static
    {
        if (!OperationType::isValid($operation) && !$op instanceof MuxedAccount) {
            throw new InvalidArgumentException("Unknown operation type '{$operation}'");
        }

        $operationBody = new static();
        $operationBody->discriminator = (new OperationType())->withType($operation);
        $operationBody->value = $op;

        return $operationBody;
    }

    /**
     * Access the underlying operation variety class.
     *
     * @return OperationVariety|MuxedAccount|null
     */
    public function unwrap(): OperationVariety|MuxedAccount|null
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}

<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;

final class OperationResultTr extends Union implements XdrUnion
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
    }

    /**
     * Create a new instance by wrapping a CreateAccountResult object.
     *
     * @param CreateAccountResult $result
     * @return static
     */
    public static function wrapCreateAccountResult(CreateAccountResult $result): static
    {
        $operationResultTr = new static();
        $operationResultTr->discriminator = OperationType::createAccount();
        $operationResultTr->value = $result;

        return $operationResultTr;
    }

    /**
     * Create a new instance by wrapping a PaymentResult object.
     *
     * @param PaymentResult $result
     * @return static
     */
    public static function wrapPaymentResult(PaymentResult $result): static
    {
        $operationResultTr = new static();
        $operationResultTr->discriminator = OperationType::payment();
        $operationResultTr->value = $result;

        return $operationResultTr;
    }

    /**
     * Create a new instance by wrapping a PathPaymentStrictReceiveResult object.
     *
     * @param PathPaymentStrictReceiveResult $result
     * @return static
     */
    public static function wrapPathPaymentStrictReceiveResult(PathPaymentStrictReceiveResult $result): static
    {
        $operationResultTr = new static();
        $operationResultTr->discriminator = OperationType::pathPaymentStrictReceive();
        $operationResultTr->value = $result;

        return $operationResultTr;
    }

    /**
     * Create a new instance by wrapping a ManageSellOfferResult object.
     *
     * @param ManageSellOfferResult $result
     * @return static
     */
    public static function wrapManageSellOfferResult(ManageSellOfferResult $result): static
    {
        $operationResultTr = new static();
        $operationResultTr->discriminator = OperationType::manageSellOffer();
        $operationResultTr->value = $result;

        return $operationResultTr;
    }

    /**
     * Create a new instance by wrapping a create passive sell offer result,
     * which is actually a ManageSellOfferResult object.
     *
     * @param ManageSellOfferResult $result
     * @return static
     */
    public static function wrapCreatePassiveSellOfferResult(ManageSellOfferResult $result): static
    {
        $operationResultTr = new static();
        $operationResultTr->discriminator = OperationType::createPassiveSellOffer();
        $operationResultTr->value = $result;

        return $operationResultTr;
    }

    /**
     * Create a new instance by wrapping a SetOptionsResult object.
     *
     * @param SetOptionsResult $result
     * @return static
     */
    public static function wrapSetOptionsResult(SetOptionsResult $result): static
    {
        $operationResultTr = new static();
        $operationResultTr->discriminator = OperationType::setOptions();
        $operationResultTr->value = $result;

        return $operationResultTr;
    }

    /**
     * Create a new instance by wrapping a ChangeTrustResult object.
     *
     * @param ChangeTrustResult $result
     * @return static
     */
    public static function wrapChangeTrustResult(ChangeTrustResult $result): static
    {
        $operationResultTr = new static();
        $operationResultTr->discriminator = OperationType::changeTrust();
        $operationResultTr->value = $result;

        return $operationResultTr;
    }

    /**
     * Create a new instance by wrapping an AllowTrustResult object.
     *
     * @param AllowTrustResult $result
     * @return static
     */
    public static function wrapAllowTrustResult(AllowTrustResult $result): static
    {
        $operationResultTr = new static();
        $operationResultTr->discriminator = OperationType::allowTrust();
        $operationResultTr->value = $result;

        return $operationResultTr;
    }

    /**
     * Create a new instance by wrapping an AccountMergeResult object.
     *
     * @param AccountMergeResult $result
     * @return static
     */
    public static function wrapAccountMergeResult(AccountMergeResult $result): static
    {
        $operationResultTr = new static();
        $operationResultTr->discriminator = OperationType::accountMerge();
        $operationResultTr->value = $result;

        return $operationResultTr;
    }

    /**
     * Create a new instance by wrapping an InflationResult object.
     *
     * @param InflationResult $result
     * @return static
     */
    public static function wrapInflationResult(InflationResult $result): static
    {
        $operationResultTr = new static();
        $operationResultTr->discriminator = OperationType::inflation();
        $operationResultTr->value = $result;

        return $operationResultTr;
    }

    /**
     * Create a new instance by wrapping a ManageDataResult object.
     *
     * @param ManageDataResult $result
     * @return static
     */
    public static function wrapManageDataResult(ManageDataResult $result): static
    {
        $operationResultTr = new static();
        $operationResultTr->discriminator = OperationType::manageData();
        $operationResultTr->value = $result;

        return $operationResultTr;
    }

    /**
     * Create a new instance by wrapping a BumpSequenceResult object.
     *
     * @param BumpSequenceResult $result
     * @return static
     */
    public static function wrapBumpSequenceResult(BumpSequenceResult $result): static
    {
        $operationResultTr = new static();
        $operationResultTr->discriminator = OperationType::bumpSequence();
        $operationResultTr->value = $result;

        return $operationResultTr;
    }

    /**
     * Create a new instance by wrapping a ManageBuyOfferResult object.
     *
     * @param ManageBuyOfferResult $result
     * @return static
     */
    public static function wrapManageBuyOfferResult(ManageBuyOfferResult $result): static
    {
        $operationResultTr = new static();
        $operationResultTr->discriminator = OperationType::manageBuyOffer();
        $operationResultTr->value = $result;

        return $operationResultTr;
    }

    /**
     * Create a new instance by wrapping a PathPaymentStrictSendResult object.
     *
     * @param PathPaymentStrictSendResult $result
     * @return static
     */
    public static function wrapPathPaymentStrictSendResult(PathPaymentStrictSendResult $result): static
    {
        $operationResultTr = new static();
        $operationResultTr->discriminator = OperationType::pathPaymentStrictSend();
        $operationResultTr->value = $result;

        return $operationResultTr;
    }

    /**
     * Create a new instance by wrapping a CreateClaimableBalanceResult object.
     *
     * @param CreateClaimableBalanceResult $result
     * @return static
     */
    public static function wrapCreateClaimableBalanceResult(CreateClaimableBalanceResult $result): static
    {
        $operationResultTr = new static();
        $operationResultTr->discriminator = OperationType::createClaimableBalance();
        $operationResultTr->value = $result;

        return $operationResultTr;
    }

    /**
     * Create a new instance by wrapping a ClaimClaimableBalanceResult object.
     *
     * @param ClaimClaimableBalanceResult $result
     * @return static
     */
    public static function wrapClaimClaimableBalanceResult(ClaimClaimableBalanceResult $result): static
    {
        $operationResultTr = new static();
        $operationResultTr->discriminator = OperationType::claimClaimableBalance();
        $operationResultTr->value = $result;

        return $operationResultTr;
    }

    /**
     * Create a new instance by wrapping a BeginSponsoringFutureReservesResult object.
     *
     * @param BeginSponsoringFutureReservesResult $result
     * @return static
     */
    public static function wrapBeginSponsoringFutureReservesResult(BeginSponsoringFutureReservesResult $result): static
    {
        $operationResultTr = new static();
        $operationResultTr->discriminator = OperationType::beginSponsoringFutureReserves();
        $operationResultTr->value = $result;

        return $operationResultTr;
    }

    /**
     * Create a new instance by wrapping an EndSponsoringFutureReservesResult object.
     *
     * @param EndSponsoringFutureReservesResult $result
     * @return static
     */
    public static function wrapEndSponsoringFutureReservesResult(EndSponsoringFutureReservesResult $result): static
    {
        $operationResultTr = new static();
        $operationResultTr->discriminator = OperationType::endSponsoringFutureReserves();
        $operationResultTr->value = $result;

        return $operationResultTr;
    }

    /**
     * Create a new instance by wrapping a RevokeSponsorshipResult object.
     *
     * @param RevokeSponsorshipResult $result
     * @return static
     */
    public static function wrapRevokeSponsorshipResult(RevokeSponsorshipResult $result): static
    {
        $operationResultTr = new static();
        $operationResultTr->discriminator = OperationType::revokeSponsorship();
        $operationResultTr->value = $result;

        return $operationResultTr;
    }

    /**
     * Create a new instance by wrapping a ClawbackResult object.
     *
     * @param ClawbackResult $result
     * @return static
     */
    public static function wrapClawbackResult(ClawbackResult $result): static
    {
        $operationResultTr = new static();
        $operationResultTr->discriminator = OperationType::clawback();
        $operationResultTr->value = $result;

        return $operationResultTr;
    }

    /**
     * Create a new instance by wrapping a ClawbackClaimableBalanceResult object.
     *
     * @param ClawbackClaimableBalanceResult $result
     * @return static
     */
    public static function wrapClawbackClaimableBalanceResult(ClawbackClaimableBalanceResult $result): static
    {
        $operationResultTr = new static();
        $operationResultTr->discriminator = OperationType::clawbackClaimableBalance();
        $operationResultTr->value = $result;

        return $operationResultTr;
    }

    /**
     * Create a new instance by wrapping a SetTrustLineFlagsResult object.
     *
     * @param SetTrustLineFlagsResult $result
     * @return static
     */
    public static function wrapSetTrustLineFlagsResult(SetTrustLineFlagsResult $result): static
    {
        $operationResultTr = new static();
        $operationResultTr->discriminator = OperationType::setTrustlineFlags();
        $operationResultTr->value = $result;

        return $operationResultTr;
    }

    /**
     * Create a new instance by wrapping a LiquidityPoolDepositResult object.
     *
     * @param LiquidityPoolDepositResult $result
     * @return static
     */
    public static function wrapLiquidityPoolDepositResult(LiquidityPoolDepositResult $result): static
    {
        $operationResultTr = new static();
        $operationResultTr->discriminator = OperationType::liquidityPoolDeposit();
        $operationResultTr->value = $result;

        return $operationResultTr;
    }

    /**
     * Create a new instance by wrapping a LiquidityPoolWithdrawResult object.
     *
     * @param LiquidityPoolWithdrawResult $result
     * @return static
     */
    public static function wrapLiquidityPoolWithdrawResult(LiquidityPoolWithdrawResult $result): static
    {
        $operationResultTr = new static();
        $operationResultTr->discriminator = OperationType::liquidityPoolWithdraw();
        $operationResultTr->value = $result;

        return $operationResultTr;
    }
    /**
     * Return the union type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof OperationType) {
            return $this->discriminator->getType();
        }

        return null;
    }

    /**
     * Unwrap the underlying operation result.
     *
     * @return OperationOutcome|null
     */
    public function unwrap(): OperationOutcome|null
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}

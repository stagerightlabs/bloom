<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

/**
 * @see https://github.com/stellar/go/blob/6fbbdc470c886f8748bc41db8dc0344df01b8772/protocols/horizon/operations/main.go
 */
class OperationResource extends Resource
{
    /**
     * Create an operation resource subclass from a resource payload.
     *
     * @param array<string, mixed> $payload
     * @return OperationResource|CreateAccountOperationResource|PaymentOperationResource
     */
    public static function operation(array $payload): OperationResource|CreateAccountOperationResource|PaymentOperationResource|PathPaymentStrictReceiveOperationResource|ManageSellOfferOperationResource|CreatePassiveSellOfferOperationResource|SetOptionsOperationResource|ChangeTrustOperationResource|AllowTrustOperationResource|AccountMergeOperationResource|ManageDataOperationResource|BumpSequenceOperationResource|ManageBuyOfferOperationResource|PathPaymentStrictSendOperationResource|CreateClaimableBalanceOperationResource|ClaimClaimableBalanceOperationResource|BeginSponsoringFutureReservesOperationResource|EndSponsoringFutureReservesOperationResource|RevokeSponsorshipOperationResource|ClawbackOperationResource|ClawbackClaimableBalanceOperationResource|SetTrustLineFlagsOperationResource|LiquidityPoolDepositOperationResource|LiquidityPoolWithdrawOperationResource
    {
        if (!array_key_exists('type_i', $payload)) {
            return OperationResource::wrap($payload);
        }

        return match ($payload['type_i']) {
            0       => CreateAccountOperationResource::wrap($payload),
            1       => PaymentOperationResource::wrap($payload),
            2       => PathPaymentStrictReceiveOperationResource::wrap($payload),
            3       => ManageSellOfferOperationResource::wrap($payload),
            4       => CreatePassiveSellOfferOperationResource::wrap($payload),
            5       => SetOptionsOperationResource::wrap($payload),
            6       => ChangeTrustOperationResource::wrap($payload),
            7       => AllowTrustOperationResource::wrap($payload),
            8       => AccountMergeOperationResource::wrap($payload),
            10      => ManageDataOperationResource::wrap($payload),
            11      => BumpSequenceOperationResource::wrap($payload),
            12      => ManageBuyOfferOperationResource::wrap($payload),
            13      => PathPaymentStrictSendOperationResource::wrap($payload),
            14      => CreateClaimableBalanceOperationResource::wrap($payload),
            15      => ClaimClaimableBalanceOperationResource::wrap($payload),
            16      => BeginSponsoringFutureReservesOperationResource::wrap($payload),
            17      => EndSponsoringFutureReservesOperationResource::wrap($payload),
            18      => RevokeSponsorshipOperationResource::wrap($payload),
            19      => ClawbackOperationResource::wrap($payload),
            20      => ClawbackClaimableBalanceOperationResource::wrap($payload),
            21      => SetTrustLineFlagsOperationResource::wrap($payload),
            22      => LiquidityPoolDepositOperationResource::wrap($payload),
            23      => LiquidityPoolWithdrawOperationResource::wrap($payload),
            default => OperationResource::wrap($payload),
        };
    }


    // 21 => self::SET_TRUSTLINE_FLAGS,

    /**
     * Return the 'effects' link.
     *
     * @return string|null
     */
    public function getEffectsLink(): ?string
    {
        return $this->getLink('effects');
    }

    /**
     * Return the 'precedes' link.
     *
     * @return string|null
     */
    public function getPrecedesLink(): ?string
    {
        return $this->getLink('precedes');
    }

    /**
     * Return the 'self' link.
     *
     * @return string|null
     */
    public function getSelfLink(): ?string
    {
        return $this->getLink('self');
    }

    /**
     * Return the 'succeeds' link.
     *
     * @return string|null
     */
    public function getSucceedsLink(): ?string
    {
        return $this->getLink('succeeds');
    }

    /**
     * Return the 'transaction' link.
     *
     * @return string|null
     */
    public function getTransactionLink(): ?string
    {
        return $this->getLink('transaction');
    }

    /**
     * The operation's Id number.
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->payload->getString('id');
    }

    /**
     * A cursor value for use in pagination.
     *
     * @return string|null
     */
    public function getPagingToken(): ?string
    {
        return $this->payload->getString('paging_token');
    }

    /**
     * A number indicating the operation type.
     *
     * @return int|null
     */
    public function getTypeNumber(): ?int
    {
        return $this->payload->getInteger('type_i');
    }

    /**
     * The name of the operation type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->payload->getString('type');
    }

    /**
     * A unique identifier for the transaction this operation belongs to.
     *
     * @return string|null
     */
    public function getTransactionHash(): ?string
    {
        return $this->payload->getString('transaction_hash');
    }

    /**
     * INdicates if this operation was part of a successful transaction.
     *
     * @return bool|null
     */
    public function transactionWasSuccessful(): ?bool
    {
        return $this->payload->getBoolean('transaction_successful');
    }

    /**
     * The address of the account that originated the operation.
     *
     * @return string|null
     */
    public function getSourceAddress(): ?string
    {
        return $this->payload->getString('source_account');
    }

    /**
     * The address of the account that sponsored the operation.
     *
     * @return string|null
     */
    public function getSponsorAddress(): ?string
    {
        return $this->payload->getString('sponsor');
    }

    /**
     * The date this transaction was created.
     *
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        if ($createdAt = $this->payload->getString('created_at')) {
            return new \DateTime($createdAt);
        }

        return null;
    }
}

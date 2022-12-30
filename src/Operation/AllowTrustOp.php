<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Asset\AssetCode;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class AllowTrustOp implements XdrStruct, OperationVariety
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected AccountId $trustor;
    protected AssetCode $asset;
    protected UInt32 $authorize; // One of 0, AUTHORIZED_FLAG, or AUTHORIZED_TO_MAINTAIN_LIABILITIES_FLAG
    public const AUTH_NONE = 0;
    public const AUTHORIZED_FLAG = 1;
    public const AUTHORIZED_TO_MAINTAIN_LIABILITIES_FLAG = 2;

    /**
     * Create a new allow-trust operation.
     *
     * @param AccountId|Addressable|string $trustor
     * @param AssetCode|string $assetCode
     * @param UInt32|integer $authorize
     * @param Addressable|string|null $source
     * @return Operation
     */
    public static function operation(
        AccountId|Addressable|string $trustor,
        AssetCode|string $assetCode,
        UInt32|int $authorize,
        Addressable|string $source = null
    ): Operation {
        $allowTrustOp = (new static())
            ->withTrustor($trustor)
            ->withAssetCode($assetCode)
            ->withAuthorizationFlag($authorize);

        return (new Operation())
            ->withBody(OperationBody::make(OperationType::ALLOW_TRUST, $allowTrustOp))
            ->withSourceAccount($source);
    }

    /**
     * Does this operation have its expected payload?
     *
     * @return bool
     */
    public function isReady(): bool
    {
        return (isset($this->trustor) && $this->trustor instanceof AccountId)
            && (isset($this->asset) && $this->asset instanceof AssetCode)
            && (isset($this->authorize) && $this->authorize instanceof UInt32);
    }

    /**
     * Get the operation's threshold category, either "low", "medium" or "high".
     *
     * @return string
     */
    public function getThreshold(): string
    {
        return Thresholds::CATEGORY_LOW;
    }

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @throws InvalidArgumentException
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->trustor)) {
            throw new InvalidArgumentException('The allow trust operation is missing a trustor');
        }

        if (!isset($this->asset)) {
            throw new InvalidArgumentException('The allow trust operation is missing an asset');
        }

        if (!isset($this->authorize)) {
            throw new InvalidArgumentException('The allow trust operation is missing an authorization flag');
        }

        $xdr->write($this->trustor)
            ->write($this->asset)
            ->write($this->authorize);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $allowTrustOp = new static();
        $allowTrustOp->trustor = $xdr->read(AccountId::class);
        $allowTrustOp->asset = $xdr->read(AssetCode::class);
        $allowTrustOp->authorize = $xdr->read(UInt32::class);

        return $allowTrustOp;
    }

    /**
     * Get the trustor.
     *
     * @return AccountId
     */
    public function getTrustor(): AccountId
    {
        return $this->trustor;
    }

    /**
     * Accept a trustor.
     *
     * @param AccountId|Addressable|string $trustor
     * @return static
     */
    public function withTrustor(AccountId|Addressable|string $trustor): static
    {
        // string
        if (is_string($trustor)) {
            $trustor = AccountId::fromAddressable($trustor);
        }

        // addressable
        if ($trustor instanceof Addressable) {
            $trustor = $trustor->getAccountId();
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->trustor = Copy::deep($trustor);

        return $clone;
    }

    /**
     * Get the asset code.
     *
     * @return AssetCode
     */
    public function getAssetCode(): AssetCode
    {
        return $this->asset;
    }

    /**
     * Accept an asset code.
     *
     * @param AssetCode|string $assetCode
     * @return static
     */
    public function withAssetCode(AssetCode|string $assetCode): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->asset = is_string($assetCode)
            ? AssetCode::fromNativeString($assetCode)
            : Copy::deep($assetCode);

        return $clone;
    }

    /**
     * Get the authorization flag.
     *
     * @return UInt32
     */
    public function getAuthorizationFlag(): UInt32
    {
        return $this->authorize;
    }

    /**
     * Accept an authorization flag.
     *
     * @param UInt32|int $authorize
     * @return static
     * @throws InvalidArgumentException
     */
    public function withAuthorizationFlag(UInt32|int $authorize): static
    {
        $authorize = UInt32::of($authorize);

        if (!in_array($authorize->toNativeInt(), [self::AUTH_NONE, self::AUTHORIZED_FLAG, self::AUTHORIZED_TO_MAINTAIN_LIABILITIES_FLAG], true)) {
            throw new InvalidArgumentException("'{$authorize->toNativeInt()}' is not a valid authorization flag.");
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->authorize = $authorize;

        return $clone;
    }
}

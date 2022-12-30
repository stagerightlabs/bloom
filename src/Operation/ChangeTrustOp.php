<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\LiquidityPool\LiquidityPoolParameters;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class ChangeTrustOp implements XdrStruct, OperationVariety
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected ChangeTrustAsset $line;
    protected Int64 $limit;

    /**
     * Create a new change-trust operation.
     *
     * @param ChangeTrustAsset|LiquidityPoolParameters|Asset|string $asset
     * @param Int64|ScaledAmount|integer|string|null $limit
     * @param Addressable|string|null $source
     * @return Operation
     */
    public static function operation(
        ChangeTrustAsset|LiquidityPoolParameters|Asset|string $asset,
        Int64|ScaledAmount|int|string $limit = null,
        Addressable|string $source = null,
    ): Operation {
        $changeTrustOp = (new static())
            ->withLine($asset)
            ->withLimit($limit);

        return (new Operation())
            ->withBody(OperationBody::make(OperationType::CHANGE_TRUST, $changeTrustOp))
            ->withSourceAccount($source);
    }

    /**
     * Does this operation have its expected payload?
     *
     * @return bool
     */
    public function isReady(): bool
    {
        return (isset($this->line) && $this->line instanceof ChangeTrustAsset);
    }

    /**
     * Get the operation's threshold category, either "low", "medium" or "high".
     *
     * @return string
     */
    public function getThreshold(): string
    {
        return Thresholds::CATEGORY_MEDIUM;
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
        if (!isset($this->line)) {
            throw new InvalidArgumentException('The change trust operation is missing trustline asset details');
        }

        if (!isset($this->limit)) {
            $this->limit = Int64::max();
        }

        $xdr->write($this->line)->write($this->limit);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $changeTrustOp = new static();
        $changeTrustOp->line = $xdr->read(ChangeTrustAsset::class);
        $changeTrustOp->limit = $xdr->read(Int64::class);

        return $changeTrustOp;
    }

    /**
     * Get the asset information.
     *
     * @return ChangeTrustAsset
     */
    public function getLine(): ChangeTrustAsset
    {
        return $this->line;
    }

    /**
     * Accept asset information.
     *
     * @param ChangeTrustAsset|Asset|string $line
     * @return static
     */
    public function withLine(ChangeTrustAsset|LiquidityPoolParameters|Asset|string $line): static
    {
        if (is_string($line)) {
            $line = Asset::fromNativeString($line);
        }

        if ($line instanceof Asset) {
            $line = ChangeTrustAsset::fromAsset($line);
        }

        if ($line instanceof LiquidityPoolParameters) {
            $line = ChangeTrustAsset::fromLiquidityPoolParameters($line);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->line = Copy::deep($line);

        return $clone;
    }

    /**
     * Get the limit.
     *
     * @return Int64
     */
    public function getLimit(): Int64
    {
        return $this->limit;
    }

    /**
     * Accept a limit.
     *
     * A string will be read as a scaled amount; an integer as a descaled value.
     *
     * @param Int64|ScaledAmount|int|string|null $limit
     * @return static
     */
    public function withLimit(Int64|ScaledAmount|int|string $limit = null): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->limit = is_null($limit)
            ? Int64::max()
            : Int64::normalize($limit);

        return $clone;
    }
}

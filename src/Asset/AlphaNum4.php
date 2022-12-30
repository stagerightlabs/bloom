<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Asset;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class AlphaNum4 implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected AssetCode4 $assetCode;
    protected AccountId $issuer;

    /**
     * Create a new instance of an AlphaNum4 identifier.
     *
     * @param AssetCode4|string $assetCode
     * @param AccountId|string $issuer
     * @return static
     */
    public static function of(AssetCode4|string $assetCode, AccountId|string $issuer): static
    {
        if (is_string($assetCode)) {
            $assetCode = AssetCode4::of($assetCode);
        }

        if (is_string($issuer)) {
            $issuer = AccountId::fromAddressable($issuer);
        }

        $alphaNum4 = new AlphaNum4();
        $alphaNum4->assetCode = $assetCode;
        $alphaNum4->issuer = $issuer;

        return $alphaNum4;
    }

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        $xdr->write($this->assetCode)->write($this->issuer);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $alphaNum4 = new static();
        $alphaNum4->assetCode = $xdr->read(AssetCode4::class);
        $alphaNum4->issuer = $xdr->read(AccountId::class);

        return $alphaNum4;
    }

    /**
     * Get the asset code.
     *
     * @return AssetCode4
     */
    public function getAssetCode(): AssetCode4
    {
        return $this->assetCode;
    }

    /**
     * Accept an asset code.
     *
     * @param AssetCode4 $assetCode
     * @return static
     */
    public function withAssetCode(AssetCode4 $assetCode): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->assetCode = Copy::deep($assetCode);

        return $clone;
    }

    /**
     * Get the issuer.
     *
     * @return AccountId
     */
    public function getIssuer(): AccountId
    {
        return $this->issuer;
    }

    /**
     * Accept an issuer.
     *
     * @param AccountId $issuer
     * @return static
     */
    public function withIssuer(AccountId $issuer): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->issuer = Copy::deep($issuer);

        return $clone;
    }

    /**
     * Allow the asset to be cast as a string in the format
     * "[asset code]:[issuer address]".
     *
     * @return string
     */
    public function __toString(): string
    {
        if (!isset($this->assetCode) || !isset($this->issuer) || !$key = $this->issuer->unwrap()) {
            return '';
        }

        return $this->assetCode->getCode() . ':' . $key->getAddress();
    }

    /**
     * Return the asset as a string in the format "[asset code]:[issuer address]".
     *
     * @return string
     */
    public function toNativeString(): string
    {
        return $this->__toString();
    }
}

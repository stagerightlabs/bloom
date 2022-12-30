<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Account;

use StageRightLabs\Bloom\Primitives\Optional;
use StageRightLabs\PhpXdr\Interfaces\XdrOptional;

final class OptionalSigner extends Optional implements XdrOptional
{
    /**
     * The encoding type for the optional value.
     *
     * @return string
     */
    public static function getXdrValueType(): string
    {
        return Signer::class;
    }

    /**
     * Instantiate an instance from an Signer.
     *
     * @param Signer $signer
     * @return static
     */
    public static function some(Signer $signer): static
    {
        return self::none()->withValue($signer);
    }

    /**
     * Return the optional value.
     *
     * @return Signer|null
     */
    public function unwrap(): ?Signer
    {
        return $this->getValue();
    }
}

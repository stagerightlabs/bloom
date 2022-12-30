<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class TrustLineEntryExt extends Union implements XdrUnion
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return XDR::INT;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            0 => XDR::VOID,
            1 => TrustLineEntryV1::class,
        ];
    }

    /**
     * Create a new instance that wraps a null value.
     *
     * @return static
     */
    public static function empty(): static
    {
        $trustLineEntryExt = new static();
        $trustLineEntryExt->discriminator = 0;
        $trustLineEntryExt->value = null;

        return $trustLineEntryExt;
    }

    /**
     * Create a new instance by wrapping a TrustLineEntryV1.
     *
     * @param TrustLineEntryV1 $trustLineEntryV1
     * @return static
     */
    public static function wrapTrustLineEntryV1(TrustLineEntryV1 $trustLineEntryV1): static
    {
        $trustLineEntryExt = new static();
        $trustLineEntryExt->discriminator = 2;
        $trustLineEntryExt->value = $trustLineEntryV1;

        return $trustLineEntryExt;
    }

    /**
     * Unwrap the underlying value.
     *
     * @return TrustLineEntryV1|null
     */
    public function unwrap(): ?TrustLineEntryV1
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}

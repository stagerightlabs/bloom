<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class LedgerCloseMeta extends Union implements XdrUnion
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
            0 => LedgerCloseMetaV0::class,
            1 => LedgerCloseMetaV1::class,
        ];
    }

    /**
     * Create a new instance by wrapping a LedgerCloseMetaV0 object.
     *
     * @param LedgerCloseMetaV0 $ledgerCloseMetaV0
     * @return static
     */
    public static function wrapLedgerCloseMetaV0(LedgerCloseMetaV0 $ledgerCloseMetaV0): static
    {
        $ledgerCloseMeta = new static();
        $ledgerCloseMeta->discriminator = 0;
        $ledgerCloseMeta->value = $ledgerCloseMetaV0;

        return $ledgerCloseMeta;
    }

    /**
     * Create a new instance by wrapping a LedgerCloseMetaV1 object.
     *
     * @param LedgerCloseMetaV1 $ledgerCloseMetaV1
     * @return static
     */
    public static function wrapLedgerCloseMetaV1(LedgerCloseMetaV1 $ledgerCloseMetaV1): static
    {
        $ledgerCloseMeta = new static();
        $ledgerCloseMeta->discriminator = 1;
        $ledgerCloseMeta->value = $ledgerCloseMetaV1;

        return $ledgerCloseMeta;
    }

    /**
     * Return the underlying value.
     *
     * @return LedgerCloseMetaV0|LedgerCloseMetaV1|null
     */
    public function unwrap(): LedgerCloseMetaV0|LedgerCloseMetaV1|null
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}

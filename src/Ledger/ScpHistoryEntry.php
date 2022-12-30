<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class ScpHistoryEntry extends Union implements XdrUnion
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
            0 => ScpHistoryEntryV0::class,
        ];
    }

    /**
     * Create a new instance by wrapping a ScpHistoryEntryV0 object.
     *
     * @param ScpHistoryEntryV0 $scpHistoryEntryV0
     * @return static
     */
    public static function wrapScpHistoryEntryV0(ScpHistoryEntryV0 $scpHistoryEntryV0): static
    {
        $scpHistoryEntry = new static();
        $scpHistoryEntry->discriminator = 0;
        $scpHistoryEntry->value = $scpHistoryEntryV0;

        return $scpHistoryEntry;
    }

    /**
     * Return the underlying value.
     *
     * @return ScpHistoryEntryV0|null
     */
    public function unwrap(): ?ScpHistoryEntryV0
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}

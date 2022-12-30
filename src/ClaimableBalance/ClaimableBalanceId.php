<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\ClaimableBalance;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;

final class ClaimableBalanceId extends Union implements XdrUnion
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return ClaimableBalanceIdType::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            ClaimableBalanceIdType::CLAIMABLE_BALANCE_ID_TYPE_V0 => Hash::class,
        ];
    }

    /**
     * Create a new text memo.
     *
     * @param Hash $hash
     * @return static
     */
    public static function wrapHash(Hash $hash): static
    {
        $memo = new static();
        $memo->discriminator = ClaimableBalanceIdType::v0();
        $memo->value = $hash;

        return $memo;
    }

    /**
     * Return the underlying value
     *
     * @return Hash|null
     */
    public function unwrap(): ?Hash
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}

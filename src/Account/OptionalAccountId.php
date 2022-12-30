<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Account;

use StageRightLabs\Bloom\Keypair\PublicKeyType;
use StageRightLabs\Bloom\Primitives\Optional;
use StageRightLabs\PhpXdr\Interfaces\XdrOptional;

final class OptionalAccountId extends Optional implements XdrOptional
{
    /**
     * The encoding type for the optional value.
     *
     * @return string
     */
    public static function getXdrValueType(): string
    {
        return AccountId::class;
    }

    /**
     * Instantiate an instance from an AccountId, addressable or string.
     *
     * @param AccountId|Addressable|string $accountId
     * @param string $publicKeyType
     * @return OptionalAccountId
     */
    public static function some(AccountId|Addressable|string $accountId, $publicKeyType = PublicKeyType::ED25519): static
    {
        if (!$accountId instanceof AccountId) {
            $accountId = AccountId::fromAddressable($accountId, $publicKeyType);
        }

        return self::none()->withValue($accountId);
    }

    /**
     * Return the optional muxed account.
     *
     * @return AccountId|null
     */
    public function unwrap(): ?AccountId
    {
        return $this->getValue();
    }
}

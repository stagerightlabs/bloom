<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Account;

use StageRightLabs\Bloom\Primitives\Optional;
use StageRightLabs\PhpXdr\Interfaces\XdrOptional;

final class OptionalAddress extends Optional implements XdrOptional
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
     * Instantiate an instance from a MuxedAccount.
     *
     * @param AccountId|Addressable|string $address
     * @return OptionalAddress
     */
    public static function some(AccountId|Addressable|string $address): static
    {
        if (!$address instanceof AccountId) {
            $address = AccountId::fromAddressable($address);
        }

        return self::none()->withValue($address);
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

<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Cryptography;

use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Keypair\StringKey;
use StageRightLabs\Bloom\Primitives\UInt256;

class ED25519 extends UInt256
{
    /**
     * Create a new instance from an address.
     *
     * @param Addressable|string $address
     * @throws InvalidArgumentException
     * @return static
     */
    public static function fromAddress(Addressable|string $address): static
    {
        if ($address instanceof Addressable) {
            $address = $address->getAddress();
        }

        $decoded = StringKey::decodeAddress($address);

        if (!$decoded['valid']) {
            throw new InvalidArgumentException('Attempting to use an invalid ED25519 address');
        }

        return ED25519::of($decoded['content']);
    }

    /**
     * Return the address string.
     *
     * @return string
     */
    public function getAddress(): string
    {
        return StringKey::encodeAddress($this->bytes);
    }
}

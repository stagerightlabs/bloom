<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Account;

use StageRightLabs\Bloom\Cryptography\DecoratedSignature;
use StageRightLabs\Bloom\Cryptography\Signature;
use StageRightLabs\Bloom\Cryptography\SignatureHint;
use StageRightLabs\Bloom\Exception\InvalidKeyException;

interface Signatory
{
    /**
     * Is this signatory able to sign transactions?
     *
     * @return bool
     */
    public function canSign(): bool;

    /**
     * Attempt to sign a message using a private key.
     *
     * @param string $message
     * @throws InvalidKeyException
     * @return Signature
     */
    public function sign(string $message): Signature;

    /**
     * Attempt to sign a message using a private key,
     * returning a decorated signature.
     *
     * @param string $message
     * @throws InvalidKeyException
     * @return DecoratedSignature
     */
    public function signDecorated(string $message): DecoratedSignature;

    /**
     * Verify a signature.
     *
     * @param string $signature
     * @param string $message
     * @return bool
     */
    public function verify(string $signature, string $message): bool;

    /**
     * Return the last four characters of the address.
     *
     * @return SignatureHint
     */
    public function getSignatureHint(): SignatureHint;

    /**
     * Return the seed.
     *
     * @return string
     */
    public function getSeed(): string;
}

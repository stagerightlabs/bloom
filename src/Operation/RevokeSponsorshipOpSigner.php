<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Cryptography\ED25519;
use StageRightLabs\Bloom\Cryptography\SignerKey;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class RevokeSponsorshipOpSigner implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected AccountId $accountId;
    protected SignerKey $signerKey;

    /**
     * Create a new instance from an addressable or a string address.
     *
     * @param Addressable|string $address
     * @return static
     */
    public static function fromAddressable(Addressable|string $address): static
    {
        $accountId = AccountId::fromAddressable($address);
        $signerKey = SignerKey::wrapEd25519(
            ED25519::fromAddress($accountId->getAddress())
        );

        return (new static())
            ->withAccountId($accountId)
            ->withSignerKey($signerKey);
    }

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @throws InvalidArgumentException
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->accountId)) {
            throw new InvalidArgumentException('The revoke sponsorship op signer is missing an account Id');
        }

        if (!isset($this->signerKey)) {
            throw new InvalidArgumentException('The revoke sponsorship op signer is missing a signer key');
        }

        $xdr->write($this->accountId)
            ->write($this->signerKey);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $revokeSponsorshipOpSigner = new static();
        $revokeSponsorshipOpSigner->accountId = $xdr->read(AccountId::class);
        $revokeSponsorshipOpSigner->signerKey = $xdr->read(SignerKey::class);

        return $revokeSponsorshipOpSigner;
    }

    /**
     * Get the account Id.
     *
     * @return AccountId
     */
    public function getAccountId(): AccountId
    {
        return $this->accountId;
    }

    /**
     * Accept an account Id.
     *
     * @param AccountId|Addressable|string $accountId
     * @return static
     */
    public function withAccountId(AccountId|Addressable|string $accountId): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->accountId = AccountId::fromAddressable($accountId);

        return $clone;
    }

    /**
     * Get the signer key.
     *
     * @return SignerKey
     */
    public function getSignerKey(): SignerKey
    {
        return $this->signerKey;
    }

    /**
     * Accept a signer key.
     *
     * @param SignerKey $signerKey
     * @return static
     */
    public function withSignerKey(SignerKey $signerKey): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->signerKey = Copy::deep($signerKey);

        return $clone;
    }
}

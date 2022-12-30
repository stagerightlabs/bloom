<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Account;

use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Cryptography\DecoratedSignature;
use StageRightLabs\Bloom\Cryptography\Signature;
use StageRightLabs\Bloom\Cryptography\SignatureHint;
use StageRightLabs\Bloom\Exception\InvalidKeyException;
use StageRightLabs\Bloom\Horizon\AccountBalanceResource;
use StageRightLabs\Bloom\Horizon\AccountResource;
use StageRightLabs\Bloom\Horizon\AccountSignerResource;
use StageRightLabs\Bloom\Keypair\Keypair;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\Transaction\SequenceNumber;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoConstructor;

class Account implements Wallet
{
    /**
     * Properties
     */
    use NoConstructor;
    protected ?Keypair $keypair;
    protected ?SequenceNumber $sequenceNumber;
    protected ?AccountResource $accountResource;

    /**
     * Attempt to sign a message using a private key.
     *
     * @param string $message
     * @throws InvalidKeyException
     * @return Signature
     */
    public function sign(string $message): Signature
    {
        return $this->getKeyPair()->sign($message);
    }

    /**
     * Attempt to sign a message using a private key,
     * returning a decorated signature.
     *
     * @param string $message
     * @throws InvalidKeyException
     * @return DecoratedSignature
     */
    public function signDecorated(string $message): DecoratedSignature
    {
        return $this->getKeyPair()->signDecorated($message);
    }

    /**
     * Verify a signature.
     *
     * @param Signature|string $signature
     * @param string $message
     * @return bool
     */
    public function verify(string $message, Signature|string $signature): bool
    {
        return $this->getKeyPair()->verify($message, $signature);
    }

    /**
     * Return the last four characters of the address.
     *
     * @return SignatureHint
     */
    public function getSignatureHint(): SignatureHint
    {
        return SignatureHint::of(substr($this->getAddress(), -4));
    }

    /**
     * Instantiate an account object from an address.
     *
     * @param Addressable|string $address
     * @return static
     */
    public static function fromAddress(Addressable|string $address): static
    {
        if ($address instanceof Addressable) {
            $address = $address->getAddress();
        }

        return (new static())->withKeyPair(new Keypair(address: $address));
    }

    /**
     * Instantiate an account object from a string.
     *
     * @param string $seed
     * @return static
     */
    public static function fromSeed(string $seed): static
    {
        return (new static())->withKeyPair(new Keypair(seed: $seed));
    }

    /**
     * Instantiate an account object from a keypair.
     *
     * @param Keypair $keypair
     * @return static
     */
    public static function fromKeypair(Keypair $keypair): static
    {
        return (new static())->withKeyPair($keypair);
    }

    /**
     * Get the public address for this account.
     *
     * @return string
     */
    public function getAddress(): string
    {
        return !is_null($this->keypair)
            ? $this->keypair->getAddress()
            : '';
    }

    /**
     * Get the keypair seed, if present.
     *
     * @return string
     */
    public function getSeed(): string
    {
        return !is_null($this->keypair)
            ? $this->keypair->getSeed()
            : '';
    }

    /**
     * Can this account do signing?
     *
     * @return bool
     */
    public function canSign(): bool
    {
        return !is_null($this->keypair)
            ? $this->keypair->canSign()
            : false;
    }

    /**
     * Get the keypair.
     *
     * @throws InvalidKeyException
     * @return Keypair
     */
    public function getKeyPair(): Keypair
    {
        if (isset($this->keypair)) {
            return $this->keypair;
        }

        throw new InvalidKeyException('This account does not have a keypair');
    }

    /**
     * Set the keypair.
     *
     * @param Keypair $keypair
     *
     * @return static
     */
    public function withKeyPair(Keypair $keypair): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->keypair = Copy::deep($keypair);

        return $clone;
    }

    /**
     * Get the current sequence number.
     *
     * @return SequenceNumber|null
     */
    public function getSequenceNumber(): ?SequenceNumber
    {
        return isset($this->sequenceNumber) ? $this->sequenceNumber : null;
    }

    /**
     * Set the sequence number.
     *
     * @param SequenceNumber $sequenceNumber
     *
     * @return static
     */
    public function withSequenceNumber(SequenceNumber $sequenceNumber): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->sequenceNumber = Copy::deep($sequenceNumber);

        return $clone;
    }

    /**
     * Set the account resource and sequence number.
     *
     * @param AccountResource $accountResource
     * @return static
     */
    public function withAccountResource(AccountResource $accountResource): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->sequenceNumber = $accountResource->getSequenceNumber();
        $clone->accountResource = $accountResource;

        return $clone;
    }

    /**
     * Return the address as an AccountId object.
     *
     * @return AccountId
     */
    public function getAccountId(): AccountId
    {
        if (!isset($this->keypair)) {
            throw new InvalidKeyException('Attempting to get an AccountId from an undefined keypair.');
        }

        return $this->keypair->getAccountId();
    }

    /**
     * Return the address as a MuxedAccount object.
     *
     * @return MuxedAccount
     */
    public function getMuxedAccount(): MuxedAccount
    {
        if (!isset($this->keypair)) {
            throw new InvalidKeyException('Attempting to get an MuxedAccount from an undefined keypair.');
        }

        return $this->keypair->getMuxedAccount();
    }

    /**
     * Convert this addressable into a weighted signer for account management.
     *
     * @param UInt32|integer $weight
     * @return Signer
     */
    public function getWeightedSigner(UInt32|int $weight): Signer
    {
        if (!isset($this->keypair)) {
            throw new InvalidKeyException('Attempting to get a weighted signer from an undefined keypair.');
        }

        return $this->keypair->getWeightedSigner($weight);
    }

    /**
     * Has this account been loaded with resource data from horizon?
     *
     * @return bool
     */
    public function hasBeenLoaded(): bool
    {
        return isset($this->accountResource);
    }

    /**
     * Return the 'self' link.
     *
     * @return string|null
     */
    public function getSelfLink(): ?string
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getSelfLink();
        }

        return null;
    }

    /**
     * Return the 'transactions' link.
     *
     * @return string|null
     */
    public function getTransactionsLink(): ?string
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getTransactionsLink();
        }

        return null;
    }

    /**
     * Return the 'operations' link.
     *
     * @return string|null
     */
    public function getOperationsLink(): ?string
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getOperationsLink();
        }

        return null;
    }

    /**
     * Return the 'payments' link.
     *
     * @return string|null
     */
    public function getPaymentsLink(): ?string
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getPaymentsLink();
        }

        return null;
    }

    /**
     * Return the 'effects' link.
     *
     * @return string|null
     */
    public function getEffectsLink(): ?string
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getEffectsLink();
        }

        return null;
    }

    /**
     * Return the 'offers' link.
     *
     * @return string|null
     */
    public function getOffersLink(): ?string
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getOffersLink();
        }

        return null;
    }

    /**
     * Return the 'trades' link.
     *
     * @return string|null
     */
    public function getTradesLink(): ?string
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getTradesLink();
        }

        return null;
    }

    /**
     * Return the 'data' link.
     *
     * @return string|null
     */
    public function getDataLink(): ?string
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getDataLink();
        }

        return null;
    }

    /**
     * A unique identifier for the account.
     *
     * @return string|null
     */
    public function getResourceId(): ?string
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getId();
        }

        return null;
    }


    /**
     * The unsigned 32-bit ledger number of the sequence number's age.
     *
     * @return UInt32|null
     */
    public function getSequenceLedger(): ?UInt32
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getSequenceLedger();
        }

        return null;
    }

    /**
     * The unsigned 64 bit UNIX timestamp of the sequence number's age.
     *
     * @return UInt64|null
     */
    public function getSequenceTime(): ?UInt64
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getSequenceTime();
        }

        return null;
    }

    /**
     * The number of subentries on the account.
     *
     * @return UInt32|null
     */
    public function getSubEntryCount(): ?UInt32
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getSubEntryCount();
        }

        return null;
    }

    /**
     * The domain that hosts the account's stellar.toml file.
     *
     * @return string
     */
    public function getHomeDomain(): ?string
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getHomeDomain();
        }

        return null;
    }

    /**
     * The sequence number of the last ledger that included changes to this account.
     *
     * @return UInt32|null
     */
    public function getLastModifiedLedgerSequence(): ?UInt32
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getLastModifiedLedgerSequence();
        }

        return null;
    }

    /**
     * The number of reserves sponsored by the account.
     *
     * @return UInt32|null
     */
    public function getReservesSponsoringCount(): ?UInt32
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getReservesSponsoringCount();
        }

        return null;
    }

    /**
     * The number of reserves sponsored for the account.
     *
     * @return UInt32|null
     */
    public function getReservesSponsoredCount(): ?UInt32
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getReservesSponsoredCount();
        }

        return null;
    }

    /**
     * The account ID of the sponsor who is paying the reserves for this account.
     *
     * @return string|null
     */
    public function getSponsorId(): ?string
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getSponsorId();
        }

        return null;
    }

    /**
     * The weight required for a valid transaction including the Allow Trust
     * and Bump Sequence operations.
     *
     * @return UInt32|null
     */
    public function getLowThreshold(): ?UInt32
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getLowThreshold();
        }

        return null;
    }

    /**
     * The weight required for a valid transaction including the Create Account,
     * Payment, Path Payment, Manage Buy Offer, Manage Sell Offer, Create Passive
     * Sell Offer, Change Trust, Inflation, and Manage Data operations.
     *
     * @return UInt32|null
     */
    public function getMediumThreshold(): ?UInt32
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getMediumThreshold();
        }

        return null;
    }

    /**
     * The weight required for a valid transaction including the Account Merge
     * and Set Options operations.
     *
     * @return UInt32|null
     */
    public function getHighThreshold(): ?UInt32
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getHighThreshold();
        }

        return null;
    }

    /**
     * The flags denoting asset issuer privileges.
     *
     * @return array<int|string, mixed>|null
     */
    public function getFlags(): ?array
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getFlags();
        }

        return null;
    }

    /**
     * If set to true, no other flags can be changed.
     *
     * @return bool|null
     */
    public function getAuthImmutableFlag(): ?bool
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getAuthImmutableFlag();
        }

        return null;
    }

    /**
     * If set to true, anyone who wants to hold an asset issued by this account
     * must first be approved by this account.
     *
     * @return bool|null
     */
    public function getAuthRequiredFlag(): ?bool
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getAuthRequiredFlag();
        }

        return null;
    }

    /**
     * If set to true, this account can freeze the balance of a holder of an
     * asset issued by this account.
     *
     * @return bool|null
     */
    public function getAuthRevocableFlag(): ?bool
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getAuthRevocableFlag();
        }

        return null;
    }

    /**
     * If set to true, trustlines created for assets issued bu this account
     * have clawbacks enabled.
     *
     * @return bool|null
     */
    public function getAuthClawbackEnabledFlag(): ?bool
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getAuthClawbackEnabledFlag();
        }

        return null;
    }

    /**
     * The assets this account holds.
     *
     * @return array<AccountBalanceResource>|null
     */
    public function getBalances(): ?array
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getBalances();
        }

        return null;
    }

    /**
     * Find the balance of a given asset held by this account.
     *
     * @param Asset|string $asset
     * @return AccountBalanceResource|null
     */
    public function getBalanceForAsset(Asset|string $asset): AccountBalanceResource|null
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getBalanceForAsset($asset);
        }

        return null;
    }

    /**
     * The public keys and associated weights that can be used to authorize
     * transactions for this account. Used for multi-sig.
     *
     * @return array<AccountSignerResource>|null
     */
    public function getSigners(): ?array
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getSigners();
        }

        return null;
    }

    /**
     * An array of account data fields.
     *
     * @param string|null $key
     * @return array<int|string, mixed>
     */
    public function getData(string $key = null): array|string|null
    {
        if (isset($this->accountResource)) {
            return $this->accountResource->getData($key);
        }

        return null;
    }
}

<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Envelope;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;

final class HashIdPreimage extends Union implements XdrUnion
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return EnvelopeType::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            EnvelopeType::ENVELOPE_OP_ID             => HashIdPreimageOperationId::class,
            EnvelopeType::ENVELOPE_POOL_REVOKE_OP_ID => HashIdPreimageRevokeId::class,
        ];
    }

    /**
     * Return the underlying hash Id preimage.
     *
     * @return HashIdPreimageOperationId|HashIdPreimageRevokeId|null
     */
    public function unwrap(): HashIdPreimageOperationId|HashIdPreimageRevokeId|null
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }

    /**
     * Create a new instance by wrapping a HashIdPreimageOperationId
     *
     * @param HashIdPreimageOperationId $hashIdPreimageOperationId
     * @return static
     */
    public static function wrapHashidIdPreimageOperationId(HashIdPreimageOperationId $hashIdPreimageOperationId): static
    {
        $hashIdPreimage = new static();
        $hashIdPreimage->discriminator = EnvelopeType::operationId();
        $hashIdPreimage->value = $hashIdPreimageOperationId;

        return $hashIdPreimage;
    }

    /**
     * Create a new instance by wrapping a HashIdPreimageRevokeId.
     *
     * @param HashIdPreimageRevokeId $hashIdPreimageRevokeId
     * @return static
     */
    public static function wrapHashIdPreimageRevokeId(HashIdPreimageRevokeId $hashIdPreimageRevokeId): static
    {
        $hashIdPreimage = new static();
        $hashIdPreimage->discriminator = EnvelopeType::poolRevokeOperationId();
        $hashIdPreimage->value = $hashIdPreimageRevokeId;

        return $hashIdPreimage;
    }
}

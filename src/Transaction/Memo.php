<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Primitives\String28;
use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class Memo extends Union implements XdrUnion
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return MemoType::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            MemoType::MEMO_NONE   => XDR::VOID,
            MemoType::MEMO_TEXT   => String28::class,
            MemoType::MEMO_ID     => UInt64::class,
            MemoType::MEMO_HASH   => Hash::class,
            MemoType::MEMO_RETURN => Hash::class,
        ];
    }

    /**
     * Create a new void memo.
     *
     * @return static
     */
    public static function none(): static
    {
        $memo = new static();
        $memo->discriminator = MemoType::none();
        $memo->value = null;

        return $memo;
    }

    /**
     * Create a new text memo.
     *
     * @param string $message
     * @return static
     */
    public static function wrapText(String28|string $message): static
    {
        if (is_string($message)) {
            $message = String28::of($message);
        }

        $memo = new static();
        $memo->discriminator = MemoType::text();
        $memo->value = $message;

        return $memo;
    }

    /**
     * Create a new id memo using an unsigned 64 bit number.
     *
     * @return static
     */
    public static function wrapId(UInt64 $id): static
    {
        $memo = new static();
        $memo->discriminator = MemoType::id();
        $memo->value = $id;

        return $memo;
    }

    /**
     * Create a new hash memo using a 32 byte hash string.
     *
     * @param Hash $hash
     * @return static
     */
    public static function wrapHash(Hash $hash): static
    {
        $memo = new static();
        $memo->discriminator = MemoType::hash();
        $memo->value = $hash;

        return $memo;
    }

    /**
     * Create a new return memo using a 32 byte hash of the refunded transaction.
     *
     * @param Hash $hash
     * @return static
     */
    public static function wrapReturn(Hash $hash): static
    {
        $memo = new static();
        $memo->discriminator = MemoType::return();
        $memo->value = $hash;

        return $memo;
    }

    /**
     * Return the underlying memo content.
     *
     * @return String28|UInt64|Hash|null
     */
    public function unwrap(): String28|UInt64|Hash|null
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}

<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class TransactionPhase extends Union implements XdrUnion
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
            0 => TxSetComponentList::class,
        ];
    }

    /**
     * Create a new instance by wrapping a TxSetComponentList object.
     *
     * @param TxSetComponentList $txSetComponentList
     * @return static
     */
    public static function wrapTxSetComponentList(TxSetComponentList $txSetComponentList): static
    {
        $transactionPhase = new static();
        $transactionPhase->discriminator = 0;
        $transactionPhase->value = $txSetComponentList;

        return $transactionPhase;
    }

    public function unwrap(): ?TxSetComponentList
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}

<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Envelope;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class EnvelopeType extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const ENVELOPE_TRANSACTION_V0 = 'envelopeTypeTxV0';
    public const ENVELOPE_SCP = 'envelopeTypeScp';
    public const ENVELOPE_TRANSACTION = 'envelopeTypeTx';
    public const ENVELOPE_AUTH = 'envelopeTypeAuth';
    public const ENVELOPE_SCP_VALUE = 'envelopeTypeScpvalue';
    public const ENVELOPE_FEE_BUMP = 'envelopeTypeTxFeeBump';
    public const ENVELOPE_OP_ID = 'envelopeTypeOpId';
    public const ENVELOPE_POOL_REVOKE_OP_ID = 'envelopeTypePoolRevokeOpId';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0 => self::ENVELOPE_TRANSACTION_V0,
            1 => self::ENVELOPE_SCP,
            2 => self::ENVELOPE_TRANSACTION,
            3 => self::ENVELOPE_AUTH,
            4 => self::ENVELOPE_SCP_VALUE,
            5 => self::ENVELOPE_FEE_BUMP,
            6 => self::ENVELOPE_OP_ID,
            7 => self::ENVELOPE_POOL_REVOKE_OP_ID,
        ];
    }

    /**
     * Return the selected envelope type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getValue();
    }

    /**
     * Create a new instance pre-selected as ENVELOPE_TRANSACTION_V0
     *
     * @return static
     */
    public static function transactionV0(): static
    {
        return (new static())->withValue(self::ENVELOPE_TRANSACTION_V0);
    }

    /**
     * Create a new instance pre-selected as ENVELOPE_SCP
     *
     * @return static
     */
    public static function scp(): static
    {
        return (new static())->withValue(self::ENVELOPE_SCP);
    }

    /**
     * Create a new instance pre-selected as ENVELOPE_TRANSACTION
     *
     * @return static
     */
    public static function transaction(): static
    {
        return (new static())->withValue(self::ENVELOPE_TRANSACTION);
    }

    /**
     * Create a new instance pre-selected as ENVELOPE_AUTH
     *
     * @return static
     */
    public static function auth(): static
    {
        return (new static())->withValue(self::ENVELOPE_AUTH);
    }

    /**
     * Create a new instance pre-selected as ENVELOPE_SCP_VALUE
     *
     * @return static
     */
    public static function scpValue(): static
    {
        return (new static())->withValue(self::ENVELOPE_SCP_VALUE);
    }

    /**
     * Create a new instance pre-selected as ENVELOPE_FEE_BUMP
     *
     * @return static
     */
    public static function feeBump(): static
    {
        return (new static())->withValue(self::ENVELOPE_FEE_BUMP);
    }

    /**
     * Create a new instance pre-selected as ENVELOPE_OP_ID
     *
     * @return static
     */
    public static function operationId(): static
    {
        return (new static())->withValue(self::ENVELOPE_OP_ID);
    }

    /**
     * Create a new instance pre-selected as ENVELOPE_POOL_REVOKE_OP_ID
     *
     * @return static
     */
    public static function poolRevokeOperationId(): static
    {
        return (new static())->withValue(self::ENVELOPE_POOL_REVOKE_OP_ID);
    }
}

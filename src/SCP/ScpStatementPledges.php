<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;

final class ScpStatementPledges extends Union implements XdrUnion
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return ScpStatementType::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            ScpStatementType::SCP_ST_PREPARE     => ScpStatementPrepare::class,
            ScpStatementType::SCP_ST_CONFIRM     => ScpStatementConfirm::class,
            ScpStatementType::SCP_ST_EXTERNALIZE => ScpStatementExternalize::class,
            ScpStatementType::SCP_ST_NOMINATE    => ScpNomination::class,
        ];
    }

    /**
     * Create a new instance by wrapping a ScpStatementPrepare object.
     *
     * @param ScpStatementPrepare $scpStatementPrepare
     * @return static
     */
    public static function wrapPrepareStatement(ScpStatementPrepare $scpStatementPrepare): static
    {
        $scpStatementPledges = new static();
        $scpStatementPledges->discriminator = ScpStatementType::prepare();
        $scpStatementPledges->value = $scpStatementPrepare;

        return $scpStatementPledges;
    }

    /**
     * Create a new instance by wrapping a ScpStatementConfirm object.
     *
     * @param ScpStatementConfirm $scpStatementConfirm
     * @return static
     */
    public static function wrapConfirmStatement(ScpStatementConfirm $scpStatementConfirm): static
    {
        $scpStatementPledges = new static();
        $scpStatementPledges->discriminator = ScpStatementType::confirm();
        $scpStatementPledges->value = $scpStatementConfirm;

        return $scpStatementPledges;
    }

    /**
     * Create a new instance by wrapping a ScpStatementExternalize object.
     *
     * @param ScpStatementExternalize $scpStatementExternalize
     * @return static
     */
    public static function wrapExternalizeStatement(ScpStatementExternalize $scpStatementExternalize): static
    {
        $scpStatementPledges = new static();
        $scpStatementPledges->discriminator = ScpStatementType::externalize();
        $scpStatementPledges->value = $scpStatementExternalize;

        return $scpStatementPledges;
    }

    /**
     * Create a new instance by wrapping a ScpNomination object.
     *
     * @param ScpNomination $scpNomination
     * @return static
     */
    public static function wrapNomination(ScpNomination $scpNomination): static
    {
        $scpStatementPledges = new static();
        $scpStatementPledges->discriminator = ScpStatementType::nominate();
        $scpStatementPledges->value = $scpNomination;

        return $scpStatementPledges;
    }

    /**
     * Return the underlying value.
     *
     * @return ScpStatementPrepare|ScpStatementConfirm|ScpStatementExternalize|ScpNomination|null
     */
    public function unwrap(): ScpStatementPrepare|ScpStatementConfirm|ScpStatementExternalize|ScpNomination|null
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}

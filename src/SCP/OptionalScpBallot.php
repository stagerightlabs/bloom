<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Primitives\Optional;
use StageRightLabs\PhpXdr\Interfaces\XdrOptional;

final class OptionalScpBallot extends Optional implements XdrOptional
{
    /**
     * The encoding type for the optional value.
     *
     * @return string
     */
    public static function getXdrValueType(): string
    {
        return ScpBallot::class;
    }

    /**
     * Instantiate an instance from an ScpBallot.
     *
     * @param ScpBallot $scpBallot
     * @return static
     */
    public static function some(ScpBallot $scpBallot): static
    {
        return self::none()->withValue($scpBallot);
    }

    /**
     * Return the optional value.
     *
     * @return ScpBallot|null
     */
    public function unwrap(): ?ScpBallot
    {
        return $this->getValue();
    }
}

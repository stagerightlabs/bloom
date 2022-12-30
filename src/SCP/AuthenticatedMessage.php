<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class AuthenticatedMessage extends Union implements XdrUnion
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
            0 => AuthenticatedMessageV0::class,
        ];
    }

    /**
     * Create a new instance by wrapping an AuthenticatedMessageV0 object.
     *
     * @param AuthenticatedMessageV0 $message
     * @return static
     */
    public static function wrapAuthenticatedMessageV0(AuthenticatedMessageV0 $message): static
    {
        $authenticatedMessage = new static();
        $authenticatedMessage->discriminator = 0;
        $authenticatedMessage->value = $message;

        return $authenticatedMessage;
    }

    /**
     * Return the underlying value.
     *
     * @return AuthenticatedMessageV0|null
     */
    public function unwrap(): ?AuthenticatedMessageV0
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}

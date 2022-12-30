<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class IpAddrType extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const IPV4 = 'IPv4';
    public const IPV6 = 'IPv6';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0 => self::IPV4,
            1 => self::IPV6,
        ];
    }

    /**
     * Return the selected type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getValue();
    }

    /**
     * Create a new instance pre-selected as IPv4.
     *
     * @return static
     */
    public static function ipv4(): static
    {
        return (new static())->withValue(self::IPV4);
    }

    /**
     * Create a new instance pre-selected as IPv6.
     *
     * @return static
     */
    public static function ipv6(): static
    {
        return (new static())->withValue(self::IPV6);
    }
}

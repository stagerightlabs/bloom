<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;

final class PeerAddressIp extends Union implements XdrUnion
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return IpAddrType::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            IpAddrType::IPV4 => IPv4::class,
            IpAddrType::IPV6 => IPv6::class,
        ];
    }

    /**
     * Create a new instance by wrapping an IPv4 address.
     *
     * @param IPv4 $ip
     * @return static
     */
    public static function wrapIPv4(IPv4 $ip): static
    {
        $peerAddressIp = new static();
        $peerAddressIp->discriminator = IpAddrType::ipv4();
        $peerAddressIp->value = $ip;

        return $peerAddressIp;
    }

    /**
     * Create a new instance by wrapping an IPv6 address.
     *
     * @param IPv6 $ip
     * @return static
     */
    public static function wrapIPv6(IPv6 $ip): static
    {
        $peerAddressIp = new static();
        $peerAddressIp->discriminator = IpAddrType::ipv6();
        $peerAddressIp->value = $ip;

        return $peerAddressIp;
    }

    /**
     * Return the underlying value.
     *
     * @return IPv4|IPv6|null
     */
    public function unwrap(): IPv4|IPv6|null
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}

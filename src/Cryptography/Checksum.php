<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Cryptography;

/**
 * Generate and verify CRC-16-CCIIT checksums.
 *
 * Borrowed from zulucrypto/stellar-api, with some small modifications.
 *
 * @see https://github.com/zulucrypto/stellar-api/blob/b73df4f262bf7e1d18ed2157ec10d0634515ed16/src/Util/Checksum.php
 */
class Checksum
{
    /**
     * Generate a CRC-16 checksum of a binary string as a 2-byte little-endian value.
     *
     * @param string $bytes
     * @return string
     */
    public static function generate(string $bytes): string
    {
        return pack('v', self::crc16($bytes));
    }

    /**
     * Returns true if $expected matches the checksum of $bytesToChecksum
     *
     * @param string $expected
     * @param string $bytesToChecksum
     * @return bool
     */
    public static function verify(string $expected, string $bytesToChecksum): bool
    {
        return self::generate($bytesToChecksum) === $expected;
    }

    /**
     * Returns the crc16 checksum of $bytes
     *
     * Ported from Java implementation at: http://introcs.cs.princeton.edu/java/61data/CRC16CCITT.java.html
     *
     * Initial value changed to 0x0000 to match Stellar configuration.
     *
     * @param string $bytes
     * @return int (4-byte checksum)
     */
    protected static function crc16(string $bytes)
    {
        $crc = 0x0000;
        $polynomial = 0x1021;

        foreach (str_split($bytes) as $byte) {
            $byte = ord($byte);

            for ($i = 0; $i < 8; $i++) {
                $bit = (($byte >> (7 - $i) & 1) == 1);
                $c15 = (($crc >> 15 & 1) == 1);
                $crc <<= 1;
                if ($c15 ^ $bit) {
                    $crc ^= $polynomial;
                }
            }
        }

        return $crc & 0xffff;
    }
}

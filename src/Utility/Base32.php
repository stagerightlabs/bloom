<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Utility;

/**
 * Encode and decode values as base 32 strings.
 *
 * Loosely inspired by ChristianRiesen/base32 and RFC 4648
 *
 * @see https://github.com/ChristianRiesen/base32
 * @see https://datatracker.ietf.org/doc/html/rfc4648
 */
final class Base32
{
    /**
     * Encode a string of bytes into base 32 using upper case letters.
     *
     * @param string $message
     * @param bool $pad
     * @return string
     */
    public static function encode(string $message, $pad = false): string
    {
        // check for empty string
        if (empty($message)) {
            return '';
        }

        // Compile a representation of the message as a string of binary digits
        // @phpstan-ignore-next-line
        $binary = array_reduce(unpack('C*', $message, 0), function ($binary, $ch) {
            return $binary .= str_pad(decbin($ch), 8, '0', STR_PAD_LEFT);
        }, '');

        // Read the binary digit string 5 bits at a time and interpret
        // each group (or pentet) as a base 32 character.
        $encoded = array_reduce(str_split($binary, 5), function ($encoded, $pentet) {
            $pentet = str_pad($pentet, 5, '0', STR_PAD_RIGHT);
            return $encoded .= self::ENCODING[$pentet];
        }, '');

        // Apply padding to the end of the encoded string if desired.
        if ($pad && strlen($encoded) % 8 !== 0) {
            return $encoded . str_repeat('=', 8 - (strlen($encoded) % 8));
        }

        return $encoded;
    }

    /**
     * Decode a base 32 string into a string of bytes.
     *
     * @param string $message
     * @return string
     */
    public static function decode(string $message): string
    {
        // Ensure all characters are upper case
        $message = strtoupper($message);

        // Remove characters that are not part of our alphabet
        $message = preg_replace('/[^A-Z2-7]/', '', $message);

        // Check for empty string
        if (empty($message)) {
            return '';
        }

        // Reverse the message into a string of binary digits
        $binary = array_reduce(str_split($message), function ($binary, $letter) {
            return $binary .= self::DECODING[$letter];
        }, '');

        // Convert the binary string into bytes
        return array_reduce(str_split($binary, 8), function ($decoded, $octet) {
            return $decoded .= strlen($octet) == 8
                ? chr(intval(bindec($octet)))
                : '';
        }, '');
    }

    /**
     * A lookup table for character encoding.
     */
    public const ENCODING = [
        '00000' => 'A',
        '00001' => 'B',
        '00010' => 'C',
        '00011' => 'D',
        '00100' => 'E',
        '00101' => 'F',
        '00110' => 'G',
        '00111' => 'H',
        '01000' => 'I',
        '01001' => 'J',
        '01010' => 'K',
        '01011' => 'L',
        '01100' => 'M',
        '01101' => 'N',
        '01110' => 'O',
        '01111' => 'P',
        '10000' => 'Q',
        '10001' => 'R',
        '10010' => 'S',
        '10011' => 'T',
        '10100' => 'U',
        '10101' => 'V',
        '10110' => 'W',
        '10111' => 'X',
        '11000' => 'Y',
        '11001' => 'Z',
        '11010' => '2',
        '11011' => '3',
        '11100' => '4',
        '11101' => '5',
        '11110' => '6',
        '11111' => '7',
    ];

    /**
     * A lookup table for character decoding.
     */
    public const DECODING = [
        'A' => '00000',
        'B' => '00001',
        'C' => '00010',
        'D' => '00011',
        'E' => '00100',
        'F' => '00101',
        'G' => '00110',
        'H' => '00111',
        'I' => '01000',
        'J' => '01001',
        'K' => '01010',
        'L' => '01011',
        'M' => '01100',
        'N' => '01101',
        'O' => '01110',
        'P' => '01111',
        'Q' => '10000',
        'R' => '10001',
        'S' => '10010',
        'T' => '10011',
        'U' => '10100',
        'V' => '10101',
        'W' => '10110',
        'X' => '10111',
        'Y' => '11000',
        'Z' => '11001',
        '2' => '11010',
        '3' => '11011',
        '4' => '11100',
        '5' => '11101',
        '6' => '11110',
        '7' => '11111',
    ];
}

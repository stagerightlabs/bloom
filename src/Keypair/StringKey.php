<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Keypair;

use StageRightLabs\Bloom\Cryptography\Checksum;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Exception\InvalidKeyException;
use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\Utility\Base32;
use StageRightLabs\PhpXdr\XDR;

/**
 * Utility methods for interacting with addressable keys.
 *
 * @see https://github.com/stellar/js-stellar-base/blob/cf7e64c8d2e7e2701c2a98c69e8df7297335dba0/src/strkey.js
 * @see https://github.com/zulucrypto/stellar-api/blob/b73df4f262bf7e1d18ed2157ec10d0634515ed16/src/AddressableKey.php
 */
final class StringKey
{
    /**
     * Constants
     */
    public const TYPE_ADDRESS = 'address';
    public const TYPE_SEED = 'seed';
    public const TYPE_MUXED_ADDRESS = 'muxed_address';
    public const TYPE_PRE_AUTH_TX = 'pre_auth_tx';
    public const TYPE_SHA_256_HASH = 'sha_256_hash';
    public const TYPE_SIGNED_PAYLOAD = 'signed_payload';

    /**
     * Convert a value to Base32 with a checksum and an optional prefix.
     *
     * @param string $bytes
     * @param string $type
     * @return string
     */
    public static function encode(string $bytes, string $type): string
    {
        $prefix = self::getPrefix($type);
        $payload = pack('C', $prefix) . $bytes;
        $checksum = Checksum::generate($payload);

        return Base32::encode($payload . $checksum);
    }

    /**
     * Recover a payload from a Base32 string and validate the checksum.
     *
     * @param string $key
     * @param string|null $type
     * @return array{valid: bool, content: string}
     */
    public static function decode(string $key, ?string $type = null): array
    {
        $type = $type ?? self::getType($key);
        $prefix = self::getPrefix($type);
        $decoded = Base32::decode(strtoupper($key));
        $checksum = substr($decoded, -2);
        $payload = substr($decoded, 1, -2);
        $prefix = pack('C', $prefix);

        // The decoded value must re-encode back to the same original value
        $canBeReconverted = $key == Base32::encode($decoded);

        // The checksum verification must pass
        $hasValidChecksum = Checksum::verify($checksum, $prefix . $payload);

        // The low three bits of the version byte must be zero, meaning that
        // the second pentet of the encoded string must be one of these:
        // 00000 => 'A', 00001 => 'B', 00010 => 'C' or 00011 => 'D'
        $lowOrderVersionBitsAreZero = in_array($key[1], ['A', 'B', 'C', 'D'], true);

        return [
            'valid'   => $canBeReconverted && $hasValidChecksum && $lowOrderVersionBitsAreZero,
            'content' => $payload,
        ];
    }

    /**
     * Attempt to decode a key and throw an exception if it fails.
     *
     * @param string $key
     * @param string|null $type
     * @throws InvalidKeyException
     * @return non-empty-string
     */
    public static function decodeOrFail(string $key, ?string $type = null): string
    {
        $result = self::decode($key, $type);

        if (!$result['valid']) {
            throw new InvalidKeyException('Attempting to decode an invalid key.');
        }

        // @codeCoverageIgnoreStart
        if (!array_key_exists('content', $result) || $result['content'] == '') {
            throw new InvalidKeyException('Attempting to decode an invalid key.');
        }
        // @codeCoverageIgnoreEnd

        return $result['content'];
    }

    /**
     * Perform a rudimentary validity check. Used when a bool response is needed.
     *
     * @param string $key
     * @param string $type
     * @return bool
     */
    public static function isValid(string $key, string $type): bool
    {
        return self::checkValidity($key, $type)['valid'];
    }

    /**
     * Perform a rudimentary validity check and return details about a failure.
     * We accept a null prefix to simplify the overall API.
     * This check is not meant to be definitive.
     *
     * @param string $key
     * @param string $type
     * @return array{valid: bool, message: string}
     */
    public static function checkValidity(string $key, string $type): array
    {
        // Is the string key length correct?
        switch ($type) {
            case StringKey::TYPE_ADDRESS:
            case StringKey::TYPE_SEED:
            case StringKey::TYPE_PRE_AUTH_TX:
            case StringKey::TYPE_SHA_256_HASH:
                if (strlen($key) != 56) {
                    return [
                        'valid'   => false,
                        'message' => 'Unexpected key length',
                    ];
                }
                break;

            case StringKey::TYPE_MUXED_ADDRESS:
                if (strlen($key) != 69) {
                    return [
                        'valid'   => false,
                        'message' => 'Unexpected key length',
                    ];
                }
                break;

            case StringKey::TYPE_SIGNED_PAYLOAD:
                if (strlen($key) < 56 || strlen($key) > 165) {
                    return [
                        'valid'   => false,
                        'message' => 'Unexpected key length',
                    ];
                }
                break;

            default:
                return [
                    'valid'   => false,
                    'message' => 'Unknown key type',
                ];
        }

        $decoded = self::decode($key, $type);

        // Did the decoded checksum validate?
        if (!$decoded['valid']) {
            return [
                'valid'   => false,
                'message' => 'Invalid checksum',
            ];
        }

        // Does the stored payload length match the actual length?
        if ($type == StringKey::TYPE_SIGNED_PAYLOAD) {
            // Decode the stored payload length
            $length = unpack('N', substr($decoded['content'], 32, 4));
            $length = $length ? $length[1] : 0;
            // Find the next largest multiple of four
            $paddedLength = $length % 4 == 0 ? $length : $length + (4 - ($length % 4));
            // Determine if the payload substring length matches the padded length
            $payloadLength = strlen(substr($decoded['content'], 36));

            // If not then this signed payload does not pass validation
            if ($paddedLength != $payloadLength) {
                return [
                    'valid'   => false,
                    'message' => 'Payload length mismatch',
                ];
            }
        }

        // All the checks have passed; we can assume validity
        return [
            'valid'   => true,
            'message' => '',
        ];
    }

    /**
     * Encode bytes into a seed with an 'S' prefix.
     *
     * @param string $bytes
     * @return string
     */
    public static function encodeSeed(string $bytes): string
    {
        return self::encode($bytes, self::TYPE_SEED);
    }

    /**
     * Recover the bytes of a seed from an encoded string.
     *
     * @param string $seed
     * @return array{valid: bool, content: string}
     */
    public static function decodeSeed(string $seed): array
    {
        return self::decode($seed, self::TYPE_SEED);
    }

    /**
     * Perform a simple validity check on an encoded seed.
     *
     * @param string $seed
     * @return bool
     */
    public static function isValidSeed(string $seed): bool
    {
        return self::isValid($seed, self::TYPE_SEED);
    }

    /**
     * Encode bytes into an address with a 'G' prefix.
     *
     * @param string $bytes
     * @return string
     */
    public static function encodeAddress($bytes): string
    {
        return self::encode($bytes, self::TYPE_ADDRESS);
    }

    /**
     * Recover the bytes of an address from an encoded string.
     *
     * @param string $address
     * @return array{valid: bool, content: string}
     */
    public static function decodeAddress(string $address): array
    {
        return self::decode($address, self::TYPE_ADDRESS);
    }

    /**
     * Perform a simple validity check on an address.
     *
     * @param string $address
     * @return bool
     */
    public static function isValidAddress(string $address): bool
    {
        return self::isValid($address, self::TYPE_ADDRESS);
    }

    /**
     * Encode bytes into an address with a 'M' prefix for a muxed key.
     *
     * @param string $bytes
     * @return string
     */
    protected static function encodeMuxedAddress(string $bytes): string
    {
        return self::encode($bytes, self::TYPE_MUXED_ADDRESS);
    }

    /**
     * Recover the bytes of a muxed address from an encoded string.
     *
     * @param string $address
     * @return array{valid: bool, content: string}
     */
    protected static function decodeMuxedAddress(string $address): array
    {
        return self::decode($address, self::TYPE_MUXED_ADDRESS);
    }

    /**
     * Perform a simple validity check on an encoded muxed public key.
     *
     * @param string $address
     * @return bool
     */
    public static function isValidMuxedAddress(string $address): bool
    {
        return self::isValid($address, self::TYPE_MUXED_ADDRESS);
    }

    /**
     * Encode bytes into a key with a 'T' prefix for a pre auth transaction.
     *
     * @param string $bytes
     * @return string
     */
    public static function encodePreAuthTx(string $bytes): string
    {
        return self::encode($bytes, self::TYPE_PRE_AUTH_TX);
    }

    /**
     * Recover the bytes of a pre auth transaction key from an encoded string.
     *
     * @param string $tx
     * @return array{valid: bool, content: string}
     */
    public static function decodePreAuthTx(string $tx): array
    {
        return self::decode($tx, self::TYPE_PRE_AUTH_TX);
    }

    /**
     * Perform a simple validity check on an encoded pre auth transaction key.
     *
     * @param string $tx
     * @return bool
     */
    public static function isValidPreAuthTx(string $tx): bool
    {
        return self::isValid($tx, self::TYPE_PRE_AUTH_TX);
    }

    /**
     * Encode a SHA256 hash into a key with an 'X' prefix.
     *
     * @param string $bytes
     * @return string
     */
    public static function encodeSha256Hash(string $bytes): string
    {
        return self::encode($bytes, self::TYPE_SHA_256_HASH);
    }

    /**
     * Recover a SHA256 hash from an encoded string.
     *
     * @param string $sha
     * @return array{valid: bool, content: string}
     */
    public static function decodeSha256Hash(string $sha): array
    {
        return self::decode($sha, self::TYPE_SHA_256_HASH);
    }

    /**
     * Perform a simple validity check on an encoded SHA256 hash.
     *
     * @param string $sha
     * @return bool
     */
    public static function isValidSha256Hash(string $sha): bool
    {
        return self::isValid($sha, self::TYPE_SHA_256_HASH);
    }

    /**
     * Encode a signed payload into a key with an 'Y' prefix.
     *
     * @param string $bytes
     * @return string
     */
    public static function encodeSignedPayload(string $bytes): string
    {
        return self::encode($bytes, self::TYPE_SIGNED_PAYLOAD);
    }

    /**
     * Recover a signed payload from an encoded string.
     *
     * @param string $signedPayload
     * @return array{valid: bool, content: string}
     */
    public static function decodeSignedPayload(string $signedPayload): array
    {
        return self::decode($signedPayload, self::TYPE_SIGNED_PAYLOAD);
    }

    /**
     * Perform a simple validity check on an encoded signed payload.
     *
     * @param string $sha
     * @return bool
     */
    public static function isValidSignedPayload(string $sha): bool
    {
        return self::isValid($sha, self::TYPE_SIGNED_PAYLOAD);
    }

    /**
     * Derive a public address from a secret seed.
     *
     * @param string $seed
     * @throws InvalidKeyException
     * @return string
     */
    public static function addressFromSeed(string $seed): string
    {
        $rawKeypair = sodium_crypto_sign_seed_keypair(
            self::decodeOrFail($seed, self::TYPE_SEED)
        );
        $payload = sodium_crypto_sign_publickey($rawKeypair);
        $prefix = pack('C', self::getPrefix(self::TYPE_ADDRESS));
        $checksum = Checksum::generate($prefix . $payload);

        return Base32::encode($prefix . $payload . $checksum);
    }

    /**
     * Return ED25519 bytes and the ID from a muxed address string.
     *
     * @see https://github.com/stellar/js-stellar-base/blob/cf7e64c8d2e7e2701c2a98c69e8df7297335dba0/src/util/decode_encode_muxed_account.js#L90
     * @param string $address
     * @throws InvalidKeyException
     * @return array{'id': UInt64, 'ed25519': string}
     */
    public static function deconstructMuxedAddress(string $address): array
    {
        $bytes = self::decodeMuxedAddress($address);

        if (!$bytes['valid']) {
            $exception = new InvalidKeyException("Attempting to deconstruct an invalid muxed address.");
            $exception->setKey($address);
            throw $exception;
        }

        return [
            'id'      => XDR::fromBytes(substr($bytes['content'], -8))->read(UInt64::class),
            'ed25519' => substr($bytes['content'], 0, -8),
        ];
    }

    /**
     * Generate a muxed address from an address (or bytes) and an ID.
     *
     * @param string $address
     * @param int|UInt64 $id
     * @throws InvalidKeyException
     * @return string
     */
    public static function constructMuxedAddress(string $address, int|UInt64 $id): string
    {
        // Is this already a muxed address?
        if (self::isValidMuxedAddress($address)) {
            $exception = new InvalidKeyException('Attempting to mux an address that has already been muxed.');
            $exception->setKey($address);
            throw $exception;
        }

        // Are we working with an address or with bytes?
        if ($address[0] == 'G') {
            $decoded = self::decodeAddress($address);

            if (!$decoded['valid']) {
                $exception = new InvalidKeyException("Attempting to mux an invalid address.");
                $exception->setKey($address);
                throw $exception;
            }

            $bytes = $decoded['content'];
        } else {
            $bytes = $address;
        }

        if (is_int($id)) {
            $id = UInt64::of($id);
        }

        return self::encodeMuxedAddress(
            $bytes . XDR::fresh()->write($id, UInt64::class)->buffer()
        );
    }

    /**
     * Determine a key type based on the first letter.
     *
     * @param string $key
     * @return string
     */
    public static function getType(string $key): string
    {
        $letter = substr($key, 0, 1);

        switch ($letter) {
            case 'G':
                return self::TYPE_ADDRESS;

            case 'S':
                return self::TYPE_SEED;

            case 'M':
                return self::TYPE_MUXED_ADDRESS;

            case 'T':
                return self::TYPE_PRE_AUTH_TX;

            case 'X':
                return self::TYPE_SHA_256_HASH;

            case 'P':
                return self::TYPE_SIGNED_PAYLOAD;

            default:
                $exception = new InvalidKeyException('Unknown Key String Type');
                $exception->setKey($key);
                throw $exception;
        }
    }

    /**
     * Fetch a binary prefix as a string.
     *
     * @param string $type
     * @throws InvalidArgumentException
     * @return string
     */
    public static function getPrefix(string $type): string
    {
        if (array_key_exists($type, self::PREFIXES)) {
            return strval(self::PREFIXES[$type]);
        }

        throw new InvalidArgumentException("Unknown prefix requested: '{$type}'");
    }

    /**
     * Byte prefixes that ensure encoded keys start with the correct letter.
     */
    protected const PREFIXES = [
        self::TYPE_ADDRESS        => 6 << 3, // 'G'
        self::TYPE_SEED           => 18 << 3, // 'S'
        self::TYPE_MUXED_ADDRESS  => 12 << 3, // M
        self::TYPE_PRE_AUTH_TX    => 19 << 3, // T
        self::TYPE_SHA_256_HASH   => 23 << 3, // X
        self::TYPE_SIGNED_PAYLOAD => 15 << 3, // P
    ];
}

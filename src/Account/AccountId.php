<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Account;

use StageRightLabs\Bloom\Keypair\PublicKey;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;

/**
 * An integer representation of the bytes that make up a public account address.
 */
class AccountId extends PublicKey implements XdrUnion
{
}

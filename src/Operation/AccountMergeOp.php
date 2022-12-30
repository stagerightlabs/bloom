<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\MuxedAccount;
use StageRightLabs\Bloom\Account\Thresholds;

class AccountMergeOp implements OperationVariety
{
    /**
     * Create a new merge operation.
     *
     * @param Addressable|string $destination
     * @param Addressable|string|null $source
     * @return Operation
     */
    public static function operation(
        Addressable|string $destination,
        Addressable|string $source = null
    ): Operation {
        $muxedAccount = MuxedAccount::fromAddressable($destination);

        return (new Operation())
            ->withBody(OperationBody::make(OperationType::ACCOUNT_MERGE, $muxedAccount))
            ->withSourceAccount($source);
    }

    /**
     * Does this operation have its expected payload?
     *
     * @return bool
     */
    public function isReady(): bool
    {
        return true;
    }

    /**
     * Get the operation's threshold category, either "low", "medium" or "high".
     *
     * @return string
     */
    public function getThreshold(): string
    {
        return Thresholds::CATEGORY_HIGH;
    }
}

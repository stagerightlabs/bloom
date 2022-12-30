<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\Thresholds;

class EndSponsoringFutureReservesOp implements OperationVariety
{
    /**
     * Create a new end-sponsoring-future-reserves operation.
     *
     * @param Addressable|string $source
     * @return Operation
     */
    public static function operation(
        Addressable|string $source = null,
    ): Operation {
        return (new Operation())
            ->withBody(OperationBody::make(OperationType::END_SPONSORING_FUTURE_RESERVES))
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
        return Thresholds::CATEGORY_MEDIUM;
    }
}

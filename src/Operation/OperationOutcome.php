<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

interface OperationOutcome
{
    /**
     * Return the result type.
     *
     * @return string|null
     */
    public function getType(): ?string;

    /**
     * Was the operation successful?
     *
     * @return bool
     */
    public function wasSuccessful(): bool;

    /**
     * Was the operation not successful?
     *
     * @return bool
     */
    public function wasNotSuccessful(): bool;

    /**
     * Return an error message that describes the problem, if there was one.
     *
     * @return string
     */
    public function getErrorMessage(): ?string;

    /**
     * Return the error code.
     *
     * @return string|null
     */
    public function getErrorCode(): ?string;
}

<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Exception;

use Exception;
use Throwable;

class BloomException extends Exception
{
    /**
     * @var string[]
     */
    protected array $options;

    /**
     * Instantiate a new exception instance.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    final public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get the value of options.
     *
     * @return string[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Set the value of options.
     *
     * @param string[] $options
     *
     * @return self
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Wrap a vendor exception as a Bloom exception.
     *
     * @param Exception $ex
     * @return static
     */
    public static function fromException(Exception $ex): static
    {
        return new static(
            $ex->getMessage(),
            $ex->getCode(),
            $ex
        );
    }
}

<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Arr;
use StageRightLabs\Bloom\Utility\Text;
use StageRightLabs\PhpXdr\Interfaces\XdrArray;

/**
 * @extends Arr<OperationResult>
 */
class OperationResultList extends Arr implements XdrArray
{
    /**
     * Properties
     */
    public const MAX_LENGTH = 2147483647;

    /**
     * The XDR encoding type for array members.
     *
     * @return string
     */
    public static function getXdrType(): string
    {
        return OperationResult::class;
    }

    /**
     * The maximum number of allowed array members.
     *
     * @return int
     */
    public static function getMaxLength(): int
    {
        return self::MAX_LENGTH;
    }

    /**
     * Instantiate an empty array.
     *
     * @return static
     */
    public static function empty(): static
    {
        return static::of([]);
    }

    /**
     * Retrieve a summary of the operation results as a native array of strings.
     *
     * @return array<int, array{operation: string, code: string, message: string}>
     */
    public function getSummary(): array
    {
        return array_reduce($this->arr, function ($carry, $operationResult) {
            $operation = 'operation_result';
            if ($operationResult->unwrap() instanceof OperationResultTr && $operationResult->unwrap()->unwrap() instanceof OperationOutcome) {
                $operationResult = $operationResult->unwrap()->unwrap();
                $operation = Text::snakeCase(Text::classBaseName(get_class($operationResult)));
            }

            $carry[] = [
                'operation' => $operation,
                'code'      => Text::snakeCase(strval($operationResult->getErrorCode())),
                'message'   => $operationResult->getErrorMessage(),
            ];
            return $carry;
        }, []);
    }
}

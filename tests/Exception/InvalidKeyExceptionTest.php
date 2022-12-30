<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Exception;

use StageRightLabs\Bloom\Exception\InvalidKeyException;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Exception\InvalidKeyException
 */
class InvalidKeyExceptionTest extends TestCase
{
    /**
     * @test
     * @covers ::getKey
     * @covers ::setKey
     */
    public function it_can_be_given_a_key_string()
    {
        $exception = new InvalidKeyException('some exception');
        $exception->setKey('some key string');

        $this->assertEquals('some key string', $exception->getKey());
    }
}

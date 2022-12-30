<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Exception;

use StageRightLabs\Bloom\Exception\BloomException;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Exception\BloomException
 */
class BloomExceptionTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     */
    public function it_can_be_instantiated()
    {
        $exception = new InvalidArgumentException('some exception');
        $this->assertInstanceOf(InvalidArgumentException::class, $exception);
    }

    /**
     * @test
     * @covers ::setOptions
     * @covers ::getOptions
     */
    public function it_can_be_given_a_list_of_options()
    {
        $exception = new InvalidArgumentException('some exception');
        $exception->setOptions(['a', 'b', 'c']);

        $this->assertEquals(['a', 'b', 'c'], $exception->getOptions());
    }

    /**
     * @test
     * @covers ::fromException
     */
    public function it_can_be_instantiated_from_a_regular_exception()
    {
        $exception = new \Exception();
        $bloomException = BloomException::fromException($exception);

        $this->assertInstanceOf(BloomException::class, $bloomException);
    }
}

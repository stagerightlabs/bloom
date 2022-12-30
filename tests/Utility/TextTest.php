<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Utility;

use StageRightLabs\Bloom\Operation\CreateAccountOp;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Utility\Text;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Utility\Text
 */
class TextTest extends TestCase
{
    /**
     * @test
     * @covers ::classBaseName
     */
    public function it_returns_a_class_base_name()
    {
        $classBaseName = Text::classBaseName(CreateAccountOp::class);

        $this->assertEquals('CreateAccountOp', $classBaseName);
    }

    /**
     * @test
     * @covers ::snakeCase
     */
    public function it_converts_text_to_snake_case()
    {
        $this->assertEquals('hello', Text::snakeCase('Hello'));
        $this->assertEquals('hello_world', Text::snakeCase('Hello World'));
        $this->assertEquals('create_account_op', Text::snakeCase('CreateAccountOp'));
    }
}

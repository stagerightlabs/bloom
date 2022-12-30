<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Utility;

use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Utility\Json;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Utility\Json
 */
class JsonTest extends TestCase
{
    /**
     * @test
     * @covers ::of
     * @covers ::__construct
     */
    public function it_can_be_instantiated()
    {
        $json = Json::of('["foo","bar"]');
        $this->assertInstanceOf(Json::class, $json);
        $this->assertEquals(['foo', 'bar'], $json->getAll());
    }

    /**
     * @test
     * @covers ::fromArray
     * @covers ::__construct
     */
    public function it_can_be_instantiated_from_an_array()
    {
        $json = Json::fromArray(['foo', 'bar']);
        $this->assertInstanceOf(Json::class, $json);
        $this->assertEquals(['foo', 'bar'], $json->getAll());
    }

    /**
     * @test
     * @covers ::getKeys
     */
    public function it_returns_the_payload_keys()
    {
        $json = Json::of('{"foo": "bar", "baz": "bat"}');
        $this->assertEquals(['foo', 'baz'], $json->getKeys());
    }

    /**
     * @test
     * @covers ::hasKey
     */
    public function it_determines_if_a_key_exists()
    {
        $json = Json::of('{"foo":"hello","bar":false,"baz":1,"bat":{"a":2,"b":"two","c":true}}');

        $this->assertTrue($json->hasKey('foo'));
        $this->assertFalse($json->hasKey('nope'));
        $this->assertFalse($json->hasKey(['foo', 'nope']));
        $this->assertTrue($json->hasKey('bat.a'));
        $this->assertFalse($json->hasKey('bat.nope'));
        $this->assertFalse($json->hasKey(['bat.a', 'bat.nope']));
    }

    /**
     * @test
     * @covers ::getAll
     */
    public function it_returns_the_entire_payload()
    {
        $json = Json::of('{"foo":"hello","bar":false,"baz":1,"bat":{"a":2,"b":"two","c":true}}');
        $expected = [
            'foo' => 'hello',
            'bar' => false,
            'baz' => 1,
            'bat' => [
                'a' => 2,
                'b' => 'two',
                'c' => true
            ]
        ];

        $this->assertEquals($expected, $json->getAll());
    }

    /**
     * @test
     * @covers ::getBoolean
     */
    public function it_returns_nested_boolean_values()
    {
        $json = Json::of('{"foo":"hello","bar":false,"baz":1,"bat":{"a":2,"b":"two","c":true}}');


        $this->assertNull($json->getBoolean('nope'));
        $this->assertFalse($json->getBoolean('nope', false));
        $this->assertFalse($json->getBoolean('bar'));
        $this->assertTrue($json->getBoolean('bat.c'));
    }

    /**
     * @test
     * @covers ::getInteger
     * @covers ::getValue
     */
    public function it_returns_nested_integer_values()
    {
        $json = Json::of('{"foo":"hello","bar":false,"baz":1,"bat":{"a":2,"b":"two","c":true}}');

        $this->assertNull($json->getInteger('nope'));
        $this->assertEquals(0, $json->getInteger('nope', 0));
        $this->assertEquals(1, $json->getInteger('baz'));
        $this->assertEquals(2, $json->getInteger('bat.a'));
    }

    /**
     * @test
     * @covers ::getString
     */
    public function it_returns_nested_string_values()
    {
        $json = Json::of('{"foo":"hello","bar":false,"baz":1,"bat":{"a":2,"b":"two","c":true,"d":{"1":"1","2":"2"}}}');

        $this->assertNull($json->getString('nope'));
        $this->assertEquals('default', $json->getString('nope', 'default'));
        $this->assertEquals('hello', $json->getString('foo'));
        $this->assertEquals('two', $json->getString('bat.b'));
        $this->assertEquals('2', $json->getString('bat.d.2'));
    }

    /**
     * @test
     * @covers ::getArray
     */
    public function it_returns_nested_array_values()
    {
        $json = Json::of('{"foo":"hello","bar":false,"baz":1,"bat":{"a":2,"b":"two","c":true,"d":{"1":"1","2":"2"}}}');

        $this->assertNull($json->getArray('nope'));
        $this->assertEquals([], $json->getArray('nope', []));
        $this->assertEquals([1 => '1', 2 => '2'], $json->getArray('bat.d'));
    }

    /**
     * @test
     * @covers ::__toString
     * @covers ::toNativeString
     */
    public function it_can_be_converted_to_a_native_string()
    {
        $json = Json::of('{"foo":"hello","bar":false,"baz":1,"bat":{"a":2,"b":"two","c":true}}');

        $this->assertEquals(
            '{"foo":"hello","bar":false,"baz":1,"bat":{"a":2,"b":"two","c":true}}',
            $json->__toString()
        );

        $this->assertEquals(
            '{"foo":"hello","bar":false,"baz":1,"bat":{"a":2,"b":"two","c":true}}',
            $json->toNativeString()
        );
    }
}

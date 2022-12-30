<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Account;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Cryptography\Hash
 */
class HashTest extends TestCase
{
    /**
     * @test
     * @covers ::make
     */
    public function it_can_be_instantiated_statically()
    {
        $hash = Hash::make('abcd');

        $this->assertInstanceOf(Hash::class, $hash);
        $this->assertNotEquals('abcd', $hash->toNativeString());
    }

    /**
     * @test
     * @covers ::make
     */
    public function it_can_be_instantiated_statically_and_clone_a_hash()
    {
        $hashA = Hash::make('abcd');
        $hashB = Hash::make($hashA);

        $this->assertInstanceOf(Hash::class, $hashB);
        $this->assertNotEquals('abcd', $hashB->toNativeString());
        $this->assertNotEquals(spl_object_id($hashA), spl_object_id($hashB));
    }

    /**
     * @test
     * @covers ::wrap
     */
    public function it_can_wrap_an_existing_value()
    {
        $hash = Hash::wrap('abcd');

        $this->assertInstanceOf(Hash::class, $hash);
        $this->assertEquals('abcd', $hash->toNativeString());
    }

    /**
     * @test
     * @covers ::wrap
     */
    public function it_can_wrap_an_existing_value_and_clone_a_hash()
    {
        $hashA = Hash::wrap('abcd');
        $hashB = Hash::wrap($hashA);

        $this->assertInstanceOf(Hash::class, $hashB);
        $this->assertEquals('abcd', $hashB->toNativeString());
        $this->assertNotEquals(spl_object_id($hashA), spl_object_id($hashB));
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $hash = Hash::make('abcd');
        $buffer = XDR::fresh()->write($hash);

        $this->assertEquals('iNQmb9TmM40TuEX88olXnSCciXgjuSF9o+Fhk28DFYk=', $buffer->toBase64());
        $this->assertEquals($hash->toHex(), $buffer->toBase16());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $hash = XDR::fromBase64('iNQmb9TmM40TuEX88olXnSCciXgjuSF9o+Fhk28DFYk=')
            ->read(Hash::class);

        $this->assertInstanceOf(Hash::class, $hash);
        $this->assertEquals('88d4266fd4e6338d13b845fcf289579d209c897823b9217da3e161936f031589', $hash->toHex());
    }

    /**
     * @test
     * @covers ::sha256
     */
    public function it_can_be_instantiated_via_the_sha256_static_method()
    {
        $hash = Hash::sha256('abcd');

        $this->assertInstanceOf(Hash::class, $hash);
        $this->assertNotEquals('abcd', $hash->toNativeString());
    }

    /**
     * @test
     * @covers ::toHex
     */
    public function it_can_be_converted_to_base16()
    {
        $this->assertEquals(
            '88d4266fd4e6338d13b845fcf289579d209c897823b9217da3e161936f031589',
            Hash::sha256('abcd')->toHex()
        );
    }

    /**
     * @test
     * @covers ::fromHex
     */
    public function it_can_be_created_from_a_hex_string()
    {
        $this->assertInstanceOf(Hash::class, Hash::fromHex('88d4266fd4e6338d13b845fcf289579d209c897823b9217da3e161936f031589'));
    }

    /**
     * @test
     * @covers ::fromHex
     */
    public function the_from_hex_method_accepts_a_hash()
    {
        $hash = Hash::sha256('abcd');
        $copy = Hash::fromHex($hash);

        $this->assertNotEquals(spl_object_id($hash), spl_object_id($copy));
    }

    /**
     * @test
     * @covers ::fromHex
     */
    public function it_restores_missing_leading_zeros_if_needed()
    {
        $hash = Hash::fromHex('007d13d6068a14bc951c085efcccbd61d5cd1b37f163a3f8269b46209eee49e1');
        $this->assertEquals(Hash::make('d62c8226')->toHex(), $hash->toHex());
    }

    /**
     * @test
     * @covers ::__toString
     * @covers ::toNativeString
     */
    public function it_can_be_converted_to_a_native_string()
    {
        $hash = Hash::sha256('abcd');
        $this->assertEquals((string)$hash, $hash->toNativeString());
    }

    /**
     * @test
     * @covers ::withValue
     */
    public function it_accepts_a_value()
    {
        $hash = (new Hash())->withValue('abcd');
        $this->assertEquals('abcd', $hash->toNativeString());
    }

    /**
     * @test
     * @covers ::withValue
     */
    public function it_does_not_accept_a_value_longer_than_32_bytes()
    {
        $this->expectException(InvalidArgumentException::class);
        (new Hash())->withValue(str_repeat('A', 33));
    }
}

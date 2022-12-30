<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\SCP\EncryptedBody;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\EncryptedBody
 */
class EncryptedBodyTest extends TestCase
{
    /**
     * @test
     * @covers ::of
     */
    public function it_can_be_created_via_static_helper()
    {
        $encryptedBody = EncryptedBody::of('example');
        $this->assertInstanceOf(EncryptedBody::class, $encryptedBody);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $encryptedBody = EncryptedBody::of('example');
        $buffer = XDR::fresh()->write($encryptedBody);

        $this->assertEquals('AAAAB2V4YW1wbGUA', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_value_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new EncryptedBody());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $encryptedBody = XDR::fromBase64('AAAAB2V4YW1wbGUA')->read(EncryptedBody::class);

        $this->assertInstanceOf(EncryptedBody::class, $encryptedBody);
        $this->assertEquals('example', $encryptedBody->toNativeString());
    }

    /**
     * @test
     * @covers ::withValue
     * @covers ::getValue
     */
    public function it_accepts_a_value()
    {
        $encryptedBody = (new EncryptedBody())->withValue('example');

        $this->assertInstanceOf(EncryptedBody::class, $encryptedBody);
        $this->assertEquals('example', $encryptedBody->getValue());
    }

    /**
     * @test
     * @covers ::withValue
     */
    public function it_does_not_accepts_a_value_longer_than_max_byte_length()
    {
        $this->expectException(InvalidArgumentException::class);
        $value = str_repeat('A', EncryptedBody::MAX_BYTE_LENGTH + 1);
        (new EncryptedBody())->withValue($value);
    }

    /**
     * @test
     * @covers ::toNativeString
     */
    public function it_can_be_converted_to_a_string()
    {
        $encryptedBody = (new EncryptedBody())->withValue('example');
        $this->assertEquals('example', $encryptedBody->toNativeString());
    }
}

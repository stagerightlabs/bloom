<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\SCP\AuthenticatedMessage;
use StageRightLabs\Bloom\SCP\AuthenticatedMessageV0;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\AuthenticatedMessage
 */
class AuthenticatedMessageTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(XDR::INT, AuthenticatedMessage::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            0 => AuthenticatedMessageV0::class,
        ];

        $this->assertEquals($expected, AuthenticatedMessage::arms());
    }

    /**
     * @test
     * @covers ::wrapAuthenticatedMessageV0
     * @covers ::unwrap
     */
    public function it_can_wrap_an_authenticated_message_v0()
    {
        $authenticatedMessage = AuthenticatedMessage::wrapAuthenticatedMessageV0(new AuthenticatedMessageV0());

        $this->assertInstanceOf(AuthenticatedMessage::class, $authenticatedMessage);
        $this->assertInstanceOf(AuthenticatedMessageV0::class, $authenticatedMessage->unwrap());
    }

    /**
     * @test
     * @covers ::unwrap
     */
    public function it_unwraps_to_null_if_no_value_is_set()
    {
        $this->assertNull((new AuthenticatedMessage())->unwrap());
    }
}

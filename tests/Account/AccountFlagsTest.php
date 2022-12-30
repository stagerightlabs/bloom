<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Account;

use StageRightLabs\Bloom\Account\AccountFlags;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Account\AccountFlags
 */
class AccountFlagsTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            1 => AccountFlags::AUTH_REQUIRED_FLAG,
            2 => AccountFlags::AUTH_REVOCABLE_FLAG,
            4 => AccountFlags::AUTH_IMMUTABLE_FLAG,
            8 => AccountFlags::AUTH_CLAWBACK_ENABLED_FLAG,
        ];
        $accountFlags = new AccountFlags();

        $this->assertEquals($expected, $accountFlags->getOptions());
    }

    /**
     * @test
     * @covers ::toNativeInt
     */
    public function it_returns_an_integer_representation_of_the_selected_flag()
    {
        $this->assertEquals(1, AccountFlags::authRequired()->toNativeInt());
        $this->assertEquals(2, AccountFlags::authRevokable()->toNativeInt());
        $this->assertEquals(4, AccountFlags::authImmutable()->toNativeInt());
        $this->assertEquals(8, AccountFlags::authClawbackEnabled()->toNativeInt());
    }

    /**
     * @test
     * @covers ::authRequired
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_auth_required_flag()
    {
        $accountFlags = AccountFlags::authRequired();

        $this->assertInstanceOf(AccountFlags::class, $accountFlags);
        $this->assertEquals(AccountFlags::AUTH_REQUIRED_FLAG, $accountFlags->getType());
    }

    /**
     * @test
     * @covers ::authRevokable
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_auth_revokable_flag()
    {
        $accountFlags = AccountFlags::authRevokable();

        $this->assertInstanceOf(AccountFlags::class, $accountFlags);
        $this->assertEquals(AccountFlags::AUTH_REVOCABLE_FLAG, $accountFlags->getType());
    }

    /**
     * @test
     * @covers ::authImmutable
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_auth_immutable_flag()
    {
        $accountFlags = AccountFlags::authImmutable();

        $this->assertInstanceOf(AccountFlags::class, $accountFlags);
        $this->assertEquals(AccountFlags::AUTH_IMMUTABLE_FLAG, $accountFlags->getType());
    }

    /**
     * @test
     * @covers ::authClawbackEnabled
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_clawback_enabled_flag()
    {
        $accountFlags = AccountFlags::authClawbackEnabled();

        $this->assertInstanceOf(AccountFlags::class, $accountFlags);
        $this->assertEquals(AccountFlags::AUTH_CLAWBACK_ENABLED_FLAG, $accountFlags->getType());
    }
}

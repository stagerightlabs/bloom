<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Horizon\SetOptionsOperationResource;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\SetOptionsOperationResource
 */
class SetOptionsOperationResourceTest extends TestCase
{
    /**
     * @test
     * @covers ::getSignerKey
     */
    public function it_returns_the_signer_key()
    {
        $operation = SetOptionsOperationResource::wrap(
            Response::fake('set_options_operation')->getBody()
        );

        $this->assertEquals(
            'GABMKJM6I25XI4K7U6XWMULOUQIQ27BCTMLS6BYYSOWKTBUXVRJSXHYQ',
            $operation->getSignerKey()
        );
    }

    /**
     * @test
     * @covers ::getSignerWeight
     */
    public function it_returns_the_signer_weight()
    {
        $operation = SetOptionsOperationResource::wrap(
            Response::fake('set_options_operation')->getBody()
        );

        $this->assertEquals(20, $operation->getSignerWeight());
    }

    /**
     * @test
     * @covers ::getMasterKeyWeight
     */
    public function it_returns_the_master_key_weight()
    {
        $operation = SetOptionsOperationResource::wrap(
            Response::fake('set_options_operation')->getBody()
        );

        $this->assertEquals(255, $operation->getMasterKeyWeight());
    }

    /**
     * @test
     * @covers ::getLowThreshold
     */
    public function it_returns_the_low_threshold()
    {
        $operation = SetOptionsOperationResource::wrap(
            Response::fake('set_options_operation')->getBody()
        );

        $this->assertEquals(1, $operation->getLowThreshold());
    }

    /**
     * @test
     * @covers ::getMediumThreshold
     */
    public function it_returns_the_medium_threshold()
    {
        $operation = SetOptionsOperationResource::wrap(
            Response::fake('set_options_operation')->getBody()
        );

        $this->assertEquals(2, $operation->getMediumThreshold());
    }

    /**
     * @test
     * @covers ::getHighThreshold
     */
    public function it_returns_the_high_threshold()
    {
        $operation = SetOptionsOperationResource::wrap(
            Response::fake('set_options_operation')->getBody()
        );

        $this->assertEquals(3, $operation->getHighThreshold());
    }

    /**
     * @test
     * @covers ::getHomeDomain
     */
    public function it_returns_the_home_domain()
    {
        $operation = SetOptionsOperationResource::wrap(
            Response::fake('set_options_operation')->getBody()
        );

        $this->assertEquals('www.stellar.org', $operation->getHomeDomain());
    }

    /**
     * @test
     * @covers ::getSetFlags
     */
    public function it_returns_the_set_flags_as_integers()
    {
        $operation = SetOptionsOperationResource::wrap(
            Response::fake('set_options_operation')->getBody()
        );

        $this->assertEquals([1, 2, 4], $operation->getSetFlags());
    }

    /**
     * @test
     * @covers ::getSetFlagsStrings
     */
    public function it_returns_the_set_flags_as_strings()
    {
        $operation = SetOptionsOperationResource::wrap(
            Response::fake('set_options_operation')->getBody()
        );
        $expected = [
            'AUTH_REQUIRED_FLAG',
            'AUTH_REVOCABLE_FLAG',
            'AUTH_IMMUTABLE_FLAG'
        ];

        $this->assertEquals($expected, $operation->getSetFlagsStrings());
    }

    /**
     * @test
     * @covers ::getClearFlags
     */
    public function it_returns_the_clear_flags_as_integers()
    {
        $operation = SetOptionsOperationResource::wrap(
            Response::fake('set_options_operation')->getBody()
        );

        $this->assertEquals([1, 2, 4], $operation->getClearFlags());
    }

    /**
     * @test
     * @covers ::getClearFlagsStrings
     */
    public function it_returns_the_clear_flags_as_strings()
    {
        $operation = SetOptionsOperationResource::wrap(
            Response::fake('set_options_operation')->getBody()
        );
        $expected = [
            'AUTH_REQUIRED_FLAG',
            'AUTH_REVOCABLE_FLAG',
            'AUTH_IMMUTABLE_FLAG'
        ];

        $this->assertEquals($expected, $operation->getClearFlagsStrings());
    }
}

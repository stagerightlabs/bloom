<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Operation\PathPaymentStrictSendResult;
use StageRightLabs\Bloom\Operation\PathPaymentStrictSendResultCode;
use StageRightLabs\Bloom\Operation\PathPaymentStrictSendResultSuccess;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\PathPaymentStrictSendResult
 */
class PathPaymentStrictSendResultTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(PathPaymentStrictSendResultCode::class, PathPaymentStrictSendResult::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_SUCCESS            => PathPaymentStrictSendResultSuccess::class,
            PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_MALFORMED          => XDR::VOID,
            PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_UNDERFUNDED        => XDR::VOID,
            PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_SRC_NO_TRUST       => XDR::VOID,
            PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_SRC_NOT_AUTHORIZED => XDR::VOID,
            PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_NO_DESTINATION     => XDR::VOID,
            PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_NO_TRUST           => XDR::VOID,
            PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_NOT_AUTHORIZED     => XDR::VOID,
            PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_LINE_FULL          => XDR::VOID,
            PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_NO_ISSUER          => Asset::class,
            PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_TOO_FEW_OFFERS     => XDR::VOID,
            PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_OFFER_CROSS_SELF   => XDR::VOID,
            PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_UNDER_DESTMIN      => XDR::VOID,
        ];

        $this->assertEquals($expected, PathPaymentStrictSendResult::arms());
    }

    /**
     * @test
     * @covers ::wrapPathPaymentStrictSendResultSuccess
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_path_payment_strict_send_result_success()
    {
        $pathPaymentStrictSendResult = PathPaymentStrictSendResult::wrapPathPaymentStrictSendResultSuccess(
            new PathPaymentStrictSendResultSuccess()
        );

        $this->assertInstanceOf(PathPaymentStrictSendResult::class, $pathPaymentStrictSendResult);
        $this->assertInstanceOf(PathPaymentStrictSendResultSuccess::class, $pathPaymentStrictSendResult->unwrap());
        $this->assertEquals(PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_SUCCESS, $pathPaymentStrictSendResult->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_null_if_no_value_is_set()
    {
        $this->assertNull((new PathPaymentStrictSendResult())->getType());
    }

    /**
     * @test
     * @covers ::simulate
     */
    public function it_can_simulate_an_error_result()
    {
        $pathPaymentStrictSendResult = PathPaymentStrictSendResult::simulate(PathPaymentStrictSendResultCode::malformed());

        $this->assertInstanceOf(PathPaymentStrictSendResult::class, $pathPaymentStrictSendResult);
        $this->assertEquals(PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_MALFORMED, $pathPaymentStrictSendResult->getType());
    }

    /**
     * @test
     * @covers ::wrapAsset
     * @covers ::unwrap
     */
    public function it_can_wrap_an_asset()
    {
        $pathPaymentStrictSendResult = PathPaymentStrictSendResult::wrapAsset(
            new Asset()
        );

        $this->assertInstanceOf(PathPaymentStrictSendResult::class, $pathPaymentStrictSendResult);
        $this->assertInstanceOf(Asset::class, $pathPaymentStrictSendResult->unwrap());
    }

    /**
     * @test
     * @covers ::wasSuccessful
     * @covers ::wasNotSuccessful
     */
    public function it_knows_if_it_was_successful()
    {
        $pathPaymentStrictSendResultA = PathPaymentStrictSendResult::wrapPathPaymentStrictSendResultSuccess(
            new PathPaymentStrictSendResultSuccess()
        );
        $pathPaymentStrictSendResultB = PathPaymentStrictSendResult::wrapAsset(
            new Asset()
        );

        $this->assertTrue($pathPaymentStrictSendResultA->wasSuccessful());
        $this->assertFalse($pathPaymentStrictSendResultA->wasNotSuccessful());
        $this->assertTrue($pathPaymentStrictSendResultB->wasNotSuccessful());
        $this->assertFalse($pathPaymentStrictSendResultB->wasSuccessful());
    }

    /**
     * @test
     * @covers ::getErrorMessage
     * @covers ::getErrorCode
     */
    public function it_returns_an_error_message_and_code()
    {
        $pathPaymentStrictSendResult = PathPaymentStrictSendResult::simulate(PathPaymentStrictSendResultCode::malformed());

        $this->assertNotEmpty($pathPaymentStrictSendResult->getErrorMessage());
        $this->assertEquals('path_payment_strict_send_malformed', $pathPaymentStrictSendResult->getErrorCode());
        $this->assertNull((new PathPaymentStrictSendResult())->getErrorMessage());
        $this->assertNull((new PathPaymentStrictSendResult())->getErrorCode());
    }
}

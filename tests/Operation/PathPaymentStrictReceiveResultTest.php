<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Operation\PathPaymentStrictReceiveResult;
use StageRightLabs\Bloom\Operation\PathPaymentStrictReceiveResultCode;
use StageRightLabs\Bloom\Operation\PathPaymentStrictReceiveResultSuccess;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\PathPaymentStrictReceiveResult
 */
class PathPaymentStrictReceiveResultTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(PathPaymentStrictReceiveResultCode::class, PathPaymentStrictReceiveResult::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_SUCCESS            => PathPaymentStrictReceiveResultSuccess::class,
            PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_MALFORMED          => XDR::VOID,
            PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_UNDERFUNDED        => XDR::VOID,
            PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_SRC_NO_TRUST       => XDR::VOID,
            PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_SRC_NOT_AUTHORIZED => XDR::VOID,
            PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_NO_DESTINATION     => XDR::VOID,
            PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_NO_TRUST           => XDR::VOID,
            PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_NOT_AUTHORIZED     => XDR::VOID,
            PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_LINE_FULL          => XDR::VOID,
            PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_NO_ISSUER          => Asset::class,
            PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_TOO_FEW_OFFERS     => XDR::VOID,
            PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_OFFER_CROSSES_SELF => XDR::VOID,
            PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_OVER_SENDMAX       => XDR::VOID,
        ];

        $this->assertEquals($expected, PathPaymentStrictReceiveResult::arms());
    }

    /**
     * @test
     * @covers ::wrapPathPaymentStrictReceiveResultSuccess
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_path_payment_strict_receive_result_success()
    {
        $pathPaymentStrictReceiveResultSuccess = new PathPaymentStrictReceiveResultSuccess();
        $pathPaymentStrictReceiveResult = (new PathPaymentStrictReceiveResult())
            ->wrapPathPaymentStrictReceiveResultSuccess($pathPaymentStrictReceiveResultSuccess);

        $this->assertInstanceOf(PathPaymentStrictReceiveResult::class, $pathPaymentStrictReceiveResult);
        $this->assertInstanceOf(PathPaymentStrictReceiveResultSuccess::class, $pathPaymentStrictReceiveResult->unwrap());
        $this->assertEquals(PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_SUCCESS, $pathPaymentStrictReceiveResult->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_null_if_no_value_is_set()
    {
        $this->assertNull((new PathPaymentStrictReceiveResult())->getType());
    }

    /**
     * @test
     * @covers ::simulate
     */
    public function it_can_simulate_an_error_result()
    {
        $pathPaymentStrictReceiveResult = PathPaymentStrictReceiveResult::simulate(PathPaymentStrictReceiveResultCode::malformed());

        $this->assertInstanceOf(PathPaymentStrictReceiveResult::class, $pathPaymentStrictReceiveResult);
        $this->assertEquals(PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_MALFORMED, $pathPaymentStrictReceiveResult->getType());
    }

    /**
     * @test
     * @covers ::wrapNoIssuerAsset
     */
    public function it_can_wrap_a_no_issuer_asset()
    {
        $asset = Asset::wrapAlphaNum4(AlphaNum4::of('ABCD', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ'));
        $pathPaymentStrictReceiveResult = PathPaymentStrictReceiveResult::wrapNoIssuerAsset($asset);

        $this->assertInstanceOf(Asset::class, $pathPaymentStrictReceiveResult->unwrap());
    }

    /**
     * @test
     * @covers ::wasSuccessful
     * @covers ::wasNotSuccessful
     */
    public function it_knows_if_it_was_successful()
    {
        $pathPaymentStrictReceiveResultSuccess = new PathPaymentStrictReceiveResultSuccess();
        $pathPaymentStrictReceiveResultA = (new PathPaymentStrictReceiveResult())
            ->wrapPathPaymentStrictReceiveResultSuccess($pathPaymentStrictReceiveResultSuccess);
        $asset = Asset::wrapAlphaNum4(AlphaNum4::of('ABCD', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ'));
        $pathPaymentStrictReceiveResultB = PathPaymentStrictReceiveResult::wrapNoIssuerAsset($asset);

        $this->assertTrue($pathPaymentStrictReceiveResultA->wasSuccessful());
        $this->assertFalse($pathPaymentStrictReceiveResultA->wasNotSuccessful());
        $this->assertTrue($pathPaymentStrictReceiveResultB->wasNotSuccessful());
        $this->assertFalse($pathPaymentStrictReceiveResultB->wasSuccessful());
    }

    /**
     * @test
     * @covers ::getErrorMessage
     * @covers ::getErrorCode
     */
    public function it_returns_an_error_message_and_code()
    {
        $pathPaymentStrictReceiveResult = PathPaymentStrictReceiveResult::simulate(PathPaymentStrictReceiveResultCode::malformed());

        $this->assertNotEmpty($pathPaymentStrictReceiveResult->getErrorMessage());
        $this->assertEquals('path_payment_strict_receive_malformed', $pathPaymentStrictReceiveResult->getErrorCode());
        $this->assertNull((new PathPaymentStrictReceiveResult())->getErrorMessage());
        $this->assertNull((new PathPaymentStrictReceiveResult())->getErrorCode());
    }
}

<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Envelope;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Envelope\EnvelopeType;
use StageRightLabs\Bloom\Envelope\HashIdPreimage;
use StageRightLabs\Bloom\Envelope\HashIdPreimageOperationId;
use StageRightLabs\Bloom\Envelope\HashIdPreimageRevokeId;
use StageRightLabs\Bloom\LiquidityPool\PoolId;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\SequenceNumber;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Envelope\HashIdPreimage
 */
class HashIdPreimageTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(EnvelopeType::class, HashIdPreimage::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            EnvelopeType::ENVELOPE_OP_ID             => HashIdPreimageOperationId::class,
            EnvelopeType::ENVELOPE_POOL_REVOKE_OP_ID => HashIdPreimageRevokeId::class,
        ];

        $this->assertEquals($expected, HashIdPreimage::arms());
    }

    /**
     * @test
     * @covers ::wrapHashidIdPreimageOperationId
     * @covers ::unwrap
     */
    public function it_can_wrap_a_hashid_preimage_operation_id()
    {
        $sourceAccount = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $hashIdPreimageOperationId = (new HashIdPreimageOperationId())
            ->withSourceAccount($sourceAccount)
            ->withSequenceNumber(SequenceNumber::of(1))
            ->withOperationNumber(UInt32::of(2));
        $hashIdPreimage = HashIdPreimage::wrapHashidIdPreimageOperationId($hashIdPreimageOperationId);

        $this->assertInstanceOf(HashIdPreimage::class, $hashIdPreimage);
        $this->assertInstanceOf(HashIdPreimageOperationId::class, $hashIdPreimage->unwrap());
    }

    /**
     * @test
     * @covers ::wrapHashIdPreimageRevokeId
     */
    public function it_can_wrap_a_hash_id_preimage_revoke_id()
    {
        $sourceAccount = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $hashIdPreimageRevokeId = (new HashIdPreimageRevokeId())
            ->withSourceAccount($sourceAccount)
            ->withSequenceNumber(SequenceNumber::of(1))
            ->withOperationNumber(UInt32::of(2))
            ->withLiquidityPoolId(PoolId::make('3'))
            ->withAsset($asset);
        $hashIdPreimage = HashIdPreimage::wrapHashIdPreimageRevokeId($hashIdPreimageRevokeId);

        $this->assertInstanceOf(HashIdPreimage::class, $hashIdPreimage);
        $this->assertInstanceOf(HashIdPreimageRevokeId::class, $hashIdPreimage->unwrap());
    }
}

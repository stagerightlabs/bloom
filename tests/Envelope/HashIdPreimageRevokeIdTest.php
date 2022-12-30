<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Envelope;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Envelope\HashIdPreimageRevokeId;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\LiquidityPool\PoolId;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\SequenceNumber;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Envelope\HashIdPreimageRevokeId
 */
class HashIdPreimageRevokeIdTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
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
        $buffer = XDR::fresh()->write($hashIdPreimageRevokeId);

        $this->assertEquals('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAAAAAAEAAAACTgdAhWK+24tgzgXB3s/jrRa3IjCWfeAfZAt+Rym0n84AAAABQUJDRAAAAABVwB+HR928hqbQy42KJwdUutlnScF73mgSoHnf3ql6Ug==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_source_account_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $hashIdPreimageRevokeId = (new HashIdPreimageRevokeId())
            ->withSequenceNumber(SequenceNumber::of(1))
            ->withOperationNumber(UInt32::of(2))
            ->withLiquidityPoolId(PoolId::make('3'))
            ->withAsset($asset);
        XDR::fresh()->write($hashIdPreimageRevokeId);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_sequence_number_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $sourceAccount = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $hashIdPreimageRevokeId = (new HashIdPreimageRevokeId())
            ->withSourceAccount($sourceAccount)
            ->withOperationNumber(UInt32::of(2))
            ->withLiquidityPoolId(PoolId::make('3'))
            ->withAsset($asset);
        XDR::fresh()->write($hashIdPreimageRevokeId);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_operation_number_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $sourceAccount = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $hashIdPreimageRevokeId = (new HashIdPreimageRevokeId())
            ->withSourceAccount($sourceAccount)
            ->withSequenceNumber(SequenceNumber::of(1))
            ->withLiquidityPoolId(PoolId::make('3'))
            ->withAsset($asset);
        XDR::fresh()->write($hashIdPreimageRevokeId);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_liquidity_pool_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $sourceAccount = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $hashIdPreimageRevokeId = (new HashIdPreimageRevokeId())
            ->withSourceAccount($sourceAccount)
            ->withSequenceNumber(SequenceNumber::of(1))
            ->withOperationNumber(UInt32::of(2))
            ->withAsset($asset);
        XDR::fresh()->write($hashIdPreimageRevokeId);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_asset_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $sourceAccount = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $hashIdPreimageRevokeId = (new HashIdPreimageRevokeId())
            ->withSourceAccount($sourceAccount)
            ->withSequenceNumber(SequenceNumber::of(1))
            ->withOperationNumber(UInt32::of(2))
            ->withLiquidityPoolId(PoolId::make('3'));
        XDR::fresh()->write($hashIdPreimageRevokeId);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $hashIdPreimageRevokeId = XDR::fromBase64('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAAAAAAEAAAACTgdAhWK+24tgzgXB3s/jrRa3IjCWfeAfZAt+Rym0n84AAAABQUJDRAAAAABVwB+HR928hqbQy42KJwdUutlnScF73mgSoHnf3ql6Ug==')
            ->read(HashIdPreimageRevokeId::class);

        $this->assertInstanceOf(HashIdPreimageRevokeId::class, $hashIdPreimageRevokeId);
        $this->assertInstanceOf(AccountId::class, $hashIdPreimageRevokeId->getSourceAccount());
        $this->assertInstanceOf(SequenceNumber::class, $hashIdPreimageRevokeId->getSequenceNumber());
        $this->assertInstanceOf(UInt32::class, $hashIdPreimageRevokeId->getOperationNumber());
        $this->assertInstanceOf(PoolId::class, $hashIdPreimageRevokeId->getLiquidityPoolId());
        $this->assertInstanceOf(Asset::class, $hashIdPreimageRevokeId->getAsset());
    }

    /**
     * @test
     * @covers ::withSourceAccount
     * @covers ::getSourceAccount
     */
    public function it_accepts_a_source_account()
    {
        $sourceAccount = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $hashIdPreimageRevokeId = (new HashIdPreimageRevokeId())
            ->withSourceAccount($sourceAccount);

        $this->assertInstanceOf(HashIdPreimageRevokeId::class, $hashIdPreimageRevokeId);
        $this->assertInstanceOf(AccountId::class, $hashIdPreimageRevokeId->getSourceAccount());
    }

    /**
     * @test
     * @covers ::withSequenceNumber
     * @covers ::getSequenceNumber
     */
    public function it_accepts_a_sequence_number()
    {
        $hashIdPreimageRevokeId = (new HashIdPreimageRevokeId())
            ->withSequenceNumber(SequenceNumber::of(1));

        $this->assertInstanceOf(HashIdPreimageRevokeId::class, $hashIdPreimageRevokeId);
        $this->assertInstanceOf(SequenceNumber::class, $hashIdPreimageRevokeId->getSequenceNumber());
    }

    /**
     * @test
     * @covers ::withOperationNumber
     * @covers ::getOperationNumber
     */
    public function it_accepts_an_operation_number()
    {
        $hashIdPreimageRevokeId = (new HashIdPreimageRevokeId())
            ->withOperationNumber(UInt32::of(2));

        $this->assertInstanceOf(HashIdPreimageRevokeId::class, $hashIdPreimageRevokeId);
        $this->assertInstanceOf(UInt32::class, $hashIdPreimageRevokeId->getOperationNumber());
    }

    /**
     * @test
     * @covers ::withLiquidityPooLId
     * @covers ::getLiquidityPoolId
     */
    public function it_accepts_a_liquidity_pool_id()
    {
        $hashIdPreimageRevokeId = (new HashIdPreimageRevokeId())
            ->withLiquidityPoolId(PoolId::make('3'));

        $this->assertInstanceOf(HashIdPreimageRevokeId::class, $hashIdPreimageRevokeId);
        $this->assertInstanceOf(PoolId::class, $hashIdPreimageRevokeId->getLiquidityPoolId());
    }

    /**
     * @test
     * @covers ::withAsset
     * @covers ::getAsset
     */
    public function it_accepts_an_asset()
    {
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $hashIdPreimageRevokeId = (new HashIdPreimageRevokeId())
            ->withAsset($asset);

        $this->assertInstanceOf(HashIdPreimageRevokeId::class, $hashIdPreimageRevokeId);
        $this->assertInstanceOf(Asset::class, $hashIdPreimageRevokeId->getAsset());
    }
}

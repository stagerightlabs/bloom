<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\ClaimableBalance\ClaimantList;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Operation\CreateClaimableBalanceOp;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\CreateClaimableBalanceOp
 */
class CreateClaimableBalanceOpTest extends TestCase
{
    /**
     * @test
     * @covers ::operation
     */
    public function it_can_create_an_operation()
    {
        $operation = CreateClaimableBalanceOp::operation(
            asset: 'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            amount: '10',
            claimants: ['GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS'],
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(CreateClaimableBalanceOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::isReady
     */
    public function it_knows_when_it_is_ready()
    {
        $createClaimableBalanceOp = new CreateClaimableBalanceOp();
        $this->assertFalse($createClaimableBalanceOp->isReady());

        $createClaimableBalanceOp = $createClaimableBalanceOp->withAsset('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $this->assertFalse($createClaimableBalanceOp->isReady());

        $createClaimableBalanceOp = $createClaimableBalanceOp->withAmount('10');
        $this->assertFalse($createClaimableBalanceOp->isReady());

        $createClaimableBalanceOp = $createClaimableBalanceOp->withClaimants(['GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS']);
        $this->assertTrue($createClaimableBalanceOp->isReady());
    }

    /**
     * @test
     * @covers ::getThreshold
     */
    public function it_has_a_threshold_category()
    {
        $this->assertEquals(Thresholds::CATEGORY_MEDIUM, (new CreateClaimableBalanceOp())->getThreshold());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $createClaimableBalanceOp = (new CreateClaimableBalanceOp())
            ->withAsset($asset)
            ->withAmount(Int64::of(1));
        $buffer = XDR::fresh()->write($createClaimableBalanceOp);

        $this->assertEquals('AAAAAUFCQ0QAAAAAVcAfh0fdvIam0MuNiicHVLrZZ0nBe95oEqB5396pelIAAAAAAAAAAQAAAAA=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_asset_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $createClaimableBalanceOp = (new CreateClaimableBalanceOp())
            ->withAmount(Int64::of(1));
        XDR::fresh()->write($createClaimableBalanceOp);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_amount_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $createClaimableBalanceOp = (new CreateClaimableBalanceOp())
            ->withAsset($asset);
        XDR::fresh()->write($createClaimableBalanceOp);
        $this->markTestIncomplete();
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $createClaimableBalanceOp = XDR::fromBase64('AAAAAUFCQ0QAAAAAVcAfh0fdvIam0MuNiicHVLrZZ0nBe95oEqB5396pelIAAAAAAAAAAQAAAAA=')
            ->read(CreateClaimableBalanceOp::class);

        $this->assertInstanceOf(CreateClaimableBalanceOp::class, $createClaimableBalanceOp);
        $this->assertInstanceOf(Asset::class, $createClaimableBalanceOp->getAsset());
        $this->assertInstanceOf(Int64::class, $createClaimableBalanceOp->getAmount());
        $this->assertInstanceOf(ClaimantList::class, $createClaimableBalanceOp->getClaimants());
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
        $createClaimableBalanceOp = (new CreateClaimableBalanceOp())
            ->withAsset($asset);

        $this->assertInstanceOf(CreateClaimableBalanceOp::class, $createClaimableBalanceOp);
        $this->assertInstanceOf(Asset::class, $createClaimableBalanceOp->getAsset());
    }

    /**
     * @test
     * @covers ::withAsset
     * @covers ::getAsset
     */
    public function it_accepts_an_asset_string()
    {
        $createClaimableBalanceOp = (new CreateClaimableBalanceOp())
            ->withAsset('ABCD:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');

        $this->assertInstanceOf(CreateClaimableBalanceOp::class, $createClaimableBalanceOp);
        $this->assertInstanceOf(Asset::class, $createClaimableBalanceOp->getAsset());
    }

    /**
     * @test
     * @covers ::withAmount
     * @covers ::getAmount
     */
    public function it_accepts_an_amount()
    {
        $createClaimableBalanceOp = (new CreateClaimableBalanceOp())
            ->withAmount(Int64::of(1));

        $this->assertInstanceOf(CreateClaimableBalanceOp::class, $createClaimableBalanceOp);
        $this->assertInstanceOf(Int64::class, $createClaimableBalanceOp->getAmount());
    }

    /**
     * @test
     * @covers ::withClaimants
     * @covers ::getClaimants
     */
    public function it_accepts_a_list_of_claimants()
    {
        $createClaimableBalanceOp = (new CreateClaimableBalanceOp())
            ->withClaimants(ClaimantList::empty());

        $this->assertInstanceOf(CreateClaimableBalanceOp::class, $createClaimableBalanceOp);
        $this->assertInstanceOf(ClaimantList::class, $createClaimableBalanceOp->getClaimants());
    }
}

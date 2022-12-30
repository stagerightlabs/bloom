<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\ClaimableBalance\ClaimableBalanceId;
use StageRightLabs\Bloom\ClaimableBalance\ClaimantList;
use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\ClaimableBalanceEntry;
use StageRightLabs\Bloom\Ledger\ClaimableBalanceEntryExt;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\ClaimableBalanceEntry
 */
class ClaimableBalanceEntryTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $claimableBalanceId = (new ClaimableBalanceId())->wrapHash(Hash::make('1'));
        $claimants = ClaimantList::empty();
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $amount = Int64::of(10000000);
        $claimableBalanceEntry = (new ClaimableBalanceEntry())
            ->withClaimableBalanceId($claimableBalanceId)
            ->withClaimants($claimants)
            ->withAsset($asset)
            ->withAmount($amount);
        $buffer = XDR::fresh()->write($claimableBalanceEntry);

        $this->assertEquals(
            'AAAAAGuGsnP/NPzhnWuATv9aP1dHraTqoi8dScAeUt23h1tLAAAAAAAAAAFBQkNEAAAAAFXAH4dH3byGptDLjYonB1S62WdJwXveaBKged/eqXpSAAAAAACYloAAAAAA',
            $buffer->toBase64()
        );
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_claimable_balance_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $claimants = ClaimantList::empty();
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $amount = Int64::of(10000000);
        $claimableBalanceEntry = (new ClaimableBalanceEntry())
            ->withClaimants($claimants)
            ->withAsset($asset)
            ->withAmount($amount);
        XDR::fresh()->write($claimableBalanceEntry);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_list_of_claimants_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $claimableBalanceId = (new ClaimableBalanceId())->wrapHash(Hash::make('1'));
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $amount = Int64::of(10000000);
        $claimableBalanceEntry = (new ClaimableBalanceEntry())
            ->withClaimableBalanceId($claimableBalanceId)
            ->withAsset($asset)
            ->withAmount($amount);
        XDR::fresh()->write($claimableBalanceEntry);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_asset_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $claimableBalanceId = (new ClaimableBalanceId())->wrapHash(Hash::make('1'));
        $claimants = ClaimantList::empty();
        $amount = Int64::of(10000000);
        $claimableBalanceEntry = (new ClaimableBalanceEntry())
            ->withClaimableBalanceId($claimableBalanceId)
            ->withClaimants($claimants)
            ->withAmount($amount);
        XDR::fresh()->write($claimableBalanceEntry);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_amount_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $claimableBalanceId = (new ClaimableBalanceId())->wrapHash(Hash::make('1'));
        $claimants = ClaimantList::empty();
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $claimableBalanceEntry = (new ClaimableBalanceEntry())
            ->withClaimableBalanceId($claimableBalanceId)
            ->withClaimants($claimants)
            ->withAsset($asset);
        XDR::fresh()->write($claimableBalanceEntry);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $claimableBalanceEntry = XDR::fromBase64('AAAAAGuGsnP/NPzhnWuATv9aP1dHraTqoi8dScAeUt23h1tLAAAAAAAAAAFBQkNEAAAAAFXAH4dH3byGptDLjYonB1S62WdJwXveaBKged/eqXpSAAAAAACYloAAAAAA')
            ->read(ClaimableBalanceEntry::class);

        $this->assertInstanceOf(ClaimableBalanceEntry::class, $claimableBalanceEntry);
        $this->assertInstanceOf(ClaimableBalanceId::class, $claimableBalanceEntry->getClaimableBalanceId());
        $this->assertInstanceOf(ClaimantList::class, $claimableBalanceEntry->getClaimants());
        $this->assertInstanceOf(Asset::class, $claimableBalanceEntry->getAsset());
        $this->assertInstanceOf(Int64::class, $claimableBalanceEntry->getAmount());
        $this->assertInstanceOf(ClaimableBalanceEntryExt::class, $claimableBalanceEntry->getExtension());
    }

    /**
     * @test
     * @covers ::withClaimableBalanceId
     * @covers ::getClaimableBalanceId
     */
    public function it_accepts_a_claimable_balance_id()
    {
        $claimableBalanceId = (new ClaimableBalanceId())->wrapHash(Hash::make('1'));
        $claimableBalanceEntry = (new ClaimableBalanceEntry())
            ->withClaimableBalanceId($claimableBalanceId);

        $this->assertInstanceOf(ClaimableBalanceEntry::class, $claimableBalanceEntry);
        $this->assertInstanceOf(ClaimableBalanceId::class, $claimableBalanceEntry->getClaimableBalanceId());
    }

    /**
     * @test
     * @covers ::withClaimants
     * @covers ::getClaimants
     */
    public function it_accepts_a_list_of_claimants()
    {
        $claimants = ClaimantList::empty();
        $claimableBalanceEntry = (new ClaimableBalanceEntry())
            ->withClaimants($claimants);

        $this->assertInstanceOf(ClaimableBalanceEntry::class, $claimableBalanceEntry);
        $this->assertInstanceOf(ClaimantList::class, $claimableBalanceEntry->getClaimants());
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
        $claimableBalanceEntry = (new ClaimableBalanceEntry())->withAsset($asset);

        $this->assertInstanceOf(ClaimableBalanceEntry::class, $claimableBalanceEntry);
        $this->assertInstanceOf(Asset::class, $claimableBalanceEntry->getAsset());
    }

    /**
     * @test
     * @covers ::withAmount
     * @covers ::getAmount
     */
    public function it_accepts_an_int64_as_an_amount()
    {
        $claimableBalanceEntry = (new ClaimableBalanceEntry())
            ->withAmount(Int64::of(10000000));

        $this->assertInstanceOf(ClaimableBalanceEntry::class, $claimableBalanceEntry);
        $this->assertInstanceOf(Int64::class, $claimableBalanceEntry->getAmount());
    }

    /**
     * @test
     * @covers ::withAmount
     * @covers ::getAmount
     */
    public function it_accepts_a_scaled_amount_as_an_amount()
    {
        $claimableBalanceEntry = (new ClaimableBalanceEntry())
            ->withAmount(ScaledAmount::of(1));

        $this->assertInstanceOf(ClaimableBalanceEntry::class, $claimableBalanceEntry);
        $this->assertInstanceOf(Int64::class, $claimableBalanceEntry->getAmount());
        $this->assertEquals('10000000', $claimableBalanceEntry->getAmount()->toNativeString());
    }

    /**
     * @test
     * @covers ::withExtension
     * @covers ::getExtension
     */
    public function it_accepts_an_extension()
    {
        $claimableBalanceEntry = (new ClaimableBalanceEntry())
            ->withExtension(ClaimableBalanceEntryExt::empty());

        $this->assertInstanceOf(ClaimableBalanceEntry::class, $claimableBalanceEntry);
        $this->assertInstanceOf(ClaimableBalanceEntryExt::class, $claimableBalanceEntry->getExtension());
    }
}

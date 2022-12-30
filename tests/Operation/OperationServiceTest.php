<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Account\MuxedAccount;
use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\Ledger\LedgerKeyAccount;
use StageRightLabs\Bloom\Ledger\LedgerKeyClaimableBalance;
use StageRightLabs\Bloom\Ledger\LedgerKeyData;
use StageRightLabs\Bloom\Ledger\LedgerKeyLiquidityPool;
use StageRightLabs\Bloom\Ledger\LedgerKeyOffer;
use StageRightLabs\Bloom\Ledger\LedgerKeyTrustLine;
use StageRightLabs\Bloom\LiquidityPool\PoolId;
use StageRightLabs\Bloom\Operation\AllowTrustOp;
use StageRightLabs\Bloom\Operation\BeginSponsoringFutureReservesOp;
use StageRightLabs\Bloom\Operation\BumpSequenceOp;
use StageRightLabs\Bloom\Operation\ChangeTrustOp;
use StageRightLabs\Bloom\Operation\ClaimClaimableBalanceOp;
use StageRightLabs\Bloom\Operation\ClawbackClaimableBalanceOp;
use StageRightLabs\Bloom\Operation\ClawbackOp;
use StageRightLabs\Bloom\Operation\CreateAccountOp;
use StageRightLabs\Bloom\Operation\CreateClaimableBalanceOp;
use StageRightLabs\Bloom\Operation\CreatePassiveSellOfferOp;
use StageRightLabs\Bloom\Operation\LiquidityPoolDepositOp;
use StageRightLabs\Bloom\Operation\LiquidityPoolWithdrawOp;
use StageRightLabs\Bloom\Operation\ManageBuyOfferOp;
use StageRightLabs\Bloom\Operation\ManageDataOp;
use StageRightLabs\Bloom\Operation\ManageSellOfferOp;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Operation\OperationService;
use StageRightLabs\Bloom\Operation\PathPaymentStrictReceiveOp;
use StageRightLabs\Bloom\Operation\PathPaymentStrictSendOp;
use StageRightLabs\Bloom\Operation\PaymentOp;
use StageRightLabs\Bloom\Operation\RevokeSponsorshipOp;
use StageRightLabs\Bloom\Operation\RevokeSponsorshipOpSigner;
use StageRightLabs\Bloom\Operation\SetOptionsOp;
use StageRightLabs\Bloom\Operation\SetTrustLineFlagsOp;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\OperationService
 */
class OperationServiceTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     */
    public function the_operation_service_can_be_located()
    {
        $bloom = new Bloom();
        $this->assertInstanceOf(OperationService::class, $bloom->operation);
    }

    /**
     * @test
     * @covers ::createAccount
     */
    public function it_can_make_a_create_account_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->createAccount(
            destination: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            startingBalance: '10'
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(CreateAccountOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::payment
     */
    public function it_can_make_a_payment_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->payment(
            destination: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            asset: 'TEST:GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            amount: '10'
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(PaymentOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::pathPaymentStrictSend
     */
    public function it_can_make_a_path_payment_strict_send_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->pathPaymentStrictSend(
            sendingAsset: 'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            sendingAmount: '2',
            destination: 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            destinationAsset: 'ABCDEFGHIJKL:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            destinationMinimum: '1',
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(PathPaymentStrictSendOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::pathPaymentStrictReceive
     */
    public function it_can_make_a_path_payment_strict_receive_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->pathPaymentStrictReceive(
            sendingAsset: 'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            sendingMaximum: '2',
            destination: 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            destinationAsset: 'ABCDEFGHIJKL:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            destinationAmount: '1',
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(PathPaymentStrictReceiveOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::manageBuyOffer
     */
    public function it_can_make_a_manage_buy_offer_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->manageBuyOffer(
            sellingAsset: 'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            buyingAsset: 'ABCDEFG:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            buyingAmount: '10',
            price: '3.75',
            offerId: 0,
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(ManageBuyOfferOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::manageSellOffer
     */
    public function it_can_make_a_manage_sell_offer_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->manageSellOffer(
            sellingAsset: 'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            sellingAmount: '10',
            buyingAsset: 'ABCDEFG:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            price: '3.75',
            offerId: 0,
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(ManageSellOfferOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::createPassiveSellOffer
     */
    public function it_can_make_a_create_passive_sell_offer_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->createPassiveSellOffer(
            sellingAsset: 'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            buyingAsset: 'ABCDEFG:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            price: '3.75',
            amount: '10',
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(CreatePassiveSellOfferOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::setOptions
     */
    public function it_can_make_a_set_options_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->setOptions(
            inflationDestination: Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW')->getAccountId(),
            clearFlags: 1,
            setFlags: 2,
            masterWeight: 3,
            lowThreshold: 4,
            mediumThreshold: 5,
            highThreshold: 6,
            homeDomain: 'example.com',
            signer: Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW')->getWeightedSigner(255)
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(SetOptionsOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::changeTrust
     */
    public function it_can_make_a_change_trust_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->changeTrust(
            asset: 'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            limit: 0,
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(ChangeTrustOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::allowTrust
     */
    public function it_can_make_an_allow_trust_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->allowTrust(
            trustor: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            assetCode: 'ABCD',
            authorize: AllowTrustOp::AUTHORIZED_FLAG,
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(AllowTrustOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::accountMerge
     */
    public function it_can_make_an_account_merge_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->accountMerge(
            destination: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            source: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(MuxedAccount::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::manageData
     */
    public function it_can_make_a_manage_data_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->manageData(
            name: 'FOO',
            value: 'BAR',
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(ManageDataOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::bumpSequence
     */
    public function it_can_make_a_bump_sequence_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->bumpSequence(
            bumpTo: 256,
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(BumpSequenceOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::createClaimableBalance
     */
    public function it_can_make_a_create_claimable_balance_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->createClaimableBalance(
            asset: 'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            amount: '10',
            claimants: ['GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS'],
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(CreateClaimableBalanceOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::claimClaimableBalance
     */
    public function it_can_make_a_claim_claimable_balance_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->claimClaimableBalance(
            balanceId: '0000000017496e54b6e193d074e50c47bad7ec8c411bd06ce984e6a9b4cd109e65569a4e'
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(ClaimClaimableBalanceOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::beginSponsoringFutureReserves
     */
    public function it_can_make_a_begin_sponsoring_future_reserves_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->beginSponsoringFutureReserves(
            sponsoredId: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(BeginSponsoringFutureReservesOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::endSponsoringFutureReserves
     */
    public function it_can_make_an_end_sponsoring_future_reserves_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->endSponsoringFutureReserves(
            source: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertNull($operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::revokeAccountSponsorship
     */
    public function it_can_make_a_revoke_account_sponsorship_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->revokeAccountSponsorship(
            address: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN'
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(RevokeSponsorshipOp::class, $operation->getBody()->unwrap());
        $this->assertInstanceOf(LedgerKeyAccount::class, $operation->getBody()->unwrap()->unwrap()->unwrap());
    }

    /**
     * @test
     * @covers ::revokeTrustLineSponsorship
     */
    public function it_can_make_a_revoke_trust_line_sponsorship_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->revokeTrustlineSponsorship(
            address: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
            asset: 'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(RevokeSponsorshipOp::class, $operation->getBody()->unwrap());
        $this->assertInstanceOf(LedgerKeyTrustLine::class, $operation->getBody()->unwrap()->unwrap()->unwrap());
    }

    /**
     * @test
     * @covers ::revokeOfferSponsorship
     */
    public function it_can_make_a_revoke_offer_sponsorship_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->revokeOfferSponsorship(
            sellerId: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
            offerId: 1,
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(RevokeSponsorshipOp::class, $operation->getBody()->unwrap());
        $this->assertInstanceOf(LedgerKeyOffer::class, $operation->getBody()->unwrap()->unwrap()->unwrap());
    }

    /**
     * @test
     * @covers ::revokeDataSponsorship
     */
    public function it_can_make_a_revoke_data_sponsorship_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->revokeDataSponsorship(
            address: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
            dataName: 'FOOBAR',
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(RevokeSponsorshipOp::class, $operation->getBody()->unwrap());
        $this->assertInstanceOf(LedgerKeyData::class, $operation->getBody()->unwrap()->unwrap()->unwrap());
    }

    /**
     * @test
     * @covers ::revokeClaimableBalanceSponsorship
     */
    public function it_can_make_a_revoke_claimable_balance_sponsorship_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->revokeClaimableBalanceSponsorship(
            balanceId: '0000000017496e54b6e193d074e50c47bad7ec8c411bd06ce984e6a9b4cd109e65569a4e'
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(RevokeSponsorshipOp::class, $operation->getBody()->unwrap());
        $this->assertInstanceOf(LedgerKeyClaimableBalance::class, $operation->getBody()->unwrap()->unwrap()->unwrap());
    }

    /**
     * @test
     * @covers ::revokeLiquidityPoolSponsorship
     */
    public function it_can_make_a_revoke_liquidity_pool_sponsorship_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->revokeLiquidityPoolSponsorship(
            poolId: PoolId::make('1')
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(RevokeSponsorshipOp::class, $operation->getBody()->unwrap());
        $this->assertInstanceOf(LedgerKeyLiquidityPool::class, $operation->getBody()->unwrap()->unwrap()->unwrap());
    }

    /**
     * @test
     * @covers ::revokeSignerSponsorship
     */
    public function it_can_make_a_revoke_signer_sponsorship_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->revokeSignerSponsorship(
            address: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN'
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(RevokeSponsorshipOp::class, $operation->getBody()->unwrap());
        $this->assertInstanceOf(RevokeSponsorshipOpSigner::class, $operation->getBody()->unwrap()->unwrap());
    }

    /**
     * @test
     * @covers ::clawback
     */
    public function it_can_make_a_clawback_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->clawback(
            from: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
            asset: 'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            amount: '10',
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(ClawbackOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::clawbackClaimableBalance
     */
    public function it_can_make_a_clawback_claimable_balance_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->clawbackClaimableBalance(
            balanceId: '0000000017496e54b6e193d074e50c47bad7ec8c411bd06ce984e6a9b4cd109e65569a4e'
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(ClawbackClaimableBalanceOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::setTrustLineFlags
     *
     * @return void
     */
    public function it_can_create_a_set_trustline_flags_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->setTrustLineFlags(
            trustor: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
            asset: 'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            authorized: true,
            authorizedToMaintainLiabilities: false,
            clawbackEnabled: false,
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(SetTrustLineFlagsOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::liquidityPoolDeposit
     */
    public function it_can_make_a_liquidity_pool_deposit_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->liquidityPoolDeposit(
            poolId: PoolId::make('1'),
            maxAmountA: '10',
            maxAmountB: '8',
            minPrice: '2/1',
            maxPrice: '3/1',
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(LiquidityPoolDepositOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::liquidityPoolWithdraw
     */
    public function it_can_make_a_liquidity_pool_withdraw_operation()
    {
        $bloom = new Bloom();
        $operation = $bloom->operation->liquidityPoolWithdraw(
            poolId: PoolId::make('1'),
            amount: '10',
            minAmountA: '6',
            minAmountB: '4',
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(LiquidityPoolWithdrawOp::class, $operation->getBody()->unwrap());
    }
}

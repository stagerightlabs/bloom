<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Account;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\MuxedAccount;
use StageRightLabs\Bloom\Account\Signer;
use StageRightLabs\Bloom\Cryptography\DecoratedSignature;
use StageRightLabs\Bloom\Cryptography\Signature;
use StageRightLabs\Bloom\Cryptography\SignatureHint;
use StageRightLabs\Bloom\Exception\InvalidKeyException;
use StageRightLabs\Bloom\Horizon\AccountBalanceResource;
use StageRightLabs\Bloom\Horizon\AccountResource;
use StageRightLabs\Bloom\Horizon\AccountSignerResource;
use StageRightLabs\Bloom\Horizon\Resource;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Keypair\Keypair;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\SequenceNumber;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Account\Account
 */
class AccountTest extends TestCase
{
    /**
     * @test
     * @covers ::sign
     */
    public function it_can_sign_a_message()
    {
        $keypair = Keypair::fromSeed('SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6');
        $account = Account::fromKeypair($keypair);
        $signature = $account->sign('abcd');

        $this->assertInstanceOf(Signature::class, $signature);
    }

    /**
     * @test
     * @covers ::sign
     */
    public function it_cannot_sign_a_message_without_private_bytes()
    {
        $this->expectException(InvalidKeyException::class);
        $keypair = Keypair::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $account = Account::fromKeypair($keypair);
        $account->sign('abcd');
    }

    /**
     * @test
     * @covers ::signDecorated
     */
    public function it_can_sign_a_message_and_return_a_decorated_signature()
    {
        $keypair = Keypair::fromSeed('SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6');
        $account = Account::fromKeypair($keypair);
        $decorated = $account->signDecorated('abcd');

        $this->assertInstanceOf(DecoratedSignature::class, $decorated);
        $this->assertTrue($keypair->verify('abcd', $decorated->getSignature()));
    }

    /**
     * @test
     * @covers ::verify
     */
    public function it_can_verify_signatures()
    {
        $privateKey = hex2bin('1123740522f11bfef6b3671f51e159ccf589ccf8965262dd5f97d1721d383dd4');
        $keypair = new Keypair(privateKey: $privateKey);
        $account = Account::fromKeypair($keypair);
        $validSignature = hex2bin('587d4b472eeef7d07aafcd0b049640b0bb3f39784118c2e2b73a04fa2f64c9c538b4b2d0f5335e968a480021fdc23e98c0ddf424cb15d8131df8cb6c4bb58309');
        $invalidSignature = hex2bin('687d4b472eeef7d07aafcd0b049640b0bb3f39784118c2e2b73a04fa2f64c9c538b4b2d0f5335e968a480021fdc23e98c0ddf424cb15d8131df8cb6c4bb58309');

        $this->assertTrue($account->verify('hello world', $validSignature));
        $this->assertFalse($account->verify('hello world', $invalidSignature));
    }

    /**
     * @test
     * @covers ::getSignatureHint
     */
    public function it_returns_a_signature_hint()
    {
        // Address: GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ
        $keypair = Keypair::fromSeed('SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6');
        $account = Account::fromKeypair($keypair);
        $hint = $account->getSignatureHint();

        $this->assertInstanceOf(SignatureHint::class, $hint);
        $this->assertEquals('GPSJ', $hint->getValue());
    }

    /**
     * @test
     * @covers ::fromAddress
     */
    public function it_can_be_instantiated_from_an_address()
    {
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW', $account->getAddress());
    }

    /**
     * @test
     * @covers ::fromAddress
     */
    public function it_can_be_instantiated_from_an_addressable()
    {
        $original = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $copy = Account::fromAddress($original);

        $this->assertInstanceOf(Account::class, $copy);
        $this->assertEquals('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW', $copy->getAddress());
        $this->assertNotEquals(spl_object_id($original), spl_object_id($copy));
    }

    /**
     * @test
     * @covers ::fromSeed
     */
    public function it_can_be_instantiated_from_a_seed()
    {
        $account = Account::fromSeed('SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6');
        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals('GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ', $account->getAddress());
    }

    /**
     * @test
     * @covers ::fromKeypair
     */
    public function it_can_be_instantiated_from_a_keypair()
    {
        $keypair = new Keypair(seed: 'SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6');
        $account = Account::fromKeypair($keypair);
        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals('GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ', $account->getAddress());
    }

    /**
     * @test
     * @covers ::getAddress
     */
    public function it_returns_the_address()
    {
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $this->assertEquals('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW', $account->getAddress());
    }

    /**
     * @test
     * @covers ::getSeed
     */
    public function it_returns_the_seed()
    {
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = Account::fromSeed('SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6');

        $this->assertEmpty($accountA->getSeed());
        $this->assertEquals(
            'SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6',
            $accountB->getSeed()
        );
    }

    /**
     * @test
     * @covers ::canSign
     */
    public function it_knows_if_it_can_sign()
    {
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $this->assertFalse($account->canSign());

        $account = Account::fromSeed('SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6');
        $this->assertTrue($account->canSign());
    }

    /**
     * @test
     * @covers ::getKeyPair
     */
    public function it_returns_the_keypair()
    {
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $this->assertInstanceOf(Keypair::class, $account->getKeyPair());

        $this->expectException(InvalidKeyException::class);
        $account = new Account();
        $account->getKeyPair();
    }

    /**
     * @test
     * @covers ::withKeypair
     */
    public function it_accepts_a_keypair()
    {
        $firstKeypair = new Keypair(address: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $secondKeypair = new Keypair(address: 'GAAYP5PGGRZLM5OQIPI4LJNENVJY4FFMKZW2U5IEKPAHS362B6G2XTG4');
        $firstAccount = Account::fromKeypair($firstKeypair);
        $secondAccount = $firstAccount->withKeyPair($secondKeypair);

        $this->assertInstanceOf(Account::class, $secondAccount);
        $this->assertNotEquals($firstAccount->getAddress(), $secondAccount->getAddress());
    }

    /**
     * @test
     * @covers ::getSequenceNumber
     */
    public function it_returns_the_sequence_number()
    {
        $sequenceNumber = SequenceNumber::of('1');
        $account = (new Account())->withSequenceNumber($sequenceNumber);

        $this->assertInstanceOf(SequenceNumber::class, $account->getSequenceNumber());
    }

    /**
     * @test
     * @covers ::withSequenceNumber
     */
    public function it_accepts_a_sequence_number()
    {
        $firstSequenceNumber = SequenceNumber::of('1');
        $secondSequenceNumber = SequenceNumber::of('2');
        $firstAccount = (new Account())->withSequenceNumber($firstSequenceNumber);
        $secondAccount = $firstAccount->withSequenceNumber($secondSequenceNumber);

        $this->assertInstanceOf(Account::class, $secondAccount);
        $this->assertNotEquals(
            $firstAccount->getSequenceNumber()->toNativeInt(),
            $secondAccount->getSequenceNumber()->toNativeInt()
        );
    }

    /**
     * @test
     * @covers ::withAccountResource
     * @covers ::hasBeenLoaded
     */
    public function it_accepts_an_account_resource()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));

        $this->assertFalse($accountA->hasBeenLoaded());
        $this->assertTrue($accountB->hasBeenLoaded());
    }

    /**
     * @test
     * @covers ::getAccountId
     */
    public function it_returns_an_account_id()
    {
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $this->assertInstanceOf(AccountId::class, $account->getAccountId());
    }

    /**
     * @test
     * @covers ::getAccountId
     */
    public function it_cannot_return_an_account_id_without_a_keypair()
    {
        $this->expectException(InvalidKeyException::class);
        (new Account())->getAccountId();
    }

    /**
     * @test
     * @covers ::getMuxedAccount
     */
    public function it_returns_a_muxed_account()
    {
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $this->assertInstanceOf(MuxedAccount::class, $account->getMuxedAccount());
    }

    /**
     * @test
     * @covers ::getMuxedAccount
     */
    public function it_cannot_return_a_muxed_account_without_a_keypair()
    {
        $this->expectException(InvalidKeyException::class);
        (new Account())->getMuxedAccount();
    }

    /**
     * @test
     * @covers ::getWeightedSigner
     */
    public function it_returns_a_weighted_signer()
    {
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');

        $this->assertInstanceOf(Signer::class, $account->getWeightedSigner(10));
        $this->assertEquals(10, $account->getWeightedSigner(10)->getWeight()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getWeightedSigner
     */
    public function it_cannot_return_a_weighted_signer_without_a_keypair()
    {
        $this->expectException(InvalidKeyException::class);
        (new Account())->getWeightedSigner(10);
    }

    /**
     * @test
     * @covers ::getSelfLink
     */
    public function it_returns_the_self_link_when_available()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));

        $this->assertNull($accountA->getSelfLink());
        $this->assertEquals('https://horizon-testnet.stellar.org/accounts/[address]', $accountB->getSelfLink());
    }

    /**
     * @test
     * @covers ::getTransactionsLink
     */
    public function it_returns_the_transactions_link_when_available()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));

        $this->assertNull($accountA->getTransactionsLink());
        $this->assertEquals('https://horizon-testnet.stellar.org/accounts/[address]/transactions{?cursor,limit,order}', $accountB->getTransactionsLink());
    }

    /**
     * @test
     * @covers ::getOperationsLink
     */
    public function it_returns_the_operations_link_when_available()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));

        $this->assertNull($accountA->getOperationsLink());
        $this->assertEquals('https://horizon-testnet.stellar.org/accounts/[address]/operations{?cursor,limit,order}', $accountB->getOperationsLink());
    }

    /**
     * @test
     * @covers ::getPaymentsLink
     */
    public function it_returns_the_payments_link_when_available()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));

        $this->assertNull($accountA->getPaymentsLink());
        $this->assertEquals('https://horizon-testnet.stellar.org/accounts/[address]/payments{?cursor,limit,order}', $accountB->getPaymentsLink());
    }

    /**
     * @test
     * @covers ::getEffectsLink
     */
    public function it_returns_the_effects_link_when_available()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));

        $this->assertNull($accountA->getEffectsLink());
        $this->assertEquals('https://horizon-testnet.stellar.org/accounts/[address]/effects{?cursor,limit,order}', $accountB->getEffectsLink());
    }

    /**
     * @test
     * @covers ::getOffersLink
     */
    public function it_returns_the_offers_link_when_available()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));

        $this->assertNull($accountA->getOffersLink());
        $this->assertEquals('https://horizon-testnet.stellar.org/accounts/[address]/offers{?cursor,limit,order}', $accountB->getOffersLink());
    }

    /**
     * @test
     * @covers ::getTradesLink
     */
    public function it_returns_the_trades_link_when_available()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));

        $this->assertNull($accountA->getTradesLink());
        $this->assertEquals('https://horizon-testnet.stellar.org/accounts/[address]/trades{?cursor,limit,order}', $accountB->getTradesLink());
    }

    /**
     * @test
     * @covers ::getDataLink
     */
    public function it_returns_the_data_link_when_available()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));

        $this->assertNull($accountA->getDataLink());
        $this->assertEquals('https://horizon-testnet.stellar.org/accounts/[address]/data/{key}', $accountB->getDataLink());
    }

    /**
     * @test
     * @covers ::getResourceId
     */
    public function it_returns_the_resource_id()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account', [
            'address' => 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'
        ]));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));

        $this->assertNull($accountA->getResourceId());
        $this->assertEquals('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW', $accountB->getResourceId());
    }

    /**
     * @test
     * @covers ::getSequenceLedger
     */
    public function it_returns_the_sequence_ledger()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));

        $this->assertNull($accountA->getSequenceLedger());
        $this->assertInstanceOf(UInt32::class, $accountB->getSequenceLedger());
        $this->assertEquals(50, $accountB->getSequenceLedger()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getSequenceTime
     */
    public function it_returns_the_sequence_time()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));

        $this->assertNull($accountA->getSequenceTime());
        $this->assertInstanceOf(UInt64::class, $accountB->getSequenceTime());
        $this->assertEquals('100', $accountB->getSequenceTime()->toNativeString());
    }

    /**
     * @test
     * @covers ::getSubEntryCount
     */
    public function it_returns_the_sub_entry_count()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));

        $this->assertNull($accountA->getSubEntryCount());
        $this->assertInstanceOf(UInt32::class, $accountB->getSubEntryCount());
        $this->assertEquals(10, $accountB->getSubEntryCount()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getHomeDomain
     */
    public function it_returns_the_home_domain()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));

        $this->assertNull($accountA->getHomeDomain());
        $this->assertEquals('[domain]', $accountB->getHomeDomain());
    }

    /**
     * @test
     * @covers ::getLastModifiedLedgerSequence
     */
    public function it_returns_the_last_modified_ledger_sequence()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));

        $this->assertNull($accountA->getLastModifiedLedgerSequence());
        $this->assertInstanceOf(UInt32::class, $accountB->getLastModifiedLedgerSequence());
        $this->assertEquals(1305619, $accountB->getLastModifiedLedgerSequence()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getReservesSponsoringCount
     */
    public function it_returns_the_reserves_sponsoring_count()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));

        $this->assertNull($accountA->getReservesSponsoringCount());
        $this->assertInstanceOf(UInt32::class, $accountB->getReservesSponsoringCount());
        $this->assertEquals(1, $accountB->getReservesSponsoringCount()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getReservesSponsoredCount
     */
    public function it_returns_the_reserves_sponsored_count()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));

        $this->assertNull($accountA->getReservesSponsoredCount());
        $this->assertInstanceOf(UInt32::class, $accountB->getReservesSponsoredCount());
        $this->assertEquals(2, $accountB->getReservesSponsoredCount()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getSponsorId
     */
    public function it_returns_the_sponsor_id()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));

        $this->assertNull($accountA->getSponsorId());
        $this->assertEquals('[sponsor]', $accountB->getSponsorId());
    }

    /**
     * @test
     * @covers ::getLowThreshold
     */
    public function it_returns_the_low_threshold()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));

        $this->assertNull($accountA->getLowThreshold());
        $this->assertInstanceOf(UInt32::class, $accountB->getLowThreshold());
        $this->assertEquals(1, $accountB->getLowThreshold()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getMediumThreshold
     */
    public function it_returns_the_medium_threshold()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));

        $this->assertNull($accountA->getMediumThreshold());
        $this->assertInstanceOf(UInt32::class, $accountB->getMediumThreshold());
        $this->assertEquals(2, $accountB->getMediumThreshold()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getHighThreshold
     */
    public function it_returns_the_high_threshold()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));

        $this->assertNull($accountA->getHighThreshold());
        $this->assertInstanceOf(UInt32::class, $accountB->getHighThreshold());
        $this->assertEquals(3, $accountB->getHighThreshold()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getFlags
     */
    public function it_returns_the_account_flags()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));
        $expected = [
            'auth_required'         => true,
            'auth_revocable'        => true,
            'auth_immutable'        => true,
            'auth_clawback_enabled' => true,
        ];

        $this->assertNull($accountA->getFlags());
        $this->assertEquals($expected, $accountB->getFlags());
    }

    /**
     * @test
     * @covers ::getAuthImmutableFlag
     */
    public function it_returns_the_auth_immutable_flag()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));

        $this->assertNull($accountA->getAuthImmutableFlag());
        $this->assertEquals(true, $accountB->getAuthImmutableFlag());
    }

    /**
     * @test
     * @covers ::getAuthRequiredFlag
     */
    public function it_returns_the_auth_required_flag()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));

        $this->assertNull($accountA->getAuthRequiredFlag());
        $this->assertEquals(true, $accountB->getAuthRequiredFlag());
    }

    /**
     * @test
     * @covers ::getAuthRevocableFlag
     */
    public function it_returns_the_auth_revocable_flag()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));

        $this->assertNull($accountA->getAuthRevocableFlag());
        $this->assertEquals(true, $accountB->getAuthRevocableFlag());
    }

    /**
     * @test
     * @covers ::getAuthClawbackEnabledFlag
     */
    public function it_returns_the_auth_clawback_enabled_flag()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));

        $this->assertNull($accountA->getAuthClawbackEnabledFlag());
        $this->assertEquals(true, $accountB->getAuthClawbackEnabledFlag());
    }

    /**
     * @test
     * @covers ::getBalances
     */
    public function it_returns_the_account_balances()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));
        $expected = [
            'balance'             => '10000.0000000',
            'buying_liabilities'  => '0.0000000',
            'selling_liabilities' => '0.0000000',
            'asset_type'          => 'native',
        ];

        $this->assertNull($accountA->getBalances());
        $this->assertInstanceOf(AccountBalanceResource::class, $accountB->getBalances()[1]);
        $this->assertEquals($expected, $accountB->getBalances()[1]->toArray());
    }

    /**
     * @test
     * @covers ::getBalanceForAsset
     */
    public function it_returns_the_balance_for_a_given_asset()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));
        $balance = $accountB->getBalanceForAsset('USDC:GBBD47IF6LWK7P7MDEVSCWR7DPUWV3NY3DTQEVFL4NAT4AQH3ZLLFLA5');

        $this->assertInstanceOf(AccountBalanceResource::class, $balance);
        $this->assertEquals('USDC', $balance->getAssetCode());
        $this->assertEquals('GBBD47IF6LWK7P7MDEVSCWR7DPUWV3NY3DTQEVFL4NAT4AQH3ZLLFLA5', $balance->getAssetIssuer());
        $this->assertNull($accountA->getBalanceForAsset('USDC:GBBD47IF6LWK7P7MDEVSCWR7DPUWV3NY3DTQEVFL4NAT4AQH3ZLLFLA5'));
    }

    /**
     * @test
     * @covers ::getSigners
     */
    public function it_returns_the_signers()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));
        $expected = [
            'weight' => 1,
            'key'    => '[address]',
            'type'   => 'ed25519_public_key',
        ];

        $this->assertNull($accountA->getSigners());
        $this->assertInstanceOf(AccountSignerResource::class, $accountB->getSigners()[0]);
        $this->assertEquals($expected, $accountB->getSigners()[0]->toArray());
    }

    /**
     * @test
     * @covers ::getData
     */
    public function it_returns_the_account_data()
    {
        $resource = Resource::fromResponse(Response::fake('retrieve_account'));
        $accountA = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $accountB = $accountA->withAccountResource(AccountResource::fromResource($resource));
        $expected = [
            'foo' => 'bar',
        ];

        $this->assertNull($accountA->getData());
        $this->assertEquals($expected, $accountB->getData());
    }
}

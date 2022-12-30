<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Account;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\Horizon\Error;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\SequenceNumber;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Account\AccountService
 */
class AccountServiceTest extends TestCase
{
    /**
     * @test
     * @covers ::retrieve
     */
    public function it_retrieves_account_details_for_an_account_with_an_address()
    {
        $bloom = Bloom::fake();
        $bloom->horizon->withResponse(Response::fake('retrieve_account', [
            'address' => 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'
        ]));
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $this->assertFalse($account->hasBeenLoaded());

        $account = $bloom->account->retrieve($account);

        $this->assertInstanceOf(Account::class, $account);
        $this->assertTrue($account->hasBeenLoaded());
    }

    /**
     * @test
     * @covers ::retrieve
     */
    public function it_retrieves_account_details_for_an_account_with_a_seed()
    {
        $bloom = Bloom::fake();
        $bloom->horizon->withResponse(Response::fake('retrieve_account', [
            'address' => 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'
        ]));
        $account = Account::fromSeed('SDXYSHJV2VDYBKZPBJK6G2WQJOFTM37RQTXV2LDUWMPILXMSPCMXIFL6');
        $this->assertFalse($account->hasBeenLoaded());

        $account = $bloom->account->retrieve($account);


        $this->assertInstanceOf(Account::class, $account);
        $this->assertTrue($account->hasBeenLoaded());
    }

    /**
     * @test
     * @covers ::retrieve
     */
    public function it_retrieves_account_details_for_an_addressable()
    {
        $bloom = Bloom::fake();
        $bloom->horizon->withResponse(Response::fake('retrieve_account', [
            'address' => 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'
        ]));

        $account = $bloom->account->retrieve(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(Account::class, $account);
        $this->assertTrue($account->hasBeenLoaded());
    }

    /**
     * @test
     * @covers ::retrieve
     */
    public function it_retrieves_account_details_for_a_string_address()
    {
        $bloom = Bloom::fake();
        $bloom->horizon->withResponse(Response::fake('retrieve_account', [
            'address' => 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'
        ]));

        $account = $bloom->account->retrieve('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');

        $this->assertInstanceOf(Account::class, $account);
        $this->assertTrue($account->hasBeenLoaded());
    }

    /**
     * @test
     * @covers ::retrieve
     */
    public function it_returns_an_error_if_the_account_detail_request_fails()
    {
        $bloom = Bloom::fake();
        $bloom->horizon->withResponse(Response::fake('generic_error', statusCode: 400));
        $response = $bloom->account->retrieve('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');

        $this->assertInstanceOf(Error::class, $response);
    }

    /**
     * @test
     * @covers ::incrementSequenceNumber
     */
    public function it_can_increment_a_sequence_number()
    {
        $bloom = Bloom::fake();
        $account = (new Account())->withSequenceNumber(SequenceNumber::of(1));

        $account = $bloom->account->incrementSequenceNumber($account);
        $this->assertTrue($account->getSequenceNumber()->isEqualTo(2));

        $account = $bloom->account->incrementSequenceNumber($account, 2);
        $this->assertTrue($account->getSequenceNumber()->isEqualTo(4));
    }
}

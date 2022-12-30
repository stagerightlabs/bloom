<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Operation\CreateAccountOp;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\CreateAccountOp
 */
class CreateAccountOpTest extends TestCase
{
    /**
     * @test
     * @covers ::operation
     */
    public function it_can_create_an_operation()
    {
        $operation = CreateAccountOp::operation(
            destination: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
            startingBalance: '10',
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(CreateAccountOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::isReady
     */
    public function it_knows_when_it_is_ready()
    {
        $createAccountOp = new CreateAccountOp();
        $this->assertFalse($createAccountOp->isReady());

        $createAccountOp = $createAccountOp->withDestination('GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN');
        $this->assertFalse($createAccountOp->isReady());

        $createAccountOp = $createAccountOp->withStartingBalance('10');
        $this->assertTrue($createAccountOp->isReady());
    }

    /**
     * @test
     * @covers ::getThreshold
     */
    public function it_has_a_threshold_category()
    {
        $this->assertEquals(Thresholds::CATEGORY_MEDIUM, (new CreateAccountOp())->getThreshold());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $createAccountOp = (new CreateAccountOp())
            ->withDestination('GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN')
            ->withStartingBalance('10');
        $buffer = XDR::fresh()->write($createAccountOp);

        $this->assertEquals('AAAAABVDUP46pOTFitIqVaR9Pl5fRwJPIbIkZxU2X9svKzmLAAAAAAX14QA=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_destination_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $createAccountOp = (new CreateAccountOp())->withStartingBalance('10');

        XDR::fresh()->write($createAccountOp);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_starting_balance_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $createAccountOp = (new CreateAccountOp())->withDestination('GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN');

        XDR::fresh()->write($createAccountOp);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $createAccountOp = XDR::fromBase64('AAAAABVDUP46pOTFitIqVaR9Pl5fRwJPIbIkZxU2X9svKzmLAAAAAAX14QA=')
            ->read(CreateAccountOp::class);

        $this->assertInstanceOf(AccountId::class, $createAccountOp->getDestination());
        $this->assertTrue($createAccountOp->getStartingBalance()->isEqualTo('100000000'));
    }

    /**
     * @test
     * @covers ::withDestination
     */
    public function it_accepts_a_string_as_a_destination()
    {
        $createAccountOp = (new CreateAccountOp())->withDestination('GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN');

        $this->assertInstanceOf(AccountId::class, $createAccountOp->getDestination());
        $this->assertEquals(
            'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
            $createAccountOp->getDestination()->unwrap()->getAddress()
        );
    }

    /**
     * @test
     * @covers ::withDestination
     * @covers ::getDestination
     */
    public function it_accepts_an_addressable_as_a_destination()
    {
        $account = Account::fromAddress('GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN');
        $createAccountOp = (new CreateAccountOp())->withDestination($account);

        $this->assertInstanceOf(AccountId::class, $createAccountOp->getDestination());
        $this->assertEquals(
            'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
            $createAccountOp->getDestination()->unwrap()->getAddress()
        );
    }

    /**
     * @test
     * @covers ::withDestination
     */
    public function it_accepts_an_account_id_as_a_destination()
    {
        $accountId = AccountId::fromAddressable('GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN');
        $createAccountOp = (new CreateAccountOp())->withDestination($accountId);

        $this->assertInstanceOf(AccountId::class, $createAccountOp->getDestination());
        $this->assertEquals(
            'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
            $createAccountOp->getDestination()->unwrap()->getAddress()
        );
    }

    /**
     * @test
     * @covers ::withStartingBalance
     * @covers ::getStartingBalance
     */
    public function it_accepts_an_int64_starting_balance()
    {
        $startingBalance = Int64::of(100);
        $createAccountOp = (new CreateAccountOp())->withStartingBalance($startingBalance);

        $this->assertInstanceOf(Int64::class, $createAccountOp->getStartingBalance());
        $this->assertTrue($createAccountOp->getStartingBalance()->isEqualTo(100));
    }

    /**
     * @test
     * @covers ::withStartingBalance
     */
    public function it_accepts_a_string_as_a_starting_balance()
    {
        $createAccountOp = (new CreateAccountOp())->withStartingBalance('100');

        $this->assertInstanceOf(Int64::class, $createAccountOp->getStartingBalance());
        $this->assertTrue($createAccountOp->getStartingBalance()->isEqualTo(1000000000));
    }
}

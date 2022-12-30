<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Account\OptionalAddress;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Operation\CreateAccountOp;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Operation\OperationBody;
use StageRightLabs\Bloom\Operation\OperationType;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\Operation
 */
class OperationTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $operation = CreateAccountOp::operation(
            destination: 'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
            startingBalance: '10',
            source: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'
        );

        $buffer = XDR::fresh()->write($operation);

        $this->assertEquals('AAAAAQAAAABqbUHHOUNTgIZpeXjQQHgNYcXOazSxcCrhBCh2M4Od7gAAAAAAAAAAFUNQ/jqk5MWK0ipVpH0+Xl9HAk8hsiRnFTZf2y8rOYsAAAAABfXhAA==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_optional_address_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $operationBody = OperationBody::make(OperationType::CREATE_ACCOUNT);
        $operation = (new Operation())->withBody($operationBody);
        XDR::fresh()->write($operation);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_body_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $account = OptionalAddress::some('GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN');
        $operation = (new Operation())->withSourceAccount($account);
        XDR::fresh()->write($operation);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $operation = XDR::fromBase64('AAAAAQAAAABqbUHHOUNTgIZpeXjQQHgNYcXOazSxcCrhBCh2M4Od7gAAAAAAAAAAFUNQ/jqk5MWK0ipVpH0+Xl9HAk8hsiRnFTZf2y8rOYsAAAAABfXhAA==')
            ->read(Operation::class);

        $this->assertEquals(
            'GAKUGUH6HKSOJRMK2IVFLJD5HZPF6RYCJ4Q3EJDHCU3F7WZPFM4YX6AN',
            $operation->getBody()->unwrap()->getDestination()->unwrap()->getAddress()
        );
    }

    /**
     * @test
     * @covers ::withSourceAccount
     * @covers ::getSourceAccount
     */
    public function it_accepts_an_optional_address_as_a_source_account()
    {
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $optional = OptionalAddress::some($account);
        $operation = (new Operation())->withSourceAccount($optional);

        $this->assertEquals(
            'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            $operation->getSourceAccount()->getAddress()
        );
    }

    /**
     * @test
     * @covers ::withSourceAccount
     * @covers ::getSourceAccount
     */
    public function it_accepts_an_addressable_as_a_source_account()
    {
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $operation = (new Operation())->withSourceAccount($account);

        $this->assertEquals(
            'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            $operation->getSourceAccount()->getAddress()
        );
    }

    /**
     * @test
     * @covers ::withSourceAccount
     * @covers ::getSourceAccount
     */
    public function it_accepts_null_as_a_source_account()
    {
        $operation = (new Operation())->withSourceAccount(null);

        $this->assertNull($operation->getSourceAccount());
    }

    /**
     * @test
     * @covers ::withBody
     * @covers ::getBody
     */
    public function it_accepts_an_operation_body()
    {
        $operation = (new Operation())->withBody(new OperationBody());
        $this->assertInstanceOf(OperationBody::class, $operation->getBody());
    }
}

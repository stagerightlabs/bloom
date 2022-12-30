<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Envelope\TransactionEnvelope;
use StageRightLabs\Bloom\Horizon\Error;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\TransactionResult;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\Error
 */
class ErrorTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     * @covers ::getPayload
     */
    public function it_can_be_instantiated_with_an_array_payload()
    {
        $error = new Error(['foo' => 'bar']);

        $this->assertInstanceOf(Error::class, $error);
        $this->assertEquals(['foo' => 'bar'], $error->getPayload()->getAll());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getPayload
     */
    public function it_can_be_instantiated_with_a_string_payload()
    {
        $error = new Error('{"foo": "bar"}');

        $this->assertInstanceOf(Error::class, $error);
        $this->assertEquals(['foo' => 'bar'], $error->getPayload()->getAll());
    }

    /**
     * @test
     * @covers ::fromResponse
     * @covers ::getPayload
     */
    public function it_can_be_instantiated_from_a_response()
    {
        $error = Error::fromResponse(Response::fake('example'));

        $this->assertInstanceOf(Error::class, $error);
        $this->assertEquals(['[foo]' => '[bar]'], $error->getPayload()->getAll());
    }

    /**
     * @test
     * @covers ::getResponse
     */
    public function it_returns_the_original_response_when_present()
    {
        $error = Error::fromResponse(Response::fake('example', statusCode: 400));
        $this->assertInstanceOf(Response::class, $error->getResponse());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_the_error_type()
    {
        $error = Error::fromResponse(Response::fake('generic_error', statusCode: 400));
        $this->assertEquals('https://stellar.org/horizon-errors/transaction_failed', $error->getType());
    }

    /**
     * @test
     * @covers ::getTitle
     */
    public function it_returns_the_error_title()
    {
        $error = Error::fromResponse(Response::fake('generic_error', statusCode: 400));
        $this->assertEquals('Transaction Failed', $error->getTitle());
    }

    /**
     * @test
     * @covers ::getStatus
     */
    public function it_returns_the_error_status()
    {
        $error = Error::fromResponse(Response::fake('generic_error', statusCode: 400));
        $this->assertEquals(400, $error->getStatus());
    }

    /**
     * @test
     * @covers ::getDetail
     */
    public function it_returns_the_error_detail()
    {
        $error = Error::fromResponse(Response::fake('generic_error', statusCode: 400));
        $expected = 'The transaction failed when submitted to the stellar network. The `extras.result_codes` field on this response contains further details.  Descriptions of each code can be found at: https://www.stellar.org/developers/learn/concepts/list-of-operations.html';

        $this->assertEquals($expected, $error->getDetail());
    }

    /**
     * @test
     * @covers ::getExtras
     */
    public function it_returns_the_extras_array()
    {
        $error = Error::fromResponse(Response::fake('generic_error', statusCode: 400));
        $expected = [
            'envelope_xdr' => 'AAAAAgAAAAA5qAjNaESrHyzpNei9hvw9SnHeyCnmufVEBQfB6dRaygAAAGQAEXT9AAAABQAAAAIAAAABAAAAAGN/3kIAAAAAZWER/gAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABAAAAAQAAAAA5qAjNaESrHyzpNei9hvw9SnHeyCnmufVEBQfB6dRaygAAAAAAAAAAm1RMGeJPomtqKXMU9jSEQ3eW9RBqKcZNC7vSbe4TeJ4AAAAABfXhAAAAAAAAAAAA',
            'result_codes' => [
                'transaction' => 'tx_failed',
                'operations'  => ['op_inner'],
            ],
            'result_xdr'   => 'AAAAAAAAAGT/////AAAAAQAAAAAAAAAA/////AAAAAA=',
        ];

        $this->assertEquals($expected, $error->getExtras());
    }

    /**
     * @test
     * @covers ::getEnvelopeXdr
     */
    public function it_returns_the_transaction_envelope_xdr()
    {
        $error = Error::fromResponse(Response::fake('generic_error', statusCode: 400));
        $expected = 'AAAAAgAAAAA5qAjNaESrHyzpNei9hvw9SnHeyCnmufVEBQfB6dRaygAAAGQAEXT9AAAABQAAAAIAAAABAAAAAGN/3kIAAAAAZWER/gAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABAAAAAQAAAAA5qAjNaESrHyzpNei9hvw9SnHeyCnmufVEBQfB6dRaygAAAAAAAAAAm1RMGeJPomtqKXMU9jSEQ3eW9RBqKcZNC7vSbe4TeJ4AAAAABfXhAAAAAAAAAAAA';

        $this->assertEquals($expected, $error->getEnvelopeXdr());
    }

    /**
     * @test
     * @covers ::getEnvelope
     */
    public function it_returns_the_transaction_envelope_object()
    {
        $error = Error::fromResponse(Response::fake('generic_error', statusCode: 400));

        $this->assertInstanceOf(TransactionEnvelope::class, $error->getEnvelope());
        $this->assertNull((new Error())->getEnvelope());
    }

    /**
     * @test
     * @covers ::getResultXdr
     */
    public function it_returns_the_result_xdr()
    {
        $error = Error::fromResponse(Response::fake('generic_error', statusCode: 400));
        $expected = 'AAAAAAAAAGT/////AAAAAQAAAAAAAAAA/////AAAAAA=';

        $this->assertEquals($expected, $error->getResultXdr());
    }

    /**
     * @test
     * @covers ::getResult
     */
    public function it_returns_the_decoded_result_object()
    {
        $error = Error::fromResponse(Response::fake('generic_error', statusCode: 400));

        $this->assertInstanceOf(TransactionResult::class, $error->getResult());
        $this->assertNull((new Error())->getResult());
    }

    /**
     * @test
     * @covers ::getTransactionResultCode
     */
    public function it_returns_the_transaction_result_code()
    {
        $error = Error::fromResponse(Response::fake('generic_error', statusCode: 400));
        $this->assertEquals('tx_failed', $error->getTransactionResultCode());
    }

    /**
     * @test
     * @covers ::getOperationResultCodes
     */
    public function it_returns_the_operation_result_codes()
    {
        $error = Error::fromResponse(Response::fake('generic_error', statusCode: 400));
        $this->assertEquals(['op_inner'], $error->getOperationResultCodes());
    }

    /**
     * @test
     * @covers ::getMessage
     */
    public function it_returns_an_error_message()
    {
        $error = Error::fromResponse(Response::fake('generic_error', statusCode: 400));
        $this->assertEquals('Error:: [tx_failed] One of the operations failed (none were applied).', $error->getMessage());
    }

    /**
     * @test
     * @covers ::requestFailed
     */
    public function it_knows_that_it_represents_a_failed_request()
    {
        $resource = Error::fromResponse(Response::fake('generic_error', statusCode: 400));
        $this->assertTrue($resource->requestFailed());
    }
}

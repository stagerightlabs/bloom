<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Account;

use StageRightLabs\Bloom\Horizon\AccountResource;
use StageRightLabs\Bloom\Horizon\Error;
use StageRightLabs\Bloom\Horizon\TransactionResourceCollection;
use StageRightLabs\Bloom\Keypair\Keypair;
use StageRightLabs\Bloom\Service;

/**
 * Tools for working with Stellar accounts. These methods can be accessed by the
 * `account` property on a bloom instance: `$bloom->account`:
 */
final class AccountService extends Service
{
    /**
     * Fetch the details of a single account from Horizon.
     *
     * Example:
     * ```php
     * $bloom = new Bloom();
     * $account = $bloom->account->retrieve('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
     * ```
     *
     * It is possible to instantiate an Account object without the Horizon data.
     * The `hasBeenLoaded()` method will tell you if the data is available:
     *
     * ```php
     * if ($account->hasBeenLoaded()) {
     *     // do something...
     * }
     * ```
     *
     * @see https://developers.stellar.org/api/resources/accounts/single/
     * @param Addressable|string $addressable
     * @return Account|Error
     */
    public function retrieve(Addressable|string $addressable): Account|Error
    {
        if ($addressable instanceof Wallet || $addressable instanceof Signatory) {
            $keypair = $addressable->canSign()
                ? Keypair::fromSeed($addressable->getSeed())
                : Keypair::fromAddress($addressable->getAddress());
        } elseif ($addressable instanceof Addressable) {
            $keypair = Keypair::fromAddress($addressable->getAddress());
        } else {
            $keypair = Keypair::fromAddress($addressable);
        }

        $response = $this->bloom->horizon->get(
            $this->bloom->config->getNetworkUrl("accounts/{$keypair->getAddress()}")
        );

        if ($response instanceof Error) {
            return $response;
        }

        return (new Account())
            ->withKeyPair($keypair)
            ->withAccountResource(AccountResource::fromResponse($response));
    }

    /**
     * Increment an account's sequence number. No changes will be made if the
     * current sequence number has not been loaded from Horizon.
     *
     * Example
     * ```php
     * $account = $bloom->account->incrementSequenceNumber($account);
     * $sequenceNumber = $account->getSequenceNumber();
     * $int = $sequenceNumber->toNativeInt();
     * $string = $sequenceNumber->toNativeString();
     * ```
     *
     * @param Account $account
     * @param int $bump Increment amount. The default is 1.
     *
     * @return Account
     */
    public function incrementSequenceNumber(Account $account, int $bump = 1): Account
    {
        $sequenceNumber = $account->getSequenceNumber();

        return $sequenceNumber
            ? $account->withSequenceNumber($sequenceNumber->increment($bump))
            : $account;
    }

    /**
     * Retrieve a paginated listing of an Account's transactions.
     *
     * Example
     * ```php
     * $account = $bloom->account->retrieve('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
     * $transactions = $bloom->account->transactions(account: $account, limit: 20, order: 'desc')
     * foreach ($transactions as $transaction) {
     *     // do something with the transaction resource object.
     * }
     * ```
     *
     * @see https://developers.stellar.org/api/resources/accounts/transactions/
     * @param Account|Addressable|string $account
     * @param string|null $cursor A number that points to a specific location in a collection of responses.
     * @param string $order The sort order for the transactions; either 'asc' or 'desc'. Defaults to ascending if no value is set.
     * @param int $limit The maximum number of records returned. Must be between 1 and 200; the default is 10.
     * @param bool $includeFailed When true, failed transactions will be included in the response. Default is false.
     * @return TransactionResourceCollection|Error
     */
    public function transactions(
        Account|Addressable|string $account,
        string $cursor = null,
        string $order = 'asc',
        int $limit = 10,
        bool $includeFailed = false,
    ): TransactionResourceCollection|Error {
        // Ensure we have a valid 'order' value
        if (!in_array($order, ['asc', 'desc'], true)) {
            $order = 'asc';
        }

        // Ensure we have a valid 'limit' value
        if ($limit < 1 || $limit > 200) {
            $limit = 10;
        }

        // Normalize the account
        $account = Account::fromAddress($account);

        // Build the request URL
        $url = $this->bloom->config->getNetworkUrl(
            "accounts/{$account->getAddress()}/transactions",
            [
                'cursor'         => $cursor,
                'order'          => $order,
                'limit'          => $limit,
                'include_failed' => $includeFailed
            ]
        );

        // Make the request
        $response = $this->bloom->horizon->get($url);

        // Did we get an error?
        if ($response instanceof Error) {
            return $response;
        }

        // Wrap the response in a transaction resource collection.
        return TransactionResourceCollection::fromResponse($response);
    }
}

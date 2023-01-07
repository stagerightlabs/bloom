<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Friendbot;

use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Exception\BloomException;
use StageRightLabs\Bloom\Horizon\Error;
use StageRightLabs\Bloom\Horizon\TransactionResource;
use StageRightLabs\Bloom\Service;

final class FriendbotService extends Service
{
    /**
     * Use friendbot to fund an account.
     *
     * @param Addressable|string $address
     * @throws BloomException
     * @return TransactionResource|Error
     */
    public function fund(Addressable|string $address): TransactionResource|Error
    {
        if (!$this->bloom->config->friendbotIsAllowed()) {
            throw new BloomException('Friendbot has been disabled; check your configuration settings.');
        }

        if ($address instanceof Addressable) {
            $address = $address->getAddress();
        }

        $url = $this->bloom->config->getFriendbotUrl(query: ['addr' => $address]);
        $response = $this->bloom->horizon->get($url);

        return (!$response instanceof Error)
            ? TransactionResource::fromResponse($response)
            : $response;
    }
}

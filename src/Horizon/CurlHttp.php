<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

use DateTime;
use StageRightLabs\Bloom\Exception\HttpException;

/**
 * @codeCoverageIgnore
 */
class CurlHttp implements Http
{
    /**
     * @var string
     */
    public const DEFAULT_LOGGING_PATH = __DIR__ . '/../../log';

    /**
     * @var string
     */
    protected $loggingPath;

    /**
     * @var bool
     */
    protected $loggingEnabled;

    /**
     * Instantiate a new class instance.
     *
     * @param bool $loggingEnabled
     * @param string $loggingPath
     */
    public function __construct(
        bool $loggingEnabled = false,
        string $loggingPath = null,
    ) {
        $this->loggingEnabled = $loggingEnabled;
        $this->loggingPath = $loggingPath ?? self::DEFAULT_LOGGING_PATH;
    }

    /**
     * Perform a GET request.
     *
     * @param string $url
     * @param array<string, mixed> $options - reserved for future use
     * @return Response
     */
    public function get(string $url, array $options = []): Response
    {
        // Initialize a cURL session
        $handle = curl_init();

        // Configure the cURL session
        curl_setopt_array($handle, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => true,
        ]);

        // Make the request
        $response = curl_exec($handle);

        // Close the cURL session
        curl_close($handle);

        // Check for cURL failure
        if (is_bool($response)) {
            throw new HttpException("cURL failed to retrieve '{$url}' - this may be a system level problem.");
        }

        // Maybe log the response
        if ($this->loggingEnabled) {
            $content = 'URL: ' . $url . "\r\n\r\n" . "====== RESPONSE ======\r\n\r\n" . $response;
            $this->log($content, 'get');
        }

        // Parse the response string
        $headerSize = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
        $rawHeaders = substr($response, 0, $headerSize);
        $statusCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        // Wrap the response content in a HorizonResponse object
        return new Response(
            // status code
            $statusCode,
            // headers
            HeaderBag::make($rawHeaders),
            // body
            substr($response, $headerSize),
            // timing
            curl_getinfo($handle, CURLINFO_TOTAL_TIME)
        );
    }

    /**
     * Perform a GET request.
     *
     * @param string $url
     * @param array<string, string> $payload
     * @param array<string, mixed> $options - reserved for future use
     * @return Response
     */
    public function post(string $url, array $payload = [], array $options = []): Response
    {
        // Initialize a cURL session
        $handle = curl_init();

        // Configure the cURL session
        curl_setopt_array($handle, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
        ]);

        // Make the request
        $response = curl_exec($handle);

        // Close the cURL session
        curl_close($handle);

        // Check for cURL failure
        if (is_bool($response)) {
            throw new HttpException("cURL failed to post to '{$url}' - this may be a system level problem.");
        }

        // Maybe log the response
        if ($this->loggingEnabled) {
            $content = 'URL: ' . $url
                . "\r\n\r\n====== REQUEST ======:\r\n\r\n"
                . json_encode($payload)
                . "\r\n\r\n====== RESPONSE ======\r\n\r\n"
                . $response;
            $this->log($content, 'post');
        }

        // Parse the response string
        $headerSize = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
        $rawHeaders = substr($response, 0, $headerSize);
        $statusCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        // Wrap the response content in a HorizonResponse object
        return new Response(
            // status code
            $statusCode,
            // headers
            HeaderBag::make($rawHeaders),
            // body
            substr($response, $headerSize),
            // timing
            curl_getinfo($handle, CURLINFO_TOTAL_TIME)
        );
    }

    /**
     * Write a log entry to disk.
     *
     * @param string $content
     * @param string $method
     * @return void
     */
    protected function log(string $content, string $method)
    {
        $path = realpath($this->loggingPath);
        if (is_dir($this->loggingPath) && is_string($path)) {
            $filename = (new DateTime())->format('YmdHis') . '_' . strtolower($method) . '.log';
            file_put_contents($path . '/' . $filename, $content);
        } else {
            \trigger_error("Invalid Bloom logging path: '{$this->loggingPath}'", E_USER_WARNING);
        }
    }
}

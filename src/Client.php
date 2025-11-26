<?php

namespace DazzaDev\DgiiSvSender;

use DazzaDev\DgiiSvSender\Exceptions\DGIIException;
use DazzaDev\DgiiSvSender\Exceptions\SenderException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;

abstract class Client
{
    /**
     * HTTP client instance
     */
    protected GuzzleClient $httpClient;

    /**
     * Whether to use test environment
     */
    protected bool $isTest = false;

    /**
     * NIT
     */
    protected string $nit = '';

    /**
     * API base URLs
     */
    private const API_URL_TEST = 'https://apitest.dtes.mh.gob.sv';

    private const API_URL_PROD = 'https://api.dtes.mh.gob.sv';

    /**
     * Bearer token for Authorization header (full value, e.g. "Bearer <token>")
     */
    protected ?string $bearerToken = null;

    /**
     * Constructor to initialize the HTTP client
     */
    public function __construct()
    {
        $this->httpClient = new GuzzleClient;
    }

    /**
     * Set the environment to test mode
     */
    public function setTestMode(bool $isTest = true): self
    {
        $this->isTest = $isTest;

        return $this;
    }

    /**
     * Set bearer token (expects full header value, e.g. "Bearer <token>")
     */
    public function setBearerToken(?string $bearerToken): self
    {
        $this->bearerToken = $bearerToken;

        return $this;
    }

    /**
     * Get bearer token (full header value)
     */
    public function getBearerToken(): ?string
    {
        return $this->bearerToken;
    }

    /**
     * Get whether test mode is enabled
     */
    public function isTestMode(): bool
    {
        return $this->isTest;
    }

    /**
     * Get environment code
     */
    public function getEnvironmentCode(): string
    {
        return $this->isTest ? '00' : '01';
    }

    /**
     * Get the API URL based on environment
     */
    public function getApiUrl(): string
    {
        return $this->isTest ? self::API_URL_TEST : self::API_URL_PROD;
    }

    /**
     * Set NIT
     */
    public function setNit(string $nit): self
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Get NIT
     */
    public function getNit(): string
    {
        return $this->nit;
    }

    /**
     * Ensure bearer token is present
     */
    protected function ensureBearerToken(): void
    {
        if (empty($this->bearerToken)) {
            throw new SenderException('Missing bearer token: autentica primero con Client::auth');
        }
    }

    /**
     * Wrap a request with consistent RequestException handling
     */
    protected function handleRequest(callable $request, string $errorContext): array
    {
        try {
            return $request();
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $errorBody = (string) $e->getResponse()->getBody();
                throw new DGIIException($errorContext.': '.$e->getResponse()->getStatusCode().' - '.$errorBody);
            }

            throw new DGIIException($e->getMessage());
        }
    }

    /**
     * Validate response and throw formatted exception on ERROR status
     */
    protected function checkErrorStatus(array $responseBody, string $context = 'DGII request failed'): void
    {
        $status = $responseBody['status'] ?? null;
        if (in_array($status, ['ERROR', 'RECHAZADO'], true)) {
            $errorMsg = $context;
            if (isset($responseBody['body']['descripcionMsg'])) {
                $errorMsg .= ': '.$responseBody['body']['descripcionMsg'];
            }
            if (isset($responseBody['body']['codigoMsg'])) {
                $errorMsg .= ' (Code: '.$responseBody['body']['codigoMsg'].')';
            }
            $errorMsg .= ' (Status: '.$status.')';
            throw new DGIIException($errorMsg);
        }
    }

    /**
     * POST Method
     */
    public function post(string $url, array $params, array $options = []): array
    {
        $options = array_merge([
            'json' => $params,
            'headers' => [
                'Authorization' => $this->bearerToken,
                'Content-Type' => 'application/json',
            ],
        ], $options);

        $response = $this->httpClient->post($url, $options);

        $responseBody = json_decode($response->getBody()->getContents(), true);

        // Validate response status
        // $this->checkErrorStatus($responseBody ?? []);

        return $responseBody ?? [];
    }

    /**
     * GET Method
     */
    public function get(string $url, array $options = []): array
    {
        $options = array_merge([
            'headers' => [
                'Authorization' => $this->bearerToken,
                'Content-Type' => 'application/json',
            ],
        ], $options);

        $response = $this->httpClient->get($url, $options);

        $responseBody = json_decode($response->getBody()->getContents(), true);

        // Validate response status
        // $this->checkErrorStatus($responseBody ?? []);

        return $responseBody ?? [];
    }
}

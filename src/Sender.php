<?php

namespace DazzaDev\DgiiSvSender;

use DazzaDev\DgiiSvSender\Actions\Auth;
use DazzaDev\DgiiSvSender\Actions\Contingency;
use DazzaDev\DgiiSvSender\Actions\Invalidate;
use DazzaDev\DgiiSvSender\Actions\Search;
use DazzaDev\DgiiSvSender\Actions\SearchBatch;
use DazzaDev\DgiiSvSender\Actions\Send;
use DazzaDev\DgiiSvSender\Actions\SendBatch;

class Sender extends Client
{
    /**
     * Authenticate using the Auth class
     */
    public function auth(string $user, string $password): string
    {
        $auth = new Auth;
        $auth->setTestMode($this->isTest);

        $token = $auth->auth($user, $password);
        $this->setBearerToken($token);

        return $token;
    }

    /**
     * Send Contingency event
     */
    public function contingencyEvent(string $json): array
    {
        $contingency = new Contingency;
        $contingency->setTestMode($this->isTest);
        $contingency->setBearerToken($this->bearerToken);
        $contingency->setNit($this->nit);

        return $contingency->contingencyEvent($json);
    }

    /**
     * Send DTE.
     */
    public function send(int $sendId, int $version, string $documentType, string $generationCode, string $signedJson): array
    {
        $send = new Send;
        $send->setTestMode($this->isTest);
        $send->setBearerToken($this->bearerToken);

        return $send->send($sendId, $version, $documentType, $generationCode, $signedJson);
    }

    /**
     * Send DTE Batch.
     */
    public function sendBatch(string $sendId, int $version, array $signedJsonDocuments): array
    {
        $sendBatch = new SendBatch;
        $sendBatch->setTestMode($this->isTest);
        $sendBatch->setBearerToken($this->bearerToken);
        $sendBatch->setNit($this->nit);

        return $sendBatch->sendBatch($sendId, $version, $signedJsonDocuments);
    }

    /**
     * Search DTE.
     */
    public function search(string $documentType, string $generationCode): array
    {
        $search = new Search;
        $search->setTestMode($this->isTest);
        $search->setBearerToken($this->bearerToken);
        $search->setNit($this->nit);

        return $search->search($documentType, $generationCode);
    }

    /**
     * Search DTE Batch.
     */
    public function searchBatch(string $batchCode): array
    {
        $searchBatch = new SearchBatch;
        $searchBatch->setTestMode($this->isTest);
        $searchBatch->setBearerToken($this->bearerToken);
        $searchBatch->setNit($this->nit);

        return $searchBatch->searchBatch($batchCode);
    }

    /**
     * Invalidate DTE.
     */
    public function invalidate(int $sendId, int $version, string $signedJson): array
    {
        $invalidate = new Invalidate;
        $invalidate->setTestMode($this->isTest);
        $invalidate->setBearerToken($this->bearerToken);

        return $invalidate->invalidate($sendId, $version, $signedJson);
    }
}

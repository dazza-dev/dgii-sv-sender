<?php

namespace DazzaDev\DgiiSvSender\Actions;

use DazzaDev\DgiiSvSender\Client;

class SearchBatch extends Client
{
    /**
     * Search a DTE batch.
     */
    public function searchBatch(string $batchCode): array
    {
        $this->ensureBearerToken();

        return $this->handleRequest(function () use ($batchCode) {
            return $this->get($this->getApiUrl().'/fesv/recepcion/consultadtelote/'.$batchCode);
        }, 'DTE batch search failed');
    }
}

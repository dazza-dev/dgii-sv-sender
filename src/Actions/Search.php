<?php

namespace DazzaDev\DgiiSvSender\Actions;

use DazzaDev\DgiiSvSender\Client;

class Search extends Client
{
    /**
     * Search a DTE.
     */
    public function search(string $documentType, string $generationCode): array
    {
        $this->ensureBearerToken();

        return $this->handleRequest(function () use ($documentType, $generationCode) {
            return $this->post($this->getApiUrl().'/fesv/recepcion/consultadte/', [
                'nitEmisor' => $this->getNit(),
                'tdte' => $documentType,
                'codigoGeneracion' => $generationCode,
            ]);
        }, 'DTE search failed');
    }
}

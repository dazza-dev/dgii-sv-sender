<?php

namespace DazzaDev\DgiiSvSender\Actions;

use DazzaDev\DgiiSvSender\Client;

class SendBatch extends Client
{
    /**
     * Send a DTE Batch.
     */
    public function sendBatch(string $sendId, int $version, array $signedJsonDocuments): array
    {
        $this->ensureBearerToken();

        return $this->handleRequest(function () use ($sendId, $version, $signedJsonDocuments) {
            return $this->post($this->getApiUrl().'/fesv/recepcionlote/', [
                'ambiente' => $this->getEnvironmentCode(),
                'idEnvio' => $sendId,
                'version' => $version,
                'nitEmisor' => $this->getNit(),
                'documentos' => $signedJsonDocuments,
            ]);
        }, 'DTE send failed');
    }
}

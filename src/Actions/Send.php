<?php

namespace DazzaDev\DgiiSvSender\Actions;

use DazzaDev\DgiiSvSender\Client;

class Send extends Client
{
    /**
     * Send a DTE.
     */
    public function send(int $sendId, int $version, string $documentType, string $generationCode, string $signedJson): array
    {
        $this->ensureBearerToken();

        return $this->handleRequest(function () use ($sendId, $version, $documentType, $generationCode, $signedJson) {
            return $this->post($this->getApiUrl().'/fesv/recepciondte', [
                'ambiente' => $this->getEnvironmentCode(),
                'idEnvio' => $sendId,
                'version' => $version,
                'tipoDte' => $documentType,
                'codigoGeneracion' => $generationCode,
                'documento' => $signedJson,
            ]);
        }, 'DTE send failed');
    }
}

<?php

namespace DazzaDev\DgiiSvSender\Actions;

use DazzaDev\DgiiSvSender\Client;

class Invalidate extends Client
{
    /**
     * Invalidate a DTE.
     */
    public function invalidate(int $sendId, int $version, string $signedJson): array
    {
        $this->ensureBearerToken();

        return $this->handleRequest(function () use ($sendId, $version, $signedJson) {
            return $this->post($this->getApiUrl().'/fesv/anulardte', [
                'ambiente' => $this->getEnvironmentCode(),
                'idEnvio' => $sendId,
                'version' => $version,
                'documento' => $signedJson,
            ]);
        }, 'DTE invalidate failed');
    }
}

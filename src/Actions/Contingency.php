<?php

namespace DazzaDev\DgiiSvSender\Actions;

use DazzaDev\DgiiSvSender\Client;

class Contingency extends Client
{
    /**
     * Send Contingency event
     */
    public function contingencyEvent(string $json): array
    {
        $this->ensureBearerToken();

        return $this->handleRequest(function () use ($json) {
            return $this->post($this->getApiUrl().'/fesv/contingencia', [
                'nit' => $this->getNit(),
                'documento' => $json,
            ]);
        }, 'Contingency event failed');
    }
}

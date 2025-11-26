<?php

namespace DazzaDev\DgiiSvSender\Actions;

use DazzaDev\DgiiSvSender\Client;
use DazzaDev\DgiiSvSender\Exceptions\AuthException;
use GuzzleHttp\Exception\GuzzleException;

class Auth extends Client
{
    /**
     * Authenticate
     */
    public function auth(string $user, string $password): string
    {
        try {
            $response = $this->httpClient->post($this->getApiUrl().'/seguridad/auth', [
                'form_params' => [
                    'user' => $user,
                    'pwd' => $password,
                ],
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
            ]);

            $responseBody = $response->getBody()->getContents();
            $json = json_decode($responseBody, true);

            // Check if the response has an error status
            if (isset($json['status']) && $json['status'] === 'ERROR') {
                $errorMsg = 'Auth failed';
                if (isset($json['body']['descripcionMsg'])) {
                    $errorMsg .= ': '.$json['body']['descripcionMsg'];
                }
                if (isset($json['body']['codigoMsg'])) {
                    $errorMsg .= ' (Code: '.$json['body']['codigoMsg'].')';
                }
                throw new AuthException($errorMsg);
            }

            // Check if we have a valid token
            if (isset($json['body']['token'])) {
                return $json['body']['token'];
            }

            throw new AuthException('Auth failed: '.($responseBody ?? 'no response'));
        } catch (GuzzleException $e) {
            throw new AuthException('Auth failed: '.$e->getMessage());
        }
    }
}

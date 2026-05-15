<?php

namespace App\Infrastructure\Auth\OAuth;

/**
 * Handles the Google OAuth 2.0 flow:
 *  - Builds the authorization URL (step A of the RFC 6749 diagram)
 *  - Exchanges the authorization code for an access token (step C-D)
 *  - Fetches the user profile from Google (step E-F)
 *
 * No password is stored here — authentication is fully delegated to Google.
 */
final class GoogleOAuthClient
{
    private string $clientId;
    private string $clientSecret;
    private string $redirectUri;

    private const AUTH_URL  = 'https://accounts.google.com/o/oauth2/v2/auth';
    private const TOKEN_URL = 'https://oauth2.googleapis.com/token';
    private const USER_URL  = 'https://www.googleapis.com/oauth2/v3/userinfo';

    public function __construct(array $config)
    {
        $this->clientId     = $config['client_id'];
        $this->clientSecret = $config['client_secret'];
        $this->redirectUri  = $config['redirect_uri'];
    }

    /**
     * Returns the Google authorization URL that the user must be redirected to.
     */
    public function getAuthorizationUrl(): string
    {
        $params = http_build_query([
            'client_id'     => $this->clientId,
            'redirect_uri'  => $this->redirectUri,
            'response_type' => 'code',
            'scope'         => 'openid email profile',
            'access_type'   => 'online',
        ]);

        return self::AUTH_URL . '?' . $params;
    }

    /**
     * Exchanges the authorization code (received on the callback) for a GoogleUser.
     *
     * @throws \RuntimeException when token exchange or user-info fetch fails.
     */
    public function getUserFromCode(string $code): GoogleUser
    {
        $accessToken = $this->exchangeCodeForToken($code);
        return $this->fetchUser($accessToken);
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    private function exchangeCodeForToken(string $code): string
    {
        $payload = http_build_query([
            'code'          => $code,
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri'  => $this->redirectUri,
            'grant_type'    => 'authorization_code',
        ]);

        $ch = curl_init(self::TOKEN_URL);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || $response === false) {
            throw new \RuntimeException('Google token exchange failed (HTTP ' . $httpCode . ')');
        }

        $data = json_decode($response, true);

        if (empty($data['access_token'])) {
            throw new \RuntimeException('No access_token in Google response');
        }

        return $data['access_token'];
    }

    private function fetchUser(string $accessToken): GoogleUser
    {
        $ch = curl_init(self::USER_URL);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $accessToken],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || $response === false) {
            throw new \RuntimeException('Failed to fetch Google user info (HTTP ' . $httpCode . ')');
        }

        $data = json_decode($response, true);

        return new GoogleUser(
            id:    $data['sub'],
            email: $data['email'],
            name:  $data['name']
        );
    }
}

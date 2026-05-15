<?php

namespace App\Infrastructure\Controller;

use App\Application\Auth\Login\LoginCommand;
use App\Application\Auth\Login\LoginHandler;
use App\Domain\Auth\AuthRepository;
use App\Infrastructure\Auth\OAuth\GoogleOAuthClient;
use App\Infrastructure\Auth\Persistence\DoctrineAuthRepository;
use App\Infrastructure\Auth\Token\JwtTokenGenerator;
use App\Infrastructure\Http\RequestAPI;
use App\Infrastructure\Http\ResponseJson;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Handles the two OAuth routes:
 *
 *  GET /auth/login    → redirects the user to Google's authorization page
 *  GET /auth/callback → receives the code from Google, issues our own JWT
 */
final class AuthController
{
    private GoogleOAuthClient $oauthClient;
    private LoginHandler $loginHandler;

    public function __construct(RequestAPI $request, EntityManagerInterface $em)
    {
        $config = require __DIR__ . '/../../../config/oauth.php';

        $this->oauthClient = new GoogleOAuthClient($config['google']);

        $repository   = new DoctrineAuthRepository($em);
        $tokenGen     = new JwtTokenGenerator($_ENV['JWT_SECRET'] ?? 'change_me_in_env');
        $this->loginHandler = new LoginHandler($repository, $tokenGen);
    }

    /**
     * GET /auth/login
     * Redirects the browser to Google's consent screen.
     */
    public function login(RequestAPI $request): void
    {
        $url = $this->oauthClient->getAuthorizationUrl();
        header('Location: ' . $url);
        exit;
    }

    /**
     * GET /auth/callback
     * Google redirects here with ?code=... after the user grants access.
     * We exchange the code, find-or-create the user, and return our JWT.
     */
    public function callback(RequestAPI $request): void
    {
        $code = $_GET['code'] ?? null;

        if ($code === null) {
            (new ResponseJson(400, 'Missing OAuth code'))->send();
        }

        try {
            $googleUser = $this->oauthClient->getUserFromCode($code);

            $command = new LoginCommand(
                googleId: $googleUser->id,
                email:    $googleUser->email,
                name:     $googleUser->name
            );

            $jwt = $this->loginHandler->handle($command);

            (new ResponseJson(200, 'Login correcte', ['token' => $jwt]))->send();

        } catch (\RuntimeException $e) {
            (new ResponseJson(500, 'OAuth error: ' . $e->getMessage()))->send();
        }
    }
}

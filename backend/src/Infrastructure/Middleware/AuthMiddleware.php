<?php

namespace App\Infrastructure\Middleware;

use App\Infrastructure\Auth\Token\TokenValidator;
use App\Infrastructure\Http\ResponseJson;

/**
 * Middleware that checks the Authorization: Bearer <token> header.
 *
 * Usage (inside any controller or in the router before dispatching):
 *
 *   AuthMiddleware::check();   // exits with 401 if token is missing or invalid
 *   $claims = AuthMiddleware::claims(); // returns the payload after a successful check
 */
final class AuthMiddleware
{
    private static ?array $claims = null;

    public static function check(): void
    {
        $secret = $_ENV['JWT_SECRET'] ?? 'change_me_in_env';
        $validator = new TokenValidator($secret);

        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        if (!str_starts_with($authHeader, 'Bearer ')) {
            (new ResponseJson(401, 'Unauthorized: missing token'))->send();
        }

        $token = substr($authHeader, 7);

        try {
            self::$claims = $validator->validate($token);
        } catch (\RuntimeException $e) {
            (new ResponseJson(401, 'Unauthorized: ' . $e->getMessage()))->send();
        }
    }

    /**
     * Returns the JWT payload after a successful AuthMiddleware::check() call.
     */
    public static function claims(): ?array
    {
        return self::$claims;
    }
}

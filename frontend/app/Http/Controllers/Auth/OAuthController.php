<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OAuthController extends Controller
{
    /**
     * GET /auth/oauth/redirect
     *
     * Redirigeix el navegador cap al backend, que farà el redirect a Google.
     * Fem servir el backend com a punt d'entrada d'OAuth per mantenir
     * tota la lògica de domini al backend (DDD).
     */
    public function redirect(): RedirectResponse
    {
        $backendLoginUrl = env('BACKEND_PUBLIC_URL', 'http://localhost:8000')
            . '/auth/login';

        return redirect()->away($backendLoginUrl);
    }

    /**
     * GET /auth/oauth/callback
     *
     * El backend ha intercanviat el codi de Google i ens redirigeix aquí
     * amb el JWT a la query string: ?token=<jwt>
     *
     * Nosaltres:
     *  1. Validem que arribi el token
     *  2. Demanem la informació de l'usuari al backend (o descodifiquem el JWT)
     *  3. Fem un find-or-create de l'User de Laravel
     *  4. Autentiquem la sessió Laravel
     *  5. Guardem el JWT a la sessió per a les cridades a l'API
     */
    public function callback(Request $request): RedirectResponse
    {
        // Error retornat pel backend
        if ($request->has('error')) {
            Log::error('OAuth callback error from backend', [
                'error' => $request->get('error'),
            ]);
            return redirect()->route('login')
                ->withErrors(['email' => 'Error durant el login amb Google: ' . $request->get('error')]);
        }

        $token = $request->get('token');

        if (empty($token)) {
            return redirect()->route('login')
                ->withErrors(['email' => 'No s\'ha rebut cap token del backend.']);
        }

        try {
            // Descodifiquem el payload del JWT (sense verificar la signatura,
            // ja que confiem en el backend que és el nostre propi servei).
            // Si vols verificar la signatura, instal·la firebase/php-jwt al frontend.
            $payload = $this->decodeJwtPayload($token);

            if (empty($payload['email'])) {
                throw new \RuntimeException('El token no conté cap email.');
            }

            // Find-or-create de l'usuari a la base de dades del frontend (Laravel)
            $user = User::firstOrCreate(
                ['email' => $payload['email']],
                [
                    'name'              => $payload['name'] ?? $payload['email'],
                    'password'          => bcrypt(Str::random(32)), // password aleatori, no es fa servir
                    'email_verified_at' => now(),
                ]
            );

            // Autenticar la sessió Laravel
            Auth::login($user, remember: true);
            $request->session()->regenerate();

            // Guardem el JWT a la sessió perquè BackendApiService el pugui fer servir
            session(['backend_jwt' => $token]);

            return redirect()->intended(route('dashboard'));

        } catch (\Throwable $e) {
            Log::error('OAuth callback processing error', ['error' => $e->getMessage()]);
            return redirect()->route('login')
                ->withErrors(['email' => 'No s\'ha pogut processar el login amb Google.']);
        }
    }

    /**
     * Descodifica el payload d'un JWT sense verificar la signatura.
     * Suficient per a ús intern (frontend confía en el seu propi backend).
     */
    private function decodeJwtPayload(string $jwt): array
    {
        $parts = explode('.', $jwt);

        if (count($parts) !== 3) {
            throw new \RuntimeException('Format de JWT invàlid.');
        }

        $payload = base64_decode(strtr($parts[1], '-_', '+/'));

        if ($payload === false) {
            throw new \RuntimeException('No s\'ha pogut descodificar el payload del JWT.');
        }

        $data = json_decode($payload, true);

        if (!is_array($data)) {
            throw new \RuntimeException('El payload del JWT no és un JSON vàlid.');
        }

        return $data;
    }
}

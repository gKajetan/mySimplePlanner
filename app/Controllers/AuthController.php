<?php

namespace App\Controllers;

use App\Services\AuthService;
use Exception;

class AuthController extends ApiController
{
    private $authService;

    public function __construct()
    {
        parent::__construct();
        // Kontroler tworzy instancję serwisu
        $this->authService = new AuthService();
    }

    /**
     * Obsługuje żądanie logowania.
     */
    public function handleLogin()
    {
        try {
            // 1. Pobierz dane HTTP
            $data = $this->getJsonInput();
            $name = $data['name'] ?? '';
            $password = $data['password'] ?? '';

            // 2. Wywołaj logikę biznesową (Serwis)
            $tokens = $this->authService->login($name, $password);

            // 3. Obsłuż odpowiedź HTTP
            // ZMIANA: refreshToken wysyłamy w JSON, usunięto setRefreshTokenCookie
            $this->sendJsonResponse([
                'success' => true,
                'message' => 'Login successful',
                'token' => $tokens['accessToken'],
                'refreshToken' => $tokens['refreshToken'] // Dodane pole w odpowiedzi
            ], 200);

        } catch (Exception $e) {
            // 4. Obsłuż błędy z serwisu
            $this->sendJsonResponse(
                ['success' => false, 'message' => $e->getMessage()], 
                $e->getCode() ?: 401
            );
        }
    }

    /**
     * Obsługuje żądanie odświeżenia tokena.
     */
    public function handleRefresh()
    {
        try {
            // 1. Pobierz dane HTTP
            // ZMIANA: refreshToken w body (JSON), a nie w ciasteczku
            $data = $this->getJsonInput();
            $refreshToken = $data['refreshToken'] ?? '';
            
            // 2. Wywołaj logikę biznesową (Serwis)
            $newAccessToken = $this->authService->refresh($refreshToken);

            // 3. Obsłuż odpowiedź HTTP
            $this->sendJsonResponse([
                'success' => true,
                'token' => $newAccessToken
            ], 200);

        } catch (Exception $e) {
            // 4. Obsłuż błędy
            $this->sendJsonResponse(
                ['success' => false, 'message' => $e->getMessage()], 
                $e->getCode() ?: 401
            );
        }
    }

    /**
     * Obsługuje żądanie wylogowania.
     */
    public function handleLogout()
    {
        try {
            // 1. Pobierz dane HTTP
            // ZMIANA: Oczekujemy refreshToken w body (JSON)
            $data = $this->getJsonInput();
            $refreshToken = $data['refreshToken'] ?? '';
            
            // 2. Wywołaj logikę biznesową (Serwis)
            $this->authService->logout($refreshToken);

            // 3. Obsłuż odpowiedź HTTP
            // Usunięto clearRefreshTokenCookie
            $this->sendJsonResponse(['success' => true, 'message' => 'Logged out'], 200);

        } catch (Exception $e) {
            // 4. Obsłuż błędy
            $this->sendJsonResponse(
                ['success' => false, 'message' => $e->getMessage()], 
                $e->getCode() ?: 500
            );
        }
    }
    
    /**
     * Obsługuje żądanie rejestracji.
     */
    public function handleRegistration()
    {
        try {
            // 1. Pobierz dane HTTP
            $data = $this->getJsonInput();
            $name = $data['name'] ?? '';
            $password = $data['password'] ?? '';

            // 2. Wywołaj logikę biznesową (Serwis)
            $this->authService->register($name, $password);

            // 3. Obsłuż odpowiedź HTTP
            $this->sendJsonResponse(['success' => true, 'message' => 'Registration successful'], 201);

        } catch (Exception $e) {
            // 4. Obsłuż błędy
            $this->sendJsonResponse(
                ['success' => false, 'message' => $e->getMessage()], 
                $e->getCode() ?: 400
            );
        }
    }
}
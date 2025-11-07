<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class ApiController
{
    protected $userId;

    public function __construct()
    {
        // Konstruktor jest teraz pusty - nie uruchamiamy sesji
    }

    /**
     * Wymusza autentykację dla endpointu za pomocą tokena JWT.
     */
    protected function checkAuthentication()
    {
        $secretKey = getenv('JWT_SECRET'); // Potrzebujesz tego w .env!
        if (!$secretKey) {
            $this->sendJsonResponse(['error' => 'JWT secret not configured on server'], 500);
            exit;
        }

        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        
        if (empty($authHeader) || !preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {
            $this->sendJsonResponse(['error' => 'Authorization header missing or invalid'], 401);
            exit;
        }

        $token = $matches[1];

        try {
            // Dekoduj token
            $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
            
            // Token jest poprawny, wyodrębnij ID użytkownika z payloadu
            $this->userId = $decoded->data->userId;

        } catch (Exception $e) {
            // Błąd (np. token wygasł, zły podpis)
            $this->sendJsonResponse(['error' => 'Unauthorized: ' . $e->getMessage()], 401);
            exit;
        }
    }

    /**
     * Pobiera dane wejściowe JSON z ciała żądania (bez zmian).
     * @return array|null
     */
    protected function getJsonInput(): ?array
    {
        $input = file_get_contents('php://input');
        return json_decode($input, true);
    }

    /**
     * Wysyła odpowiedź w formacie JSON (bez zmian).
     */
    protected function sendJsonResponse($data, int $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
    }
}
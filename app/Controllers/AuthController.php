<?php

namespace App\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;

class AuthController extends ApiController
{
    // Przetwarza dane logowania z JSON i ZWRACA TOKEN
    public function handleLogin()
    {
        $data = $this->getJsonInput();
        $name = $data['name'] ?? '';
        $password = $data['password'] ?? '';

        $userModel = new User();
        $user = $userModel->findByName($name);

        if ($user && password_verify($password, $user['password'])) {
            // Hasło poprawne - generuj token JWT
            
            $secretKey = getenv('JWT_SECRET'); // Ten sam sekret co w ApiController
            $issuedAt = time();
            $expirationTime = $issuedAt + 3600; // Token ważny przez 1 godzinę
            
            $payload = [
                'iat' => $issuedAt,
                'exp' => $expirationTime,
                'data' => [
                    'userId' => $user['id'],
                    'userName' => $user['name']
                ]
            ];

            $token = JWT::encode($payload, $secretKey, 'HS256');

            // Zwróć token klientowi
            $this->sendJsonResponse([
                'success' => true,
                'message' => 'Login successful',
                'token' => $token
            ], 200);
            
        } else {
            $this->sendJsonResponse(['success' => false, 'message' => 'Invalid name or password'], 401);
        }
    }

    // Funkcja logout() została usunięta.

    // Przetwarza dane rejestracji z JSON (bez zmian)
    public function handleRegistration()
    {
        $data = $this->getJsonInput();
        $name = $data['name'] ?? '';
        $password = $data['password'] ?? '';

        if (empty($name) || empty($password)) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Name and password are required'], 400);
            return;
        }

        $userModel = new User();

        if ($userModel->findByName($name)) {
            $this->sendJsonResponse(['success' => false, 'message' => 'User with this name already exists'], 409); // 409 Conflict
            return;
        }

        $success = $userModel->create($name, $password);

        if ($success) {
            $this->sendJsonResponse(['success' => true, 'message' => 'Registration successful'], 201); // 201 Created
        } else {
            $this->sendJsonResponse(['success' => false, 'message' => 'Error during registration'], 500);
        }
    }
}
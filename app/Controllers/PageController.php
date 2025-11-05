<?php

namespace App\Controllers;
use App\Models\Task; // Dodaj import modelu Task

class PageController 
{
    // main page

    public function main() 
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /");
            exit;
        }

        // POBIOERZ dane użytkownika i jego zadania
        $username = $_SESSION['user_name'];
        $userId = $_SESSION['user_id'];
        
        $taskModel = new Task();
        $tasks = $taskModel->findByUserId($userId);
        
        // Przekazanie zmiennych
        $this->renderView('main', [
            'username' => $username,
            'tasks' => $tasks
        ]);
    }

    // widok
    protected function renderView(string $viewName, array $data = [])
    {
        extract($data);
        require_once __DIR__ . "/../Views/{$viewName}.php";
    }
}
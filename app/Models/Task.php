<?php

namespace App\Models;

class Task
{
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    // Znajdź zadania dla konkretnego użytkownika.
    public function findByUserId(int $userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY due_date ASC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // Znajdź jedno zadanie po jego ID.
    public function findById(int $taskId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE id = ?");
        $stmt->execute([$taskId]);
        return $stmt->fetch();
    }

    // Sprawdź, czy zadanie należy do użytkownika (ze względów bezpieczeństwa).
    public function isOwner(int $taskId, int $userId): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM tasks WHERE id = ? AND user_id = ?");
        $stmt->execute([$taskId, $userId]);
        return (bool) $stmt->fetchColumn();
    }


    //Stwórz nowe zadanie.
    public function create(int $userId, string $title, string $description, int $priority, string $dueDate): int|false
    {
        $sql = "INSERT INTO tasks (user_id, title, description, priority, due_date) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $success = $stmt->execute([$userId, $title, $description, $priority, $dueDate]);

        if ($success) {
            // Zwróć ID ostatnio wstawionego wiersza
            return (int) $this->pdo->lastInsertId();
        } else {
            return false;
        }
    }

    // Zaktualizuj istniejące zadanie.

    public function update(int $taskId, string $title, string $description, int $priority, string $dueDate): bool
    {
        $sql = "UPDATE tasks SET title = ?, description = ?, priority = ?, due_date = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$title, $description, $priority, $dueDate, $taskId]);
    }

    // Usuń zadanie.
    public function delete(int $taskId): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE id = ?");
        return $stmt->execute([$taskId]);
    }
}
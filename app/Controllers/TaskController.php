<?php

namespace App\Controllers;

use App\Models\Task;

class TaskController extends PageController
{
    private $taskModel;
    private $userId;

    public function __construct()
    {
        // Upewnij się, że użytkownik jest zalogowany
        if (!isset($_SESSION['user_id'])) {
            header("Location: /");
            exit;
        }
        
        $this->taskModel = new Task();
        $this->userId = $_SESSION['user_id'];
    }

    // Tworzy nowe zadanie.
    public function create()
    {
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $priority = (int) ($_POST['priority'] ?? 1);
        $dueDate = $_POST['due_date'] ?? '';

        if (!empty($title) && !empty($dueDate)) {
            $this->taskModel->create($this->userId, $title, $description, $priority, $dueDate);
        }
        
        header("Location: /main");
        exit;
    }

    // Formularz edycji zadania
    public function showEditForm()
    {
        $taskId = (int) ($_GET['id'] ?? 0);
        
        // Sprawdź, czy użytkownik jest właścicielem zadania
        if (!$this->taskModel->isOwner($taskId, $this->userId)) {
            header("Location: /main");
            exit;
        }

        $task = $this->taskModel->findById($taskId);
        $this->renderView('edit_task', ['task' => $task]);
    }

    // Aktualizuacja zadania
    public function update()
    {
        $taskId = (int) ($_POST['task_id'] ?? 0);
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $priority = (int) ($_POST['priority'] ?? 1);
        $dueDate = $_POST['due_date'] ?? '';

        // Sprawdź, czy użytkownik jest właścicielem
        if ($taskId && !empty($title) && !empty($dueDate)) {
            if ($this->taskModel->isOwner($taskId, $this->userId)) {
                $this->taskModel->update($taskId, $title, $description, $priority, $dueDate);
            }
        }

        header("Location: /main");
        exit;
    }

    // Usuwanie zadania
    public function delete()
    {
        $taskId = (int) ($_POST['task_id'] ?? 0);

        // Sprawdź, czy użytkownik jest właścicielem
        if ($taskId && $this->taskModel->isOwner($taskId, $this->userId)) {
            $this->taskModel->delete($taskId);
        }
        
        header("Location: /main");
        exit;
    }
}
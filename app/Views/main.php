<?php
// gwiazdki priorytetu
function renderPriorityStars($priority) {
    $html = '';
    for ($i = 1; $i <= 5; $i++) {
        $html .= ($i <= $priority) ? '&#9733;' : '&#9734;';
    }
    return $html;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Twój Planner</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

    <div class="container">
        <div class="header">
            <h2>Witaj, <?= htmlspecialchars($username) ?>!</h2>
            <a href="/logout">Wyloguj</a>
        </div>

        <div class="add-task-form">
            <h2>Dodaj nowe zadanie</h2>
            <form action="/task/create" method="POST">
                <div>
                    <label for="title">Tytuł:</label>
                    <input type="text" name="title" id="title" required>
                </div>
                <div>
                    <label for="description">Opis:</label>
                    <textarea name="description" id="description" rows="3"></textarea>
                </div>
                <div>
                    <label for="due_date">Termin wykonania:</label>
                    <input type="datetime-local" name="due_date" id="due_date" required>
                </div>
                <div>
                    <label for="priority">Priorytet (1-5):</label>
                    <select name="priority" id="priority">
                        <option value="1">1 (Najniższy)</option>
                        <option value="2">2</option>
                        <option value="3" selected>3 (Normalny)</option>
                        <option value="4">4</option>
                        <option value="5">5 (Najwyższy)</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Dodaj zadanie</button>
            </form>
        </div>

        <div class="task-list">
            <h2>Twoje zadania</h2>
            
            <?php if (empty($tasks)): ?>
                <p>Nie masz żadnych zadań. Dodaj pierwsze!</p>
            <?php else: ?>
                <?php foreach ($tasks as $task): ?>
                    <div class="task-item">
                        <h3><?= htmlspecialchars($task['title']) ?></h3>
                        <p><?= htmlspecialchars($task['description']) ?></p>

                        <div class="task-meta">
                            <div class="task-time" 
                                 id="countdown-<?= $task['id'] ?>" 
                                 data-due-date="<?= $task['due_date'] ?>">
                                </div>
                            <div class="task-priority">
                                <?= renderPriorityStars($task['priority']) ?>
                            </div>
                        </div>

                        <div class="task-actions">
                            <a href="/task/edit?id=<?= $task['id'] ?>" class="btn btn-primary">Edytuj</a>
                            
                            <form action="/task/delete" method="POST" onsubmit="return confirm('Czy na pewno chcesz usunąć to zadanie?');">
                                <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                                <button type="submit" class="btn btn-danger">Usuń</button>
                            </form>
                        </div>
                        </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>

    <script>
        function updateCountdowns() {
            const now = new Date();
            const countdownElements = document.querySelectorAll('[data-due-date]');
            
            countdownElements.forEach(element => {
                const dueDate = new Date(element.dataset.dueDate);
                const diffMs = dueDate - now;

                if (diffMs <= 0) {
                    element.innerHTML = "Po terminie";
                    element.style.color = "red";
                } else {
                    const diff = {};
                    diff.days = Math.floor(diffMs / (1000 * 60 * 60 * 24));
                    diff.hours = Math.floor((diffMs % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    diff.minutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
                    
                    let output = "";
                    if (diff.days > 0) output += `${diff.days}d `;
                    if (diff.hours > 0) output += `${diff.hours}h `;
                    if (diff.minutes > 0) output += `${diff.minutes}m`;
                    
                    element.innerHTML = output.trim() || "0m";
                }
            });
        }
        updateCountdowns();
        setInterval(updateCountdowns, 60000);
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Edytuj Zadanie</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Edytuj zadanie</h1>

        <?php if (isset($task)): ?>
            <form action="/task/update" method="POST">
                <input type="hidden" name="task_id" value="<?= $task['id'] ?>">

                <div>
                    <label for="title">Tytuł:</label>
                    <input type="text" name="title" id="title" value="<?= htmlspecialchars($task['title']) ?>" required>
                </div>
                <div>
                    <label for="description">Opis:</label>
                    <textarea name="description" id="description" rows="3"><?= htmlspecialchars($task['description']) ?></textarea>
                </div>
                <div>
                    <label for="due_date">Termin wykonania:</label>
                    <input type="datetime-local" name="due_date" id="due_date" 
                           value="<?= date('Y-m-d\TH:i', strtotime($task['due_date'])) ?>" required>
                </div>
                <div>
                    <label for="priority">Priorytet (1-5):</label>
                    <select name="priority" id="priority">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?= $i ?>" <?= ($i == $task['priority']) ? 'selected' : '' ?>>
                                <?= $i ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
            </form>
            <p><a href="/main">Anuluj i wróć do listy</a></p>
        <?php else: ?>
            <p>Nie znaleziono zadania.</p>
        <?php endif; ?>
    </div>
</body>
</html>
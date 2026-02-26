<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Nova Assignatura</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; padding: 30px; background: #f4f4f4; }
        .form-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 500px; margin: auto; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn-submit { background: #4CAF50; color: white; padding: 12px; border: none; border-radius: 4px; width: 100%; cursor: pointer; font-weight: bold; }
        .back-link { display: block; margin-top: 20px; text-align: center; color: #666; text-decoration: none; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>📚 Nova Assignatura</h2>
        <form action="/subject/store" method="POST">
            <div class="form-group">
                <label>ID:</label>
                <input type="text" name="id" placeholder="Ex: SUBJ-01" required>
            </div>
            <div class="form-group">
                <label>Nom de l'Assignatura:</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Assignar Professor (Opcional):</label>
                <select name="teacher_id">
                    <option value="">-- Cap professor --</option>
                    <?php foreach ($teachers as $teacher): ?>
                        <option value="<?= htmlspecialchars($teacher->id()->value()) ?>">
                            <?= htmlspecialchars($teacher->name()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn-submit">Crear Assignatura</button>
        </form>
        <a href="/subject" class="back-link">⬅ Cancel·lar</a>
    </div>
</body>
</html>
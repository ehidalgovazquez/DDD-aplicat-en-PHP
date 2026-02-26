<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Editar Assignatura</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; padding: 30px; background: #f4f4f4; }
        .form-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 500px; margin: auto; border-top: 5px solid #ef6c00; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .readonly { background-color: #eee; cursor: not-allowed; }
        .btn-submit { background: #ef6c00; color: white; padding: 12px; border: none; border-radius: 4px; width: 100%; cursor: pointer; font-weight: bold; }
        .back-link { display: block; margin-top: 20px; text-align: center; color: #666; text-decoration: none; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>✏️ Editar Assignatura</h2>
        <form action="/subject/update" method="POST">
            <div class="form-group">
                <label>ID (No editable):</label>
                <input type="text" name="id" value="<?= htmlspecialchars($subject->id()->value()) ?>" readonly class="readonly">
            </div>
            <div class="form-group">
                <label>Nom:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($subject->name()) ?>" required>
            </div>
            <div class="form-group">
                <label>Professor:</label>
                <select name="teacher_id">
                    <option value="">-- Sense professor --</option>
                    <?php foreach ($teachers as $teacher): ?>
                        <option value="<?= htmlspecialchars($teacher->id()->value()) ?>"
                            <?= ($subject->teacherId() && $subject->teacherId()->value() === $teacher->id()->value()) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($teacher->name()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn-submit">Guardar Canvis</button>
        </form>
        <a href="/subject" class="back-link">⬅ Tornar</a>
    </div>
</body>
</html>
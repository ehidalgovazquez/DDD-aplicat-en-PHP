<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Editar Curs</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; padding: 30px; background: #f4f4f4; }
        .form-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 500px; margin: auto; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .input-readonly { background-color: #eee; color: #666; cursor: not-allowed; }
        .btn-submit { background: #ef6c00; color: white; padding: 12px; border: none; border-radius: 4px; width: 100%; cursor: pointer; font-size: 1em; font-weight: bold; }
        .back-link { display: block; margin-top: 20px; text-align: center; color: #666; text-decoration: none; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>✏️ Editar Curs</h2>
        <form action="/course/update" method="POST">
            <div class="form-group">
                <label>ID del Curs (No editable):</label>
                <input type="text" name="id" value="<?= htmlspecialchars($course->id()->value()) ?>" readonly class="input-readonly">
            </div>
            <div class="form-group">
                <label>Nom del Curs:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($course->name()) ?>" required>
            </div>
            <button type="submit" class="btn-submit">Actualitzar Curs</button>
        </form>
        <a href="/course" class="back-link">⬅ Cancel·lar i tornar</a>
    </div>
</body>
</html>
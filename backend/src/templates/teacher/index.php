<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Gestió de Professors</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; padding: 30px; line-height: 1.6; background-color: #f4f7f6; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .btn-add { background: #b30089; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; font-weight: bold; }
        .alert { padding: 15px; margin: 20px 0; border-radius: 4px; color: white; font-weight: bold; }
        .alert-error { background: #d32f2f; }
        .alert-success { background: #388e3c; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-top: 30px; }
        .card { border: 1px solid #ddd; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); background: #fff; border-left: 5px solid #b30089; position: relative; }
        .card-id { font-size: 0.85em; color: #b30089; font-weight: bold; display: block; }
        .card-name { font-size: 1.4em; color: #333; display: block; margin: 10px 0; font-weight: bold; }
        .card-email { font-size: 0.9em; color: #666; font-style: italic; display: block; margin-bottom: 10px; }
        .actions { margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #eee; padding-top: 15px; }
        .btn-edit { background: #ef6c00; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-size: 0.9em; font-weight: bold; }
        .btn-delete { background: #d32f2f; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-family: inherit; font-size: 0.9em; font-weight: bold; }
        .back-link { display: inline-block; margin-top: 30px; text-decoration: none; color: #555; }
    </style>
</head>
<body>
    <div class="header">
        <h1>👨‍🏫 Gestió de Professors</h1>
        <a href="/teacher/create" class="btn-add">➕ Add Teacher</a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <div class="grid">
        <?php foreach ($teachers as $teacher): ?>
            <div class="card">
                <span class="card-id">ID: <?= htmlspecialchars($teacher->id()->value()) ?></span>
                <span class="card-name"><?= htmlspecialchars($teacher->name()) ?></span>
                <span class="card-email">📧 <?= htmlspecialchars($teacher->email()) ?></span>
                
                <div class="actions">
                    <a href="/teacher/edit?id=<?= urlencode($teacher->id()->value()) ?>" class="btn-edit">✏️ Editar</a>
                    <form action="/teacher/delete" method="POST" onsubmit="return confirm('Eliminar aquest professor?');">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($teacher->id()->value()) ?>">
                        <button type="submit" class="btn-delete">🗑️ Eliminar</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <a href="/" class="back-link">⬅ Tornar al Menú</a>
</body>
</html>
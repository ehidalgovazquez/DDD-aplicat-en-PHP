<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Gestió d'Assignatures</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; padding: 30px; line-height: 1.6; background-color: #f4f7f6; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .btn-add { background: #673AB7; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; font-weight: bold; }
        .alert { padding: 15px; margin: 20px 0; border-radius: 4px; color: white; font-weight: bold; }
        .alert-error { background: #d32f2f; }
        .alert-success { background: #388e3c; }
        .subject-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-top: 30px; }
        .subject-card { border: 1px solid #ddd; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); background: #fff; border-left: 5px solid #673AB7; position: relative; }
        .subject-name { font-size: 1.4em; color: #333; display: block; margin: 10px 0; font-weight: bold; }
        small { font-size: 0.85em; color: #673AB7; font-weight: bold; }
        .teacher-box { background: #f9f9f9; padding: 12px; border-radius: 4px; border: 1px solid #eee; margin: 15px 0; }
        .teacher-box label { display: block; font-size: 0.8em; font-weight: bold; margin-bottom: 5px; color: #666; text-transform: uppercase; }
        .teacher-select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; background: white; cursor: pointer; font-family: inherit; }
        .actions { margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #eee; padding-top: 15px; }
        .btn-action { background: #ef6c00; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-size: 0.9em; font-weight: bold; }
        .btn-danger { background: #d32f2f; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-family: inherit; font-size: 0.9em; font-weight: bold; }
        .back-link { display: inline-block; margin-top: 30px; text-decoration: none; color: #555; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📚 Gestió de Subjects</h1>
        <a href="/subject/create" class="btn-add">➕ Nova Assignatura</a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <div class="subject-grid">
        <?php if (empty($subjects)): ?>
            <p>No hi ha assignatures creades.</p>
        <?php else: ?>
            <?php foreach ($subjects as $s): ?>
                <div class="subject-card">
                    <small>ID: <?= htmlspecialchars($s->id()->value()) ?></small>
                    <span class="subject-name"><?= htmlspecialchars($s->name()) ?></span>

                    <div class="teacher-box">
                        <form action="/subject/assign" method="POST">
                            <input type="hidden" name="subject_id" value="<?= htmlspecialchars($s->id()->value()) ?>">
                            <label>Professor Titular:</label>
                            <select name="teacher_id" class="teacher-select" onchange="this.form.submit()">
                                <option value="">-- Sense professor --</option>
                                <?php foreach ($teachers as $t): ?>
                                    <option value="<?= htmlspecialchars($t->id()->value()) ?>" 
                                        <?= ($s->teacherId() && $s->teacherId()->value() === $t->id()->value()) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($t->name()) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>

                    <div class="actions">
                        <a href="/subject/edit?id=<?= urlencode($s->id()->value()) ?>" class="btn-action">✏️ Editar</a>
                        <form action="/subject/delete" method="POST" onsubmit="return confirm('Segur que vols eliminar aquesta assignatura?');">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($s->id()->value()) ?>">
                            <button type="submit" class="btn-danger">🗑️ Eliminar</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <a href="/" class="back-link">⬅ Tornar al Menú Principal</a>
</body>
</html>
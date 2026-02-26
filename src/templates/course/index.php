<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Gestió de Cursos</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; padding: 30px; line-height: 1.6; background-color: #f4f7f6; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .btn-add { background: #0056b3; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; font-weight: bold; }
        .alert { padding: 15px; margin: 20px 0; border-radius: 4px; color: white; font-weight: bold; }
        .alert-error { background: #d32f2f; }
        .alert-success { background: #388e3c; }
        .course-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-top: 30px; }
        .course-card { border: 1px solid #ddd; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); background: #fff; border-left: 5px solid #0056b3; display: flex; flex-direction: column; position: relative; }
        .course-code { font-size: 0.85em; color: #0056b3; font-weight: bold; text-transform: uppercase; margin-bottom: 5px; display: block; }
        .course-name { font-size: 1.4em; color: #333; margin-bottom: 10px; display: block; font-weight: bold; }
        .course-details { background: #f9f9f9; padding: 12px; border-radius: 4px; border: 1px solid #eee; font-size: 0.9em; color: #666; flex-grow: 1; }
        .actions { margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #eee; padding-top: 15px; }
        .btn-edit { background: #ef6c00; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-size: 0.9em; font-weight: bold; }
        .btn-delete { background: #d32f2f; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-family: inherit; font-size: 0.9em; font-weight: bold; }
        .back-link { display: inline-block; margin-top: 30px; text-decoration: none; color: #555; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Gestió de Courses</h1>
        <a href="/course/create" class="btn-add">➕ Add Course</a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <div class="course-grid">
        <?php foreach ($courses as $course): ?>
            <div class="course-card">
                <span class="course-code">ID: <?= htmlspecialchars($course->id()->value()) ?></span>
                <span class="course-name"><?= htmlspecialchars($course->name()) ?></span>
                
                <div class="course-details">
                    <span>Aquest curs està actiu al sistema.</span>
                </div>

                <div class="actions">
                    <a href="/course/edit?id=<?= urlencode($course->id()->value()) ?>" class="btn-edit">✏️ Editar</a>

                    <form action="/course/delete" method="POST" onsubmit="return confirm('Segur que vols eliminar aquest curs?');">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($course->id()->value()) ?>">
                        <button type="submit" class="btn-delete">🗑️ Eliminar</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <a href="/" class="back-link">⬅ Tornar al Menú Principal</a>

</body>
</html>
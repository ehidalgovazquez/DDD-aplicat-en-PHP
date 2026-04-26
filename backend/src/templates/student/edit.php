<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Editar Alumne</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; padding: 30px; background: #f4f4f4; }
        .form-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 500px; margin: auto; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .input-readonly { background-color: #eee; color: #666; cursor: not-allowed; border: 1px solid #ccc; }
        .btn-submit { background: #ef6c00; color: white; padding: 12px; border: none; border-radius: 4px; width: 100%; cursor: pointer; font-size: 1em; font-weight: bold; }
        .btn-submit:hover { background: #e65100; } 
        .back-link { display: block; margin-top: 20px; text-align: center; color: #666; text-decoration: none; }
        .alert-error { background-color: #ffebee; color: #c62828; padding: 15px; border-radius: 4px; border-left: 5px solid #c62828; margin-bottom: 20px; }
        .info-text { font-size: 0.85em; color: #888; margin-top: 5px; display: block; }
    </style>
</head>
<body>
    
    <div class="form-container">
        <h2>✏️ Editar Alumne</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert-error">
                ⚠️ <?= $_SESSION['error']; ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="/student/update" method="POST">
            <div class="form-group">
                <label>ID Alumne (No editable):</label>
                <input type="text" name="id" value="<?= htmlspecialchars($student->id()->value()) ?>" readonly class="input-readonly">
            </div>

            <div class="form-group">
                <label>Nom Complet:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($student->name()) ?>" required placeholder="Nom de l'estudiant">
            </div>

            <div class="form-group">
                <label>Correu Electrònic:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($student->email()) ?>" required placeholder="alumne@escoladirecta.cat">
            </div>

            <div class="form-group">
                <label>Curs Matriculat:</label>
                <select name="course_id">
                    <option value="">-- No matricular encara / Cap curs --</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?= $course->id()->value() ?>" 
                            <?= ($student->courseId() && $student->courseId()->value() === $course->id()->value()) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($course->name()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="info-text">Pots canviar el curs de l'estudiant seleccionant-ne un de nou.</span>
            </div>

            <button type="submit" class="btn-submit">Actualitzar Dades</button>
        </form>

        <a href="/student" class="back-link">⬅ Cancel·lar i tornar</a>
    </div>

</body>
</html>
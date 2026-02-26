<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Gestió d'Alumnes</title>
    <style>
        /* Referència directa a l'estil unificat del projecte */
        body { font-family: 'Segoe UI', Tahoma, sans-serif; padding: 30px; line-height: 1.6; background-color: #f4f7f6; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .btn-add { background: #4CAF50; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; font-weight: bold; }
        
        /* Alertes coherents */
        .alert { padding: 15px; margin: 20px 0; border-radius: 4px; color: white; font-weight: bold; }
        .alert-error { background: #d32f2f; }
        .alert-success { background: #388e3c; }

        /* Grid i Cards adaptats */
        .student-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; margin-top: 30px; }
        .student-card { border: 1px solid #ddd; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); background: #fff; border-left: 5px solid #4CAF50; position: relative; }
        
        .student-id { font-size: 0.85em; color: #4CAF50; font-weight: bold; display: block; }
        .student-name { font-size: 1.4em; color: #333; margin: 5px 0; font-weight: bold; display: block; }
        .student-email { color: #666; font-size: 0.9em; display: block; margin-bottom: 15px; font-style: italic; }
        
        /* Informació de curs */
        .course-info { margin: 15px 0; background: #f9f9f9; padding: 10px; border-radius: 4px; border: 1px solid #eee; }
        .course-badge { background: #e8f5e9; color: #2e7d32; padding: 4px 8px; border-radius: 4px; font-size: 0.85em; font-weight: bold; }
        .no-course { color: #999; font-style: italic; font-size: 0.85em; }

        /* Accions unificades */
        .actions { margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #eee; padding-top: 15px; }
        .btn-edit { background: #ef6c00; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-size: 0.9em; font-weight: bold; }
        
        /* Estil per al formulari de canvi ràpid similar al teacher-box */
        .enroll-form { margin-top: 15px; padding-top: 15px; border-top: 1px dashed #ddd; }
        .enroll-label { font-size: 0.8em; font-weight: bold; color: #666; text-transform: uppercase; display: block; margin-bottom: 5px; }
        .enroll-select { flex-grow: 1; padding: 8px; border-radius: 4px; border: 1px solid #ddd; font-family: inherit; }
        .btn-save-quick { background: #4CAF50; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; }
        
        .back-link { display: inline-block; margin-top: 30px; text-decoration: none; color: #555; }
    </style>
</head>
<body>

    <div class="header">
        <h1>🎓 Gestió d'Alumnes</h1>
        <a href="/student/create" class="btn-add">➕ Add Student</a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <div class="student-grid">
        <?php foreach ($students as $student): ?>
            <div class="student-card">
                <span class="student-id">ID: <?= htmlspecialchars($student->id()->value()) ?></span>
                <span class="student-name"><?= htmlspecialchars($student->name()) ?></span>
                <span class="student-email">📧 <?= htmlspecialchars($student->email()) ?></span>
                
                <div class="course-info">
                    <strong>Estat:</strong> 
                    <?php if ($student->courseId()): ?>
                        <span class="course-badge"><?= htmlspecialchars($student->courseId()->value()) ?></span>
                    <?php else: ?>
                        <span class="no-course">Sense matricular</span>
                    <?php endif; ?>
                </div>

                <div class="actions">
                    <a href="/student/edit?id=<?= urlencode($student->id()->value()) ?>" class="btn-edit">✏️ Editar</a>

                    <form action="/student/delete" method="POST" onsubmit="return confirm('Estàs segur que vols eliminar aquest estudiant?');">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($student->id()->value()) ?>">
                        <button type="submit" style="background: #d32f2f; color: white; border: none; padding: 8px 16px; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 0.9em; font-family: inherit;">
                            🗑️ Eliminar
                        </button>
                    </form>
                </div>

                <form action="/student/enroll" method="POST" class="enroll-form">
                    <input type="hidden" name="student_id" value="<?= htmlspecialchars($student->id()->value()) ?>">
                    
                    <label class="enroll-label">Canvi ràpid de curs:</label>
                    <div style="display: flex; gap: 5px;">
                        <select name="course_id" class="enroll-select">
                            <option value="">-- Selecciona curs --</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?= $course->id()->value() ?>" 
                                    <?= ($student->courseId() && $student->courseId()->value() === $course->id()->value()) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($course->name()) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn-save-quick" title="Guardar canvi de curs">
                            💾
                        </button>
                    </div>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <a href="/" class="back-link">⬅ Tornar al Menú Principal</a>

</body>
</html>
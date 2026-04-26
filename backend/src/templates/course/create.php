<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Nou Course</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; padding: 30px; background: #f4f4f4; }
        .form-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 500px; margin: auto; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn-submit { background: #009688; color: white; padding: 12px; border: none; border-radius: 4px; width: 100%; cursor: pointer; font-size: 1em; font-weight: bold; }
        .back-link { display: block; margin-top: 20px; text-align: center; color: #666; text-decoration: none; }
        .alert-error { background-color: #ffebee; color: #c62828; padding: 15px; border-radius: 4px; border-left: 5px solid #c62828; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>➕ Registrar Nou Course</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert-error">
                ⚠️ <?= $_SESSION['error']; ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        <form action="/course/store" method="POST">
            <div class="form-group">
                <label>ID Course:</label>
                <input type="text" name="id" required placeholder="Ex: CRS-001">
            </div>
            <div class="form-group">
                <label>Nom Complet:</label>
                <input type="text" name="name" required placeholder="Nom del course">
            </div>
            <button type="submit" class="btn-submit">Registrar Course</button>
        </form>
        <a href="/course" class="back-link">⬅ Cancel·lar i tornar</a>
    </div>
</body>
</html>
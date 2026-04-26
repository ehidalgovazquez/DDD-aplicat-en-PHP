<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Crear Professor</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; padding: 30px; background: #f4f4f4; }
        .form-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 500px; margin: auto; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn-submit { background: #4CAF50; color: white; padding: 12px; border: none; border-radius: 4px; width: 100%; cursor: pointer; font-weight: bold; }
        .back-link { display: block; margin-top: 20px; text-align: center; color: #666; text-decoration: none; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>👨‍🏫 Nou Professor</h2>
        <form action="/teacher/store" method="POST">
            <div class="form-group">
                <label>ID:</label>
                <input type="text" name="id" placeholder="Ex: T-001" required>
            </div>
            <div class="form-group">
                <label>Nom Complet:</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <button type="submit" class="btn-submit">Registrar Professor</button>
        </form>
        <a href="/teacher" class="back-link">⬅ Cancel·lar</a>
    </div>
</body>
</html>
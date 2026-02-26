<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Editar Professor</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; padding: 30px; background-color: #f4f7f6; }
        .form-container { 
            background: white; 
            padding: 40px; 
            border-radius: 8px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
            max-width: 500px; 
            margin: 40px auto; 
            border-top: 5px solid #ef6c00;
        }
        h2 { color: #333; margin-top: 0; text-align: center; }
        .form-group { margin-bottom: 25px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        input { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #ddd; 
            border-radius: 4px; 
            box-sizing: border-box; 
            font-size: 1em;
        }
        input:focus { border-color: #ef6c00; outline: none; box-shadow: 0 0 5px rgba(239, 108, 0, 0.2); }
        .readonly { background-color: #f9f9f9; color: #888; cursor: not-allowed; border-style: dashed; }
        .btn-submit { 
            background: #ef6c00; 
            color: white; 
            padding: 14px; 
            border: none; 
            border-radius: 4px; 
            width: 100%; 
            cursor: pointer; 
            font-weight: bold; 
            font-size: 1.1em;
            transition: background 0.3s;
        }
        .btn-submit:hover { background: #e65100; }
        .back-link { display: block; margin-top: 25px; text-align: center; color: #666; text-decoration: none; font-size: 0.9em; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>✏️ Editar Professor</h2>
        
        <form action="/teacher/update" method="POST">
            <div class="form-group">
                <label for="id">Identificador (ID):</label>
                <input type="text" id="id" name="id" value="<?= htmlspecialchars($teacher->id()->value()) ?>" readonly class="readonly">
            </div>

            <div class="form-group">
                <label for="name">Nom Complet:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($teacher->name()) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Correu Electrònic:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($teacher->email()) ?>" required>
            </div>

            <button type="submit" class="btn-submit">Guardar Canvis</button>
        </form>

        <a href="/teacher" class="back-link">⬅ Tornar a la llista</a>
    </div>
</body>
</html>
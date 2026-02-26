<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Portal Académico</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .grid { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 20px; 
            max-width: 800px; 
            margin-top: 20px;
        }
        .card { 
            background: #FFD700; 
            padding: 50px; 
            text-align: center; 
            text-decoration: none; 
            color: black; 
            font-weight: bold;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
        }
        .card:hover { background: #e6c200; }
    </style>
</head>
<body>
    <h1>Benvingut al Portal de Gestió</h1>
    <div class="grid">
        <a href="/student" class="card">GESTIÓ ALUMNES</a>
        <a href="/teacher" class="card">GESTIÓ PROFESSORS</a>
        <a href="/subject" class="card">GESTIÓ ASIGNATURES</a>
        <a href="/course" class="card">GESTIÓ CURSOS</a>
    </div>
</body>
</html>
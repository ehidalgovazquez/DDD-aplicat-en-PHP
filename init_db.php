<?php
// init_db.php

try {
    // 1. Crea el archivo físico database.sqlite si no existe
    $pdo = new PDO('sqlite:' . __DIR__ . '/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Definimos las tablas necesarias para tus 6 casos de uso
    $sql = "
        -- Caso 2: Cursos
        CREATE TABLE IF NOT EXISTS courses (
            id TEXT PRIMARY KEY,
            name TEXT NOT NULL
        );

        -- Caso 4: Profesores
        CREATE TABLE IF NOT EXISTS teachers (
            id TEXT PRIMARY KEY,
            name TEXT NOT NULL,
            email TEXT NOT NULL
        );

        -- Caso 1 y 5: Alumnos y su matrícula
        CREATE TABLE IF NOT EXISTS students (
            id TEXT PRIMARY KEY,
            name TEXT NOT NULL,
            email TEXT NOT NULL,
            course_id TEXT,
            FOREIGN KEY (course_id) REFERENCES courses(id)
        );

        -- Caso 3 y 6: Asignaturas y su profesor
        CREATE TABLE IF NOT EXISTS subjects (
            id TEXT PRIMARY KEY,
            name TEXT NOT NULL,
            teacher_id TEXT,
            FOREIGN KEY (teacher_id) REFERENCES teachers(id)
        );
    ";

    $pdo->exec($sql);
    echo "✅ Base de dades 'database.sqlite' creada i configurada amb èxit!";

} catch (PDOException $e) {
    echo "❌ Error al crear la base de dades: " . $e->getMessage();
}
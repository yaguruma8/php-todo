<?php

//
// Database
//
function getPdoInstance()
{
    try {
        $pdo = new PDO(
            DSN,
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        $e->getMessage();
        exit;
    }
}

//
// TODO関連
//

// create
function addTodo(PDO $pdo)
{
    $title = trim(filter_input(INPUT_POST, 'title'));
    if ($title === '') {
        return;
    }
    $stmt = $pdo->prepare(
        "INSERT INTO todos (title) VALUES (:title)"
    );
    $stmt->bindValue('title', $title, PDO::PARAM_STR);
    $stmt->execute();
}

// read
function getTodos(PDO $pdo)
{
    return $pdo->query("SELECT * FROM todos ORDER BY id DESC;")->fetchAll();
}

// Update
function toggleTodo(PDO $pdo)
{
    $id = filter_input(INPUT_POST, 'id');
    if (empty($id)) {
        return;
    }
    $stmt = $pdo->prepare(
        "UPDATE todos
        SET is_done = NOT is_done
        WHERE id = :id"
    );
    $stmt->bindValue('id', $id, PDO::PARAM_INT);
    $stmt->execute();
}

//delete
function deleteTodo(PDO $pdo)
{
    $id = filter_input(INPUT_POST, 'id');
    if (empty($id)) {
        return;
    }
    $stmt = $pdo->prepare(
        "DELETE FROM todos
        WHERE id = :id"
    );
    $stmt->bindValue('id', $id, PDO::PARAM_INT);
    $stmt->execute();
}



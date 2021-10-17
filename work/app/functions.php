<?php


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



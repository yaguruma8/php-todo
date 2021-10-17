<?php

class Todo
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function processPost()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Token::validate();

            $action = filter_input(INPUT_GET, 'action');
            switch ($action) {
                case 'add':
                    $this->add();
                    break;
                case 'toggle':
                    $this->toggle();
                    break;
                case 'delete':
                    $this->delete();
                    break;
                default:
                    exit;
            }
            header('Location: ' . SITE_URL);
            exit;
        }
    }

    public function add()
    {
        $title = trim(filter_input(INPUT_POST, 'title'));
        if ($title === '') {
            return;
        }
        $stmt = $this->pdo->prepare(
            "INSERT INTO todos (title) VALUES (:title)"
        );
        $stmt->bindValue('title', $title, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function getAll()
    {
        return $this->pdo->query("SELECT * FROM todos ORDER BY id DESC;")->fetchAll();
    }

    public function toggle()
    {
        $id = filter_input(INPUT_POST, 'id');
        if (empty($id)) {
            return;
        }
        $stmt = $this->pdo->prepare(
            "UPDATE todos
        SET is_done = NOT is_done
        WHERE id = :id"
        );
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function delete()
    {
        $id = filter_input(INPUT_POST, 'id');
        if (empty($id)) {
            return;
        }
        $stmt = $this->pdo->prepare(
            "DELETE FROM todos
        WHERE id = :id"
        );
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
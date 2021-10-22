<?php

namespace Myapp;

class Todo
{
    private $pdo;

    public function __construct(\PDO$pdo)
    {
        $this->pdo = $pdo;
        Token::create();
    }

    public function processPost()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Token::validate();

            $action = filter_input(INPUT_GET, 'action');
            switch ($action) {
                case 'add':
                    $id = $this->add();
                    header('Content-Type: application/json');
                    echo json_encode(['id' => $id]);
                    break;
                case 'toggle':
                    $this->toggle();
                    break;
                case 'delete':
                    $this->delete();
                    break;
                case 'purge':
                    $this->purge();
                    break;
                default:
                    echo 'invalid post';
                    exit;
            }
            exit;
        }
    }

    public function getAll()
    {
        return $this->pdo->query("SELECT * FROM todos ORDER BY id DESC;")->fetchAll();
    }

    private function add()
    {
        $title = trim(filter_input(INPUT_POST, 'title'));
        if ($title === '') {
            return;
        }
        $stmt = $this->pdo->prepare(
            "INSERT INTO todos (title) VALUES (:title)"
        );
        $stmt->bindValue('title', $title, \PDO::PARAM_STR);
        $stmt->execute();
        return (int) $this->pdo->lastInsertId();
    }

    private function toggle()
    {
        $id = filter_input(INPUT_POST, 'id');
        if (empty($id)) {
            return;
        }
        // 削除したtodoをチェックした場合->404
        $stmt = $this->pdo->prepare("SELECT * FROM todos WHERE id = :id");
        $stmt->bindValue('id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $todo = $stmt->fetch();
        if (empty($todo)) {
            header('HTTP', true, 404);
            exit;
        }
        // フロント側のチェック状態にDBを合わせる
        $checkFlag = filter_input(INPUT_POST, 'checkFlag', FILTER_VALIDATE_BOOLEAN);

        $stmt = $this->pdo->prepare(
            "UPDATE todos
        SET is_done = :checkFlag
        WHERE id = :id"
        );
        $stmt->bindValue('checkFlag', $checkFlag, \PDO::PARAM_BOOL);
        $stmt->bindValue('id', $id, \PDO::PARAM_INT);
        $stmt->execute();

    }

    private function delete()
    {
        $id = filter_input(INPUT_POST, 'id');
        if (empty($id)) {
            return;
        }
        $stmt = $this->pdo->prepare(
            "DELETE FROM todos
        WHERE id = :id"
        );
        $stmt->bindValue('id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    }

    private function purge()
    {
        // フロント側のチェック済みidの配列
        $appCheckedIds = array_map(
            function ($value) {return (int) $value;},
            explode(',', filter_input(INPUT_POST, 'appCheckedIds'))
        );
        // DB側のis_doneがtrueであるidの配列
        $dbIsDoneIds = $this->pdo->query(
            "SELECT id FROM todos WHERE is_done = 1"
        )->fetchAll(\PDO::FETCH_COLUMN);
        // 差異があれば404を返して処理を終了
        $diff = array_diff($dbIsDoneIds, $appCheckedIds);
        if (count($appCheckedIds) !== count($dbIsDoneIds) || count($diff)) {
            header('HTTP', true, 404);
            exit;
        }
        // 差異がなければDBを削除する
        $this->pdo->query("DELETE FROM todos WHERE is_done = 1");
    }
}

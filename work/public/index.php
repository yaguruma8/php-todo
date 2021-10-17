<?php

require_once __DIR__ . '/../app/config.php';

use Myapp\Database;
use Myapp\Todo;
use Myapp\Utils;

$pdo = Database::getInstance();

$todo = new Todo($pdo);
$todo->processPost();

$todos = $todo->getAll();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>My Todos</title>
  <link rel="stylesheet" href="css/styles.css">
  <script src="js/main.js" defer></script>
</head>
<body>
  <main>
    <header>
      <h1>Todos</h1>
      <form action="?action=purge" method="post">
        <span class="purge">Purge</span>
        <input type="hidden" name="token" value="<?=Utils::h($_SESSION['token']);?>">
      </form>

    </header>

    <form action="?action=add" method="post">
      <input type="text" name="title" placeholder="input new todo">
      <input type="hidden" name="token" value="<?=Utils::h($_SESSION['token']);?>">
    </form>
    <ul>
      <?php foreach ($todos as $todo): ?>
        <li>
          <input type="checkbox" class="toggle" 
            data-id="<?=Utils::h($todo->id);?>"
            data-token="<?=Utils::h($_SESSION['token']);?>"
            <?=$todo->is_done ? 'checked' : '';?> >

          <span class="<?=$todo->is_done ? 'done' : '';?>">
            <?=Utils::h($todo->title);?>
          </span>

          <span class="delete"
            data-id="<?=Utils::h($todo->id);?>"
            data-token="<?=Utils::h($_SESSION['token']);?>">
            X
          </span>

        </li>
      <?php endforeach;?>
    </ul>
  </main>
</body>
</html>

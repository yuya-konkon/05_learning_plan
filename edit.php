<?php

require_once('config.php');
require_once('functions.php');

$dbh = connectDB();

// 受け取ったレコードのid
$id = $_GET['id'];

$sql = "select * from plans where id = :id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(":id", $id);
$stmt->execute();

$plan = $stmt->fetch(PDO::FETCH_ASSOC);

// 編集
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'];
  $due_date = $_POST['due_date'];

  $errors = [];
  // バリデーション
  if ($title == '') {
    $errors['title'] = 'タスク名を入力してください。';
  }

  if ($title == $plan['title']) {
    $errors['title'] = 'タスク名が変更されていません。';
  }

  if ($due_date == '') {
    $errors['due_date'] = '期限を設定してください。';
  }

  if ($due_date == $plan['due_date']) {
    $errors['due_date'] = '期限が変更されていません。';
  }

  // エラーが1つもなければレコードを更新
  if (empty($errors)) {
    $sql = "update plans set title = :title, due_date = :due_date, updated_at = now() where id = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":title", $title);
    $stmt->bindParam(":due_date", $due_date);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    header('Location: index.php');
    exit;
  }
}


?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="css/style.css">
  <title>編集画面</title>
</head>

<body>
  <h1>編集</h1>
  <p>
    <form action="" method="post">
      学習内容: <input type="text" name="title" value="<?php echo h($plan['title']); ?>">
      期限日: <input tyfpe="date" name="due_date">
      <input type="submit" value="編集">
      <?php if ($errors > 0) : ?>
        <ul class="errors">
          <?php foreach ($errors as $key => $value) : ?>
            <li><?php echo h($value); ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </form>
  </p>
</body>

</html>
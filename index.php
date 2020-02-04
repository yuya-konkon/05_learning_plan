<?php

require_once('config.php');
require_once('functions.php');

$dbh = connectDB();

// 未達成レコードの取得
$sql = "select * from plans where status = 'notyet'";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$notyet_plans = $stmt->fetchAll(PDO::FETCH_ASSOC);



// 新規タスクの追加
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'];
  $dudate = $_POST['due_date'];

  $errors = [];

  // 入力がなければエラー

  if ($title == '') {
    $errors['title'] = 'タスク名を入力してください';
  }

  // 期限が設定されていなければエラー
  if ($dudate == "") {
    $errors['$dudate'];
  }

  // エラーがなければ実行
  if (empty($errors)) {
    $sql = "insert into plans (title, due_date, created_at, updated_at) values (:title, now(),now()) ";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":title", $title);
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
  <title>学習管理アプリ</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <h1>学習管理アプリ</h1>

  <p>
    <form action="" method="post">
      学習内容: <input type="text"><br>
      期限日: <input type="date" name="">
      <input type="submit" value="追加"><br>
      <span class="expired">
        <ul style="color:red;">
          <li>
            <?php echo h($errors ['title']) ?>
          </li>
        </ul>
      </span>
    </form>
  </p>

  <h2>未達成</h2>
  <ul>
    <?php foreach ($notyet_plans as $plan) : ?>
      <li>
        <?php echo h($plan['title']); ?>
      </li>
    <?php endforeach; ?>
  </ul>

  <h2>達成済み</h2>


</body>

</html>
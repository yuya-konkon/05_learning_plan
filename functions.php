<?php

// データベースに接続
function connectDb() {
  try {
    return new PDO(DSN, DB_USER, DB_PASSWORD);
  } catch (PDOException $e) {
    echo $e->getMessage();
    exit;
  }
}

// エスケープ処理
function h($s ){
  return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}
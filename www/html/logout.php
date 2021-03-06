<?php
// それぞれのページから情報を取得
require_once '../conf/const.php';
require_once '../model/functions.php';

// セッションを開始
session_start();

// iframe対策
header("X-FRAME-OPTIONS: DENY");

// 関数を定義
$_SESSION = array();
$params = session_get_cookie_params();

// Cookieを削除
setcookie(session_name(), '', time() - 42000,
  $params["path"], 
  $params["domain"],
  $params["secure"], 
  $params["httponly"]
);

// セッションを削除
session_destroy();

// ログインページに戻る
redirect_to('login.php');


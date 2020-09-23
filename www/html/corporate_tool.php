<?php
// それぞれのページから情報を取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'corporate.php';
require_once MODEL_PATH . 'user.php';

// セッションを開始
session_start();

// トークンを生成
$token = get_csrf_token();

// iframe対策
header("X-FRAME-OPTIONS: DENY");

// ログインされていなければログインページに戻る
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// データベースに接続
$db = get_db_connect();

// ユーザー情報の取得
$acount = get_login_user($db);

// 管理者でなければログインページに移動
if(is_manager($acount) === false){
  redirect_to(LOGIN_URL);
}

// 全てのユーザーの全ての情報を取得
$corporates = get_corporates($db);

// VIEWの読み込み
include_once VIEW_PATH . 'corporate_tool_view.php';
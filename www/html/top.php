<?php
// それぞれのページから関数を取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'data.php';
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

// 資料情報の取得
$datas = get_open_datas($db);

// VIEWの読み込み
include_once VIEW_PATH . 'top_view.php';
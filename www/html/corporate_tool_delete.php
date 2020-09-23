<?php
// それぞれのページから情報を取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'corporate.php';

// セッションを開始
session_start();

// iframe対策
header("X-FRAME-OPTIONS: DENY");

// ログインがされていなければログインページに返す
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// データベースに接続
$db = get_db_connect();

// ユーザ情報の取得
$acount = get_login_user($db);

// 管理者でなければログインページに移動
if(is_manager($acount) === false){
  redirect_to(LOGIN_URL);
}

// POSTからユーザーIDを取得
$corporate_id = get_post('corporate_id');

// POSTからトークンを取得
$token = get_post('csrf_token');

// sessionからトークンを取得
$session = get_session('csrf_token');

// トークンが適正であれば以下を実行
if(is_valid_csrf_token($token, $session) === true){

  // 法人アカウントを削除
  if(destroy_corporate($db, $corporate_id) === true){
    set_message('法人アカウントを削除しました。');
  } else {
    set_error('法人アカウントの削除に失敗しました。');
  }

  // ユーザー管理ページに戻る
  redirect_to(CORPORATE_URL);

  // 不正アクセスの場合
}else{
  // エラーを表示
  set_error('不正なアクセスです。');

  // ログインページに戻る
  redirect_to(LOGIN_URL);
}
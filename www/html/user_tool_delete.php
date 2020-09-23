<?php
// それぞれのページから情報を取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

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

// POSTからIDを取得
$user_id = get_post('user_id');

// POSTからトークンを取得
$token = get_post('csrf_token');

// sessionからトークンを取得
$session = get_session('csrf_token');

// トークンが適正であれば以下を実行
if(is_valid_csrf_token($token, $session) === true){

  // ユーザーを削除
  if(destroy_user($db, $user_id) === true){
    set_message('ユーザーを削除しました。');
  } else {
    set_error('ユーザーの削除に失敗しました。');
  }
  if(is_manager($acount) === true){

    // ユーザー管理ページに戻る
    redirect_to(USER_URL);
  }else{
    redirect_to(LOGOUT_URL);
  }
  // 不正アクセスの場合
}else{
  // エラーを表示
  set_error('不正なアクセスです。');

  // ログインページに戻る
  redirect_to(LOGIN_URL);
}
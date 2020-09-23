<?php
// それぞれのページから情報を取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

// セッションを開始
session_start();

// iframe対策
header("X-FRAME-OPTIONS: DENY");

// ログインされていればホームページに移動
if(is_logined() === true){
  redirect_to(HOME_URL);
}

// POSTから情報を取得
$user_name = get_post('name');
$password = get_post('password');

// POSTからトークンを取得
$token = get_post('csrf_token');

// sessionからトークンを取得
$session = get_session('csrf_token');

// トークンが適正であれば以下を実行
if(is_valid_csrf_token($token, $session) === true){

  // データベースに接続
  $db = get_db_connect();

  // ログインを実行
  $acount = login_as($db, $user_name, $password);

  // ユーザー情報が正しくなければエラーメッセージを表示してログインページに戻る
  if( $acount === false){
    set_error('ログインに失敗しました。');
    redirect_to(LOGIN_URL);
  }

  // ログイン日時を更新
  update_login($db, $acount['user_id']);
  
  // ログインメッセージの表示
  set_message('ログインしました。');

  // 管理者はユーザ管理ページに移動
  if ($acount['manage'] === USER_TYPE_MANAGER){
    redirect_to(USER_URL);
  }
  
  // ホームページに移動
  redirect_to(HOME_URL);

// 不正アクセスの場合
}else{
  // エラーを表示
  set_error('不正なアクセスです。');

  // ログインページに戻る
  redirect_to(LOGIN_URL);
}
<?php
// それぞれのページから関数を取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

// セッションを開始
session_start();

// iframe対策
header("X-FRAME-OPTIONS: DENY");

// ログインされていなければログインページに戻る
if(is_logined() === false){
    redirect_to(LOGIN_URL);
}

// データベースに接続
$db = get_db_connect();

// ユーザー情報を取得
$acount = get_login_user($db);

// POSTから情報を取得
$user_name = get_post('user_name');

// POSTからトークンを取得
$token = get_post('csrf_token');

// sessionからトークンを取得
$session = get_session('csrf_token');

// トークンが適正であれば以下を実行
if(is_valid_csrf_token($token, $session) === true){
    
    // ユーザ名の変更
    if(change_user_name($db, $acount['user_id'], $user_name)){
        set_message('ユーザ名を変更しました。');
    }else {
        set_error('ユーザ名の変更に失敗しました。');
    }

  // マイページに戻る
  redirect_to(MYPAGE_URL);

// 不正アクセスの場合
}else{
  // エラーを表示
  set_error('不正なアクセスです。');

  // ホームページに戻る
  redirect_to(LOGIN_URL);
}
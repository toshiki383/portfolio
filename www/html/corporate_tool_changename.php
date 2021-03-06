<?php
// それぞれのページから関数を取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'corporate.php';

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

if(is_manager($acount) === false){
    redirect_to(LOGIN_URL);
}

// POSTから情報を取得
$corporate_id = get_post('corporate_id');
$corporate_name = get_post('corporate_name');
// $corporate_code = get_post('corporate_code');

// POSTからトークンを取得
$token = get_post('csrf_token');

// sessionからトークンを取得
$session = get_session('csrf_token');

// トークンが適正であれば以下を実行
if(is_valid_csrf_token($token, $session) === true){
    
    // 法人名の変更
    if(change_corporate_name($db, $corporate_id, $corporate_name)){
        set_message('法人名を変更しました。');
    }else {
        set_error('法人名の変更に失敗しました。');
    }

  // 法人管理ページに戻る
  redirect_to(CORPORATE_URL);

// 不正アクセスの場合
}else{
  // エラーを表示
  set_error('不正なアクセスです。');

  // ホームページに戻る
  redirect_to(LOGIN_URL);
}
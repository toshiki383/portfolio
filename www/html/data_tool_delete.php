<?php
// それぞれのページから情報を取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'data.php';

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

// 管理者または発表者でなければログインページに移動
if(is_manager($acount) || is_presenter($acount) === true){
    
}else{
  redirect_to(LOGIN_URL);
}

// POSTから情報を取得
$data_id = get_post('data_id');

// POSTからトークンを取得
$token = get_post('csrf_token');

// sessionからトークンを取得
$session = get_session('csrf_token');

// トークンが適正であれば以下を実行
if(is_valid_csrf_token($token, $session) === true){

  // 資料を削除
  if(destroy_data($db, $data_id) === true){
    set_message('資料を削除しました。');
  } else {
    set_error('資料の削除に失敗しました。');
  }

  // 資料管理ページに戻る
  redirect_to(DATA_URL);

  // 不正アクセスの場合
}else{
  // エラーを表示
  set_error('不正なアクセスです。');

  // ログインページに戻る
  redirect_to(LOGIN_URL);
}
<?php
// それぞれのページから関数を取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'data.php';

// セッションを開始
session_start();

// iframe対策
header("X-FRAME-OPTIONS: DENY");

// ログインが実行されなければログインページに戻る
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// データベースに接続
$db = get_db_connect();

// ログイン情報を取得
$acount = get_login_user($db);

// 管理者または発表者でなければログインページに移動
if(is_manager($acount) || is_presenter($acount) === true){
}else{
  redirect_to(LOGIN_URL);
}

// POSTから情報を取得
$data_id = get_post('data_id');
$close_datetime = get_post('close_datetime');

// POSTからトークンを取得
$token = get_post('csrf_token');

// sessionからトークンを取得
$session = get_session('csrf_token');

// トークンが適正であれば以下を実行
if(is_valid_csrf_token($token, $session) === true){

  // 公開終了日時の変更
  if(change_close_datetime($db, $data_id, $close_datetime)){
    set_message('公開終了日時を変更しました。');
  }else {
      set_error('公開終了日時の変更に失敗しました。');
  }

  // ユーザー管理ページに移動
  redirect_to(DATA_URL);

// 不正アクセスの場合
}else{
  // エラーを表示
  set_error('不正なアクセスです。');

  // ホームページに戻る
  redirect_to(LOGIN_URL);
}
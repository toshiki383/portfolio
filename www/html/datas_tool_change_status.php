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
$data_ids = get_post('ids');
$status = get_post('status');

// POSTからトークンを取得
$token = get_post('csrf_token');

// sessionからトークンを取得
$session = get_session('csrf_token');

// トークンが適正であれば以下を実行
if(is_valid_csrf_token($token, $session) === true){

  if($data_ids === ''){
    set_error('資料が選択されていません');
    // ユーザー管理ページに移動
    redirect_to(DATA_URL);

  // 資料ステータスの変更
  }else if($status === 'close'){
    check_data_change_close($db, $data_ids);

    set_message('資料を非公開にしました。');

  } else if($status === 'open') {
    check_data_change_open($db, $data_ids);

    set_message('資料を公開しました。');

  // 正しく実行されなければエラーを表示
  }else {
  set_error('不正なリクエストです。');
  }

  // 資料管理ページに移動
  redirect_to(DATA_URL);

// 不正アクセスの場合
}else{
  // エラーを表示
  set_error('不正なアクセスです。');

  // ログインページに戻る
  redirect_to(LOGIN_URL);
}
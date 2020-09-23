<?php
// それぞれのページから関数を取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

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

// 管理者でなければログインページに移動
if(is_manager($acount) === false){
  redirect_to(LOGIN_URL);
}

// POSTから情報を取得
$user_id = get_post('user_id');
$change_manage = get_post('change_manage');

// POSTからトークンを取得
$token = get_post('csrf_token');

// sessionからトークンを取得
$session = get_session('csrf_token');

// トークンが適正であれば以下を実行
if(is_valid_csrf_token($token, $session) === true){

  // 商品ステータスの変更
  if($change_manage === 'normal'){
    update_user_manage($db, $user_id, USER_TYPE_NORMAL);
    set_message('一般ユーザーに変更しました。');

  }else if($change_manage === 'presenter'){
    update_user_manage($db, $user_id, USER_TYPE_PRESENTER);
    set_message('発表者に変更しました。');

  }else if($change_manage === 'manager'){
    update_user_manage($db, $user_id, USER_TYPE_MANAGER);
    set_message('管理者に変更しました。');

  // 正しく実行されなければエラーを表示
  }else {
    set_error('不正なリクエストです。');
  }

  // 管理者は管理ページに戻り、それ以外はトップページに移動する
  if(is_manager($acount) === true){

    redirect_to(USER_URL);
  }else{

    redirect_to(HOME_URL);  
  }

// 不正アクセスの場合
}else{
  // エラーを表示
  set_error('不正なアクセスです。');

  // ログインページに戻る
  redirect_to(LOGIN_URL);
}
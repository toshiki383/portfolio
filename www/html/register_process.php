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

// POSTからそれぞれ情報を取得
$post_code = get_post('corporate_code');
$user_name = get_post('user_name');
$password = get_post('password');
$password_confirmation = get_post('password_confirmation');

// POSTからトークンを取得
$token = get_post('csrf_token');

// sessionからトークンを取得
$session = get_session('csrf_token');

// トークンが適正であれば以下を実行
if(is_valid_csrf_token($token, $session) === true){

  // データベースに接続
  $db = get_db_connect();

  $select_code = get_corporate_code($db, $post_code);
  $select_code = $select_code['corporate_code'];

  $corporate_id = get_corporate_code($db, $post_code);
  $corporate_id = $corporate_id['corporate_id'];

  $select_name = get_user_by_name($db, $user_name);
  $select_name = $select_name['user_name'];
  
  // ユーザーの登録
  try{

    $result = regist_user($db, $select_code, $post_code, $select_name, $user_name, $password, $password_confirmation, $corporate_id);
    if( $result === false){
      set_error('ユーザー登録に失敗しました。');
      redirect_to(REGISTER_URL);
    }
  }catch(PDOException $e){
    set_error('ユーザー登録に失敗しました。');
    redirect_to(REGISTER_URL);
  }

  // メッセージを取得
  set_message('ユーザー登録が完了しました。');

  // ログインを実行
  login_as($db, $user_name, $password);

  // ホームページに移動
  redirect_to(HOME_URL);

// 不正アクセスの場合
}else{
  // エラーを表示
  set_error('不正なアクセスです。');

  // ログインページに戻る
  redirect_to(LOGIN_URL);
}
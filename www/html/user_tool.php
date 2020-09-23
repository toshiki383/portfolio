<?php
// それぞれのページから情報を取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'data.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'corporate.php';


// セッションを開始
session_start();

// トークンを生成
$token = get_csrf_token();

// iframe対策
header("X-FRAME-OPTIONS: DENY");

// ログインされていなければログインページに戻る
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// データベースに接続
$db = get_db_connect();

// ユーザー情報の取得
$acount = get_login_user($db);

// 管理者でなければログインページに移動
if(is_manager($acount) === false){
  redirect_to(LOGIN_URL);
}

$corporates = get_corporates($db);

// GETからユーザー区分検索の情報を取得
$sarch_manage = get_get('sarch_manage');
$sarch_corporate = get_get('sarch_corporate');


$users = get_users($db);

if($sarch_manage === 'all'){
  $users = get_users($db);
  if($sarch_corporate === 'all'){
    $users = get_users($db);
  }else if(is_positive_integer($sarch_corporate)){
    $users = get_sarch_corporate($db, $sarch_corporate);
  }
}else if(is_positive_integer($sarch_manage)){
  $users = get_manage($db, $sarch_manage);
  if($sarch_corporate === 'all'){
    $users = get_manage($db, $sarch_manage);
  }else if(is_positive_integer($sarch_corporate)){
    $users = get_manage_corporate($db, $sarch_manage, $sarch_corporate);
  }
}

// VIEWの読み込み
include_once VIEW_PATH . 'user_tool_view.php';
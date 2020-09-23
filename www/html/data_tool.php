<?php
// それぞれのページから情報を取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'data.php';
require_once MODEL_PATH . 'user.php';

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


// 管理者または発表者でなければログインページに移動
if(is_manager($acount) || is_presenter($acount) === true){
} else  {
  redirect_to(LOGIN_URL);
}

// 管理者は全ての資料情報を取得
if(is_manager($acount) === true){

  // GETからユーザー区分検索の情報を取得
  $sarch_status = get_get('sarch_status');
  
  // 全ての資料の全ての情報を取得
  if($sarch_status === ''){
    
    $datas = get_all_data($db);
  }

  // ユーザーの絞り込み
  if($sarch_status === 'close'){
    $datas = get_status($db, DATA_STATUS_CLOSE);
    set_message('非公開資料の絞り込みをしました。');

  }else if($sarch_status === 'open'){
    $datas = get_status($db, DATA_STATUS_OPEN);
    set_message('公開資料の絞り込みをしました。');
  }

// 発表者は自分の資料情報のみを取得
}else if(is_presenter($acount) === true){

  $datas = get_user_data($db, $acount['user_id']);
}

// VIEWに出力
include_once '../view/data_tool_view.php';
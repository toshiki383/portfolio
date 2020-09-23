<?php
// var_dumpの関数
function dd($var){
  var_dump($var);
  exit();
}

// htmlspecialcharsの省略
function h($str){
  return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
}

// 指定したurlに移動
function redirect_to($url){
  header('Location: ' . $url);
  exit;
}

// getから情報を取得
function get_get($name){
  if(isset($_GET[$name]) === true){
    return $_GET[$name];
  };
  return '';
}

// POSTから情報を取得
function get_post($name){
  if(isset($_POST[$name]) === true){
    return $_POST[$name];
  };
  return '';
}

// ファイルの情報を取得
function get_file($name){
  if(isset($_FILES[$name]) === true){
    return $_FILES[$name];
  };
  return array();
}

// セッションを取得
function get_session($name){
  if(isset($_SESSION[$name]) === true){
    return $_SESSION[$name];
  };
  return '';
}

// SESSIONの代入
function set_session($name, $value){
  $_SESSION[$name] = $value;
}

// エラー文の書き込み
function set_error($error){
  $_SESSION['__errors'][] = $error;
}

// エラーの取得
function get_errors(){
  $errors = get_session('__errors');
  if($errors === ''){
    return array();
  }
  set_session('__errors',  array());
  return $errors;
}

// エラーのチェック
function has_error(){
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;
}

// エラー文の表示
function set_message($message){
  $_SESSION['__messages'][] = $message;
}

// メッセージを取得
function get_messages(){
  $messages = get_session('__messages');
  if($messages === ''){
    return array();
  }
  set_session('__messages',  array());
  return $messages;
}

// ログイン時にセッションを取得
function is_logined(){
  return get_session('user_id') !== '';
}

// 文字のチェック
function get_random_string($length = 20){
  return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
}

// 文字数のチェック
function is_valid_length($string, $minimum_length, $maximum_length = PHP_INT_MAX){
  $length = mb_strlen($string);
  return ($minimum_length <= $length) && ($length <= $maximum_length);
}

// 英数字のチェック
function is_alphanumeric($string){
  return is_valid_format($string, REGEXP_ALPHANUMERIC);
}

// 正の整数のチェック
function is_positive_integer($string){
  return is_valid_format($string, REGEXP_POSITIVE_INTEGER);
}

// 日付のチェック
function is_date($string){
  return is_valid_format($string, REGEXP_DATE);
}

// 日時のチェック
function is_datetime($string){
  return is_valid_format($string, REGEXP_DATETIME);
}

// 文字形式のチェック
function is_valid_format($string, $format){
  return preg_match($format, $string) === 1;
}

// PDF

// PDFの保存
function save_pdf($pdf, $filename){
  return move_uploaded_file($pdf['tmp_name'], PDF_DIR . $filename);
}

// PDFの消去
function delete_pdf($filename){
  if(file_exists(PDF_DIR . $filename) === true){
    unlink(PDF_DIR . $filename);
    return true;
  }
  return false;
}

// PDFのチェック
function is_valid_upload_pdf($pdf){
  if(is_uploaded_file($pdf['tmp_name']) === false){
    set_error('ファイル形式が不正です。');
    return false;
  }
  $mimetype = exif_imagetype($pdf['tmp_name']);
  if( isset('application/pdf'[$mimetype]) === false ){
    set_error('ファイル形式は' . implode('、', application/pdf) . 'のみ利用可能です。');
    return false;
  }
  return true;
}

// filenameの取得
function get_upload_filename($file){
  if(is_valid_upload_pdf($file) === false){
    return '';
  }
  $mimetype = exif_imagetype($file['tmp_name']);
  $ext = 'application/pdf'[$mimetype];
  return get_random_string() . '.' . $ext;
}


// トークン

// トークンの生成
function get_csrf_token(){
  // get_random_string()はユーザー定義関数。
  $token = get_random_string(30);
  // set_session()はユーザー定義関数。
  set_session('csrf_token', $token);
  return $token;
}

// トークンのチェック
function is_valid_csrf_token($token){
  if($token === '') {
    return false;
  }
  // get_session()はユーザー定義関数
  return $token === get_session('csrf_token');
}
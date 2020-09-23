<?php
// 
define('MODEL_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../model/');
define('VIEW_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../view/');

define('STYLESHEET_PATH', '/css/');
define('PDF_PATH', '/pdf/');
define('PDF_DIR', $_SERVER['DOCUMENT_ROOT'] . '/pdf/' );

// データベースの情報ユーザー情報
define('DB_HOST', 'mysql'); //localhost
define('DB_NAME', 'sample');
define('DB_USER', 'testuser');
define('DB_PASS', 'password');
define('DB_CHARSET', 'utf8');

// 
define('REGISTER_URL', '/register.php');
define('LOGIN_URL', '/login.php');
define('LOGOUT_URL', '/logout.php');
define('HOME_URL', '/top.php');
define('USER_URL', '/user_tool.php');
define('DATA_URL', '/data_tool.php');
define('CORPORATE_URL', '/corporate_tool.php');
define('HISTORY_URL', '/history.php');
define('MYPAGE_URL', '/mypage.php');
define('PASSWORD_URL', '/change_user_password.php');

// 正規表現
define('REGEXP_ALPHANUMERIC', '/\A[0-9a-zA-Z]+\z/');
define('REGEXP_POSITIVE_INTEGER', '/\A([1-9][0-9]*|0)\z/');
define('REGEXP_CORPORATE', '/^([1-9][0-9]*)$/');
define('REGEXP_DATE', '/^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$/');
define('REGEXP_DATETIME', '/^(?P<year>[0-9]{4})\-(?P<month>[0-9]{2})-(?P<day>[0-9]{2})T([01][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/');




// 文字数の規定
define('USER_NAME_LENGTH_MIN', 1);
define('USER_NAME_LENGTH_MAX', 100);
define('USER_PASSWORD_LENGTH_MIN', 6);
define('USER_PASSWORD_LENGTH_MAX', 100);

// ユーザーの区分
define('USER_TYPE_NORMAL', 0);
define('USER_TYPE_PRESENTER', 1);
define('USER_TYPE_MANAGER', 2);

// 商品のステータス規定
define('PERMITTED_USER_MANAGE', array(
  'normal' => 0,
  'presenter' => 1,
  'manager' => 2,
));

// 資料名の文字数の規定
define('DATA_NAME_LENGTH_MIN', 1);
define('DATA_NAME_LENGTH_MAX', 100);

// 法人名の文字数の規定
define('CORPORATE_NAME_LENGTH_MIN', 1);
define('CORPORATE_NAME_LENGTH_MAX', 100);

// 法人コードの文字数の規定
define('CORPORATE_CODE_LENGTH_MIN', 6);
define('CORPORATE_CODE_LENGTH_MAX', 100);

// 資料ステータスの区分
define('DATA_STATUS_OPEN', 1);
define('DATA_STATUS_CLOSE', 0);

// 資料のステータス規定
define('PERMITTED_DATA_STATUSES', array(
  'open' => 1,
  'close' => 0,
));
<?php
// 関数の取得
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';
require_once MODEL_PATH . 'corporate.php';

// 全てのユーザーの全ての情報を取得
function get_users($db){
  $sql = "
    SELECT
      user.user_id,
      user.user_name,
      user.manage,
      user.corporate_id,
      user.created,
      user.logined,
      corporate.corporate_id,
      corporate.corporate_name
    FROM
      user
    JOIN
      corporate
    ON
      user.corporate_id = corporate.corporate_id
  ";
  return fetch_all_query($db, $sql);
}

// ユーザーIDからユーザー情報の取得
function get_user($db, $user_id){
  $sql = "
    SELECT
      user.user_id, 
      user.user_name,
      user.password,
      user.manage,
      user.corporate_id,
      corporate.corporate_id,
      corporate.corporate_name
    FROM
      user
    JOIN
      corporate
    ON
      user.corporate_id = corporate.corporate_id
    WHERE
      user_id = ?
    LIMIT 1
  ";
  $params = array($user_id);
  
  return fetch_query($db, $sql, $params);
}


function get_user_regist($db, $user_id){
  $sql = "
    SELECT
      user_name
    FROM
      user
    WHERE
      user_name = ?
  ";

  $params = array($user_id);
  
  return fetch_query($db, $sql, $params);
}
  
// ユーザーネームからユーザー情報を取得
function get_user_by_name($db, $user_name){
  $sql = "
    SELECT
      user.user_id, 
      user.user_name,
      user.password,
      user.manage,
      user.corporate_id,
      corporate.corporate_id,
      corporate.corporate_name
    FROM
      user
    JOIN
      corporate
    ON
      user.corporate_id = corporate.corporate_id
    WHERE
      user_name = ?
    LIMIT 1
  ";
  $params = array($user_name);

  return fetch_query($db, $sql, $params);
}

// ユーザーの絞り込み
function get_manage($db,$manage){
  $sql = "
    SELECT
      user.user_id,
      user.user_name,
      user.manage,
      user.corporate_id,
      user.created,
      user.logined,
      corporate.corporate_id,
      corporate.corporate_name
    FROM
      user
    JOIN
      corporate
    ON
      user.corporate_id = corporate.corporate_id
    WHERE
      user.manage = ?
  ";
  $params = array($manage);

  return fetch_all_query($db, $sql, $params);
}


// 法人絞り込み検索
function get_sarch_corporate($db, $corporate_id){
  $sql = "
      SELECT
          user.user_id,
          user.user_name,
          user.manage,
          user.corporate_id,
          user.created,
          user.logined,
          corporate.corporate_id,
          corporate.corporate_name
      FROM
          user
      JOIN
          corporate
      ON
          user.corporate_id = corporate.corporate_id
      WHERE
          user.corporate_id = ?
  ";
  $params = array($corporate_id);

  return fetch_all_query($db, $sql, $params);
}



function get_manage_corporate($db, $sarch_manage, $sarch_corporate){
  $sql = "
    SELECT
      user.user_id,
      user.user_name,
      user.manage,
      user.corporate_id,
      user.created,
      user.logined,
      corporate.corporate_id,
      corporate.corporate_name
    FROM
      user
    JOIN
      corporate
    ON
      user.corporate_id = corporate.corporate_id
    WHERE
      user.manage = ?
    AND
      user.corporate_id = ?
  ";
  $params = array($sarch_manage, $sarch_corporate);

  return fetch_all_query($db, $sql, $params);
}

// ログインの実行
function login_as($db, $user_name, $password){
  $user = get_user_by_name($db, $user_name);
  if($user === false || $user['password'] !== $password){
    set_error('ユーザ名またはパスワードが違います。');
    return false;
  }
  set_session('user_id', $user['user_id']);
  return $user;
}

// セッションからユーザー情報を取得
function get_login_user($db){
  $login_user_id = get_session('user_id');

  return get_user($db, $login_user_id);
}

// ユーザー情報の登録
function regist_user($db, $select_code, $corporate_code, $select_name, $user_name, $password, $password_confirmation, $corporate_id) {
  if( is_valid_user($select_code, $corporate_code, $select_name, $user_name, $password, $password_confirmation) === false){
    return false;
  }

  return insert_user($db, $user_name, $password, $corporate_id);
}

// 管理ユーザー
function is_manager($user){
  return $user['manage'] === USER_TYPE_MANAGER;
}

// 発表者
function is_presenter($user){
  return $user['manage'] === USER_TYPE_PRESENTER;
}

// 一般ユーザー
function is_normal($user){
  return $user['manage'] === USER_TYPE_NORMAL;
}

// ユーザー管理区分の変更
function update_login($db, $user_id){
  $sql = "
    UPDATE
      user
    SET
      logined = NOW()
    WHERE
      user_id = ?
    LIMIT 1
  ";
  $params = array($user_id);

  return execute_query($db, $sql, $params);
}

// ユーザー管理区分の変更
function update_user_manage($db, $user_id, $manage){
  $sql = "
    UPDATE
      user
    SET
      manage = ?
    WHERE
      user_id = ?
    LIMIT 1
  ";
  $params = array($manage, $user_id);

  return execute_query($db, $sql, $params);
}


// ユーザーの削除
function destroy_user($db, $user_id){
  $user = get_user($db, $user_id);
  if($user === false){
    return false;
  }
  $db->beginTransaction();
  if(delete_user($db, $user['user_id'])){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
}

// ユーザー削除のデリート文
function delete_user($db, $user_id){
  $sql = "
    DELETE FROM
      user
    WHERE
      user_id = ?
    LIMIT 1
  ";
  $params = array($user_id);

  return execute_query($db, $sql, $params);
}

// ユーザー名の変更
function change_user_name($db, $acount, $user_name){
  if(is_valid_user_name($user_name) === false){
      return false;
  }
  update_user_name($db, $acount, $user_name);

  return true;
}

// ユーザー名の変更のアップデート文
function update_user_name($db, $acount, $user_name){
  $sql = "
      UPDATE
          user
      SET
          user_name = ?
      WHERE
          user_id = ?
  ";
  $params = array($user_name, $acount);

  return execute_query($db, $sql, $params);
}

// ユーザーパスワードの変更
function change_user_password($db, $select_id, $select_password, $old_password, $new_password, $password_confirmation){
  if(valid_password($select_password, $old_password, $new_password, $password_confirmation) === false){
    return false;
  }
  update_user_password($db, $select_id, $new_password);
  return true;
}

// パスワード変更のチェック
function valid_password($select_password, $old_password, $new_password, $password_confirmation){
  
  $is_valid_user_password = is_valid_user_password($select_password, $old_password);
  $is_valid_password = is_valid_password($new_password, $password_confirmation);
  return $is_valid_user_password && $is_valid_password;
}

// 保存されているパスワードとPOSTされたパスワードがあっているかのチェック
function is_valid_user_password($select_password, $old_password){
  $is_valid = true;
  
  if($select_password !== $old_password){
    set_error('パスワードが違います。');
    $is_valid = false;
  }
  return $is_valid;
}

// パスワード変更のアップデート文
function update_user_password($db, $select_id, $new_password){
  $sql = "
    UPDATE
      user
    SET
      password = ?
    WHERE
      user_id = ?
  ";
  $params = array($new_password, $select_id);

  return execute_query($db, $sql, $params);
}

// ユーザー登録のチェック
function is_valid_user($select_code, $post_code, $select_name, $user_name, $password, $password_confirmation){
  // 短絡評価を避けるため一旦代入。
  $is_valid_corporate_code = is_valid_corporate_code($select_code, $post_code);
  $is_valid_user_name = is_valid_regist_user_name($select_name, $user_name);
  $is_valid_password = is_valid_password($password, $password_confirmation);
  return $is_valid_user_name && $is_valid_password && $is_valid_corporate_code;
}

// 法人コードのチェック
function is_valid_corporate_code($select_code, $post_code) {
  $is_valid = true;
  if($post_code !== $select_code){
    set_error('法人コードが違います。コードを確認してください。');
    $is_valid = false;
  }
  return $is_valid;
}

// ユーザー名のチェック
function is_valid_user_name($user_name) {
  $is_valid = true;
  if(is_valid_length($user_name, USER_NAME_LENGTH_MIN, USER_NAME_LENGTH_MAX) === false){
    set_error('ユーザー名は'. USER_NAME_LENGTH_MIN . '文字以上、' . USER_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  // if(is_alphanumeric($user_name) === false){
  //   set_error('ユーザー名は半角英数字で入力してください。');
  //   $is_valid = false;
  // }
  return $is_valid;
}

// ユーザー登録時のユーザ名チェック
function is_valid_regist_user_name($select_name, $user_name) {
  $is_valid = true;
  if(is_valid_length($user_name, USER_NAME_LENGTH_MIN, USER_NAME_LENGTH_MAX) === false){
    set_error('ユーザー名は'. USER_NAME_LENGTH_MIN . '文字以上、' . USER_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  if($select_name === $user_name){
    set_error('ユーザー名が存在します。他のユーザ名を入力してください。');
    $is_valid = false;
  }

  return $is_valid;
}

// パスワードのチェック
function is_valid_password($password, $password_confirmation){
  $is_valid = true;
  if(is_valid_length($password, USER_PASSWORD_LENGTH_MIN, USER_PASSWORD_LENGTH_MAX) === false){
    set_error('パスワードは'. USER_PASSWORD_LENGTH_MIN . '文字以上、' . USER_PASSWORD_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  if(is_alphanumeric($password) === false){
    set_error('パスワードは半角英数字で入力してください。');
    $is_valid = false;
  }
  if($password !== $password_confirmation){
    set_error('パスワードがパスワード(確認用)と一致しません。');
    $is_valid = false;
  }
  return $is_valid;
}
  
// ユーザー情報のインサート
function insert_user($db, $user_name, $password ,$coroprate_id){
  $sql = "
    INSERT INTO
      user(user_name, password, corporate_id, created, logined)
    VALUES (?, ?, ?, NOW(), NOW());
  ";
  $params = array($user_name, $password ,$coroprate_id);

  return execute_query($db, $sql, $params);
}


// チェックされたユーザの削除
function destroy_check_user($db, $user_ids){
  $data = get_check_user($db, $user_ids);
  if($data === false){
    return false;
  }
  $db->beginTransaction();
  if(delete_check_user($db, $user_ids)){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
}


// 
function delete_check_user($db, $user_ids){
  $sql = "
    DELETE
    FROM
      user
    WHERE
      user_id
    IN(". substr(str_repeat(',?',count($user_ids)),1). ")
  ";
  
  $params = $user_ids;
  
  return execute_query($db, $sql, $params);
}

// チェックした情報を取得
function get_check_user($db, $user_ids){
  $sql = "
    SELECT
      *
    FROM
      user
    WHERE
      user_id
    IN(". substr(str_repeat(',?',count($user_ids)),1). ")
  ";
  $params = $user_ids;

  return fetch_query($db, $sql, $params);
}


function check_user_change_normal($db, $user_ids){
  $sql = "
    UPDATE
      user
    SET
      manage = 0
    WHERE
      user_id
    IN(". substr(str_repeat(',?',count($user_ids)),1). ")
  ";
  $params = $user_ids;

  return execute_query($db, $sql, $params);
}


function check_user_change_presenter($db, $user_ids){
  $sql = "
    UPDATE
      user
    SET
      manage = 1
    WHERE
      user_id
    IN(". substr(str_repeat(',?',count($user_ids)),1). ")
  ";
  $params = $user_ids;

  return execute_query($db, $sql, $params);
}


function check_user_change_manager($db, $user_ids){
  $sql = "
    UPDATE
      user
    SET
      manage = 2
    WHERE
      user_id
    IN(". substr(str_repeat(',?',count($user_ids)),1). ")
  ";
  $params = $user_ids;

  return execute_query($db, $sql, $params);
}
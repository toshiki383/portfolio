<?php
// 関数の取得
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// 法人管理ページの一覧のセレクト文
function get_corporates($db){
    $sql = "
        SELECT
            *
        FROM
            corporate
    ";
  return fetch_all_query($db, $sql);
}

// 法人IDから特定の法人情報を取得
function get_corporate($db, $corporate_id){
    $sql = "
        SELECT
            *
        FROM
            corporate
        WHERE
            corporate_id = ?
        LIMIT 1
    ";
    $params = array($corporate_id);

    return fetch_query($db, $sql, $params);
}


// ユーザー登録時の法人コードチェックのセレクト文
function get_corporate_code($db, $post_code){
    $sql = "
        SELECT
            corporate.corporate_id,
            corporate.corporate_code
        FROM
            corporate
        WHERE
            corporate.corporate_code = ?
    ";
    $params = array($post_code);

    return fetch_query($db, $sql, $params);

}


// 法人削除

// 法人の削除
function destroy_corporate($db, $corporate_id){
    $corporate = get_corporate($db, $corporate_id);
    
    if($corporate === false){
        return false;
    }
    $db->beginTransaction();
    if(delete_corporate($db, $corporate['corporate_id'])){
        
        $db->commit();
        return true;
    }
    $db->rollback();
    return false;
}
  
// 法人削除のデリート文
function delete_corporate($db, $corporate_id){
    $sql = "
      DELETE FROM
        corporate
      WHERE
        corporate_id = ?
      LIMIT 1
    ";
    $params = array($corporate_id);
  
    return execute_query($db, $sql, $params);
}

// 法人登録


// 法人の登録の関数
function regist_corporate($db, $corporate_name, $corporate_code){
    if(validate_corporate($corporate_name, $corporate_code) === false){
        return false;
    }
    return regist_corporate_transaction($db, $corporate_name, $corporate_code);
}

// 法人登録のトランザクション
function regist_corporate_transaction($db, $corporate_name, $corporate_code){
    $db->beginTransaction();
    if(insert_corporate($db, $corporate_name, $corporate_code)){
        $db->commit();
        return true;
    }
    $db->rollback();
    return false;   
}

// 法人登録のインサート文
function insert_corporate($db, $corporate_name, $corporate_code){
    
    $sql = "
      INSERT INTO
        corporate(
          corporate_name,
          corporate_code,
          created,
          updated
        )
      VALUES(?, ?, NOW(), NOW())
    ";
  
    $params = array($corporate_name, $corporate_code);
  
    return execute_query($db, $sql, $params);
}

// 法人情報を確認する
function validate_corporate($corporate_name, $corporate_code){
    $is_valid_corporate_name = is_valid_corporate_name($corporate_name);
    $is_valid_check_corporate_code = is_valid_check_corporate_code($corporate_code);
  
    return $is_valid_corporate_name && $is_valid_check_corporate_code;
}

// 法人名のチェック
function is_valid_corporate_name($corporate_name){
    $is_valid = true;
    if(is_valid_length($corporate_name, DATA_NAME_LENGTH_MIN, DATA_NAME_LENGTH_MAX) === false){
      set_error('法人名は'. DATA_NAME_LENGTH_MIN . '文字以上、' . DATA_NAME_LENGTH_MAX . '文字以内にしてください。');
      $is_valid = false;
    }
    return $is_valid;
}

// 法人コードのチェック
function is_valid_check_corporate_code($corporate_code){
    $is_valid = true;
    if(is_valid_length($corporate_code, CORPORATE_CODE_LENGTH_MIN, CORPORATE_CODE_LENGTH_MAX) === false){
      set_error('法人コードは'. CORPORATE_CODE_LENGTH_MIN . '文字以上、' . CORPORATE_CODE_LENGTH_MAX . '文字以内にしてください。');
      $is_valid = false;
    }
    if(is_alphanumeric($corporate_code) === false){
        set_error('法人コードは半角英数字で入力してください。');
        $is_valid = false;
      }
    return $is_valid;
}


// 法人名変更

// 法人名変更の関数
function change_corporate_name($db, $corporate_id, $corporate_name){
    if(is_valid_corporate_name($corporate_name) === false){
        return false;
    }
    update_corporate_name($db, $corporate_id, $corporate_name);
    
    return true;
}

// 法人名変更のアップデート文
function update_corporate_name($db, $corporate_id, $corporate_name){
    $sql = "
        UPDATE
            corporate
        SET
            corporate_name = ?,
            updated = NOW()
        WHERE
            corporate_id = ?
    ";
    $params = array($corporate_name, $corporate_id);
  
    return execute_query($db, $sql, $params);
}


// 法人コード変更

// 法人コード変更の関数
function change_corporate_code($db, $corporate_id, $corporate_code){
    if(is_valid_check_corporate_code($corporate_code) === false){
        return false;
    }
    update_corporate_code($db, $corporate_id, $corporate_code);
    
    return true;
}

// 法人コード変更のアップデート文
function update_corporate_code($db, $corporate_id, $corporate_code){
    $sql = "
        UPDATE
            corporate
        SET
            corporate_code = ?,
            updated = NOW()
        WHERE
            corporate_id = ?
    ";
    $params = array($corporate_code, $corporate_id);
  
    return execute_query($db, $sql, $params);
}
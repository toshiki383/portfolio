<?php

// 関数の取得
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';


// トップページの情報取得

// 公開されている商品情報を取得
function get_open_datas($db){
  return get_datas($db, true);
}

// トップページの研修資料一覧のセレクト文
function get_datas($db, $is_open = false){
    $sql = '
        SELECT
        data.data_id,
        data.training_date,
        data.data_name,
        data.pdf,
        data.status
        FROM
        data
    ';
    if($is_open === true){
        $sql .= '
            WHERE status = 1
        ';
    }

  return fetch_all_query($db, $sql);
}


// ユーザIDから資料データを取得
function get_user_data($db, $acount){
  $sql = "
    SELECT
      data.data_id,
      data.training_date,
      data.data_name,
      data.pdf,
      data.status,
      data.open_datetime,
      data.close_datetime,
      data.user_id,
      data.created,
      user.user_id,
      user.user_name
    FROM
      data
    JOIN
      user
    ON
      data.user_id = user.user_id
    WHERE
      data.user_id = ?
  ";
$params = array($acount);

return fetch_all_query($db, $sql, $params);
}


// 指定した資料IDから資料情報の取得
function get_data($db, $data_id){
  $sql = "
    SELECT
      *
    FROM
      data
    WHERE
      data_id = ?
    LIMIT 1
  ";
  $params = array($data_id);

  return fetch_query($db, $sql, $params);
}

// 全ての研修資料のセレクト文
function get_all_data($db){
  $sql = "
    SELECT
      data.data_id,
      data.training_date,
      data.data_name,
      data.pdf,
      data.status,
      data.open_datetime,
      data.close_datetime,
      data.user_id,
      data.created,
      user.user_id,
      user.user_name
    FROM
      data
    JOIN
      user
    ON
      data.user_id = user.user_id
  ";

  return fetch_all_query($db, $sql);
}

// ユーザ区分での絞り込み
function get_status($db,$status){
  $sql = "
    SELECT
      *
    FROM
      data
    WHERE
      data.status = ?
  ";
  $params = array($status);

  return fetch_all_query($db, $sql, $params);
}

// ユーザー区分の変更のアップデート文
function update_data_status($db, $data_id, $status){
  $sql = "
    UPDATE
      data
    SET
      status = ?
    WHERE
      data_id = ?
    LIMIT 1
  ";
  $params = array($status, $data_id);

  return execute_query($db, $sql, $params);
}

// 研修名変更


// 研修名変更の関数
function change_data_name($db, $data_id, $data_name){
  if(is_valid_data_name($data_name) === false){
      return false;
  }
  update_data_name($db, $data_id, $data_name);
  
  return true;
}

// 研修名変更のアップデート文
function update_data_name($db, $data_id, $data_name){
  $sql = "
    UPDATE
      data
    SET
      data_name = ?
    WHERE
      data_id = ?
    LIMIT 1
  ";
  $params = array($data_name, $data_id);

  return execute_query($db, $sql, $params);
}


// 研修日変更

// 研修日変更の関数
function change_training_date($db, $data_id, $training_date){
  if(is_date($training_date) === false){
    set_error('日付を正しく入力してください');
      return false;   
  }
  update_training_date($db, $data_id, $training_date);
  
  return true;
}

// 研修日変更のアップデート文
function update_training_date($db, $data_id, $training_date){
  $sql = "
    UPDATE
      data
    SET
      training_date = ?
    WHERE
      data_id = ?
    LIMIT 1
  ";
  $params = array($training_date, $data_id);

  return execute_query($db, $sql, $params);
}


// 公開開始時間変更

// 公開開始日時変更の関数
function change_open_datetime($db, $data_id, $open_datetime){
  if(is_datetime($open_datetime) === false){
      return false;
  }
  update_open_datetime($db, $data_id, $open_datetime);
  
  return true;
}

// 公開開始日時のアップデート文
function update_open_datetime($db, $data_id, $open_datetime){
  $sql = "
    UPDATE
      data
    SET
      open_datetime = ?
    WHERE
      data_id = ?
    LIMIT 1
  ";
  $params = array($open_datetime, $data_id);

  return execute_query($db, $sql, $params);
}


// 公開終了時間変更

// 公開終了日時変更の関数
function change_close_datetime($db, $data_id, $close_datetime){
  if(is_datetime($close_datetime) === false){
      return false;
  }
  update_close_datetime($db, $data_id, $close_datetime);
  
  return true;
}

// 公開終了日時のアップデート文
function update_close_datetime($db, $data_id, $close_datetime){
  $sql = "
    UPDATE
      data
    SET
      close_datetime = ?
    WHERE
      data_id = ?
    LIMIT 1
  ";
  $params = array($close_datetime, $data_id);

  return execute_query($db, $sql, $params);
}


// 資料の登録

// 資料情報の登録
function regist_data($db, $training_date, $data_name, $pdf, $user_id){
  $filename = get_upload_filename($pdf);
  if(validate_data($training_date, $data_name, $filename) === false){
    return false;
  }
  return regist_data_transaction($db, $training_date, $data_name, $user_id, $pdf, $filename);
}

// 資料登録のトランザクション
function regist_data_transaction($db, $training_date, $data_name, $user_id, $pdf, $filename){
  $db->beginTransaction();
  if(insert_data($db, $training_date, $data_name, $filename, $user_id)
      && save_pdf($pdf, $filename)){
      $db->commit();
      return true;
  }
  $db->rollback();
  return false;
}

// 資料のインサート
function insert_data($db, $training_date, $data_name, $filename, $user_id){
    $sql = "
      INSERT INTO
        data(training_date, data_name, pdf, user_id, created)
      VALUES (?, ?, ?, ?, NOW())
    ";
    $params = array($training_date, $data_name, $filename, $user_id);
  
    return execute_query($db, $sql, $params);
  }

// 資料情報を確認する
function validate_data($training_date, $data_name, $filename){
  
  $is_valid_training_date = is_valid_training_date($training_date);
  $is_valid_data_name = is_valid_data_name($data_name);
  // $is_valid_open_datetime = is_valid_datetime($open_datetime);
  // $is_valid_close_datetime = is_valid_datetime($close_datetime);
  $is_valid_data_filename = is_valid_data_filename($filename);

  return $is_valid_training_date
    && $is_valid_data_name
    // && $is_valid_open_datetime
    // && $is_valid_close_datetime
    && $is_valid_data_filename;
}

// 公開日時のチェック
function is_valid_datetime($datetime){
  $is_valid = true;
  if(is_datetime($datetime) === false){
    set_error('公開日時を正しく入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

// 資料名のチェック
function is_valid_data_name($data_name){
  $is_valid = true;
  if(is_valid_length($data_name, DATA_NAME_LENGTH_MIN, DATA_NAME_LENGTH_MAX) === false){
    set_error('資料名は'. DATA_NAME_LENGTH_MIN . '文字以上、' . DATA_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  return $is_valid;
}


// 研修日のチェック
function is_valid_training_date($training_date){
  $is_valid = true;
  if(is_date($training_date) === false){
    $is_valid = false;
    set_error('研修日を正しく入力してください。');
  }
  return $is_valid;
}


// filenameのチェック
function is_valid_data_filename($filename){
  $is_valid = true;
  if($filename === ''){
    $is_valid = false;
  }
  return $is_valid;
}


// 資料削除

// 資料の削除関数
function destroy_data($db, $data_id){
  $data = get_data($db, $data_id);
  dd($data['pdf']);
  if($data === false){
    return false;
  }
  $db->beginTransaction();
  if(delete_data($db, $data['data_id'])
    && delete_pdf($data['pdf'])){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
}


// 資料削除

// 資料削除のデリート文
function delete_data($db, $data_id){
  $sql = "
    DELETE FROM
      data
    WHERE
      data_id = ?
    LIMIT 1
  ";
  $params = array($data_id);

  return execute_query($db, $sql, $params);
}


// 資料の削除関数
function destroy_check_data($db, $data_ids){
  $datas = get_check_data($db, $data_ids);
  if($datas === false){
    return false;
  }
  $db->beginTransaction();
  if((delete_check_data($db, $data_ids)) === true){
    
    foreach($datas as $data){
      delete_pdf($data['pdf']);
    }
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
}

function delete_check_data($db, $data_ids){
  $sql = "
    DELETE
    FROM
      data
    WHERE
      data_id
    IN(". substr(str_repeat(',?',count($data_ids)),1). ")
  ";
  $params = $data_ids;
  
  return execute_query($db, $sql, $params);
}

// チェックしたIDから情報を取得
function get_check_data($db, $data_ids){
  $sql = "
    SELECT
      pdf
    FROM
      data
    WHERE
      data_id
    IN(". substr(str_repeat(',?',count($data_ids)),1). ")
  ";
  $params = $data_ids;

  return fetch_all_query($db, $sql, $params);
}

function check_data_change_close($db, $data_ids){
  $sql = "
    UPDATE
      data
    SET
      status = 0
    WHERE
      data_id
    IN(". substr(str_repeat(',?',count($data_ids)),1). ")
  ";
  $params = $data_ids;

  return execute_query($db, $sql, $params);
}


function check_data_change_open($db, $data_ids){
  $sql = "
    UPDATE
      data
    SET
      status = 1
    WHERE
      data_id
    IN(". substr(str_repeat(',?',count($data_ids)),1). ")
  ";
  $params = $data_ids;

  return execute_query($db, $sql, $params);
}
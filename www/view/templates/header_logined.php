<header style="
  margin-left: auto;
  margin-right: auto;
  width: 960px;
  margin-bottom:10px;
  border-bottom: solid 1px #dddddd;
  display: flex;justify-content:space-between;
  ">
    <div style="padding-bottom: 0;">
      <ul style="display:flex;">
        <li style="list-style-type:none;"><a style="text-decoration: none;color:navy;border-right: solid 1px #dddddd;padding: 15px;margin: 0px 1px 0px 1px;" href="<?php print(HOME_URL);?>">ホーム</a></li>
      <?php if(is_manager($acount) || is_presenter($acount)){ ?>
        <li style="list-style-type:none;">
          <a style="text-decoration:none;color:navy;border-right: solid 1px #dddddd;padding: 15px;margin: 0px 1px 0px 1px;" href="<?php print(DATA_URL);?>">研修資料管理</a>
        </li>
      <?php } ?>
      <?php if(is_manager($acount)){ ?>
        <li style="list-style-type:none;">
          <a style="text-decoration:none;color:navy;border-right: solid 1px #dddddd;padding: 15px;margin: 0px 1px 0px 1px;" href="<?php print(CORPORATE_URL);?>">法人管理</a>
        </li>
          <li style="list-style-type:none;">
            <a style="text-decoration:none;color:navy;border-right: solid 1px #dddddd;padding: 15px;margin: 0px 1px 0px 1px;" href="<?php print(USER_URL);?>">ユーザー管理</a>
          </li>
        <?php } ?>
        <li style="list-style-type:none;">
          <a style="text-decoration:none;color:navy;border-right: solid 1px #dddddd;padding: 15px;margin: 0px 1px 0px 1px;" href="<?php print(MYPAGE_URL);?>">マイページ</a>
        </li>
        <li style="list-style-type:none;">
          <a style="text-decoration:none;color:navy;padding: 15px;margin: 0px 1px 0px 1px;" href="<?php print(LOGOUT_URL);?>" id="logout">ログアウト</a>
        </li>
      </ul>
    </div>
    <div style="text-align: left;">
      <label>法人名 : </label>
      <?php print $acount['corporate_name'];?><br>
      
      <label>区分 : </label>
      <?php if(is_manager($acount)){
        print '管理者ユーザー';
      }else if(is_presenter($acount)){
        print '発表者ユーザー';
      }else {
        print '一般ユーザー';
      }
      ?><br>
      <label>名前 : </label>
      <?php print($acount['user_name']); ?>
    </div>
</header>
<script>
  // ボタンを押した時の確認
  $('#logout').on('click', () => confirm('ログアウトします。よろしいですか？'))
</script>
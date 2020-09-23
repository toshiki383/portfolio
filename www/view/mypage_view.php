<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>マイページ</title>
        <?php include VIEW_PATH . 'templates/head.php'; ?>
        <style>
            body{
                margin: 0 auto;
                margin-left: auto;
                margin-right: auto;
                min-width: 960px;
                text-align: center;
            }
            dl{
                display: inline-block;
                text-align: left;
                border: solid 1px;
            }
            dd{
                text-align: center;
                padding: 10px;
            }
            dt{
                border-top: solid 1px;
                border-bottom: solid 1px;
                padding: 10px;
                background-color: #dddddd;
            }
            .container {
                margin: 0 auto;
                text-align: center;
            }
            #delete{
                background-color: #ffffff;
                text-align: center;
                border-bottom: none;
            }
            #name{
                border-top: none;
            }
            .delete{
                color: red;
            }
        </style>
    </head>
    <body>
    <?php include VIEW_PATH . 'templates/header_logined.php';?>
        <div class="container">
            
            <h1>マイページ</h1>
            <p id="explanation">
                このページではそれぞれのユーザーが自分の情報の確認削できます。<br>
                またユーザ名、パスワードの変更ができ、アカウントの削除もできます。
            </p>
            
            <?php include VIEW_PATH . 'templates/messages.php'; ?>
            <dl>
                <dt id="name">ユーザーID</dt>
                <dd><?php print h($acount['user_id']); ?></dd>
                <dt>ユーザー名</dt>
                <dd>
                    <form method="post" action="change_user_name.php">
                        <input type="hidden" name="csrf_token" value="<?=$token?>">
                        <input type="text" name="user_name" value="<?php print h($acount['user_name']);?>">
                        <input type="submit" value="変更">
                    </form>
                </dd>
                <dt>パスワード</dt>
                <dd>********
                    <form method="post" action="change_user_password.php">
                        <input type="submit" value="変更">
                    </form>
                </dd>
                <dt>事業所名</dt>
                <dd><?php print h($acount['corporate_name']); ?></dd>
                <dt>ユーザー区分</dt>
                <dd>
                    <?php if(is_manager($acount)){
                                print '管理者';
                        }else if(is_presenter($acount)){
                            print '発表者';
                        }else {
                            print '一般';
                        }
                    ?>
                </dd>
                <dt id ="delete">
                    <form method="post" action="user_tool_delete.php">
                        <input type="hidden" name="csrf_token" value="<?=$token?>">
                        <input type="submit" value="アカウント削除" class="delete">
                        <input type="hidden" name="user_id" value="<?php print h($acount['user_id']); ?>">
                    </form>
                </dt>
            </dl>
        </div>
        <script>
            // ボタンを押した時の確認
            $('.delete').on('click', () => confirm('本当に削除しますか？'))
        </script>
    </body>
</html>
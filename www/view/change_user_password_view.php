<!DOCTYPE html>
<html lang="ja">
    <head>
        <?php include VIEW_PATH . 'templates/head.php'; ?>
        <style>
            body{
                margin: 0 auto;
                margin-left: auto;
                margin-right: auto;
                min-width: 960px;
                text-align: center;
            }
            a{
                text-decoration:none;
            }
        </style>
        <title>パスワード変更</title>
    </head>
    <body>

        <div class="container">
            <?php include VIEW_PATH . 'templates/header_logined.php';?>
            <h1>パスワード変更</h1>
            <p>パスワードを変更するページです</p>
            
            <?php include VIEW_PATH . 'templates/messages.php'; ?>
            <form method="post" action="change_user_password_process.php">
                <input type="hidden" name="csrf_token" value="<?=$token?>">
                <p>
                    <label>現在のパスワード : <label>
                    <input type="password" name="old_password">
                </p>
                <p>
                    <label>新しいパスワード : <label>
                    <input type="password" name="new_password">
                </p>
                <p>
                    <label>新しいパスワード（確認用） : <label>
                    <input type="password" name="password_confirmation">
                </p>
                <p>
                    <input type="submit" value="送信">
                    <input type="hidden" name="user_id" value="<?php print h($acount['user_id']); ?>">
                </p>
            </form>
            <p><a href="mypage.php">マイページへ</a></p>
        </div>
    </body>
</html>
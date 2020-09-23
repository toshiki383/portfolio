<!DOCTYPE html>
<html lang="ja">
    <head>
        <?php include VIEW_PATH . 'templates/head.php'; ?>
        <title>新規会員登録ページ</title>
        <style>
            body{
                margin: 0 auto;
                margin-left: auto;
                margin-right: auto;
                min-width: 960px;
                text-align: center;
            }
            #explanation, #form{
                display: inline-block;
                text-align: left;
            }
            a{
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>社会福祉研修資料</h1>
            <h2>新規会員登録</h2>

            <?php include VIEW_PATH . 'templates/messages.php'; ?>
            
            <form method="post" action="register_process.php">
                <div id="form">
                    <input type="hidden" name="csrf_token" value="<?=$token?>">
                    <p>
                        <label for="corporate_code">事業所コード: </label>
                        <input type="text" name="corporate_code" id="corporate_code">
                    </p>
                    <p>
                        <label for="user_name">名前: </label>
                        <input type="text" name="user_name" id="user_name">
                    </p>
                    <p>
                        <label for="password">パスワード: </label>
                        <input type="password" name="password" id="password">
                    </p>
                    <p>
                        <label for="password_confirmation">パスワード（確認用）: </label>
                        <input type="password" name="password_confirmation" id="password_confirmation">
                    </p>
                </div>
                <p><input type="submit" value="登録"></p>
            </form>
            <p><a href="login.php">ログインページへ</a></p>
            <div id="explanation">
                <p>
                    このページでは新規の会員登録をすることができます。<br>
                    登録する際には法人コードを必要をしています。<br>
                    各法人に配布された法人コードと一致すれば登録することができる仕組みです。<br>
                    法人コードを<br>
                    "aaaaaaaa"と入力すると「社会福祉法人　AA施設」<br>
                    "bbbbbbbb"と入力すると「社会福祉法人　BB施設」<br>
                    "cccccccc"と入力すると「社会福祉法人　CC施設」<br>
                    で登録することができます。<br>
                    登録されたユーザのユーザ区分は自動的に「一般」となります。
                </p>
            </div>
        </div>
    </body>
</html>
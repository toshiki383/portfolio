<!DOCTYPE html>
<html lang="ja">
    <head>
        <?php include VIEW_PATH . 'templates/head.php'; ?>
        <title>ログインページ</title>
        <style>
            body{
                margin: 0 auto;
                margin-left: auto;
                margin-right: auto;
                min-width: 960px;
                text-align: center;
            }
            #explanation{
                display: inline-block;
                text-align: left;
            }
            a{
                text-decoration: none;
            }
        </style>
    </haed>
    <body>
        <div class="container">
            <h1>社会福祉研修資料</h1>
            <?php include VIEW_PATH . 'templates/messages.php'; ?>
            <form method="post" action="login_process.php">
                <input type="hidden" name="csrf_token" value="<?=$token?>">
                <p>
                    <label for="name">名前: </label>
                    <input type="text" name="name" id="name">
                </p>
                <p>
                    <label for="password">パスワード: </label>
                    <input type="password" name="password" id="password">
                </p>
                <input type="submit" value="ログイン">
            </form>
            <p><a href="register.php">新規会員登録</a></p>
            <h2>サイトのご案内</h2>
            <div id="explanation">
                <p>
                    このサイトは社会福祉法人向けの、研修資料の共有を目的としたWEBサイトです。
                </p>
                <p>
                    ユーザ区分は「一般」「発表者」「管理者」の三種類あります。<br>
                    ユーザ名は一般が「normal」、発表者は「presenter」、管理者は「manager」です。<br>
                    パスワードは全て「password」でログインできます。<br>
                    管理者はこのサイトの全ての権限があります。<br>
                    発表者は研修資料のアップロードを行うことができ、自分の研修資料のみ管理することができます。<br>
                    一般は公開されている研修資料を見ることができます。<br>
                    ご確認の程、よろしくお願い致します。
                </p>
            </div>
            </script>
        </div>
    </body>
</html>
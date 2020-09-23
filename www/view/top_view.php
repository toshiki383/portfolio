<!DOCTYPE html>
<html lang="ja">
    <head>
        <?php include VIEW_PATH . 'templates/head.php'; ?>
        <title>トップページ</title>
        <style>
            body{
                margin: 0;
                min-width: 960px;
            }
            .container {
                width: 960px;
                margin: 0 auto;
            }
        </style>
    </head>
    <body>
        <div class="container">
        <?php include VIEW_PATH . 'templates/header_logined.php';?>
        
            <h1>社会福祉研修資料一覧</h1>
        
            <div class="content">
                <p>
                    このページはログインしている全てのユーザが閲覧できるページです。<br>
                    資料が公開されていればリンクが表示されます。<br>
                    リンクを押すと資料を閲覧することができます。
                </p>
                <button class="button">▼ このページの説明</button>
            </div>

            <?php include VIEW_PATH . 'templates/messages.php'; ?>

            <?php if(count($datas) > 0){ ?>

                <?php foreach($datas as $data){ ?>
                    <ul>
                        <li><a href="<?php print(PDF_PATH . $data['pdf']); ?>"><?php print h($data['training_date']) . '　' . h($data['data_name']);?>(PDF)</a></li>
                    </ul>
                <?php } ?>

            <?php } else { ?>
                <p>公開されている研修資料はありません。</p>
            <?php } ?>
        </div>
        <script>
            // 説明文
            $(function () {
                $('.button').prevAll().hide();
                $('.button').click(function () {
                    if ($(this).prevAll().is(':hidden')) {
                        $(this).prevAll().slideDown();
                        $(this).text('▲閉じる').addClass('close');
                    } else {
                        $(this).prevAll().slideUp();
                        $(this).text('▼説明を見る').removeClass('close');
                    }
                });
            });
        </script>
    </body>
</html>
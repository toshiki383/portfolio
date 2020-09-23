<!DOCTYPE html>
<html lang="ja">
    <head>
        <?php include VIEW_PATH . 'templates/head.php'; ?>
        <title>法人管理</title>
        <style>
            body{
                margin: 0;
                min-width: 960px;
            }
            .container {
                width: 960px;
                margin: 0 auto;
            }
            table{
                border:solid 1px;
                margin:0;
            }
            td, th{
                border-right:solid 1px;
                border-bottom:solid 1px;
            }
            .delete{
                color: red;
            }
        </style>
    </head>
    <body>

        <div class="container">

            <?php include VIEW_PATH . 'templates/header_logined.php';?>

            <h1>法人管理</h1>
            <div class="content">
                <p>
                    このページでは法人アカウントを管理しています。<br>
                    新しい法人の登録、既存の法人の削除ができます。<br>
                    また法人名、法人コードの編集ができます。                    
                </p>
                <button class="button">▼ このページの説明</button>
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

            <form method="post" action="corporate_tool_insert.php">
                <input type="hidden" name="csrf_token" value="<?=$token?>">
                <p>
                    <label for="corporate_name">法人名: </label>
                    <input class="form-control" type="text" name="corporate_name" id="corporate_name">
                </p>
                <p>
                    <label for="corporate_code">法人コード: </label>
                    <input type="text" name="corporate_code" id="corporate_code">
                </p>
                <p><input type="submit" value="登録"></p>
            </form>

            <?php include VIEW_PATH . 'templates/messages.php'; ?>
            
            <table>
                <thead>
                    <tr>
                        <th>法人ID</th>
                        <th>法人名</th>
                        <th>法人コード</th>
                        <th>登録日時</th>
                        <th>更新日時</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($corporates as $corporate){?>
                        <tr>
                            <td><?php print h($corporate['corporate_id']); ?></td>
                            <td>
                                <form method="post" action="corporate_tool_changename.php">
                                    <input type="hidden" name="csrf_token" value="<?=$token?>">
                                    <input type="text" name="corporate_name" value="<?php print h($corporate['corporate_name']); ?>">
                                    <input type="hidden" name="corporate_id" value="<?php print h($corporate['corporate_id']); ?>">
                                    <input type="submit" value="変更">
                                </form>
                            </td>
                            <td>
                                <form method="post" action="corporate_tool_changecode.php">
                                    <input type="hidden" name="csrf_token" value="<?=$token?>">
                                    <input type="text" name="corporate_code" value="<?php print h($corporate['corporate_code']); ?>">
                                    <input type="hidden" name="corporate_id" value="<?php print h($corporate['corporate_id']); ?>">
                                    <input type="submit" value="変更">
                                </form>
                            </td>
                            <td><?php print h($corporate['created']); ?></td>
                            <td><?php print h($corporate['updated']); ?></td>
                            <td>
                                <form method="post" action="corporate_tool_delete.php">
                                    <input type="hidden" name="csrf_token" value="<?=$token?>">
                                    <input type="submit" value="削除" class="delete">
                                    <input type="hidden" name="corporate_id" value="<?php print h($corporate['corporate_id']); ?>">
                                </form>
                            </td>
                        </tr>
                    <?php  }?>
                </tbody>
            </table>
        </div>
        <script>
            // ボタン押した時の確認
            $('.delete').on('click', () => confirm('本当に削除しますか？'))
        </script>
    </body>
</html>
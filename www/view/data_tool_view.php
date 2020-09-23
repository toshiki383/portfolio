<!DOCTYPE html>
<html lang="ja">
    <head>
        <?php include VIEW_PATH . 'templates/head.php'; ?>
        <title>研修資料管理</title>
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
            #close{
                background-color: #aaaaaa;
            }
            .delete{
                color: red;
            }
        </style>
    </head>
    <body>

        <div class="container">

            <?php include VIEW_PATH . 'templates/header_logined.php';?>
            
            <h1>研修資料管理</h1>
            <div class="content">
                <p>
                    このページは研修資料をアップロード、変更、削除など研修資料を管理するページです。<br>
                    発表者と管理者がこのページにアクセスすることができます。<br>
                    発表者は自分のがアップした資料のみを管理できるのに対し、管理者は全ての資料の管理ができます。<br>
                    絞り込みの検索機能、チェックボックスの機能を実装しています。
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
            
            <div style="display: flex;justify-content: space-between;">
                <div>
                    <form method="post" action="data_tool_insert.php" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?=$token?>">
                        <p><label for="training_date">研修日 : </label><input type="date" name="training_date" id="training_date"></p>
                        <p><label for="date_name">研修名 : </label><input type="text" name="data_name" id="date_name"></p>
                        <p><label for="pdf">PDF : </label><input type="file"  name="pdf" id="pdf"></p>
                        <!-- <p>
                            <label>公開日時</label>
                            <input type="datetime-local" step="1" name="open_datetime">
                            <label>　〜　</label>
                            <input type="datetime-local" step="1" name="close_datetime">
                        <p> -->
                        <p><input type="submit" value="登録"></p>
                    </form>
                    <?php include VIEW_PATH . 'templates/messages.php'; ?>
                </div>
                
                <?php if(is_manager($acount) === true){ ?>
                    <div>
                        <label>公開ステータス絞り込み</label>
                        <form method="get">
                            <select name="sarch_status">
                                <option value="">全て</option>
                                <option value="close" <?php if($sarch_status === 'close'){?>selected<?php }?>>非公開</option>
                                <option value="open" <?php if($sarch_status === 'open'){?>selected<?php }?>>公開</option>
                            </select>
                            <input type="submit" name="" value="表示">
                        </form>
                    </div>
                <?php }?>
            </div>
            <?php if(count($datas) > 0){ ?>
            
                <div style="display:flex;">
                    <label>チェックした項目を</label>
                    <form method="post" action="datas_tool_change_status.php">
                        <input type="hidden" name="csrf_token" value="<?=$token?>">
                        <input type="submit" value="非公開" class="close">
                        <input type="hidden" name="status" value="close">
                        <div class="cdatas">
                        </div>
                    </form>
                    <form method="post" action="datas_tool_change_status.php">
                        <input type="hidden" name="csrf_token" value="<?=$token?>">
                        <input type="submit" value="公開" class="open">
                        <input type="hidden" name="status" value="open">
                        <div class="cdatas">
                        </div>
                    </form>
                    <form method="post" action="datas_tool_delete.php">
                        <input type="hidden" name="csrf_token" value="<?=$token?>">
                        <input type="submit" value="削除" class="delete">
                        <div class="cdatas">
                        </div>
                    </form>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="all_check"></th>
                            <th>資料ID</th>
                            <th>研修日</th>
                            <th>研修名</th>
                            <th>PDF</th>
                            <th>発表者</th>
                            <!-- <th>公開開始時間</th>
                            <th>公開終了時間</th> -->
                            <th>公開ステータス</th>
                            <th>登録日時</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($datas as $data){ ?>
                            <?php if($data['status'] === 0){?>
                            <tr id="close">
                            <?php }else{ ?>
                            <tr>
                            <?php } ?>
                                <td><input type="checkbox" class="cbox" value="<?php print h($data['data_id']); ?>"></td>
                                <td><?php print h($data['data_id']); ?></td>
                                <td>
                                    <form method="post" action="data_tool_change_training_date.php">
                                        <input type="hidden" name="csrf_token" value="<?=$token?>">
                                        <input type="date" name="training_date" value="<?php print h($data['training_date']); ?>">
                                        <input type="hidden" name="data_id" value="<?php print h($data['data_id']); ?>">
                                        <input type="submit" value="変更">
                                    </form>
                                </td>
                                <td>
                                    <form method="post" action="data_tool_change_name.php">
                                        <input type="hidden" name="csrf_token" value="<?=$token?>">
                                        <input type="text" name="data_name" value="<?php print h($data['data_name']); ?>">
                                        <input type="hidden" name="data_id" value="<?php print h($data['data_id']); ?>">
                                        <input type="submit" value="変更">
                                    </form>
                                </td>
                                <td>
                                    <a href="<?php print(PDF_PATH . $data['pdf']); ?>"><?php print h($data['data_name']); ?></a>
                                </td>
                                <td><?php print h($data['user_name']);?></td>
                                <!-- <td>
                                    <form method="post" action="data_tool_change_open_datetime.php">
                                        <input type="hidden" name="csrf_token" value="<?=$token?>">
                                        <input type="datetime-local" name="open_datetime" step="1" value="<?php print h($data['open_datetime']); ?>">
                                        <input type="hidden" name="data_id" value="<?php print h($data['data_id']); ?>">
                                        <input type="submit" value="変更">
                                    </form>
                                </td>
                                <td>
                                    <form method="post" action="data_tool_change_close_datetime.php">
                                        <input type="hidden" name="csrf_token" value="<?=$token?>">
                                        <input type="datetime-local" step="1" name="close_datetime" value="<?php print h($data['close_datetime']); ?>">
                                        <input type="hidden" name="data_id" value="<?php print h($data['data_id']); ?>">
                                        <input type="submit" value="変更">
                                    </form>
                                </td> -->
                                <td>
                                    <?php  if($data['status'] === 0){
                                            print '非公開';
                                    }       else if($data['status'] === 1){
                                            print '公開';
                                    };
                                    ?>
                                </td>
                                <td><?php print $data['created']; ?></td>
                                <td>
                                    <form method="post" action="data_tool_change_status.php">
                                        <input type="hidden" name="csrf_token" value="<?=$token?>">
                                        <input type="submit" value="非公開" class="close">
                                        <input type="hidden" name="change_status" value="close">
                                        <input type="hidden" name="data_id" value="<?php print h($data['data_id']); ?>">
                                    </form>
                                    <form method="post" action="data_tool_change_status.php">
                                        <input type="hidden" name="csrf_token" value="<?=$token?>">
                                        <input type="submit" value="公開" class="open">
                                        <input type="hidden" name="change_status" value="open">
                                        <input type="hidden" name="data_id" value="<?php print h($data['data_id']); ?>">
                                    </form>
                                    <form method="post" action="data_tool_delete.php">
                                        <input type="hidden" name="csrf_token" value="<?=$token?>">
                                        <input type="submit" value="削除" class="delete">
                                        <input type="hidden" name="data_id" value="<?php print h($data['data_id']); ?>">
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php } else { ?>
                <p>研修資料はありません。</p>
            <?php } ?> 
        <script>
            $('.delete').on('click', () => confirm('本当に削除しますか？'));
            $('.open').on('click', () => confirm('資料を公開します。よろしいですか？'));
            $('.close').on('click', () => confirm('資料を非公開にします。よろしいですか？'));
        </script>
        <script>
            $(function(){
                $('.cbox').on('change',function(){
                    $('.cdatas').html("");
                    $('.cbox:checked').each(function() {
                        var ip = $('<input>');
                        ip.attr('name','ids[]');
                        ip.attr('type','hidden');
                        ip.val($(this).val());
                        $('.cdatas').append(ip);
                    });
                })
                
                $('.all_check').click(function(){
                    if($(this).prop('checked')===true){
                        $('.cbox').each(function(){
                            $(this).prop('checked',true);
                        })
                    }else{
                        $('.cbox').each(function(){
                            $(this).prop('checked',false);
                        }) 
                    }
                    $('.cbox').trigger('change');
                })
            })
        </script>
    </body>
</html>
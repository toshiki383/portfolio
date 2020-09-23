<!DOCTYPE html>
<html lang="ja">
    <head>
        <?php include VIEW_PATH . 'templates/head.php'; ?>
        <title>ユーザー管理</title>
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
            #manager{
                background-color: yellow;
            }
            #presenter{
                background-color: lightblue;
            }
            .delete{
                color: red;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <?php include VIEW_PATH . 'templates/header_logined.php';?>
            <div style="display: flex; justify-content: space-between;">
                <h1>ユーザー管理</h1>
                
                <div style="display: flex;">  
                    <label>法人名</label>
                    <form method="get">
                        <select name="sarch_corporate">
                            <option value="all">全て</option>
                        <?php foreach($corporates as $corporate){ ?>
                            <option value="<?php print $corporate['corporate_id'];?>" <?php if ((int)$_GET['sarch_corporate'] === $corporate['corporate_id']){print 'selected';}  ?>><?php print $corporate['corporate_name'];?></option>
                        <?php } ?>
                        </select>
                        <input type="submit" name="" value="表示">
                
                    <label>管理区分</label>
                        <select name="sarch_manage">
                            <option value="all">全て</option>
                            <option value="0" <?php if($sarch_manage === '0'){?>selected<?php }?>>一般</option>
                            <option value="1" <?php if($sarch_manage === '1'){?>selected<?php }?>>発表者</option>
                            <option value="2" <?php if($sarch_manage === '2'){?> selected <?php } ?>>管理者</option>
                        </select>
                        <input type="submit" value="表示">
                    </form>
                </div>
            </div>
            <div class="content">
                <p>
                    このページはユーザ情報を管理するページです。<br>
                    管理者のみが利用することができ、ユーザ区分の変更、ユーザの削除ができます。<br>
                    絞り込み検索の機能、チェックボックスの機能を実装しています。<br>
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
            <?php include VIEW_PATH . 'templates/messages.php'; ?>
            
            <?php if(count($users) > 0){ ?>
                <div style="display:flex;">
                    <label>チェックした項目を</label>
                    <form method="post" action="users_tool_change_manage.php">
                        <input type="hidden" name="csrf_token" value="<?=$token?>">
                        <input type="submit" value="一般に変更" class="normal">
                        <input type="hidden" name="manage" value="normal">
                        <div class="cusers">
                        </div>
                    </form>
                    <form method="post" action="users_tool_change_manage.php">
                        <input type="hidden" name="csrf_token" value="<?=$token?>">
                        <input type="submit" value="発表者に変更" class="presenter">
                        <input type="hidden" name="manage" value="presenter">
                        <div class="cusers">
                        </div>
                    </form>
                    <form method="post" action="users_tool_change_manage.php">
                        <input type="hidden" name="csrf_token" value="<?=$token?>">
                        <input type="submit" value="管理者に変更" class="manager">
                        <input type="hidden" name="manage" value="manager">
                        <div class="cusers">
                        </div>
                    </form>
                    <form method="post" action="users_tool_delete.php">
                        <input type="hidden" name="csrf_token" value="<?=$token?>">
                        <input type="submit" value="削除" class="delete">
                        <div class="cusers">
                        </div>
                    </form>
                </div>
            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" class="all_check"></th>
                        <th>ユーザーID</th>
                        <th>ユーザー名</th>
                        <th>事業所名</th>
                        <th>ユーザ区分</th>
                        <th>登録日時</th>
                        <th>最新ログイン日時</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user){ ?>
                        <?php if($user['manage'] === 2){?>
                        <tr id="manager">
                        <?php }else if($user['manage'] === 1){?>
                        <tr id="presenter">
                        <?php }else{ ?>
                        <tr>
                        <?php } ?>
                            <td><input type="checkbox" class="cbox" value="<?php print h($user['user_id']);?>"></td>
                            <td><?php print h($user['user_id']); ?></td>
                            <td><?php print h($user['user_name']);?></td>
                            <td><?php print h($user['corporate_name']); ?></td>
                            <td>
                                <?php if(is_manager($user)){
                                    print '管理者';
                                }else if(is_presenter($user)){
                                    print '発表者';
                                }else {
                                    print '一般';
                                }
                                ?>
                            </td>
                            <td><?php print h($user['created']); ?></td>
                            <td><?php print h($user['logined']); ?></td>
                            <td>
                                <form method="post" action="user_tool_change_manage.php">
                                    <input type="hidden" name="csrf_token" value="<?=$token?>">
                                    <input type="submit" value="一般に変更" class="normal">
                                    <input type="hidden" name="change_manage" value="normal">
                                    <input type="hidden" name="user_id" value="<?php print h($user['user_id']); ?>">
                                </form>
                                <form method="post" action="user_tool_change_manage.php">
                                    <input type="hidden" name="csrf_token" value="<?=$token?>">
                                    <input type="submit" value="発表者に変更" class="presenter">
                                    <input type="hidden" name="change_manage" value="presenter">
                                    <input type="hidden" name="user_id" value="<?php print h($user['user_id']); ?>">
                                </form>
                                <form method="post" action="user_tool_change_manage.php">
                                    <input type="hidden" name="csrf_token" value="<?=$token?>">
                                    <input type="submit" value="管理者に変更" class="manager">
                                    <input type="hidden" name="change_manage" value="manager">
                                    <input type="hidden" name="user_id" value="<?php print h($user['user_id']); ?>">
                                </form>
                                <form method="post" action="user_tool_delete.php">
                                    <input type="hidden" name="csrf_token" value="<?=$token?>">
                                    <input type="submit" value="削除" class="delete">
                                    <input type="hidden" name="user_id" value="<?php print h($user['user_id']); ?>">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
                <p>該当のユーザーがいません。</p>
            <?php } ?> 
        </div>
        <script>
            // ボタンを押した時の確認
            $('.delete').on('click', () => confirm('本当に削除しますか？'))
            $('.normal').on('click', () => confirm('一般に変更します。よろしいですか？'))
            $('.presenter').on('click', () => confirm('発表者に変更します。よろしいですか？'))
            $('.manager').on('click', () => confirm('管理者に変更します。よろしいですか？'))
    
            // チェックボックス
            $(function(){
                $('.cbox').on('change',function(){
                    $('.cusers').html("");
                    $('.cbox:checked').each(function() {
                        var ip = $('<input>');
                        ip.attr('name','ids[]');
                        ip.attr('type','hidden');
                        ip.val($(this).val());
                        $('.cusers').append(ip);
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
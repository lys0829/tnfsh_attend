<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>

<div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-12" id="main-page">
            <a class="btn btn-success" href="<?=$TnfshAttend->uri('admin','new_user')?>">新增帳號</a>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>帳號</th>
                        <th>使用者名稱</th>
                        <th>所屬群組</th>
                        <th>帳號種類</th>
                        <th>點名次數</th>
                        <th>註冊日期</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($tmpl['users'] as $user){ ?>
                        <tr>
                            <td><?=$user['uid']?></td>
                            <td><?=$user['username']?></td>
                            <td><?=$user['nickname']?></td>
                            <td><?=$user['group']?></td>
                            <td><?=$user['passhash']?'系統帳號':'Mail'?></td>
                            <td><?=$user['num']?></td>
                            <td><?=$user['timestamp']?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

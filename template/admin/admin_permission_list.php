<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>

<div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-12" id="main-page">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>權限名稱</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($tmpl['permissions'] as $per){ ?>
                        <tr>
                            <td><?=$per['pid']?></td>
                            <td><?=$per['show_name']?></td>
                            <td width="500">
                                <a class="icon-bttn" href="<?=$TnfshAttend->uri('admin','permission_manage')?>?pid=<?=$per['pid']?>">
                                    <span class="glyphicon glyphicon-pencil" title="編輯設定"></span>
                                </a>
                                <a class="" href="<?=$TnfshAttend->uri('admin','permission_manage')?>?pid=<?=$per['pid']?>&policy=allow&edit=user">
                                    允許使用者名單
                                </a>
                                <a class="" href="<?=$TnfshAttend->uri('admin','permission_manage')?>?pid=<?=$per['pid']?>&policy=allow&edit=group">
                                    允許群組名單
                                </a>
                                <a class="" href="<?=$TnfshAttend->uri('admin','permission_manage')?>?pid=<?=$per['pid']?>&policy=deny&edit=user">
                                    排除使用者名單
                                </a>
                                <a class="" href="<?=$TnfshAttend->uri('admin','permission_manage')?>?pid=<?=$per['pid']?>&policy=deny&edit=group">
                                    排除群組名單
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

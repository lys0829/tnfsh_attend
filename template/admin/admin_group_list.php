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
                        <th>群組名稱</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($tmpl['groups'] as $group){ ?>
                        <tr>
                            <td><?=$group['gid']?></td>
                            <td><?=$group['gname_show']?></td>
                            <td>
                                <a class="icon-bttn" href="<?=$TnfshAttend->uri('admin','group_manage')?>?gid=<?=$group['gid']?>">
                                    <span class="glyphicon glyphicon-user" title="編輯成員"></span>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

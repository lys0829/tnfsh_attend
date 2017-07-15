<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>

<script>
function save(){
    api_submit('<?=$TnfshAttend->uri('admin','api','permission_manage')?>',"#permission_user","#permission_save_message",function(res){
        location.assign("<?=$TnfshAttend->uri('admin','permission_manage')?>"+"?pid="+"<?=$tmpl["permission"]["pid"]?>&edit=<?=$tmpl['edit']?>&policy=<?=$tmpl['allow_or_deny']?>");
    });
    //$("#group_user").submit();
}
</script>

<script>
 $(document).ready(function(){
  $("#CheckAll").click(function(){
   if($("#CheckAll").prop("checked")){//如果全選按鈕有被選擇的話（被選擇是true）
    $("input[name='gu[]']").each(function(){
     $(this).prop("checked",true);//把所有的核取方框的property都變成勾選
    })
   }else{
    $("input[name='gu[]']").each(function(){
     $(this).prop("checked",false);//把所有的核方框的property都取消勾選
    })
   }
  })
 })
</script>

<div class="container">
    <div class="row">
        <div class="col-sm-2 col-md-2" style="min-height:100px">
            <h1><?=$tmpl['permission']['show_name']?></h1>
            <?php if($tmpl['allow_or_deny']=='allow'):?>
            <p>設定允許群組</p>
            <?php else:?>
            <p>設定排除群組</p>
            <?php endif;?>
            <button type="button" class="btn btn-success" onClick="save();"><span class="glyphicon glyphicon-ok"></span>儲存</button>
            <div id="permission_save_message"></div>
        </div>
        <div class="col-sm-10 col-md-10" style="min-height:100px">
            <form class="form" action="admin.php" method="post" id="permission_user">
                <input type="checkbox" name="CheckAll" value="全選" id="CheckAll">全選
                <input type="hidden" name="pid" value="<?=$tmpl['permission']['pid']?>">
                <input type="hidden" name="group_or_user" value="<?=$tmpl['edit']?>">
                <input type="hidden" name="allow_or_deny" value="<?=$tmpl['allow_or_deny']?>">
                <table>
                    <?php $i=1?>
                    <?php foreach($tmpl['allgroup'] as $group){?>
                        <?php if($i%6 == 1):?><tr><?php endif;?>
                            <?php if(isset($tmpl['permission_groups'][$group['gid']])):?>
                            <td width="150"><input type="checkbox" name="gu[]" value="<?=$group['gid']?>" checked><?=$group['gname_show']?></input></td>
                            <?php else:?>
                            <td width="150"><input type="checkbox" name="gu[]" value="<?=$group['gid']?>"><?=$group['gname_show']?></input></td>
                            <?php endif;?>
                        <?php if($i%6 == 0):?></tr><?php endif;?>
                        <?php $i++;?>
                    <?php }?>
                </table>
            </form>
        </div>
    </div>
</div>
<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>

<script>
function save(){
    api_submit('<?=$TnfshAttend->uri('admin','api','group_manage')?>',"#group_user","#group_save_message",function(res){
        location.assign("<?=$TnfshAttend->uri('admin','group_manage')?>"+"?gid="+"<?=$tmpl["group"]["gid"]?>");
    });
    //$("#group_user").submit();
}
</script>

<script>
 $(document).ready(function(){
  $("#CheckAll").click(function(){
   if($("#CheckAll").prop("checked")){//如果全選按鈕有被選擇的話（被選擇是true）
    $("input[name='guser[]']").each(function(){
     $(this).prop("checked",true);//把所有的核取方框的property都變成勾選
    })
   }else{
    $("input[name='guser[]']").each(function(){
     $(this).prop("checked",false);//把所有的核方框的property都取消勾選
    })
   }
  })
 })
</script>

<div class="container">
    <div class="row">
        <div class="col-sm-2 col-md-2" style="min-height:100px">
            <h1><?=$tmpl['group']['gname_show']?></h1>
            <button type="button" class="btn btn-success" onClick="save();"><span class="glyphicon glyphicon-ok"></span>儲存</button>
            <div id="group_save_message"></div>
        </div>
        <div class="col-sm-10 col-md-10" style="min-height:100px">
            <form class="form" action="<?=$TnfshAttend->uri('admin','api','group_manage')?>" method="post" id="group_user">
                <input type="checkbox" name="CheckAll" value="全選" id="CheckAll">全選
                <input type="hidden" name="gid" value="<?=$tmpl['group']['gid']?>">
                <table>
                    <?php $i=1?>
                    <?php foreach($tmpl['alluser'] as $user){?>
                        <?php if($i%6 == 1):?><tr><?php endif;?>
                            <?php if(isset($tmpl['group_users'][$user['uid']])):?>
                            <td width="150"><input type="checkbox" name="guser[]" value="<?=$user['uid']?>" checked><?=$user['nickname']?></input></td>
                            <?php else:?>
                            <td width="150"><input type="checkbox" name="guser[]" value="<?=$user['uid']?>"><?=$user['nickname']?></input></td>
                            <?php endif;?>
                        <?php if($i%6 == 0):?></tr><?php endif;?>
                        <?php $i++;?>
                    <?php }?>
                </table>
            </form>
        </div>
    </div>
</div>
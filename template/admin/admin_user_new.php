<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}

?>

<script>
$(document).ready(function(){
    $("#new").submit(function(e)
    {
        e.preventDefault();
        api_submit("<?=$TnfshAttend->uri('admin','api','new_user')?>","#new","#comment",function(e){
            location.href = "<?=$TnfshAttend->uri('admin','user_list')?>";
        });
    });
});
</script>

<div class="container">
    <div class="row">
        <h1>新增使用者</h1>
        <form class="form-inline" id="new">
            <div class="form-group">
                帳號(同mail帳號)
                <input type="text" class="form-control" name="username">
            </div>
            <div class="form-group">
                使用者名稱(顯示用)
                <input type="text" class="form-control" name="nickname">
            </div>
            <button type="submit" class="btn btn-primary">新增</button>
            <span id="comment"></span>
        </form>
    </div>
</div>

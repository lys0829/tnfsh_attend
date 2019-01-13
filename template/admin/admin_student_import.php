<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}

?>

<script>
$(document).ready(function(){
    $("#upload").submit(function(e)
    {
        $("#comment").html("匯入中...");
        e.preventDefault();
        api_submit("<?=$TnfshAttend->uri('admin','api','student_import')?>","#upload","#comment",function(e){
            location.reload();
        });
    });
});
</script>

<div class="container">
    <div class="row">
        <h1>匯入學生名單</h1>
        <form class="form-inline" id="upload">
            <div class="form-group">
                學生名單CSV
                <input type="file" class="form-control-file" id="list-csv" name="file" accept=".csv">
            </div>
            <div class="checkbox">
                <input type="checkbox" name="ignore_first" value="1">忽略第一列資料（標題）
            </div>
            <button type="submit" class="btn btn-primary">上傳</button>
            <span id="comment"></span>
        </form>
    </div>
    <div class="row">
        <a class="btn btn-info" href="<?=$TnfshAttend->uri('admin','student_list_sample')?>">下載範例檔</a>
    </div>
</div>

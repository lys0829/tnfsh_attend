<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}

if($tmpl['has_data']):
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename={$tmpl['title']}.csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo $tmpl['data'];
    exit();
endif;
?>

<script>
function output(){
    if($("#date_begin").val()=="" || $("#date_end").val()==""){
        $("#message").html("請選擇日期");
        return ;
    }
    location.assign("<?=$TnfshAttend->uri('attend','output')?>"+"?begin="+$("#date_begin").val()+"&end="+$("#date_end").val()+"&class="+$("#class").val());
}
</script>

<div class="container">
    <div class="row">
    <?php if(!$tmpl['has_data']):?>
        起始日期：
        <input type="date" id="date_begin" placeholder="">

        <br><br>

        結束日期：
        <input type="date" id="date_end" placeholder="">

        <br><br>

        班級(可留白):
        <input type="text" id="class">

        <br><br>
        <button type="button" class="btn btn-success" onClick="output();">匯出</button>
        <div id="message" />
    <?php endif;?>
    </div>
</div>

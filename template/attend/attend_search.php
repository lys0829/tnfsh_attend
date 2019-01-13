<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>

<script>
function search(){
    if($("#date_begin").val()=="" || $("#date_end").val()==""){
        $("#message").html("請選擇日期");
        return ;
    }
    if($("#class").val()==""){
        $("#message").html("請輸入班級");
        return ;
    }
    if($("#number").val()==""){
        $("#message").html("請輸入座號");
        return ;
    }
    location.assign("<?=$TnfshAttend->uri('attend','search')?>"+"?begin="+$("#date_begin").val()+"&end="+$("#date_end").val()+"&class="+$("#class").val()+"&num="+$("#number").val());
}
</script>

<div class="container">
    <div class="row">
        <form class="form-inline">
            <input type="date" class="form-control mb-2 mr-sm-2" id="date_begin" placeholder="起始日期" <?php if(isset($tmpl['begin'])):?>value="<?=$tmpl['begin']?>"<?php endif;?>>
            <input type="date" class="form-control mb-2 mr-sm-2" id="date_end" placeholder="結束日期" <?php if(isset($tmpl['end'])):?>value="<?=$tmpl['end']?>"<?php endif;?>>
            <input type="text" class="form-control mb-2 mr-sm-2" id="class" placeholder="班級(e.x 101)" <?php if(isset($tmpl['class'])):?>value="<?=$tmpl['class']?>"<?php endif;?>>
            <input type="text" class="form-control mb-2 mr-sm-2" id="number" placeholder="座號(e.x 1)" <?php if(isset($tmpl['num'])):?>value="<?=$tmpl['num']?>"<?php endif;?>>
            <button type="button" class="btn btn-success" onClick="search();">查詢</button>
            <div id="message" /></div>
        </form>
    </div>
    <br>
    <?php if($tmpl['has_data']):?>
    <div class="row">
        <div class="col">
            <div style="overflow:scroll;">
                <table class="table table-striped" style="table-layout: fixed;">
                    <thead>
                        <tr>
                            <th width="125px">日期</th>
                            <th width="65px">班級</th>
                            <th width="65px">座號</th>
                            <?php if(\userControl::has_permission('view_name',$_G['uid'])):?>
                            <th width="80px">姓名</th>
                            <?php endif;?>
                            <?php foreach($tmpl['timetable'] as $c){?>
                            <th width="70px"><?=$c['course_name']?></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($tmpl['roll_book'] as $date => $data){?>
                        <tr>
                            <td><?=$date?></td>
                            <td><?=$tmpl['class']?></td>
                            <td><?=$tmpl['num']?></td>
                            <?php if(\userControl::has_permission('view_name',$_G['uid'])):?>
                            <td><?=\TnfshAttend\StudentName($tmpl['class'],$tmpl['num'])?></td>
                            <?php endif;?>
                            <?php foreach($tmpl['timetable'] as $c){?>
                            <td>
                                <?php if(isset($data[$tmpl['class']][$tmpl['num']][$c['course_id']])):?>
                                    <?php $state = $data[$tmpl['class']][$tmpl['num']][$c['course_id']];?>
                                    <?php if($state==="skip"){?>
                                    <button type="button" class="btn btn-danger">缺席</button>
                                    <?php }else if($state==="late"){?>
                                    <button type="button" class="btn btn-warning">遲到</button>
                                    <?php }else if($state==="early"){?>
                                    <button type="button" class="btn btn-info">早退</button>
                                    <?php }?>
                                <?php endif;?>
                            </td>
                            <?php }?>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif;?>
</div>

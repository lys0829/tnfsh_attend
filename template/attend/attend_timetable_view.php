<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>

<script>

function sign_check( day , date , course_id , course_name , class_name ){
    $("#sign_check_message").html("");
    $("#sign_check").modal('show');
    var text;
    text = "--日期:"+day+"<br>"+"--時間:"+course_name+"<br>"+"--班級:"+class_name+"<br>";
    $("#sign_check_info").html(text);
    $("#check").click(function(e){
        //var ctext = "?date="+date+"&course_id="+course_id+"&class="+class_name;
        //console.log(ctext);
        $("#sign_date").val(date);
        $("#sign_course_id").val(course_id);
        $("#sign_class").val(class_name);
        api_submit('<?=$TnfshAttend->uri('attend','api','sign')?>',"#signform","#sign_check_message",function(res){
            location.assign("<?=$TnfshAttend->uri('attend','roll_book')?>"+"?sign_id="+res.data);
        });
    });
}

function sign_delete(sign_id , day , course_id , course_name , class_name){
    $("#sign_delete_check_message").html("");
    $("#sign_delete_check").modal('show');
    var text;
    text = "--日期:"+day+"<br>"+"--時間:"+course_name+"<br>"+"--班級:"+class_name+"<br>";
    $("#sign_delete_check_info").html(text);
    $("#delete_check").click(function(e){
        $("#signdelete_sign_id").val(sign_id);
        api_submit('<?=$TnfshAttend->uri('attend','api','sign_delete')?>',"#signdelete","#sign_delete_check_message",function(res){
                $("#sign_delete_check").modal('hide');
                $('#sign_delete_check').on('hidden.bs.modal', function (e) {
                    loadTemplate(old);
                })
                //console.log(res);
        });
    });
}

function view_roll_book(sign_id){
    location.assign("<?=$TnfshAttend->uri('attend','roll_book')?>"+"?sign_id="+sign_id);
}

</script>

<div class="container">

    <div class="row">
        <div class="col-xs-12">
            <table cellspacing="0" cellpadding="5" style="font-size:20px" class="table table-hover table-condensed">
                <tr>
                    <th></th>
                    <?php $week=["日","一","二","三","四","五","六"];?>
                    <?php for($d=0;$d<7;$d++){ ?>
                    <th <?php echo (date("Y-m-d", $tmpl['firstdate']+86400*$d) == $tmpl['date']?'class="info"':'class="hidden-xs"'); ?> style="text-align: center;">
                        <?=date("m/d",$tmpl['firstdate']+86400*$d)."<br>(".$week[$d].")"?>
                    </th>
                    <?php } ?>
                </tr>
                <?php foreach($tmpl['tb_course'] as $course){ 
                    $cname = $course['course_name'];
                    $cid = $course['course_id'];
                ?>
                    <tr>
                        <td><?=$cname?></td>
                        <?php for($d=0;$d<7;$d++){ ?>
                        <td <?php echo (date("Y-m-d", $tmpl['firstdate']+86400*$d) == $tmpl['date']?'class="info"':'class="hidden-xs"'); ?> align="center" style="width: 130px">
                        <?php if(!isset($tmpl['signed'][$cid][$d])):?>
                            <?php if(date("Y-m-d", $tmpl['firstdate']+86400*$d) == $tmpl['date']):?>
                                <?php 
                                $day = date("Y-m-d",$tmpl['firstdate']+86400*$d);
                                $date = $day;
                                $wday = $week[date("w",$tmpl['firstdate']+86400*$d)];
                                $day.=' ('.$wday.')';
                                ?>
                                    <button type="button" class="btn btn-primary sign" style="width:90px" onClick="sign_check('<?=$day?>','<?=$date?>',<?=$cid?>,'<?=$cname?>','<?=$tmpl['class']?>');">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                        點名
                                    </button>
                            <?php endif;?>
                        <?php elseif(!$tmpl['signed'][$cid][$d]['own']):?>
                            <button type="button" class="btn btn-default" onClick="view_roll_book('<?=$tmpl['signed'][$cid][$d]['sign_id']?>');">
                                <span class="glyphicon glyphicon-user"></span>
                                <?=$tmpl['signed'][$cid][$d]['nick']?>
                            </button>
                        <?php else:?>
                            <?php 
                                $day = date("Y-m-d",$tmpl['firstdate']+86400*$d);
                                $date = $day;
                                $wday = $week[date("w",$tmpl['firstdate']+86400*$d)];
                                $day.=' ('.$wday.')';
                            ?>
                            <button type="button" class="btn btn-danger" onClick="sign_delete('<?=$tmpl['signed'][$cid][$d]['sign_id']?>','<?=$day?>','<?=$cid?>','<?=$cname?>','<?=$tmpl['class']?>');">
                                <span class="glyphicon glyphicon-trash"></span>
                            </button>
                            <button type="button" class="btn btn-info" onClick="view_roll_book('<?=$tmpl['signed'][$cid][$d]['sign_id']?>');">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </button>
                        <?php endif;?>
                        </td>
                        <?php } ?>
                    </tr>
                <?php }?>
            </table>
            
            <div class="modal fade" id="sign_check" tabindex="-1" role="dialog" aria-labelledby="sign_checklb">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="sign_checklb" style="color:black">您即將點名</h4>
                        </div>
                        <div class="modal-body">
                            您即將點名，以下為點名單基本資料<br>
                            <div id="sign_check_info"></div>
                            <form role="form" action="attend.php" method="post" id="signform">
                                <input type="hidden" value="" name="sign_date" id="sign_date">
                                <input type="hidden" value="" name="sign_course_id" id="sign_course_id">
                                <input type="hidden" value="" name="sign_class" id="sign_class">
                            </form>
                            當您按下點名後<br>
                            這張點名單將會屬於您，其他老師將無法更改<br>
                            請確認後點名
                        </div>
                        <div class="modal-footer">
                            <div id="sign_check_message"></div>
                            <button type="button" class="btn btn-success" id="cancel" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">放棄</span></button>
                            <button type="button" class="btn btn-warning" id="check">點名</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="sign_delete_check" tabindex="-1" role="dialog" aria-labelledby="sign_delete_checklb">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="sign_delete_checklb" style="color:black">您即將刪除點名單</h4>
                        </div>
                        <div class="modal-body">
                            您即將刪除點名單，以下為點名單基本資料<br>
                            <div id="sign_delete_check_info"></div>
                            <form role="form" action="attend.php" method="post" id="signdelete">
                                <input type="hidden" value="" name="sign_id" id="signdelete_sign_id">
                            </form>
                            請確認後再刪除
                        </div>
                        <div class="modal-footer">
                            <div id="sign_delete_check_message"></div>
                            <button type="button" class="btn btn-success" id="cancel" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">放棄</span></button>
                            <button type="button" class="btn btn-danger" id="delete_check">刪除</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>

<script>
function get_roll_book_data(id){
    $("#roll_book_get_signid").val(id);
    api_submit('<?=$TnfshAttend->uri('attend','api','roll_book_get')?>',"#roll_book_get","#roll_book_get_message",function(res){
       rb = JSON.parse(res['data']);
       //console.log(rb);
       skipg = rb['skip'];
       lateg = rb['late'];
       earlyg = rb['early'];
       for(i=0;i<skipg.length;i++){
           update_state(skipg[i],2);
       }
       for(i=0;i<lateg.length;i++){
           update_state(lateg[i],1);
       }
       for(i=0;i<earlyg.length;i++){
           update_state(earlyg[i],3);
       }
    });
}
function remove_people(arr) {
    var what, a = arguments, L = a.length, ax;
    while (L > 1 && arr.length) {
    what = a[--L];
        while ((ax= arr.indexOf(what)) !== -1) {
            arr.splice(ax, 1);
        }
    }
    return arr;
}
var skip,late,early;
skip = JSON.parse("<?=$tmpl['skip_json']?>");
late = JSON.parse("<?=$tmpl['late_json']?>");
early = JSON.parse("<?=$tmpl['early_json']?>");
function update_state(sid,state){
    remove_people(skip,sid);
    remove_people(late,sid);
    remove_people(early,sid);
    s = "#"+sid+"_";
    /*$(s+"late").removeAttr("disabled");
    $(s+"skip").removeAttr("disabled");
    $(s+"attend").removeAttr("disabled");*/
    //state:0=attend,1=late,2=skip;
    if(state!=0){
        if(state==1){
            late.push(sid);
            $(s+"late").addClass("btn-warning");
            $(s+"skip").removeClass("btn-danger");
            $(s+"attend").removeClass("btn-success");
            $(s+"early").removeClass("btn-info");
            //$(s+"late").attr("disabled","");
        }
        else if(state==2){
            skip.push(sid);
            $(s+"skip").addClass("btn-danger");
            //$(s+"skip").attr("disabled","");
            $(s+"attend").removeClass("btn-success");
            $(s+"late").removeClass("btn-warning");
            $(s+"early").removeClass("btn-info");
        }
        else if(state==3){
            early.push(sid);
            $(s+"early").addClass("btn-info");
            //$(s+"skip").attr("disabled","");
            $(s+"attend").removeClass("btn-success");
            $(s+"skip").removeClass("btn-danger");
            $(s+"late").removeClass("btn-warning");
        }
    }
    else{
        $(s+"attend").addClass("btn-success");
        //$(s+"attend").attr("disabled","");
        $(s+"skip").removeClass("btn-danger");
        $(s+"late").removeClass("btn-warning");
        $(s+"early").removeClass("btn-info");
    }
}
function save(){
    skip = JSON.stringify(skip);
    late = JSON.stringify(late);
    early = JSON.stringify(early);
    $("#roll_book_save_late").val(late);
    $("#roll_book_save_skip").val(skip);
    $("#roll_book_save_early").val(early);
    $("#roll_book_save_signid").val(<?=$tmpl["sign_id"]?>);
    api_submit('<?=$TnfshAttend->uri('attend','api','roll_book_save')?>',"#roll_book_save","#roll_book_save_message",function(res){
        location.assign("<?=$TnfshAttend->uri('attend','roll_book')?>"+"?sign_id="+"<?=$tmpl["sign_id"]?>");
    });
}
function goback(){
    location.assign("<?=$TnfshAttend->uri('attend','timetable')?>?old=<?=$tmpl['class']?>");
}
</script>

<div class="container">
    <div class="row">
        <div class="col-sm-2 col-md-2" style="min-height:100px">
            <h3>班級:<?=$tmpl['class']?></h3><br>
            <h3>日期:<?=$tmpl['date']?></h3><br>
            <h3>時間:<?=$tmpl['course_name']?></h3>
            <?php if(\userControl::has_permission('modify_roll_book_limit',$_G['uid']) || \userControl::has_permission('modify_roll_book',$_G['uid'])):?>
            <button type="button" class="btn btn-success" onClick="save();"><span class="glyphicon glyphicon-ok"></span>儲存</button>
            <button type="button" class="btn btn-primary" onClick="goback();">返回</button>
                <?php if($tmpl['usid']!=-1):?>
                <button type="button" class="btn btn-warning" onClick="get_roll_book_data(<?=$tmpl['usid']?>)">複製上節資料</button>
                <?php endif;?>
            <?php endif;?>
            <div id="roll_book_save_message"></div>
        </div>
        <div class="col-sm-10 col-md-10" id="main-page">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>出缺席狀況</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for($id=1;$id<=$tmpl['stnum'];$id++){ ?>
                        <tr>
                            <td><?=$id?></td>
                            <td>
                                <?php if(!in_array($id,$tmpl["skip"]) && !in_array($id,$tmpl["late"]) && !in_array($id,$tmpl["early"])):?>
                                    <button type="button" class="btn btn-success" id="<?=$id?>_attend"  onClick="update_state(<?=$id?>,0);">正常出席</button>
                                <?php else:?>
                                    <button type="button" class="btn" id="<?=$id?>_attend" onClick="update_state(<?=$id?>,0);">正常出席</button>
                                <?php endif;?>

                                <?php if(in_array($id,$tmpl["skip"])):?>
                                    <button type="button" class="btn btn-danger" id="<?=$id?>_skip" onClick="update_state(<?=$id?>,2);">缺席</button>
                                <?php else:?>
                                    <button type="button" class="btn" id="<?=$id?>_skip" onClick="update_state(<?=$id?>,2);">缺席</button>
                                <?php endif;?>

                                <?php if(in_array($id,$tmpl["late"])):?>
                                    <button type="button" class="btn btn-warning" id="<?=$id?>_late" onClick="update_state(<?=$id?>,1);">遲到</button>
                                <?php else:?>
                                    <button type="button" class="btn" id="<?=$id?>_late" onClick="update_state(<?=$id?>,1);">遲到</button>
                                <?php endif;?>

                                <?php if(in_array($id,$tmpl["early"])):?>
                                    <button type="button" class="btn btn-info" id="<?=$id?>_early" onClick="update_state(<?=$id?>,3);">早退</button>
                                <?php else:?>
                                    <button type="button" class="btn" id="<?=$id?>_early" onClick="update_state(<?=$id?>,3);">早退</button>
                                <?php endif;?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php if(\userControl::has_permission('modify_roll_book_limit',$_G['uid']) || \userControl::has_permission('modify_roll_book',$_G['uid'])):?>
            <button type="button" class="btn btn-success" onClick="save();"><span class="glyphicon glyphicon-ok"></span>儲存</button>
            <?php endif;?>
            <div id="roll_book_save_message"></div>
            <div id="roll_book_get_message"></div>
            <form role="form" action="attend.php" method="post" id="roll_book_save">
                <input type="hidden" value="" name="sign_id" id="roll_book_save_signid">
                <input type="hidden" value="" name="late" id="roll_book_save_late">
                <input type="hidden" value="" name="skip" id="roll_book_save_skip">
                <input type="hidden" value="" name="early" id="roll_book_save_early">
            </form>
            <form role="form" action="attend.php" method="post" id="roll_book_get">
                <input type="hidden" value="" name="sign_id" id="roll_book_get_signid">
            </form>
        </div>
    </div>
</div>
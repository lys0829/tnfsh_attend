<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<script>
	var CONT;
	var old = "<?=$tmpl['old']?>";
    
	$(document).ready(function(){
	    CONT = document.getElementById('content');
	    //$( "[navpage]" ).click(function(){loadTemplate($(this).attr('navpage'));});
        $("[navpage='"+old+"']").addClass('active');
        loadTemplate('timetable_view?class='+old,old);
        
	});
    

    $(document).on("click","[navpage]",function(event){
        event.preventDefault();
        loadTemplate($(this).attr('navpage'));
    });

    $(document).ready(function()
    {
        $("#class_select").change(function()
        {
            console.log('submit');
            search_class = this.value;
            if(search_class!='')loadTemplate('timetable_view?class='+search_class,search_class);
        });
    });

	function loadTemplate(template,search_class){
        console.log('load'+template);
        $("[navpage='"+old+"']").removeClass();
        $("[navpage='"+template+"']").addClass('active');
        old = template;
        $("#class_select").val(search_class);
        loadTemplateToBlock(template,'main-page');
        return ;
	}
	function loadTemplateToBlock( template , bid  ){
        console.log('load...');
	    var content = document.getElementById(bid);
	    if( content === null )return false;

        adder = '?';
        if( adder.indexOf('?') != -1 )
        {
            adder = '&';
        }
        
	    $(content).load("<?=$TnfshAttend->uri('attend','timetable_view')?>/"+template,{subpage:'yes'},function(){
            $(content).hide();
            $(content).fadeIn();
            $('#'+bid+' a[tmpl]').click(function(event){
                event.preventDefault();
                tmpl = $(this).attr('tmpl');
                console.log(tmpl);
                console.log(bid);
                loadTemplateToBlock(tmpl,bid);
            });
        });
	}
    //$('.dropdown-toggle').dropdown();
</script>
<div class="container">
    <div class="row">
      <div class="col-sm-2 col-md-2" style="min-height:100px">
        <form class="form-inline" action="" id="search">
            <div class="form-group">
                <span class="form-group-addon">班級</span>
                <select class="form-control" name="class" id="class_select">
                    <?php foreach ($_E['template']['clist'] as $cl) { ?>
                    <option value="<?=urlencode($cl)?>" id="class"><?=htmlentities($cl)?></option>
                    <?php }?>
                </select>
            </div>
        </form>
      </div>
      <div class="col-sm-10 col-md-10" id="main-page"></div>
    </div>
</div>

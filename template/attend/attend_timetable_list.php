<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <table class="table table-bordered">
                <tbody>
                    <?php for($i=1;$i<=19;$i++){?>
                        <tr>
                            <td><button type="button" class="btn btn-warning" onClick="loadTemplate('timetable_view?class=<?=100+$i?>',<?=100+$i?>)"><?=100+$i?></button></td>
                            <td><button type="button" class="btn btn-success" onClick="loadTemplate('timetable_view?class=<?=200+$i?>',<?=200+$i?>)"><?=200+$i?></button></td>
                            <td><button type="button" class="btn btn-danger" onClick="loadTemplate('timetable_view?class=<?=300+$i?>',<?=300+$i?>)"><?=300+$i?></button></td>
                        </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php namespace TnfshAttend;

require_once 'GlobalSetting.php';
require_once 'function/TnfshAttend.php';

$TnfshAttend->RegisterHandle('index','\\TnfshAttend\\Index',null,true);
//$TnfshAttend->RegisterHandle('admin','\\TnfshAttend\\Admin\\AdminHandle',$_E['ROOT'].'/admin.php');
$TnfshAttend->RegisterHandle('user','\\TnfshAttend\\User\\UserHandle',$_E['ROOT'].'/user.php');
$TnfshAttend->RegisterHandle('attend','\\TnfshAttend\\Attend\\AttendHandle',$_E['ROOT'].'/attend.php');
$TnfshAttend->RegisterHandle('admin','\\TnfshAttend\\Admin\\AdminHandle',$_E['ROOT'].'/admin.php');
$TnfshAttend->run();
function Index(){
    global $TnfshAttend;
    $param = $TnfshAttend->UriParam(1);
    switch($param){
        default:
            \Render::render('index', 'index');
            break;
    }
}

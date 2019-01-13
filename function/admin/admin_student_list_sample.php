<?php namespace TnfshAttend\Admin;

if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}

function student_list_sampleHandle(){
    global $_E,$_G;

    try{
        if(!\userControl::has_permission('manage_student',$_G['uid'])){
            throw new \Exception('Access denied');
        }
        
        \Render::renderSingleTemplate ('admin_student_list_sample', 'admin');
    }catch(\Exception $e){
        \Render::errormessage($e->getMessage());
        \Render::render('nonedefined');
    }
    
}

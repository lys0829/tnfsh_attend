<?php namespace TnfshAttend\Admin;
if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}
set_time_limit(0);
ignore_user_abort(true);
function admin_api_student_importHandle()
{
    global $_G,$_E;
    

    try{
        $begin_line = (\TnfshAttend\safe_post("ignore_first"))?1:0;
        if(!\userControl::has_permission('user_manage',$_G['uid'])){
            throw new \Exception('Access denied');
        }
        $tname = \DB::tname("students");
        $file = $_FILES['file']??['error'=>1];
        if( $file['error'] != \UPLOAD_ERR_OK)
            throw new \Exception('檔案上傳失敗 : '.$file['error']);
        
        \DB::query("begin");
        $res = \DB::query("DELETE FROM `{$tname}` WHERE 1");
        if($res === false)
            throw new \Exception('刪除舊資料失敗');
        
        move_uploaded_file($file['tmp_name'],$_E['DATADIR'].'upload_student.csv');
        //echo file_get_contents($_E['DATADIR'].'upload_student.csv');
        $handle = fopen($_E['DATADIR'].'upload_student.csv',"r");
        if(!$handle)
            throw new \Exception('開啟檔案失敗 '.$handle);
        //\TnfshAttend\throwjson('error',$file['tmp_name']);
        //\TnfshAttend\throwjson('error',file_get_contents($_E['DATADIR'].'upload_student.csv'));
        $lid = -1;
        while ($data = fgetcsv($handle)) {
            $lid++;
            if($lid<$begin_line)continue;
            $num = count($data);
            if($num!=4)
                throw new \Exception('匯入失敗 第'.$lid.'列資料錯誤');
            $data[0] = (int)$data[0];
            $data[1] = (int)$data[1];
            $data[2] = (int)$data[2];
            $data[3] = mb_convert_encoding($data[3], "UTF-8", "auto");
            $res = \DB::query("INSERT INTO `{$tname}` (`student_id`,`class`,`number`,`name`)VALUES(?,?,?,?)",$data);
            if($res === false)
                throw new \Exception('匯入失敗 第'.$lid.'列資料錯誤或資料庫問題');
        }
        fclose($handle);
        $res = \DB::query("commit");
        if($res===false)
            throw new \Exception('匯入失敗');
        
        \TnfshAttend\throwjson('SUCC',"匯入成功");
    }catch(\Exception $e){
        \DB::query("rollback");
        \TnfshAttend\throwjson('error',$e->getMessage());
    }
}
<?php namespace TnfshAttend\Attend;

if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}

function outputHandle()
{
    global $_E,$_G;
    
    $begin = \TnfshAttend\safe_get('begin');
    $end = \TnfshAttend\safe_get('end');
    $class = \TnfshAttend\safe_get('class');

    try{
        if(!\userControl::has_permission("output",$_G['uid'])){
            throw new \Exception('您不具有權限');
        }

        if(empty($begin)||empty($end)){
            $_E['template']['has_data'] = false;
        }
        else{
            $_E['template']['has_data'] = true;
            $csv = "";

            $signtname = \DB::tname("signed");
            if(!empty($class) && $class!=''){
                $signres = \DB::fetchAll("SELECT * FROM `{$signtname}` WHERE (`date` BETWEEN ? AND ?) AND `class`=? ORDER BY `date` ASC, `class` ASC, `course_id` ASC",[$begin,$end,$class]);
            }
            else{
                $signres = \DB::fetchAll("SELECT * FROM `{$signtname}` WHERE (`date` BETWEEN ? AND ?) ORDER BY `date` ASC, `class` ASC, `course_id` ASC",[$begin,$end]);
            }
            if($signres === false){
                $_E['template']['has_data'] = false;
                throw new \Exception('取得點名單資料出問題');
            }

            $ttname = \DB::tname("timetable");
            $tres = \DB::fetchAll("SELECT * FROM `{$ttname}` ORDER BY `start_time` ASC",[]);
            if($tres === false){
                $_E['template']['has_data'] = false;
                throw new \Exception('取得課表資料出問題');
            }

            $csv .= "日期,班級,座號";
            foreach($tres as $t){
                $csv .= ",".$t['course_name'];
            }
            $csv .= "\n";
            
            //$st .= $s['date'].",".$s['class'].",";
            $roll_book_output = [];

            foreach($signres as $s){

                if(!isset($roll_book_output[$s['date']])){
                    $roll_book_output[$s['date']] = [];
                }

                if(!isset($roll_book_output[$s['date']][$s['class']])){
                    $roll_book_output[$s['date']][$s['class']] = [];
                }

                $rolltname = \DB::tname("roll_book");
                $rollres = \DB::fetch("SELECT * FROM `{$rolltname}` WHERE `sign_id` = ?",[$s['sign_id']]);
                if($rollres !== false){
                    $student = [];
                    $rollres['skip'] = json_decode($rollres['skip']);
                    $rollres['late'] = json_decode($rollres['late']);
                    $rollres['early'] = json_decode($rollres['early']);
                    if(is_array($rollres['skip'])){
                        foreach($rollres['skip'] as $sk){
                            $student[$sk] = "曠課";
                        }
                    }

                    if(is_array($rollres['late'])){
                        foreach($rollres['late'] as $la){
                            $student[$la] = "遲到";
                        }
                    }

                    if(is_array($rollres['early'])){
                        foreach($rollres['early'] as $ea){
                            $student[$ea] = "早退";
                        }
                    }

                    for($id = 1;$id<=45;$id++){
                        if(isset($student[$id])){
                            $roll_book_output[$s['date']][$s['class']][$id][$s['course_id']] = $student[$id];
                        }
                    }
                }
            }

            foreach($roll_book_output as $date => $data){
                $pre = $date.",";
                foreach($data as $cl => $data2){
                    $pre1 = $pre.$cl.",";
                    foreach($data2 as $st => $data3){
                        $csv .= $pre1.$st.",";

                        foreach($tres as $tt){
                            if(isset($data3[$tt['course_id']])){
                                $csv .= $data3[$tt['course_id']].",";
                            }
                            else{
                                $csv .= ",";
                            }
                        }

                        $csv .= "\n";
                    }
                }
            }

            //$csv = iconv("UTF-8", "Windows-1252", $csv);
            $csv = mb_convert_encoding($csv,"big5","utf-8");
            $_E['template']['data'] = $csv;
            $_E['template']['title'] = $begin."~".$end;
        }

        if($_E['template']['has_data']){
            \Render::renderSingleTemplate('attend_output', 'attend');
        }
        else{
            \Render::render('attend_output', 'attend');
        }
    }catch(\Exception $e){
        \Render::errormessage($e->getMessage());
        \Render::render('nonedefined');
    }
}

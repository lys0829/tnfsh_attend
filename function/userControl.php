<?php

if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}

class userControl
{
    //Cookie Functions
    public static function SetCookie(string $name, string $value, int $expire = 0)
    {
        global $_config,$_E;

        return setcookie($_config['cookie']['namepre'].'_'.$name, $value, $expire, $_E['SITEDIR'], '', false, true);
    }

    public static function RemoveCookie(string $name)
    {
        return self::SetCookie($name, '');
    }

    public static function isCookieSet(string $name)
    {
        global $_config;

        return isset($_COOKIE[$_config['cookie']['namepre'].'_'.$name]);
    }

    public static function GetCookie(string $name)
    {
        global $_config;
        if (self::isCookieSet($name)) {
            return $_COOKIE[$_config['cookie']['namepre'].'_'.$name];
        }

        return false;
    }

    //Set a Token to Cookie and return Token
    public static function RegisterToken(string $namespace, int $timeleft, bool $usecookie = true)
    {
        global $_G,$_E,$_config;

        if ($_G['uid'] == 0) {
            self::SetCookie('uid', '0', time() + 3600);
        }

        $token = \TnfshAttend\GenerateRandomString(TOKEN_LEN);
        $timeout = time() + $timeleft;

        $_SESSION[$namespace]['token'] = $token;
        $_SESSION[$namespace]['timeout'] = $timeout;
        $_SESSION[$namespace]['uid'] = $_G['uid'];
        if ($usecookie) {
            self::SetCookie($namespace, $token, $timeout);
        }

        return $token;
    }

    public static function DeleteToken(string $namespace, bool $usecookie = true)
    {
        if ($usecookie && self::isCookieSet($namespace)) {
            self::RemoveCookie($namespace);
        }
        if (isset($_SESSION[$namespace])) {
            unset($_SESSION[$namespace]);
        }
    }

    public static function isToeknSet(string $namespace)
    {
        return isset($_SESSION[$namespace]);
    }

    public static function getSavedToken(string $namespace)
    {
        if (!self::isToeknSet($namespace)) {
            return false;
        }

        return $_SESSION[$namespace]['token'];
    }

    //bool userControl::checktoken(namespace)
    //if function return true ,it mean two things:
    //1.$_COOKIE[$_config['cookie']['namepre'].'_uid'] is leagl
    //2.token $namespace is leagl
    public static function CheckToken(string $namespace, string $rowstring = null)
    {
        global $_G,$_config;
        if (isset($rowstring)) {
            $token = $rowstring;
        } else {
            if (!self::isCookieSet($namespace) || !self::isCookieSet('uid')) {
                return false;
            } else {
                $token = self::GetCookie($namespace);
            }
        }

        $uid = self::GetCookie('uid');

        if (!preg_match('/^[a-zA-Z0-9]+$/', $token) ||
            !preg_match('/^[0-9]+$/', $uid)) {
            return false;
        }

        if (isset($_SESSION[$namespace])) {
            if ($_SESSION[$namespace]['uid']  == $uid &&
                $_SESSION[$namespace]['token'] == $token &&
                time() < $_SESSION[$namespace]['timeout']) {
                return true;
            }
        }

        return false;
    }

    //userControl::intro()
    //this function must call first to check if user has logined and set var $_G
    public static function intro()
    {
        global $_G,$_E,$permission,$_config;

        $session_setting = session_get_cookie_params();
        session_set_cookie_params($session_setting["lifetime"],$_E['SITEDIR']);
        session_start();

        $acctable = DB::tname('account');
        if (self::CheckToken('login')) {
            //load user data
            //$_COOKIE[$_config['cookie']['namepre'].'_uid'] is checked in userControl::checktoken
            $loginuid = self::GetCookie('uid');

            if ($sqldata = DB::fetch("SELECT * FROM  `$acctable` ".
                                     'WHERE `uid` = ?', [$loginuid])) {
                $_G = $sqldata;
            } else {
                LOG::msg(Level::Error, 'Caonnot Load login info from DB', $loginuid);
                $_G = $permission['guest'];
            }
        } else {
            // guest

            self::DeleteToken('login');
            $_G = $permission['guest'];
        }
    }

    public static function SetLoginToken($uid)
    {
        global $_G,$_E,$_config;
        $acctable = DB::tname('account');

        if ($sqldata = DB::fetchEx("SELECT * FROM  `$acctable` ".
                                    'WHERE `uid` = ? ', $uid)) {
            $_G['uid'] = $uid;
            self::RegisterToken('login', 864000);
            self::SetCookie('uid', $uid, time() + 864000);

            return true;
        } else {
            return false;
        }
    }

    public static function DelLoginToken()
    {
        global $_G;
        self::DeleteToken('login');
    }

    //http://stackoverflow.com/questions/31500/do-indexes-work-with-in-clause
    //Use Where in let SQL Server Decide how to optimize
    public static function getuserdata(string $table,array $uid,array $column,string $index = 'uid')
    {
        $table = DB::tname($table);
        $userdata = [];
        $uid = array_values(array_unique($uid));
        if( empty($uid) )
            return [];

        $tmp_column = [$index=>'x'];
        foreach( $column as $c ) {
            $tmp_column[$c] = 'x';
        }
        $column = DB::ArrayToQueryString($tmp_column)['column'];
        $qstr = rtrim(str_repeat('?,',count($uid)),',');

        $res = DB::fetchAll("SELECT {$column} FROM `$table` WHERE `{$index}` IN  ({$qstr})",$uid);
        if( $res ) {
            foreach($res as $u){
                $userdata[$u[$index]] = $u;
            }
        }
        return $userdata;
    }

    public static function getpermission($uid)
    {
        global $_G,$_E,$_config;
        if ($uid == -1) {
            return false;
        }
        if ($uid == $_G['uid']) {
            return true;
        }
        if (in_array($_G['uid'], $_E['site']['admin'])) {
            return true;
        }

        return false;
    }

    public static function isAdmin(int $uid):bool
    {
        global $_E;
        return in_array($uid, $_E['site']['admin']);
    }

    public static function in_group(int $uid,int $gid):bool
    {
        global $_E;
        $ugname = \DB::tname('user_group');
        $ugres = \DB::fetch("SELECT * FROM `{$ugname}` WHERE `uid`=? AND `gid`=?",[$uid,$gid]);

        if($ugres === false){
            return false;
        }
        return true;
    }

    public static function get_groupid_by_name(string $gname){
        $ugname = \DB::tname('group_list');
        $ugres = \DB::fetch("SELECT * FROM `{$ugname}` WHERE `gname`=?",[$gname]);

        if($ugres === false){
            return false;
        }
        else{
            return $ugres['gid'];
        }
    }

    public static function get_group_users(int $gid):array
    {
        global $_E;
        $ugname = \DB::tname('user_group');
        $ugres = \DB::fetchAll("SELECT * FROM `{$ugname}` WHERE `gid`=?",[$gid]);

        $users = [];
        foreach($ugres as $u){
            $users[] = $u['uid'];
        }

        return $users;
    }

    public static function get_group_by_user(int $uid):array
    {
        global $_E;
        $ugname = \DB::tname('user_group');
        $ugres = \DB::fetchAll("SELECT * FROM `{$ugname}` WHERE `uid`=?",[$uid]);
        $user_group = [];
        foreach($ugres as $gr){
            $user_group[] = $gr['gid'];
        }
        return $user_group;
    }

    public static function add_user_to_group(int $uid,int $gid):bool
    {
        global $_E;
        if(self::in_group($uid,$gid)){
            return true;
        }

        $ugname = \DB::tname('user_group');
        $ugres = \DB::query("INSERT INTO `{$ugname}` (`uid`,`gid`) VALUES (?,?)",[$uid,$gid]);
        if($ugres === false){
            return false;
        }
        return true;
    }

    public static function delete_user_from_group(int $uid,int $gid):bool
    {
        global $_E;
        if(!self::in_group($uid,$gid)){
            return true;
        }

        $ugname = \DB::tname('user_group');
        $ugres = \DB::query("DELETE FROM `{$ugname}` WHERE `uid` = ? AND `gid` = ?",[$uid,$gid]);
        if($ugres === false){
            return false;
        }
        return true;
    }

    public static function get_permission($p,$useid=false)
    {
        global $_E;
        $pertname = \DB::tname('permission_list');
        if(!$useid){
            $perres = \DB::fetch("SELECT * FROM `{$pertname}` WHERE `name`=?",[$p]);
            if($perres === false){
                return false;
            }
            return $perres;
        }
        else{
            $perres = \DB::fetch("SELECT * FROM `{$pertname}` WHERE `pid`=?",[$p]);
            if($perres === false){
                return false;
            }
            return $perres;
        }
    }

    public static function has_permission(string $name,int $uid):bool
    {
        $permission = self::get_permission_by_name($name);
        $user_group = self::get_group_by_user($uid);

        if($permission === false){
            return false;
        }

        $perid = $permission['pid'];

        $pertname = \DB::tname('user_permission');
        $perres = \DB::fetch("SELECT * FROM `{$pertname}` WHERE (`group_or_user`=? AND `gu`=? AND `pid`=?)",['user',$uid,$perid]);
        if($perres !== false){
            if($perres['allow_or_deny'] == 'allow'){
                return true;
            }
            else{
                return false;
            }
        }

        if(!empty($user_group)){
            $user_group = $user_group[0];
            $perres = \DB::fetch("SELECT * FROM `{$pertname}` WHERE (`group_or_user`=? AND `gu`=? AND `pid`=?)",['group',$user_group,$perid]);
            if($perres !== false){
                if($perres['allow_or_deny'] == 'allow'){
                    return true;
                }
                else{
                    return false;
                }
            }
        }
        
        if($permission['default_policy'] == 'allow'){
            return true;
        }
        else{
            return false;
        }
    }

    public static function change_permission_for_user(int $uid,$p,string $ad,$useid=false)
    {
        $permission = self::get_permission($p,$useid);

        if($permission === false){
            return false;
        }

        $permission = $permission['pid'];

        if($ad != 'allow' && $ad != 'deny'){
            return false;
        }

        $pertname = \DB::tname('user_permission');
        $perres = \DB::fetch("SELECT * FROM `{$pertname}` WHERE (`group_or_user`=? AND `gu`=? AND `pid`=?)",['user',$uid,$permission]);

        if($perres !== false){
            $res = \DB::query("UPDATE `{$pertname}` SET `allow_or_deny` = ? WHERE (`upid`=?)",[$ad,$perres['upid']]);
            if($res !== false){
                return true;
            }
            else{
                //LOG::msg(Level::Error, '', $res);
                return false;
            }
        }
        else{
            $res = \DB::query("INSERT INTO `{$pertname}` (`pid`,`gu`,`group_or_user`,`allow_or_deny`) VALUES (?,?,?,?)",[$permission,$uid,'user',$ad]);
            if($res !== false){
                return true;
            }
            else{
                return false;
            }
        }
    }

    public static function change_permission_for_group(int $gid,$p,string $ad,$useid=false)
    {
        $permission = self::get_permission($p,$useid);

        if($permission === false){
            return false;
        }

        $permission = $permission['pid'];

        if($ad != 'allow' && $ad != 'deny'){
            return false;
        }

        $pertname = \DB::tname('user_permission');
        $perres = \DB::fetch("SELECT * FROM `{$pertname}` WHERE (`group_or_user`=? AND `gu`=? AND `pid`=?)",['group',$gid,$permission]);

        if($perres !== false){
            $res = \DB::query("UPDATE `{$pertname}` SET `allow_or_deny` = ? WHERE (`upid`=?)",[$ad,$perres['upid']]);
            if($res !== false){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            $res = \DB::query("INSERT INTO `{$pertname}` (`pid`,`gu`,`group_or_user`,`allow_or_deny`) VALUES (?,?,?,?)",[$permission,$gid,'group',$ad]);
            if($res !== false){
                return true;
            }
            else{
                return false;
            }
        }
    }

    public static function delete_permission_from_user($p,$uid,$useid)
    {
        global $_E;
        $permission = self::get_permission($p,$useid);
        $pertname = \DB::tname('user_permission');

        $perres = \DB::fetch("DELETE FROM `{$pertname}` WHERE `group_or_user`='user' AND `pid`=? AND `gu`=?",[$permission['pid'],$uid]);
        if($perres === false){
            return false;
        }
        return $perres;
    }

    public static function delete_permission_from_group($p,$gid,$useid)
    {
        global $_E;
        $permission = self::get_permission($p,$useid);
        $pertname = \DB::tname('user_permission');

        $perres = \DB::fetch("DELETE FROM `{$pertname}` WHERE `group_or_user`='group' AND `pid`=? AND `gu`=?",[$permission['pid'],$gid]);
        if($perres === false){
            return false;
        }
        return $perres;
    }
}

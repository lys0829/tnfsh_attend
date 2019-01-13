<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=example.csv");
header("Pragma: no-cache");
header("Expires: 0");

echo mb_convert_encoding("學號,班級,座號,姓名\n", "BIG5", "auto");
echo mb_convert_encoding("510001,101,11,王小明\n", "BIG5", "auto");
?>
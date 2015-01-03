<?php
/**
 *
 * @created:  Skiychan.
 * @date:  1/3/15
 * @modified:
 */

//include_once "get_companies.php";

//$company = new company();

if (!isset($GLOBALS["HTTP_RAW_POST_DATA"]))
    die(json_encode(array("code" => 300, "status" => "数据请求错误")));

include_once 'class.base.php';
define('TOKEN', 'fshare');
$ch = new BaseClass();

// 验证,成功后必须注释
//$ch->valid();
$ch->responseMsg();


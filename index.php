<?php
/**
 *
 * @created:  Skiychan.
 * @date:  1/3/15
 * @modified: 2/10/15
 */

//include_once "get_companies.php";

//$company = new company();

include_once 'class.base.php';
define('TOKEN', 'fshare');
$ch = new BaseClass();

if (isset($_GET['echostr'])) {
    $ch->valid();
} else {
    $ch->responseMsg();
}


<?php
/**
 * index.php
 * 采集入口
 * @author:   Skiychan<developer@zzzzy.com>
 * @created:  1/6/15 21:15
 * @modified: 
 */
     
$kd_code = isset($_GET['code']) ? $_GET['code'] : 0;
$is_add = FALSE;
$result = array();
$add_companies = include_once "add_companies.php";

foreach ($add_companies as $values) {
    if ($kd_code === $values['code']) {
        $is_add = TRUE;
        //$company_info = $values;
        break;
    }
}
//var_dump($result);
//var_dump($company_info);
//如果数据不存在add_companies里，则输入失败的信息
if ($is_add) {
    $result = include_once $kd_code.".php";
}

echo json_encode($result);
<?php
/**
 * index.php
 * 采集入口
 * @author:   Skiychan<developer@zzzzy.com>
 * @created:  1/6/15 21:15
 * @modified: 
 */
     
$kd_name = isset($_GET['name']) ? $_GET['name'] : 0;
$is_add = FALSE;
    
$add_companies = include_once "add_companies.php";

foreach ($add_companies as $values) {

    if ($kd_name = $values['code']) {
        $is_add = TRUE;
        $company_info = $values;
        break;
    }
}
var_dump($company_info);
//如果数据不存在add_companies里，则输入失败的信息
if ($is_add) {
    include_once $kd_name.".php";
}
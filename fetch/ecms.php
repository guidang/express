<?php
/**
 * ecms.php
 * 易客满(美国亚马逊发中国专用快递)
 * @author:   Skiychan<developer@zzzzy.com>
 * @created:  1/6/15 21:15
 * @modified: 
 */

//lang={cn / en}
//https://www.ecmsglobal.com/oms/showtracking?trackingno=APELAX1040039963&lang=cn
//
     
$lang = "cn";
$kd_url = "https://www.ecmsglobal.com/oms/showtracking?trackingno=APELAX1040039963&lang={$lang}";

$page_info = file_get_contents($kd_url);

$page_dom = "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>".$page_info;
$xmldoc = new DOMDocument();
@$xmldoc->loadHTML($page_dom);
$xmldoc->normalizeDocument();
$page_xml= new Domxpath($xmldoc);

$a = $page_xml->query('//div[@class="order"]/ul/div');

var_dump($a);
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
$postid = isset($_GET['postid']) ? $_GET['postid'] : 0;
$kd_url = "https://www.ecmsglobal.com/oms/showtracking?trackingno={$postid}&lang={$lang}";

$page_info = file_get_contents($kd_url);
//$page_info = file_get_contents("a.html");

$page_dom = "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>".$page_info;
$xmldoc = new DOMDocument();
@$xmldoc->loadHTML($page_dom);
$xmldoc->normalizeDocument();
$page_xml= new Domxpath($xmldoc);

$event_date = $page_xml->query('//div[@class="order"]//ul//div[@class="column event_date"]');
$event_where = $page_xml->query('//div[@class="order"]//ul//div[@class="column event_where"]');
$event_desc = $page_xml->query('//div[@class="order"]//ul//div[@class="column event_desc"]');

$thisinfo = array();
$add_infos = array();
foreach ($event_date as $keys => $values) {
    $location = $event_where->item($keys)->textContent;
    $context = $event_desc->item($keys)->textContent;
    $thisinfo[] = array(
        'time' => $values->textContent,
        'location' => $location,
        'context' => $location.",\n".$context,
    );
}

if (count($thisinfo) > 0) {
    $add_infos = array(
        'message' => "ok",
        'nu' => $postid,
        'companytype' => "ecms",
        'ischeck' => "1",
        'com' => "ecms",
        'updatetime' => $event_date->item(0)->textContent,
        'status' => "200",
        'codenumber' => $postid,
        'data' => $thisinfo
    );
}
    
return $add_infos;
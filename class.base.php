<?php

/**
 *    公众平台基础类 For Fshare
 * @author:    Skiychan
 * @created:    2013.11.19
 * @modified:  2015.02.10
 */
class BaseClass
{


    public $token = '';

    public function __construct($token = NULL){
        $this->token = $token;
    }

    //判断是否来自微信/易信服务器
    public function valid(){
        $echoStr = $_GET["echostr"];

        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    private $toUsername;
    private $fromUsername;
    private $createTime;
    private $msgId;
    private $msgType;

    private $time;
    private $content;

    private $postStr;

    public function responseMsg()
    {

        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        $this->postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $this->toUsername = $this->postObj->ToUserName;
        $this->fromUsername = $this->postObj->FromUserName;
        $this->createTime = $this->postObj->CreateTime;
        $this->msgId = $this->postObj->MsgId;
        $this->msgType = $this->postObj->MsgType;
        $this->time = time();

        $resultStr = "error";

        if (!empty($postStr)) {
            $resultStr = $this->get_text();
        }

        echo $resultStr;
    }


    //接收到文本
    public function get_text()
    {
        $textTpl = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[%s]]></MsgType>
			<Content><![CDATA[%s]]></Content>
			</xml>";

        if ($this->msgType != "text") {
            $content = "快递信息输入错误。";
        } else {

            $this->content = $this->postObj->Content;

            $this->content = preg_replace("/(\s{2,})/", " ", $this->content);
            $content_list = explode(" ", $this->content);

            if (count($content_list) == 2) {
                include_once "get_companies.php";

                $company = new company();
                $company_infos = $company::get_companies_info($content_list[0]);

                //如果快递100不支持此快递则diy
                if(count($company_infos) == 0) {
                    $is_diy = true;
                    $company::$companies = include_once 'fetch/add_companies.php';
                    $company_infos = $company::get_companies_info($content_list[0]);
                }
                
                //有此快递公司
                if (isset($company_infos[0])) {

                    $num = $content_list[1];
                    $code = $company_infos[0]['code'];        //获取英文代码
                    $com = $company_infos[0]['company'];        //获取公司名称

                    $numinfo = "快递:" . $com . "\n" . "单号:" . $num . "\n";

                    if (!$is_diy) {
                        $kd_url = "http://m.kuaidi100.com/query?type=" . $code . "&postid=" . $num;
                    } else {
                        $kd_url = dirname('http://' . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"]) . '/fetch/index.php?code=' . $code . "&postid=" . $num;
                    }

                    $json_getdata = file_get_contents($kd_url);
                    //$get_kdinfo = json_decode($json_getdata);	//object
                    $get_kdinfo = json_decode($json_getdata, true);    //array
                    $last_t = "查询时间:\n" . $get_kdinfo['updatetime'] . "\n\n";    //查询时间

                    $kd_shipinfo = $get_kdinfo['data'];    //快递数据数组
                    $kd_total = count($kd_shipinfo) - 1;
                    $ship = '';
                    $detail = "\n<a href='http://api.oupag.com/developer/kuaidi/getdata.php?code=" . $code . "&num=" . $num . "'>点击查看详情</a>";

                    //物流倒序详情
                    for ($i = $kd_total; $i >= 0; $i--) {
                        $shipinfo = $kd_shipinfo[$i]['time'] . "\n" . $kd_shipinfo[$i]['context'] . "\n";
                        $ship = $shipinfo . $ship;
                    }
                    //顺序物流详情
                    /*foreach ($kd_shipinfo as $v){
                        $shipinfo = $v['time']."\n".$v['context']."\n";
                        $ship = $shipinfo.$ship;
                    }
                    */
                    $get_kdinfo = $numinfo . $last_t . "【物流详情】\n" . $ship;
                    if ($ship) {
                        $contentStr = $get_kdinfo;
                    } else {
                        //$contentStr = $numinfo . ">没有物流数据！" . $kd_url;
                        $contentStr = $numinfo . ">没有物流数据！";
                    }

                    $contentStr .= $detail;
                    
                } else {  //无此快递公司
                    $contentStr = "无此快递公司记录.";
                }
            } else {
                $contentStr = "格式错误,正确格式如下:\n快递公司名称 快递单号";
            }

        }

        $resultStr = sprintf($textTpl,
            $this->fromUsername,
            $this->toUsername,
            $this->time,
            "text",
            $contentStr);

        return $resultStr;
    }

    //判断签名，返回bool
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
}
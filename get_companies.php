<?php

/**
 * 快递公司的class
 * @created:  Skiychan.
 * @date:  1/3/15
 * @modified:
 */
class company
{
    public static $file_name = "company_lists.php";

    public static $companies;

    public function __construct()
    {

        self::$companies = $this->get_companies();
    }

    /*
     * return array companies infos
     */
    public static function get_companies()
    {
        return include_once self::$file_name;
    }

    /*
     * 通过编码取得公司名
     * @param string $code 编码
     * return string 公司名
     */
    public static function get_company_name($code)
    {
        foreach (self::$companies as $keys => $values) {
            if ($values['code'] == $code) {
                return $values['company'];
            }
        }
        return;
    }

    /*
     * 通过公司名取得编码
     * $param string $name 公司名
     * return string 编码
     */
    public static function get_company_code($name)
    {
        foreach (self::$companies as $keys => $values) {
            if ($values['name'] == $name) {
                return $values['code'];
            }
        }
        return;
    }

    /*
     * 通过快递公司名取得编码 模糊查找全部
     * $param string $name 公司名
     * return array 编码和公司名
     */
    public static function get_companies_info($name)
    {
        $lists = array();
        foreach (self::$companies as $keys => $values) {
            if (mb_stristr($values['company'], $name)) {
                unset($values['id']);
                $lists[] = $values;
            }
        }
        return $lists;
    }

    /*
     * 不需要类型直接取得编码和公司名
     * @param string 编码或者公司名
     * return array 编码和公司名
     */
    public static function get_company_info($input)
    {
        foreach (self::$companies as $keys => $values) {
            if (in_array($input, $values)) {
                unset($values["id"]);
                return $values;
            }
        }
        return;
    }
}
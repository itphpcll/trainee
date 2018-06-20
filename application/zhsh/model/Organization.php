<?php
/**
 * User: Mr.lee
 * Date: 2018/3/13
 * Time: 14:54
 */

namespace app\zhsh\model;


use think\Model;

class Organization extends Model
{
    /**
     * 去除数组空值
     * @param array $var
     * @return array|bool
     */
    static function unsetEmpty($var = []){
        if(!is_array($var)){  return false; }
        foreach ($var as $ks=>$va){
            if($va === ""){ unset($var[$ks]); }
        }
        return $var;
    }
}
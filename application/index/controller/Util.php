<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/3 0003
 * Time: 18:13
 */

namespace app\index\controller;
use think\Config;

class Util
{
    public static function getArticleImg($content)
    {
        $pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png|\.JPG|\.jpeg|\.JPEG|\.PNG|\.GIF]))[\'|\"].*?>/";
        $matchContent=array();
        preg_match_all($pattern,$content,$matchContent);
        print_r($matchContent);
        if(isset($matchContent[1][0])){
            return $matchContent[1][0];
        }else {
            return Config::get('public_assets')."/images/no_image_thumb.jpg";//在相应位置放置一张命名为no-image的jpg图片
        }
    }

    public  static function getAbstract($content)
    {
        $newContent = strip_tags(stripslashes($content));
        $newContent = trim($newContent);
        $newContent = mb_strcut($newContent,0,150,'utf-8');
        return $newContent;
    }
}

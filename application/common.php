<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------


// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require '../vendor/autoload.php';

use think\Config;
use think\Db;

// 应用公共文件
function getArticleImg($content)
{
    $pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/";
    preg_match_all($pattern,$content,$matchContent);
    if(isset($matchContent[1][0])){
        return $matchContent[1][0];
    }else {
        return Config::get('public_assets')."/images/no_image_thumb.gif";//在相应位置放置一张命名为no-image的jpg图片
    }
}


/**
 *+----------------------------------------------------------
 * 字符串截取，支持中文和其他编码
 *+----------------------------------------------------------
 * @static
 * @access public
 *+----------------------------------------------------------
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 *+----------------------------------------------------------
 * @return string
 *+----------------------------------------------------------
 */

function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true){
    if(function_exists("mb_substr")){
        if($suffix){
            if(strlen($str)>$length)
                return mb_substr($str, $start, $length, $charset)."...";
            else
                return mb_substr($str, $start, $length, $charset);
        }else{
            return mb_substr($str, $start, $length, $charset);
        }
    }elseif(function_exists('iconv_substr')) {
        if($suffix){
            return iconv_substr($str,$start,$length,$charset);
        }else{
            return iconv_substr($str,$start,$length,$charset);
        }
    }
}

function getAbstract($content,$length=200)
{
    $newContent = strip_tags(stripslashes($content));
    $newContent = trim($newContent);
    $patternArr = array('/\s/','/ /');
    $replaceArr = array('','');
    $newContent = preg_replace($patternArr,$replaceArr,$newContent);
    $newContent = mb_strcut($newContent,0,$length,'utf-8');
    return $newContent;
}

//数组分数，key为分组键值
function array_group_by($arr, $key)
{
    $grouped = [];
    foreach ($arr as $value) {
        $grouped[$value[$key]][] = $value;
    }
    // Recursively build a nested grouping if more parameters are supplied
    // Each grouped array value is grouped according to the next sequential key
    if (func_num_args() > 2) {
        $args = func_get_args();
        foreach ($grouped as $key => $value) {
            $parms = array_merge([$value], array_slice($args, 2, func_num_args()));
            $grouped[$key] = call_user_func_array('array_group_by', $parms);
        }
    }
    return $grouped;
}


function getAge($birthday){
    //格式化出生时间年月日
    $byear=date('Y',$birthday);
    $bmonth=date('m',$birthday);
    $bday=date('d',$birthday);

    //格式化当前时间年月日
    $tyear=date('Y');
    $tmonth=date('m');
    $tday=date('d');

    //开始计算年龄
    $age=$tyear-$byear;
    if($bmonth>$tmonth || $bmonth==$tmonth && $bday>$tday){
        $age--;
    }
    return $age;
}

function  arrfiltrfun($arr){
    if($arr === '' || $arr === null){
        return false;
    }
    return true;
}


/**
 * 发送邮箱
 * @param type $data 邮箱队列数据 包含邮箱地址 内容
 */
function sendEmail($data = []) {

    $systemconfig = Db::name('system')->find();

    $mail = new PHPMailer(); //实例化
    $mail->IsSMTP(); // 启用SMTP
    $mail->SMTPDebug = 0;
    $mail->Host = $systemconfig['SMTP']; //SMTP服务器 以126邮箱为例子
    $mail->Port = 587;  //邮件发送端口
    $mail->SMTPAuth = true;  //启用SMTP认证
    $mail->SMTPSecure = "tls";   // 设置安全验证方式为ssl
    $mail->CharSet = "UTF-8"; //字符集
    $mail->Encoding = "base64"; //编码方式
    $mail->Username = $systemconfig['Email'];  //你的邮箱
    $mail->Password = $systemconfig['EmailPassword'];  //你的密码
    $mail->Subject = 'SVCSA来邮件啦'; //邮件标题
    $mail->From = $systemconfig['Email'];  //发件人地址（也就是你的邮箱）
    $mail->FromName = 'SVCSA';  //发件人姓名
    if($data && is_array($data)){
        foreach ($data as $k=>$v){
            $mail->AddAddress($v['user_email'], "亲"); //添加收件人（地址，昵称）
            $mail->IsHTML(true); //支持html格式内容
            $mail->Body = $v['content']; //邮件主体内容
            //发送成功就删除
            if ($mail->Send()) {
                return ['code'=>1,'msg'=>'发送成功'];
            }else{
                return ['code'=>0,'msg'=>"Mailer Error: $mail->ErrorInfo"]; // 输出错误信息
            }
        }
    }
}

/**
 * Return asset url based on current url.
 * Currently there are two sets of uploads running together. Need to have a way to dynamically determine
 * which routes the images should be upload to
 * 
 * - prod
 * - new prod
 * - beta
 * - local
 */
function getAssetUploadUrl() {
    $currentServer = $_SERVER['SERVER_NAME'];
    switch ($currentServer) {
        case "prod.svcsa.org":
            return "/../../../public/uploads";
            break;
        default:
            //return "/../../../../uploads";
            return "/../../../public/uploads";
    }
}
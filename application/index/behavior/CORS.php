<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/16 0016
 * Time: 13:12
 */

namespace app\index\behavior;

use think\Response;

class CORS {

    public function appInit(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: token, Origin, X-Requested-With, Content-Type, Accept, Authorization");
        header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS,PATCH');

        if(request()->isOptions()){
//            header('Access-Control-Allow-Origin:*');
//            header('Access-Control-Allow-Headers:Accept,Referer,Host,Keep-Alive,User-Agent,X-Requested-With,Cache-Control,Content-Type,Cookie,token');
            header('Access-Control-Allow-Credentials:true');
            header('Access-Control-Max-Age:1728000');
            header('Content-Type:text/plain charset=UTF-8');
            header('Content-Length: 0', true);
            header('status: 204');
            header('HTTP/1.0 204 No Content');
            Response::create()->send();
        }
    }

}

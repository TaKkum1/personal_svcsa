<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/6 0006
 * Time: 21:56
 */

namespace app\index\controller;


use think\Db;
use think\Session;


class Bblog extends Base
{
    public function add()
    {
        $this->checkauthorization();

        $data = request()->only('bblogs', 'post');

        if (!isset($data['bblogs']) || !$data['bblogs']){
            $this->affectedRowsResult(0);
        }

        $jsondata = json_decode($data['bblogs'],JSON_OBJECT_AS_ARRAY);


        $result = Db::name('bb_log')->insertAll($jsondata);
        $this->affectedRowsResult($result);
    }
}
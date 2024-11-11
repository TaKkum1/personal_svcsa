<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Session;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS,PATCH');

class Base extends Controller
{
    //用于跨域测试
    public  function optionpass()
    {
//        header("Access-Control-Allow-Origin: *");
//        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
//        header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS,PATCH');

        return ;
    }

    protected  function  makeNull(&$arr)
    {
        foreach ($arr as &$key)  {
            if ($key ==='') $key = NULL;
            else if ($key === NULL) unset($key);
        }
        return $arr;
    }

    protected  function  headerAndFooter($nav = '')
    {
        $bbcompetition = Db::name('bb_competition')->select();
        $sponsor = Db::name('sponsor')->select();
        $this->view->assign('bbcompetition',$bbcompetition);
        $this->view->assign('sponsor',$sponsor);
        $this->view->assign('nav',$nav);
        return ;
    }


    protected function  checkauthorization()
    {
        // if (!Session::get('username')){ header("HTTP/1.1 401 Unauthorized");die;}
    }

    protected  function  jsonRequest()
    {
        $contenttype = strtolower(request()->header('Accept'));
        $pos = strpos($contenttype,'application/json');
        if($pos === false)
            return false;
        else
            return true;

    }

    protected function affectedRowsResult($result){
        $status = $result>0?1:0;
//        header("Access-Control-Allow-Origin: *");
//        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
//        header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS,PATCH');

        echo json_encode(['status'=>$status,
            'affectedRows'=>$result], JSON_UNESCAPED_UNICODE);
        die;
    }

    protected function dataResult($data){
//        header("Access-Control-Allow-Origin: *");
//        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
//        header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS,PATCH');

        $result = array_merge(['status'=>1,'data'=>$data]);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        die;
    }

    protected function paginatedResult($totalcount,$pagesize,$page,$data){
//        header("Access-Control-Allow-Origin: *");
//        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
//        header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS,PATCH');

        echo json_encode(['status'=>1,
            'totalcount'=>$totalcount,
            'pagesize'=>$pagesize,
            'page'=>$page,
            'data'=>$data], JSON_UNESCAPED_UNICODE);
        die;
    }

    protected function jsonResult($status, $data){
        $result = array_merge(['status'=>$status],$data);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        die;
    }
}

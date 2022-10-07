<?php
/**
 * Created by PhpStorm.
 * User: Aven
 * Date: 2018/10/28
 * Time: 1:30
 */

namespace app\index\controller;



use think\Db;
use think\Session;

class Ctfcpicture extends Base
{
    const FIELD = 'MatchID,Src,Description,Flag';

    public function add()
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        $validator = validate('Ctfc_picture');
        $result = $validator->check($data);
        if (!$result){
            $this->affectedRowsResult(0);
        }
        $result = Db::name('ctfc_picture')->insert($data);
        $this->affectedRowsResult($result);
    }

    public function delete($id){
        $this->checkauthorization();

        $result = Db::name('ctfc_picture')->where('ID', $id)->delete();
        $this->affectedRowsResult($result);
    }

    public function read($id){
        $result = Db::name('ctfc_picture')->where('ID', $id)->find();
        $this->dataResult($result);
    }

    public function lists($matchid){
        $pagesize = input('pagesize');
        $list = Db::name('ctfc_picture');
        if($matchid) $list->where('MatchID',$matchid);
        $list = $list->paginate($pagesize);
        if ($this->jsonRequest()) {
            $this->paginatedResult(
                $list->total(),
                $list->listRows(), $list->currentPage(),
                $list->items()
            );
        }

        if(!$matchid) goto notfound;

        $matchteam = Db::name('ctfc_playermatch')->where('MatchID',$matchid)->find();

        if(!$matchteam) goto notfound;

        $gallerytitle = '图集：' . date('m月j日',strtotime($matchteam['StartTime'])) . ' ' . $matchteam['MatchName'];

        $this->headerAndFooter('gallery');
        $this->view->assign('gallerytitle',$gallerytitle);
        $this->view->assign('pagerender',$list->render());
        $this->view->assign('pictures',$list->items());

        return $this->view->fetch('picture/lists');

notfound:
        header("HTTP/1.0 404 Not Found");
        die;

//
//        $list = Db::name('ctfc_picture');
//        if(input('matchid')) $list = $list->where('MatchID',input('matchid'));
//        $list = $list->paginate(input('pagesize'));
//        $this->paginatedResult(
//            $list->total(),
//            $list->listRows(),
//            $list->currentPage(),
//            $list->items()
//        );
    }

    public function update($id){
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
//        $validator = validate('Ctfc_picture');
//        $result = $validator->check($data);
//        if (!$result){
//            $this->result(0);
//        }
        $result = Db::name('ctfc_picture')->where('ID', $id)->update($data);
        $this->affectedRowsResult($result);
    }
}
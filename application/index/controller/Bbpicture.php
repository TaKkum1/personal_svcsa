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

class Bbpicture extends Base
{
    const FIELD = 'MatchID,Src,Description,Flag';

    public function add()
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        $validator = validate('BbPicture');
        $result = $validator->check($data);
        if (!$result){
            $this->affectedRowsResult(0);
        }
        $result = Db::name('bb_picture')->insert($data);
        $this->affectedRowsResult($result);
    }

    public function delete($id){
        $this->checkauthorization();

        $result = Db::name('bb_picture')->where('ID', $id)->delete();
        $this->affectedRowsResult($result);
    }

    public function read($id){
        $result = Db::name('bb_picture')->where('ID', $id)->find();
        $this->dataResult($result);
    }


    public function lists($matchid){
        $pagesize = input('pagesize');
        $list = Db::name('bb_picture');
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

        $matchteam = Db::name('bb_matchteam_view')->where('MatchID',$matchid)->find();
        $gallerytitle = '图集：' . date('m月j日',strtotime($matchteam['StartTime'])) . ' ' . $matchteam['MatchName'];

        $this->headerAndFooter('gallery');
        $this->view->assign('gallerytitle',$gallerytitle);
        $this->view->assign('pagerender',$list->render());
        $this->view->assign('pictures',$list->items());

        return $this->view->fetch('picture/lists');

notfound:
        header("HTTP/1.0 404 Not Found");
        die;
    }

    public function update($id){
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
//        $validator = validate('BbPicture');
//        $result = $validator->check($data);
//        if (!$result){
//            $this->result(0);
//        }
        $result = Db::name('bb_picture')->where('ID', $id)->update($data);
        $this->affectedRowsResult($result);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Aven
 * Date: 2018/10/28
 * Time: 1:30
 */

namespace app\index\controller;

require_once (__DIR__ . '/../../../thinkphp/library/think/db/Expression.php');

use think\Db;
use think\Session;
use think\Db\Expression;

class Bbseason extends Base
{
    const FIELD = 'Name,StartTime,TeamNumber,GroupNumber,PlayoffGroupNumber,Rules,CompetitionID';

    /*
    public function add()
    {
        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        $validator = validate('BbSeason');
        $result = $validator->check($data);
        if (!$result){
            echo 'check';
            echo $validator->getError();
            $this->result(0);
        }
        $result = Db::name('bb_season')->insert($data);
        $this->result($result);
    }*/

    public function add($bbcompetitionid=null)
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        if(!isset($data["CompetitionID"]))
            $data["CompetitionID"]=$bbcompetitionid;
        $validator = validate('BbSeason');
        $result = $validator->check($data);
        if (!$result){
 //           echo 'check';
 //           echo $validator->getError();
            $this->affectedRowsResult(0);
        }
        $result = Db::name('bb_season')->insert($data);
        $this->affectedRowsResult($result);
    }


    public function playoff($id){
        $thisseason = Db::name('bb_competitionseason_view')->where('SeasonID', $id)->find();
        $this->view->assign('thisseason', $thisseason);
        $this->headerAndFooter('competition');
        return $this->view->fetch('bbseason/playoff');
    }

    public function delete($id){
        $this->checkauthorization();

        $result = Db::name('bb_season')->where('ID', $id)->delete();
        $this->affectedRowsResult($result);
    }

    public function read($id){


        $thisseason = Db::name('bb_competitionseason_view')->where('SeasonID', $id)->find();
        if(!$thisseason) goto notfound;
        $competitionid= $thisseason['CompetitionID'];

        if($competitionid<=2)
            $this->headerAndFooter('competition'. $competitionid);
        else
            $this->headerAndFooter('competition');

        $exp = new Expression('field(SeasonID,'.$id.'),StartTime DESC');
        $result = Db::name('bb_competitionseason_view')->where('CompetitionID', $competitionid)
            ->order($exp)->select();
        $result = array_reverse($result);

        if($this->jsonRequest()) {
            $this->dataResult($result);
        } else if(count($result)>0){
            $this->view->assign('thisseason',$result[0]);
            $matches = Db::name('bb_matchteam_view')->where('SeasonID', $result[0]['SeasonID'])
                ->order('StartTime','desc')->select();
            $this->view->assign('matches',$matches);
            $otherseasons = array_slice($result,1);
            $this->view->assign('otherseasons', $otherseasons);
            $this->view->assign('competitionid', $competitionid);
            return $this->view->fetch('bbseason/read');
        }

        notfound:
            header("HTTP/1.0 404 Not Found");
            die;


    }

    public function readRecent($competitionid){
        if($competitionid<=2)
            $this->headerAndFooter('competition'. $competitionid);
        else
            $this->headerAndFooter('competition');

        $result = Db::name('bb_competitionseason_view')->where('CompetitionID', $competitionid)
            ->order('StartTime desc')->select();

        if($this->jsonRequest())
            $this->dataResult($result);

        $matches = array();

        if(count($result)==0) {
            $this->view->assign('thisseason',['CompetitionName'=>'（未启用篮球项目）',
                'SeasonID'=>'-1','SeasonName'=>'（未启用赛季）']);
            $matches=null;
        } else {
            $this->view->assign('thisseason',$result[0]);
            $matches = Db::name('bb_matchteam_view')->where('SeasonID', $result[0]['SeasonID'])
                ->order('StartTime','desc')->select();
        }


        $this->view->assign('matches',$matches);
        $otherseasons = array_slice($result,1);
        $this->view->assign('otherseasons', $otherseasons);
        $this->view->assign('competitionid', $competitionid);
        return $this->view->fetch('bbseason/read');


        notfound:
            header("HTTP/1.0 404 Not Found");
            die;

    }

    public function lists(){
        $pagesize = input('pagesize');
        $list = Db::name('bb_competitionseason_view')->paginate($pagesize);
        if($this->jsonRequest()) {
            $this->paginatedResult(
                $list->total(),
                $list->listRows(),
                $list->currentPage(),
                $list->items()
            );
        }

        notfound:
            header("HTTP/1.0 404 Not Found");
            die;
    }

    public function update($id){
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
//        $validator = validate('BbSeason');
//        $result = $validator->check($data);
//        if (!$result){
//            $this->result(0);
//        }
        $result = Db::name('bb_season')->where('ID', $id)->update($data);
        $this->affectedRowsResult($result);
    }

}

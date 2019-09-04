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

class Bbmatch extends Base
{
    const FIELD = 'TeamAID,TeamBID,ScoreTeamA,ScoreTeamB,State,StartTime,Report,VideoSrc,SeasonID';

    public function add($seasonid=null)
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        if(!isset($data["SeasonID"]))
            $data["SeasonID"]=$seasonid;
        $validator = validate('BbMatch');
        $result = $validator->check($data);
        if (!$result){
            $this->affectedRowsResult(0);
        }
        $result = Db::name('bb_match')->insert($data);
        $this->affectedRowsResult($result);
    }

    public function delete($id){
        $this->checkauthorization();

        $result = Db::name('bb_match')->where('ID', $id)->delete();
        $this->affectedRowsResult($result);
    }

    public function read($id){

        $result = Db::name('bb_matchteam')->where('MatchID', $id)->find();
        if($this->jsonRequest()) {
            $this->dataResult($result);
        } else if(count($result)>0) {
            if($result['CompetitionID']<=2)
                $this->headerAndFooter('competition'. $result['CompetitionID']);
            else
                $this->headerAndFooter('competition');

            $this->view->assign('result',$result);

            return $this->view->fetch('bbmatch/report');
        }

notfound:
        header("HTTP/1.0 404 Not Found");
        die;

    }

    public function lists($seasonid=null){
        $list = Db::name('bb_matchteam');
        if($seasonid) $list = $list->where('seasonid',$seasonid);
        $list = $list->paginate(input('pagesize'));
        $this->paginatedResult(
            $list->total(),
            $list->listRows(),
            $list->currentPage(),
            $list->items()
        );



notfound:
        header("HTTP/1.0 404 Not Found");
        die;
    }

    public function update($id){
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);

//        $validator = validate('BbMatch');
//        $result = $validator->check($data);
//        if (!$result){
//            $this->result(0);
//        }
        $result = Db::name('bb_match')->where('ID', $id)->update($data);
        $this->affectedRowsResult($result);
    }
}
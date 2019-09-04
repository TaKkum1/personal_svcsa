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

class Bbstatistics extends Base
{
    const FIELD = 'MatchID,PlayerID,Starter,Foul,Points,Assist,Steal,Block,Turnover,OffensiveRebound,Rebound,3PointsHit,2PointsHit,1PointsHit,Hit,3PointsShot,2PointsShot,1PointsShot,Shot';

    public function add($matchid = null)
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        $playerid = input('playerid');
        if(!isset($data["MatchID"]) || !isset($data["PlayerID"])) {
            $data["MatchID"]=$matchid;
            if (!$playerid) $this->affectedRowsResult(0);
            $data["PlayerID"]=$playerid;
        }
        $validator = validate('Bb_statistics');
        $result = $validator->check($data);
        if (!$result){
            $this->affectedRowsResult(0);
        }
        $result = Db::name('bb_statistics')->insert($data);
        $this->affectedRowsResult($result);
    }

    public function delete($id){
        $this->checkauthorization();

        $result = Db::name('bb_statistics')->where('ID', $id)->delete();
        $this->affectedRowsResult($result);
    }

    public function read($id){
        $result = Db::name('bb_statistics')->where('ID', $id)->find();
        $this->dataResult($result);
    }

    public function readViewByMatchTeam($matchid){
        $pagesize = input('pagesize');
        $list = Db::name('bb_statisticsplayerteam')
            ->where('matchid', $matchid)->order('Starter desc')->paginate($pagesize);
        $result = $list->items();
        if($this->jsonRequest()) {
            $this->paginatedResult(
                $list->total(),
                $list->listRows(),
                $list->currentPage(),
                $list->items()
            );
        } else if(count($result)>0){
            if($result[0]['CompetitionID']<=2)
                $this->headerAndFooter('competition'. $result[0]['CompetitionID']);
            else
                $this->headerAndFooter('competition');

            $exp = new \think\Db\Expression('field(SeasonID,'. $result[0]['SeasonID'] .'),StartTime DESC');
            $seasons = Db::name('bb_competitionseason')->where('CompetitionID', $result[0]['CompetitionID'])
                ->order($exp)->select();
            $seasons = array_reverse($seasons);
            $otherseasons = array_slice($seasons,1);
            $this->view->assign('thisseason',$seasons[0]);
            $this->view->assign('otherseasons',$otherseasons);

            $groupedResult = array_group_by($result,'TeamID');
            $groupedResultA = current($groupedResult);
            $groupedResultB = end($groupedResult);

            $this->view->assign('statsa',$groupedResultA);
            $this->view->assign('statsb',$groupedResultB);

            $this->view->assign('TeamAShortName',$groupedResultA[0]['TeamShortName']);
            $this->view->assign('TeamBShortName',$groupedResultB[0]['TeamShortName']);



            return $this->view->fetch('bbstatistics/read');
        }


notfound:
        header("HTTP/1.0 404 Not Found");
        die;

    }

    public function readViewBySeasonPlayer($seasonid){
        $ofield = input('ofield')?input('ofield'):'PlayerID';
        $desc = input('desc')>0?1:0;
        $orderby = $desc>0?$ofield . ' desc':$ofield . ' asc';
        $pagesize = input('pagesize');
        $list = Db::name('bb_statisticsseasonplayer')
            ->where('seasonid', $seasonid)
            ->order($orderby)
            ->paginate($pagesize,false);
        $result = $list->items();
        if($this->jsonRequest()) {
            $this->paginatedResult(
                $list->total(),
                $list->listRows(),
                $list->currentPage(),
                $list->items()
            );
        } else if(count($result)>0){
            if($result[0]['CompetitionID']<=2)
                $this->headerAndFooter('competition'. $result[0]['CompetitionID']);
            else
                $this->headerAndFooter('competition');

            $exp = new \think\Db\Expression('field(SeasonID,'. $result[0]['SeasonID'] .'),StartTime DESC');
            $seasons = Db::name('bb_competitionseason')->where('CompetitionID', $result[0]['CompetitionID'])
                ->order($exp)->select();
            $seasons = array_reverse($seasons);
            $otherseasons = array_slice($seasons,1);
            $this->view->assign('thisseason',$seasons[0]);
            $this->view->assign('otherseasons',$otherseasons);

            $this->view->assign('ofield',$ofield);
            $newdesc = (int)(!$desc);
            $this->view->assign('newdesc',$newdesc);
            $this->view->assign('statsresult',$result);


            return $this->view->fetch('bbstatistics/rank');
        }


notfound:
        header("HTTP/1.0 404 Not Found");
        die;
    }

    public function lists(){
        $matchid = input('matchid');
//        $teamid = input('teamid');
        $seasonid = input('seasonid');
//        $playerid = input('playerid');

        if($matchid) return $this->readViewByMatchTeam($matchid);
        if($seasonid) return $this->readViewBySeasonPlayer($seasonid);

        $pagesize = input('pagesize');
        $list = Db::name('bb_statisticsplayerteam')->paginate($pagesize);
        $this->paginatedResult(
            $list->total(),
            $list->listRows(),            $list->currentPage(),
            $list->items()
        );
    }

    public function update($id){
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
//        $validator = validate('Bb_statistics');
//        $result = $validator->check($data);
//        if (!$result){
//            $this->result(0);
//        }
        $result = Db::name('bb_statistics')->where('ID', $id)->update($data);
        $this->affectedRowsResult($result);
    }
}
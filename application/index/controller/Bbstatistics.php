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

    private function CalculateQuarterScores($logs) {
      $quarterscores = array();

      $current_quarter = 1;
      foreach($logs as $log) {
        $event = $log['Event'];

        // Update the quarters if needed.
        if (strpos($event, '第2节 start') !== false) {
          $current_quarter = 2;
        } else if (strpos($event, '第3节 start') !== false) {
          $current_quarter = 3;
        } else if (strpos($event, '第4节 start') !== false) {
          $current_quarter = 4;
        } else if (strpos($event, '1加时 start') !== false) {
          $current_quarter = 5;
        } else if (strpos($event, '2加时 start') !== false) {
          $current_quarter = 6;
        }

        // If there is a point event. record it.
        if (strpos($event, 'Made') !== false) {
          // Get Team Name.
          $team = '';
          $start = strpos($event, '(');
          $end = strpos($event, ')');
          if ($start !== false and $end !== false) {
            $team = substr($event, $start + 1, $end - $start - 1);
          }
          if (empty($team)) {
            return $quarterscores;
          }

          // Get points to be added.
          $points = 2;
          if (strpos($event, 'Three Point Made') !== false) {
            $points = 3;
          }
          if (strpos($event, 'Free Throw Made') !== false) {
            $points = 1;
          }

          // Update team scores.
          if (!array_key_exists($team, $quarterscores)) {
            $quarterscores[$team] = array();
            $quarterscores[$team][1] = 0;
            $quarterscores[$team][2] = 0;
            $quarterscores[$team][3] = 0;
            $quarterscores[$team][4] = 0;
            $quarterscores[$team][5] = 0;
            $quarterscores[$team][6] = 0;
          }
          $quarterscores[$team][$current_quarter] += $points;

        }
      }
      return $quarterscores;
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
            // Total stats for team A.
            $TotalStatA = [];
            $TotalPointsA = 0;
            $Total2PointsHitA = 0;
            $Total2PointsShotA = 0;
            $Total3PointsHitA = 0;
            $Total3PointsShotA = 0;
            $Total1PointsHitA = 0;
            $Total1PointsShotA = 0;
            $TotalOffensiveReboudA = 0;
            $TotalReboundA = 0;
            $TotalAssistA = 0;
            $TotalStealA = 0;
            $TotalBlockA = 0;
            $TotalTurnoverA = 0;
            $TotalFoulA = 0;
            foreach ($groupedResultA as $A_Player) {
              $TotalPointsA += $A_Player['Points'];
              $Total2PointsHitA += $A_Player['2PointsHit'];
              $Total2PointsShotA += $A_Player['2PointsShot'];
              $Total3PointsHitA += $A_Player['3PointsHit'];
              $Total3PointsShotA += $A_Player['3PointsShot'];
              $Total1PointsHitA += $A_Player['1PointsHit'];
              $Total1PointsShotA += $A_Player['1PointsShot'];
              $TotalOffensiveReboudA += $A_Player['OffensiveRebound'];
              $TotalReboundA += $A_Player['Rebound'];
              $TotalAssistA += $A_Player['Assist'];
              $TotalStealA += $A_Player['Steal'];
              $TotalBlockA += $A_Player['Block'];
              $TotalTurnoverA += $A_Player['Turnover'];
              $TotalFoulA += $A_Player['Foul'];
            }
            $TotalStatA['PlayerName'] = "球队";
            $TotalStatA['Starter'] = 0;
            $TotalStatA['Points'] = $TotalPointsA;
            $TotalStatA['2PointsHit'] = $Total2PointsHitA;
            $TotalStatA['2PointsShot'] = $Total2PointsShotA;
            $TotalStatA['3PointsHit'] = $Total3PointsHitA;
            $TotalStatA['3PointsShot'] = $Total3PointsShotA;
            $TotalStatA['1PointsHit'] = $Total1PointsHitA;
            $TotalStatA['1PointsShot'] = $Total1PointsShotA;
            $TotalStatA['OffensiveRebound'] = $TotalOffensiveReboudA;
            $TotalStatA['Rebound'] =$TotalReboundA;
            $TotalStatA['Assist'] = $TotalAssistA;
            $TotalStatA['Steal'] = $TotalStealA;
            $TotalStatA['Block'] = $TotalBlockA;
            $TotalStatA['Turnover'] = $TotalTurnoverA;
            $TotalStatA['Foul'] = $TotalFoulA;
            array_push($groupedResultA, $TotalStatA);

            $groupedResultB = end($groupedResult);
            // Total stats for team A.
            $TotalStatB = [];
            $TotalPointsB = 0;
            $Total2PointsHitB = 0;
            $Total2PointsShotB = 0;
            $Total3PointsHitB = 0;
            $Total3PointsShotB = 0;
            $Total1PointsHitB = 0;
            $Total1PointsShotB = 0;
            $TotalOffensiveReboudB = 0;
            $TotalReboundB = 0;
            $TotalAssistB = 0;
            $TotalStealB = 0;
            $TotalBlockB = 0;
            $TotalTurnoverB = 0;
            $TotalFoulB = 0;
            foreach ($groupedResultB as $B_Player) {
              $TotalPointsB += $B_Player['Points'];
              $Total2PointsHitB += $B_Player['2PointsHit'];
              $Total2PointsShotB += $B_Player['2PointsShot'];
              $Total3PointsHitB += $B_Player['3PointsHit'];
              $Total3PointsShotB += $B_Player['3PointsShot'];
              $Total1PointsHitB += $B_Player['1PointsHit'];
              $Total1PointsShotB += $B_Player['1PointsShot'];
              $TotalOffensiveReboudB += $B_Player['OffensiveRebound'];
              $TotalReboundB += $B_Player['Rebound'];
              $TotalAssistB += $B_Player['Assist'];
              $TotalStealB += $B_Player['Steal'];
              $TotalBlockB += $B_Player['Block'];
              $TotalTurnoverB += $B_Player['Turnover'];
              $TotalFoulB += $B_Player['Foul'];
            }
            $TotalStatB['PlayerName'] = "球队";
            $TotalStatB['Starter'] = 0;
            $TotalStatB['Points'] = $TotalPointsB;
            $TotalStatB['2PointsHit'] = $Total2PointsHitB;
            $TotalStatB['2PointsShot'] = $Total2PointsShotB;
            $TotalStatB['3PointsHit'] = $Total3PointsHitB;
            $TotalStatB['3PointsShot'] = $Total3PointsShotB;
            $TotalStatB['1PointsHit'] = $Total1PointsHitB;
            $TotalStatB['1PointsShot'] = $Total1PointsShotB;
            $TotalStatB['OffensiveRebound'] = $TotalOffensiveReboudB;
            $TotalStatB['Rebound'] =$TotalReboundB;
            $TotalStatB['Assist'] = $TotalAssistB;
            $TotalStatB['Steal'] = $TotalStealB;
            $TotalStatB['Block'] = $TotalBlockB;
            $TotalStatB['Turnover'] = $TotalTurnoverB;
            $TotalStatB['Foul'] = $TotalFoulB;
            array_push($groupedResultB, $TotalStatB);

            $this->view->assign('statsa',$groupedResultA);
            $this->view->assign('statsb',$groupedResultB);

            $this->view->assign('TeamAShortName',$groupedResultA[0]['TeamShortName']);
            $this->view->assign('TeamBShortName',$groupedResultB[0]['TeamShortName']);

            // Get Quarter Scores.
            $logs = Db::name('bb_log')->where('MatchID', $matchid)->select();
            $quarterscores = $this->CalculateQuarterScores($logs);
            $this->view->assign('QuarterScores', $quarterscores);

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

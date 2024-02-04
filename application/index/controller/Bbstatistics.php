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

/*********************
*    Helper Methods  *
**********************/
function UpdateTotalStats(&$groupedResult) {
  $TotalStat = [];
  $TotalStat['PlayerName'] = "球队";
  $TotalStat['Minute'] = "200'0\"";
  $TotalStat['Starter'] = 0;
  $TotalStat['Points'] = 0;
  $TotalStat['2PointsHit'] = 0;
  $TotalStat['2PointsShot'] = 0;
  $TotalStat['3PointsHit'] = 0;
  $TotalStat['3PointsShot'] = 0;
  $TotalStat['1PointsHit'] = 0;
  $TotalStat['1PointsShot'] = 0;
  $TotalStat['OffensiveRebound'] = 0;
  $TotalStat['Rebound'] =0;
  $TotalStat['Assist'] = 0;
  $TotalStat['Steal'] = 0;
  $TotalStat['Block'] = 0;
  $TotalStat['Turnover'] = 0;
  $TotalStat['Foul'] = 0;
  foreach ($groupedResult as &$Player) {
    $TotalStat['Points'] += $Player['Points'];
    $TotalStat['2PointsHit'] += $Player['2PointsHit'];
    $TotalStat['2PointsShot'] += $Player['2PointsShot'];
    $TotalStat['3PointsHit'] += $Player['3PointsHit'];
    $TotalStat['3PointsShot'] += $Player['3PointsShot'];
    $TotalStat['1PointsHit'] += $Player['1PointsHit'];
    $TotalStat['1PointsShot'] += $Player['1PointsShot'];
    $TotalStat['OffensiveRebound'] += $Player['OffensiveRebound'];
    $TotalStat['Rebound'] += $Player['Rebound'];
    $TotalStat['Assist'] += $Player['Assist'];
    $TotalStat['Steal'] += $Player['Steal'];
    $TotalStat['Block'] += $Player['Block'];
    if (!$Player['Turnover']) {
      $Player['Turnover'] = 0;
    }
    $TotalStat['Turnover'] += $Player['Turnover'];
    $TotalStat['Foul'] += $Player['Foul'];
  }
  array_push($groupedResult, $TotalStat);
}

function UpdateQuarter($event, &$current_quarter) {
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
}

function IsSubEvent($event, &$playername_on, &$playername_off) {
  if (strpos($event, 'enters the game') == false) {
    return false;
  } else {
    // First, trim the [] part.
    right_bracket_pos = strpos($event, ']', 0);
    $event = substr($event, right_bracket_pos+1);
    
    // Second, find all spaces pos.
    $space_pos = array();
    $last_space_pos = 0;
    while (($last_space_pos = strpos($event, ' ', $last_space_pos)) !== false) {
      $space_pos[] = $last_space_pos;
      $last_space_pos += 1;
    }
    // Third, find the pos of 'for' and 'enters'.
    $enters_pos = strpos($event, 'enters', 0);
    $for_pos = strpos($event, 'for', 0);

    // On player is right after the 2nd space and before 'enters'.
    $start = $space_pos[1] + 1;
    $end = $enters_pos - 1;
    $playername_on = substr($event, $start, $end - $start);

    // Off player is third spaces after 'for'
    $space_count = 0;
    foreach ($space_pos as $pos) {
      if ($pos > $for_pos) {
        $space_count++;
      }
      if ($space_count == 3) {
        $start = $pos + 1;
        $end = strlen($event);
        $playername_off = substr($event, $start, $end - $start);
        break;
      }
    }
    return true;
  }
}

function IsScoringEvent($event) {
  if (strpos($event, 'Made') !== false) {
    return true;
  } else {
    return false;
  }
}

function GetCurrentMinuteSecond($event, &$minute, &$second) {
  $minute_symbol = strpos($event, "'", 0);
  $second_symbol = strpos($event, "\"", 0);
  $minute = substr($event, 0, $minute_symbol);
  $second = substr($event, $minute_symbol + 1, $second_symbol - $minute_symbol - 1);
  if ($minute == '10' and $second == '0') {
    $minute = '0';
  }
}

function CalculatePlayerLastOnCourtTimeInSeconds(
  $last_on_quarter, $last_on_minute, $last_on_second,
  $current_quarter, $current_minute, $current_second) {
    $minute = 0;
    $second = 0;
    if ($last_on_quarter == $current_quarter) {
      if ($last_on_second >= $current_second) {
        $minute = $last_on_minute - $current_minute;
        $second = $last_on_second - $current_second;
      } else {
        $minute = $last_on_minute - $current_minute - 1;
        $second = $last_on_second + 60 - $current_second;
      }
    } else {
      $entire_quarter = $current_quarter - $last_on_quarter - 1;
      $minute = $last_on_minute + 10*$entire_quarter + (9 - $current_minute);
      $second = $last_on_second + (60 - $current_second);
      if ($second >= 60) {
        $minute += 1;
        $second -= 60;
      }
    }
    return $minute*60 + $second;
}

function GetTeamInfoInEvent($event) {
  $team = '';
  $start = strpos($event, '[');
  $end = strpos($event, ']');
  if ($start !== false and $end !== false) {
      $team = substr($event, $start + 1, $end - $start - 1);
  }
  return $team;
}

function ProcessLogs($logs, $teamA, $teamB) {
  $match_info = array();
  $match_info[$teamA] = array();
  $match_info[$teamA][1] = 0;
  $match_info[$teamA][2] = 0;
  $match_info[$teamA][3] = 0;
  $match_info[$teamA][4] = 0;
  $match_info[$teamA][5] = 0;
  $match_info[$teamA][6] = 0;
  $match_info[$teamB] = array();
  $match_info[$teamB][1] = 0;
  $match_info[$teamB][2] = 0;
  $match_info[$teamB][3] = 0;
  $match_info[$teamB][4] = 0;
  $match_info[$teamB][5] = 0;
  $match_info[$teamB][6] = 0;
  // For matches don't have team info.
  $match_info[''] = array();
  $match_info[''][1] = 0;
  $match_info[''][2] = 0;
  $match_info[''][3] = 0;
  $match_info[''][4] = 0;
  $match_info[''][5] = 0;
  $match_info[''][6] = 0;

  $current_quarter = 1;
  foreach($logs as $log) {
    $event = $log['Event'];
    $team = GetTeamInfoInEvent($event);

    // Update the quarters.
    UpdateQuarter($event, $current_quarter);

    $playername_on = '';
    $playername_off = '';
    if (IsSubEvent($event, $playername_on, $playername_off)) {
      // Update player minutes info.
      // Initialize the player minutes array if first seen.
      if (!array_key_exists($playername_on, $match_info[$team])) {
        $match_info[$team][$playername_on] = array();
        $match_info[$team][$playername_on]['TotalSeconds'] = 0;
        $match_info[$team][$playername_on]['LastOnQuarter'] = 1;
        $match_info[$team][$playername_on]['LastOnMinute'] = 10;
        $match_info[$team][$playername_on]['LastOnSecond'] = 0;
        $match_info[$team][$playername_on]['Status'] = 1;
      }
      if (!array_key_exists($playername_off, $match_info[$team])) {
        $match_info[$team][$playername_off] = array();
        $match_info[$team][$playername_off]['TotalSeconds'] = 0;
        $match_info[$team][$playername_off]['LastOnQuarter'] = 1;
        $match_info[$team][$playername_off]['LastOnMinute'] = 10;
        $match_info[$team][$playername_off]['LastOnSecond'] = 0;
        $match_info[$team][$playername_off]['Status'] = 0;
      }

      // Get current minute and second.
      $current_minute = '';
      $current_second = '';
      GetCurrentMinuteSecond($event, $current_minute, $current_second);

      // Record the sub in time for the on player.
      $match_info[$team][$playername_on]['LastOnQuarter'] = $current_quarter;
      $match_info[$team][$playername_on]['LastOnMinute'] = $current_minute;
      $match_info[$team][$playername_on]['LastOnSecond'] = $current_second;
      $match_info[$team][$playername_on]['Status'] = 1;

      // Update the total minutes for the off player.
      $match_info[$team][$playername_off]['TotalSeconds'] += CalculatePlayerLastOnCourtTimeInSeconds(
        $match_info[$team][$playername_off]['LastOnQuarter'],
        $match_info[$team][$playername_off]['LastOnMinute'],
        $match_info[$team][$playername_off]['LastOnSecond'],
        $current_quarter,
        intval($current_minute),
        intval($current_second));
      $match_info[$team][$playername_off]['Status'] = 0;
    } else if (IsScoringEvent($event) and !empty($team)) {
      // Get points to be added.
      $points = 2;
      if (strpos($event, 'Three Point Made') !== false) {
        $points = 3;
      }
      if (strpos($event, 'Free Throw Made') !== false) {
        $points = 1;
      }

      // Update team scores.
      $match_info[$team][$current_quarter] += $points;
    }
  }
  // Update the playing time for the players playing to the end of the game.
  foreach ($match_info[$teamA] as $key => &$player) {
    if ($key == 1 or $key == 2 or $key == 3 or $key ==4 or $key == 5 or $key == 6) {
      continue;
    }
    if ($player['Status'] == 1) {
      $player['TotalSeconds'] += CalculatePlayerLastOnCourtTimeInSeconds(
        $player['LastOnQuarter'],
        $player['LastOnMinute'],
        $player['LastOnSecond'],
        $current_quarter,
        0,
        0);
    }
  }
  foreach ($match_info[$teamB] as $key => &$player) {
    if ($key == 1 or $key == 2 or $key == 3 or $key ==4 or $key == 5 or $key == 6) {
      continue;
    }
    if ($player['Status'] == 1) {
      $player['TotalSeconds'] += CalculatePlayerLastOnCourtTimeInSeconds(
        $player['LastOnQuarter'],
        $player['LastOnMinute'],
        $player['LastOnSecond'],
        $current_quarter,
        0,
        0);
    }
  }
  foreach ($match_info[''] as $key => &$player) {
    if ($key == 1 or $key == 2 or $key == 3 or $key ==4 or $key == 5 or $key == 6) {
      continue;
    }
    if ($player['Status'] == 1) {
      $player['TotalSeconds'] += CalculatePlayerLastOnCourtTimeInSeconds(
        $player['LastOnQuarter'],
        $player['LastOnMinute'],
        $player['LastOnSecond'],
        $current_quarter,
        0,
        0);
    }
  }
  return $match_info;
}

function UpdatePlayerMinute(&$groupedResult, $match_info) {
  foreach ($groupedResult as &$player) {
    $team = $groupedResult[0]['TeamName'];
    if (array_key_exists($player['PlayerName'], $match_info[$team])) {
      $seconds = $match_info[$team][$player['PlayerName']]['TotalSeconds'];
      $player['Minute'] = strval(floor($seconds / 60))."'".strval($seconds % 60)."\"";
    } else if (array_key_exists($player['PlayerName'], $match_info[''])) {
      $seconds = $match_info[''][$player['PlayerName']]['TotalSeconds'];
      $player['Minute'] = strval(floor($seconds / 60))."'".strval($seconds % 60)."\"";
    }
    if (!array_key_exists('Minute', $player)) {
      $player['Minute'] = "40'0\"";
    }
  }
}

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
      //  echo($matchid);
        $pagesize = input('pagesize');
        $list = Db::name('bb_teamplayerstatistics_view')
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
            $seasons = Db::name('bb_competitionseason_view')->where('CompetitionID', $result[0]['CompetitionID'])
                ->order($exp)->select();
            $seasons = array_reverse($seasons);
            $otherseasons = array_slice($seasons,1);
            $this->view->assign('thisseason',$seasons[0]);
            $this->view->assign('otherseasons',$otherseasons);

            $groupedResult = array_group_by($result,'TeamID');
            $groupedResultA = current($groupedResult);
            $groupedResultB = end($groupedResult);
            //echo($groupedResultA[0]['TeamName']);
            //echo($groupedResultB[0]['TeamName']);
            UpdateTotalStats($groupedResultA);
            UpdateTotalStats($groupedResultB);

            // Read play by play from logs.
            $logs = Db::name('bb_log')->where('MatchID', $matchid)->select();

            // Process logs to get player minutes, player +-, and quarter scores.
            $match_info = ProcessLogs($logs, $groupedResultA[0]['TeamName'], $groupedResultB[0]['TeamName']);
            UpdatePlayerMinute($groupedResultA, $match_info);
            UpdatePlayerMinute($groupedResultB, $match_info);

            $this->view->assign('statsa',$groupedResultA);
            $this->view->assign('statsb',$groupedResultB);
            $this->view->assign('TeamAShortName',$groupedResultA[0]['TeamShortName']);
            $this->view->assign('TeamBShortName',$groupedResultB[0]['TeamShortName']);
            $this->view->assign('MatchInfo', $match_info);

            return $this->view->fetch('bbstatistics/read');
        }


notfound:
        header("HTTP/1.0 404 Not Found");
        die;

    }

    public function readViewBySeasonPlayer($seasonid){
        $ofield = input('ofield')?input('ofield'):'PlayerID';
        $desc = input('desc')>0?1:0;
        $playoff = input('playoff')?input('playoff'):0;
        $orderby = $desc>0?$ofield . ' desc':$ofield . ' asc';
        $pagesize = input('pagesize');

        
        if ($playoff == 0) {
          $list = Db::name('bb_seasonplayerstatistics_view')
          ->where('seasonid', $seasonid) ;
        } else {
          $list = Db::name('bb_seasonplayerstatisticsplayoff_view')
          ->where('seasonid', $seasonid) ;
        }

        
        
        $list = $list
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
            $seasons = Db::name('bb_competitionseason_view')->where('CompetitionID', $result[0]['CompetitionID'])
                ->order($exp)->select();
            $seasons = array_reverse($seasons);
            $otherseasons = array_slice($seasons,1);
            $this->view->assign('thisseason',$seasons[0]);
            $this->view->assign('otherseasons',$otherseasons);

            $this->view->assign('ofield',$ofield);
            $newdesc = (int)(!$desc);
            $this->view->assign('newdesc',$newdesc);
            $this->view->assign('statsresult',$result);
            $this->view->assign('competitionid', $result[0]['CompetitionID']);


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
        $list = Db::name('bb_teamplayerstatistics_view')->paginate($pagesize);
        $this->paginatedResult(
            $list->total(),
            $list->listRows(),
            $list->currentPage(),
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

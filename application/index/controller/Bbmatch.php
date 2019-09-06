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
    const FIELD = 'TeamAID,TeamBID,ScoreTeamA,ScoreTeamB,State,StartTime,Report,VideoSrc,SeasonID,Court';

    private $stats = array();

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

    // Format play by play event to be displayed.
    private function FormatEvent($player_id, $event, &$duplicate) {
      $result = '';

      // Don't show Official Timeout event.
      if (strpos($event, 'Official') !== false) {
        // Don't show Official timeout event.
        return '';
      }

      // Don't show xxx start/end twice.
      $prepend_newline = false;
      $append_newline = false;
      if (strpos($event, 'start') !== false or strpos($event, 'end') !== false) {
        if (strpos($event, 'start') !== false) {
          $prepend_newline = true;
        } else {
          $append_newline = true;
        }
        if ($duplicate == false) {
          $result = $event;
          $duplicate = !$duplicate;
        } else {
          // Don't show xxx start/end twice.
          $duplicate = !$duplicate;
          return '';
        }
      }

      // Aggregate stats.
      $stat_type = '';
      $stat_incremental = 1;
      $bold = false;
      if (strpos($event, 'Free Throw Made') !== false) {
        $stat_type = 'point';
        $bold = true;
      }

      if (strpos($event, 'Two Point Made') !== false) {
        $stat_type = 'point';
        $stat_incremental = 2;
        $bold = true;
      }

      if (strpos($event, 'Three Point Made') !== false) {
        $stat_type = 'point';
        $stat_incremental = 3;
        $bold = true;
      }

      if (strpos($event, 'Rebound') !== false) {
        $stat_type = 'rebound';
      }

      if (strpos($event, 'Assist') !== false) {
        $stat_type = 'assist';
      }

      if (strpos($event, 'Steal') !== false) {
        $stat_type = 'steal';
      }

      if (strpos($event, 'Block') !== false) {
        $stat_type = 'block';
      }

      if (strpos($event, 'Turnover') !== false) {
        $stat_type = 'turnover';
      }

      if (strpos($event, ' Personal Foul') !== false) {
        $stat_type = 'foul';
      }

      if (!empty($stat_type)) {
        // Append current stats to the end.
        if (array_key_exists($player_id, $this->stats[$stat_type]) == true) {
          $this->stats[$stat_type][$player_id] += $stat_incremental;
        } else {
          $this->stats[$stat_type][$player_id] = $stat_incremental;
        }
        $result = $event.' ('.$this->stats[$stat_type][$player_id].' '.$stat_type.'s)';
      } else {
        $result = $event;
      }

      // Apply special formats.
      if ($bold == true) {
        $result = '<b>'.$result.'</b>';
      }
      if ($prepend_newline == true) {
        $result = '<br>'.$result;
      }
      if ($append_newline == true) {
        $result = $result.'<br>';
      }

      // Add a newline.
      $result .= '<br>';

      return $result;
    }

    public function read($id){

        $result = Db::name('bb_matchteam')->where('MatchID', $id)->find();
        $logs = Db::name('bb_log')->where('MatchID', $id)->select();

        // Construct play by play from logs.
        $report = "";
        $duplicate = false;
        // Initialize stats.
        $this->stats['point'] = array();
        $this->stats['rebound'] = array();
        $this->stats['assist'] = array();
        $this->stats['steal'] = array();
        $this->stats['block'] = array();
        $this->stats['turnover'] = array();
        $this->stats['foul'] = array();

        foreach($logs as $log) {
          $report .= $this->FormatEvent($log['PlayerID'], $log['Event'], $duplicate);
        }
        $result['Report'] = $report;

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

    // Handler for App to read the matches info.
    public function lists($seasonid=null){
        $list = Db::name('bb_matchteam');
        if($seasonid) $list = $list->where('seasonid',$seasonid);
        $list = $list->order('StartTime','asc');
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

<?php
namespace app\index\controller;

use DateTime;
use think\Db;

class Bbnews extends Base {
    public function add_schedule_news($data, $seasonid) {
        $season = Db::name('bb_season')->where('ID', $seasonid)->find();
        $season_name = $season["Name"];
        $startTime = $data['StartTime'];
        $gameDate = date("Y-m-d", strtotime($startTime));
        $roundInfo = ($data['Round'] == 0? "常规赛" : "季后赛第{$data['Round']}轮");
        $bb_news = array();
        $bb_news['CreateDate'] = date("Y-m-d H:i:s");
        $bb_news['SeasonID'] = $seasonid;
        $bb_news['Category'] = "bb_schedule";
        $bb_news['Title'] = "New Schedules Released";
        $bb_news["Content"] = "{$season_name}[{$roundInfo}]: schedules of the game date {$gameDate} have been released.";
        $is_existed = Db::name('news')->where('Content', $bb_news["Content"])->find();
        if (!$is_existed) {
          Db::name('news')->insert($bb_news);
        }
    }

    public function add_game_result_news($data) {
        $season = Db::name('bb_season')->where('ID', $data["SeasonID"])->find();
        $season_name = $season["Name"];
        $teamA = Db::name('bb_team')->where("ID", $data["TeamAID"])->find();
        $teamA_name = $teamA["Name"];
        $teamB = Db::name('bb_team')->where("ID", $data["TeamBID"])->find();
        $teamB_name = $teamB["Name"];
        $startTime = $data['StartTime'];
        $game_date = date("Y-m-d", strtotime($startTime));
        $game_time = date('H:i:s', strtotime($startTime));
        $roundInfo = ($data['Round'] == 0? "常规赛" : "季后赛第{$data['Round']}轮");
        $bb_news = array();
        $bb_news['CreateDate'] = date("Y-m-d");
        $bb_news['SeasonID'] = $data["SeasonID"];
        $bb_news['Category'] = "bb_result";
        $bb_news['Title'] = "Game Result";
        $bb_news['Content'] = "{$season_name}[{$roundInfo}]: in the game on {$game_date} at {$game_time}, team " .($data['ScoreTeamA'] > $data['ScoreTeamB']? $teamA_name : $teamB_name) 
        . " beat team " .($data['ScoreTeamA'] > $data['ScoreTeamB']? $teamB_name : $teamA_name) ." with a score of " .($data['ScoreTeamA'] > $data['ScoreTeamB']? $data['ScoreTeamA'] : $data['ScoreTeamB'])
      .":" .($data['ScoreTeamA'] > $data['ScoreTeamB']? $data['ScoreTeamB'] : $data['ScoreTeamA']) .".";
        $bb_news['Image'] = $data['ScoreTeamA'] > $data['ScoreTeamB']? $teamA["LogoSrc"] : $teamB["LogoSrc"];
        $res = Db::name('news')->insert($bb_news); 
    }
}
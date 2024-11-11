<<<<<<< HEAD
<?php

namespace app\index\controller;

use DateTime;
use think\Db;

/*
----- bb_news DB schema -----
DROP TABLE IF EXISTS `bb_news`;
CREATE TABLE `bb_news` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `SeasonID` int(11) NOT NULL,
  `MatchID` int(11) DEFAULT NULL,
  `PlayerID` int(11) DEFAULT NULL,
  `CreateDate` datetime NOT NULL,
  `Image` varchar(1023) DEFAULT NULL,
  `Category` varchar(1023) NOT NULL,
  `Title` varchar(1023) NOT NULL,
  `Content` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
*/

class Bbnews extends Base
{
    public function add_schedule_news($data, $seasonid)
    {
        $season = Db::name('bb_season')->where('ID', $seasonid)->find();
        $season_name = $season["Name"];
        $startTime = $data['StartTime'];
        $gameDate = date("Y-m-d", strtotime($startTime));
        $roundInfo = ($data['Round'] == 0 ? "常规赛" : "季后赛第{$data['Round']}轮");
        $bb_news = array();
        $bb_news['CreateDate'] = date("Y-m-d H:i:s");
        $bb_news['SeasonID'] = $seasonid;
        $bb_news['Category'] = "bb_schedule";
        $bb_news['Title'] = "New Schedules Released";
        $bb_news["Content"] = "{$season_name}[{$roundInfo}]: schedules of the game date {$gameDate} have been released.";
        $is_existed = Db::name('bb_news')->where('Content', $bb_news["Content"])->find();
        if (!$is_existed) {
            Db::name('bb_news')->insert($bb_news);
        }
    }

    public function handle_game_result_news($data, $id)
    {
        $season = Db::name('bb_season')->where('ID', $data["SeasonID"])->find();
        $season_name = $season["Name"];
        $teamA = Db::name('bb_team')->where("ID", $data["TeamAID"])->find();
        $teamA_name = $teamA["Name"];
        $teamB = Db::name('bb_team')->where("ID", $data["TeamBID"])->find();
        $teamB_name = $teamB["Name"];
        $startTime = $data['StartTime'];
        $game_date = date("Y-m-d", strtotime($startTime));
        $game_time = date('H:i:s', strtotime($startTime));
        $roundInfo = ($data['Round'] == 0 ? "常规赛" : "季后赛第{$data['Round']}轮");

        // 在bb_news table 中查询 MatchID 为当前id的记录是否存在。不存在则创建新的game result news，存在则更新该news的内容
        $target_news = Db::name('bb_news')->where('MatchID', $id)->find();
        if (!$target_news) {
            $bb_news = array();
            $bb_news['MatchID'] = $id;
            $bb_news['CreateDate'] = date("Y-m-d");
            $bb_news['SeasonID'] = $data["SeasonID"];
            $bb_news['Category'] = "bb_result";
            $bb_news['Title'] = "Game Result";
            $bb_news['Content'] = "{$season_name}[{$roundInfo}]: in the game on {$game_date} at {$game_time}, team " . ($data['ScoreTeamA'] > $data['ScoreTeamB'] ? $teamA_name : $teamB_name)
                . " beat team " . ($data['ScoreTeamA'] > $data['ScoreTeamB'] ? $teamB_name : $teamA_name) . " with a score of " . ($data['ScoreTeamA'] > $data['ScoreTeamB'] ? $data['ScoreTeamA'] : $data['ScoreTeamB'])
                . ":" . ($data['ScoreTeamA'] > $data['ScoreTeamB'] ? $data['ScoreTeamB'] : $data['ScoreTeamA']) . ".";
            $bb_news['Image'] = $data['ScoreTeamA'] > $data['ScoreTeamB'] ? $teamA["LogoSrc"] : $teamB["LogoSrc"];
            $res = Db::name('bb_news')->insert($bb_news);
        } else {
            $image = $data['ScoreTeamA'] > $data['ScoreTeamB'] ? $teamA["LogoSrc"] : $teamB["LogoSrc"];
            $new_content = "{$season_name}[{$roundInfo}]: in the game on {$game_date} at {$game_time}, team " . ($data['ScoreTeamA'] > $data['ScoreTeamB'] ? $teamA_name : $teamB_name)
                . " beat team " . ($data['ScoreTeamA'] > $data['ScoreTeamB'] ? $teamB_name : $teamA_name) . " with a score of " . ($data['ScoreTeamA'] > $data['ScoreTeamB'] ? $data['ScoreTeamA'] : $data['ScoreTeamB'])
                . ":" . ($data['ScoreTeamA'] > $data['ScoreTeamB'] ? $data['ScoreTeamB'] : $data['ScoreTeamA']) . ".";
            $res = Db::name('bb_news')->where('MatchID', $id)->update(['Content' => $new_content, 'Image' => $image]);
        }
    }
}
=======
<?php

namespace app\index\controller;

use DateTime;
use think\Db;

/*
----- bb_news DB schema -----
DROP TABLE IF EXISTS `bb_news`;
CREATE TABLE `bb_news` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `SeasonID` int(11) NOT NULL,
  `MatchID` int(11) DEFAULT NULL,
  `PlayerID` int(11) DEFAULT NULL,
  `CreateDate` datetime NOT NULL,
  `Image` varchar(1023) DEFAULT NULL,
  `Category` varchar(1023) NOT NULL,
  `Title` varchar(1023) NOT NULL,
  `Content` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
*/

class Bbnews extends Base
{
    public function add_schedule_news($data, $seasonid)
    {
        $season = Db::name('bb_season')->where('ID', $seasonid)->find();
        $season_name = $season["Name"];
        $startTime = $data['StartTime'];
        $gameDate = date("Y-m-d", strtotime($startTime));
        $roundInfo = ($data['Round'] == 0 ? "常规赛" : "季后赛第{$data['Round']}轮");
        $bb_news = array();
        $bb_news['CreateDate'] = date("Y-m-d H:i:s");
        $bb_news['SeasonID'] = $seasonid;
        $bb_news['Category'] = "bb_schedule";
        $bb_news['Title'] = "New Schedules Released";
        $bb_news["Content"] = "{$season_name}[{$roundInfo}]: schedules of the game date {$gameDate} have been released.";
        $is_existed = Db::name('bb_news')->where('Content', $bb_news["Content"])->find();
        if (!$is_existed) {
            Db::name('bb_news')->insert($bb_news);
        }
    }

    public function handle_game_result_news($data, $id)
    {
        $season = Db::name('bb_season')->where('ID', $data["SeasonID"])->find();
        $season_name = $season["Name"];
        $teamA = Db::name('bb_team')->where("ID", $data["TeamAID"])->find();
        $teamA_name = $teamA["Name"];
        $teamB = Db::name('bb_team')->where("ID", $data["TeamBID"])->find();
        $teamB_name = $teamB["Name"];
        $startTime = $data['StartTime'];
        $game_date = date("Y-m-d", strtotime($startTime));
        $game_time = date('H:i:s', strtotime($startTime));
        $roundInfo = ($data['Round'] == 0 ? "常规赛" : "季后赛第{$data['Round']}轮");

        // 在bb_news table 中查询 MatchID 为当前id的记录是否存在。不存在则创建新的game result news，存在则更新该news的内容
        $target_news = Db::name('bb_news')->where('MatchID', $id)->find();
        if (!$target_news) {
            $bb_news = array();
            $bb_news['MatchID'] = $id;
            $bb_news['CreateDate'] = date("Y-m-d");
            $bb_news['SeasonID'] = $data["SeasonID"];
            $bb_news['Category'] = "bb_result";
            $bb_news['Title'] = "Game Result";
            $bb_news['Content'] = "{$season_name}[{$roundInfo}]: in the game on {$game_date} at {$game_time}, team " . ($data['ScoreTeamA'] > $data['ScoreTeamB'] ? $teamA_name : $teamB_name)
                . " beat team " . ($data['ScoreTeamA'] > $data['ScoreTeamB'] ? $teamB_name : $teamA_name) . " with a score of " . ($data['ScoreTeamA'] > $data['ScoreTeamB'] ? $data['ScoreTeamA'] : $data['ScoreTeamB'])
                . ":" . ($data['ScoreTeamA'] > $data['ScoreTeamB'] ? $data['ScoreTeamB'] : $data['ScoreTeamA']) . ".";
            $bb_news['Image'] = $data['ScoreTeamA'] > $data['ScoreTeamB'] ? $teamA["LogoSrc"] : $teamB["LogoSrc"];
            $res = Db::name('bb_news')->insert($bb_news);
        } else {
            $image = $data['ScoreTeamA'] > $data['ScoreTeamB'] ? $teamA["LogoSrc"] : $teamB["LogoSrc"];
            $new_content = "{$season_name}[{$roundInfo}]: in the game on {$game_date} at {$game_time}, team " . ($data['ScoreTeamA'] > $data['ScoreTeamB'] ? $teamA_name : $teamB_name)
                . " beat team " . ($data['ScoreTeamA'] > $data['ScoreTeamB'] ? $teamB_name : $teamA_name) . " with a score of " . ($data['ScoreTeamA'] > $data['ScoreTeamB'] ? $data['ScoreTeamA'] : $data['ScoreTeamB'])
                . ":" . ($data['ScoreTeamA'] > $data['ScoreTeamB'] ? $data['ScoreTeamB'] : $data['ScoreTeamA']) . ".";
            $res = Db::name('bb_news')->where('MatchID', $id)->update(['Content' => $new_content, 'Image' => $image]);
        }
    }
}
>>>>>>> 37313bc (Initial commit)

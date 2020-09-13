<?php
/**
 * Created by PhpStorm.
 * User: Aven
 * Date: 2018/10/28
 * Time: 1:30
 */

namespace app\index\controller;

require_once(__DIR__ . '/../../../thinkphp/library/think/db/Expression.php');

use think\Db;
use think\Session;

// Used for team ranking.
class TeamRankInfo {
    public $ID;
    public $Name;
    public $Wins = 0;
    public $Loses = 0;
    public $Forfeits = 0;
    public $Pt_win = 0;
    public $Pt_lose = 0;

    public function __construct($id, $name) {
      $this->ID = $id;
      $this->Name = $name;
    }

    public function addWins() {
      ++$this->Wins;
    }

    public function addLoses() {
      ++$this->Loses;
    }

    public function addForfeits() {
      ++$this->Forfeits;
    }

    public function addPointWins($points) {
      $this->Pt_win += $points;
    }

    public function addPointLoses($points) {
      $this->Pt_lose += $points;
    }

    public function getPointDiff() {
      return $this->Pt_win - $this->Pt_lose;
    }

    public function getRankPoint() {
      return $this->Wins * 2 + $this->Loses;
    }
}

// Return whether all the same rank point teams have met with each other.
function AllMet($same_rank_point_teams) {
  // TODO(Yue): Implement this method.
  return false;
}

// Rank same rank point teams by diffs and points get.
function SimpleRank($same_rank_point_teams) {
  // Divide teams by diff.
  $diff_standing = array();
  foreach ($same_rank_point_teams as $current_team) {
    $found_same_diff_team = false;
    foreach ($diff_standing as $diff => $same_diff_teams) {
      if ($current_team->getPointDiff() == $diff) {
        array_push($diff_standing[$diff], $current_team);
        $found_same_diff_team = true;
        break;
      }
    }
    if ($found_same_diff_team == false) {
      $diff_standing[$current_team->getPointDiff()] = array($current_team);
    }
  }

  // Rank same diff teams.
  krsort($diff_standing);

  // Divide same diff teams by points. Update final ranking results inline.
  $result = array();
  foreach ($diff_standing as $diff => $same_diff_teams) {
    $point_standing = array();
    foreach ($same_diff_teams as $current_team) {
      $found_same_point_team = false;
      foreach ($point_standing as $point => $same_point_teams) {
        if ($current_team->Pt_win == $point) {
          array_push($point_standing[$point], $current_team);
          $found_same_point_team = true;
          break;
        }
      }
      if ($found_same_point_team == false) {
        $point_standing[$current_team->Pt_win] = array($current_team);
      }
    }
    krsort($point_standing);
    foreach ($point_standing as $point => $same_point_teams) {
      foreach ($same_point_teams as $team_to_be_merged) {
        array_push($result, $team_to_be_merged);
      }
    }
  }
  return $result;
}

// Rank same rank point teams according to results among each other.
function ComplexRank($same_rank_point_teams, $matches) {
  // TODO(Yue): Implement this method.
}

class Bbteam extends Base
{
    const APPLY_TEAM_FIELD = 'Name,ShortName,Captain,Email,Tel,Wechat,LogoSrc,PhotoSrc,Description,TimePreference,PlayerIDs,PlayerNumbers';
    const UPDATE_TEAM_FIELD = 'Name,ShortName,Captain,Email,Tel,Wechat,LogoSrc,PhotoSrc,Description,TimePreference,PlayoffID';
    const ADD_TEAM_FIELD = 'Name,ShortName,Captain,Email,Tel,Wechat,LogoSrc,PhotoSrc,Description,TimePreference,Approval,SeasonID';
    public function add($seasonid = null)
    {
        $this->checkauthorization();

        $data = request()->only(self::ADD_TEAM_FIELD, 'post');
        $this->makeNull($data);
        if (!isset($data["SeasonID"]))
            $data["SeasonID"] = $seasonid;
        $validator = validate('Bb_team');
        $result = $validator->check($data);
        if (!$result) {
            $this->affectedRowsResult(0);
        }
        // Update bb_team.
        $team_data = array();
        $team_data['Name'] = $data['Name'];
        $team_data['ShortName'] = $data['ShortName'];
        $team_data['Captain'] = $data['Captain'];
        $team_data['Email'] = $data['Email'];
        $team_data['Tel'] = $data['Tel'];
        $team_data['Wechat'] = $data['Wechat'];
        $team_data['LogoSrc'] = $data['LogoSrc'];
        $team_data['PhotoSrc'] = $data['PhotoSrc'];
        $team_data['Description'] = $data['Description'];
        $result = Db::name('bb_team')->insert($team_data);
        // Update bb_seasonteam.
        $seasonteam_data = array();
        $seasonteam_data['SeasonID'] = $data['SeasonID'];
        // Get the team ID just inserted.
        $sql =
            'select ID '.
            'from bb_team '.
            'where Name="'.$data['Name'].'" '.
            'order by ID desc '.
            'limit 1';
        $seasonteam_data['TeamID'] = Db::query($sql)[0]["ID"];
        $seasonteam_data['Approval'] = $data['Approval'];
        $seasonteam_data['TimePreference'] = $data['TimePreference'];
        $result += Db::name('bb_seasonteam')->insert($seasonteam_data);
        $this->affectedRowsResult($result);
    }

    public function getapply($seasonid)
    {
        $this->headerAndFooter('competition');

        $competitionseason = Db::name('bb_competitionseason_view')
            ->where('SeasonID', $seasonid)->find();
        if (!$competitionseason) goto notfound;

        $sql =
            'select * '.
            'from bb_player '.
            'where bb_player.ID not in ('.
                'select distinct bb_seasonteamplayer.PlayerID '.
                'from bb_seasonteamplayer '.
                'where bb_seasonteamplayer.SeasonID='.strval($seasonid).')'.
            'order by Name asc';
        $available_players = Db::query($sql);

        $this->view->assign('available_players', $available_players);
        $this->view->assign('competitionseason', $competitionseason);

        return $this->view->fetch('bbteam/apply');

        notfound:
        header("HTTP/1.0 404 Not Found");
        die;
    }

    public function apply($seasonid)
    {
        $data = request()->only(self::APPLY_TEAM_FIELD, 'post');
        $this->makeNull($data);

        // Updated bb_team.
        $team = array();
        $team["Name"] = $data["Name"];
        $team["ShortName"] = $data["ShortName"];
        $team["Captain"] = $data["Captain"];
        $team["Email"] = $data["Email"];
        $team["Wechat"] = $data["Wechat"];
        $team["Tel"] = $data["Tel"];
        $team["Description"] = $data["Description"];

        $validator = validate('Bb_team');
        $team_result = $validator->check($team);
        if (!$team_result) {
          $this->affectedRowsResult(0);
        }

        $team["LogoSrc"] = "";
        $team["PhotoSrc"] = "";
        $assetUrl = getAssetUploadUrl();
        $infologofile = request()->file('Logo');
        $infophotofile = request()->file('Photo');

        if($infologofile)
            $team["LogoSrc"] = $infologofile->move(__DIR__ . $assetUrl)
                ->getSaveName();

        if($infophotofile)
            $team["PhotoSrc"] = $infophotofile->move(__DIR__ . $assetUrl)
                ->getSaveName();

        $team_result = Db::name('bb_team')->insert($team);

        // Update bb_seasonteam.
        // Get the TeamID just inserted.
        $seasonteam = array();
        $sql =
            'select ID '.
            'from bb_team '.
            'where Name="'.$team["Name"].'" '.
            'order by ID desc '.
            'limit 1';
        $teamid = Db::query($sql)[0]["ID"];
        $seasonteam["TeamID"] =  $teamid;
        $seasonteam["SeasonID"] = $seasonid;
        $seasonteam["Approval"] = 0;
        $seasonteam["TimePreference"] = $data["TimePref"];

        $seasonteam_result = Db::name('bb_seasonteam')->insert($seasonteam);

        // Update bb_seasonteamplayer
        $players = explode(",", $data["PlayerIDs"]);
        $numbers = explode(",", $data["PlayerNumbers"]);
        $seasonteamplayer_result = 0;
        $seasonteamplayer = array();
        for ($i = 0; $i < count($players); $i++) {
          $seasonteamplayer["SeasonID"] = $seasonteam["SeasonID"];
          $seasonteamplayer["TeamID"] = $seasonteam["TeamID"];
          $seasonteamplayer["PlayerID"] = $players[$i];
          $seasonteamplayer["PlayerNumber"] = $numbers[$i];
          $seasonteamplayer_result += Db::name('bb_seasonteamplayer')->insert($seasonteamplayer);
        }
        if ($this->jsonRequest())
            $this->affectedRowsResult($team_result + $seasonteam_result + $seasonteamplayer_result);

        // Jump to the apply result page.
        $this->headerAndFooter('competition');

        $applyresult = '';
        if ($team_result > 0 &&
            $seasonteam_result > 0 &&
            $seasonteamplayer_result > 0)
          $applyresult = '您的球队申请已提交，审核后将会给您发邮箱或者短信通知，请关注！';
        $this->view->assign('applyresult', $applyresult);
        return $this->view->fetch('bbteam/applyres');
    }

    public function delete($id)
    {
        $this->checkauthorization();
        // Delete from bb_seasonteamplayer.
        $result = Db::name('bb_seasonteamplayer')->where('TeamID', $id)->delete();
        // Delete from bb_seasonteam.
        $result += Db::name('bb_seasonteam')->where('TeamID', $id)->delete();
        // Delete from bb_team.
        $result += Db::name('bb_team')->where('ID', $id)->delete();
        $this->affectedRowsResult($result);
    }

    public function read($id)
    {
        $result = Db::name('bb_seasonteam')->where('TeamID', $id)->find();
        if ($this->jsonRequest()) {

            $this->dataResult($result);
        }

        $seasonid = $result['SeasonID'];

        $thisseason = Db::name('bb_competitionseason_view')
            ->where('SeasonID', $seasonid)
            ->find();
        $competitionid = $thisseason['CompetitionID'];
        if (!$competitionid) goto  notfound;


        if ($competitionid <= 2)
            $this->headerAndFooter('competition' . $competitionid);
        else
            $this->headerAndFooter('competition');


        $team = Db::name('bb_team')->where('ID', $id)->find();

        $playercount = Db::name('bb_seasonteamplayer')
            ->where('SeasonID', $seasonid)
            ->where('TeamID', $id)
            ->count();
        $this->view->assign('playercount', $playercount);

        $this->view->assign('thisseason', $thisseason);
        $this->view->assign('team', $team);
        $team_info = Db::name('bb_seasonteam')
            ->where('SeasonID', $seasonid)
            ->where('TeamID', $id)
            ->where('Approval', 1)
            ->find();
        $this->view->assign('time_preference', $team_info['TimePreference']);

        // Get team matches.
        $matches = Db::name('bb_matchteam_view')
            ->where('SeasonID', $seasonid)
            ->where('TeamAID|TeamBID', $id)
            ->order('StartTime','desc')
            ->select();
        $this->view->assign('matches',$matches);

        return $this->view->fetch('bbteam/read');

        notfound:
        header("HTTP/1.0 404 Not Found");
        die;
    }

    public function lists($seasonid = null)
    {
        if ($this->jsonRequest()) {
            $seasonteams = Db::name('bb_seasonteam');
            if ($seasonid) $seasonteams = $seasonteams->where('seasonid', $seasonid);
            $seasonteams = $seasonteams->select();
            $list = array();
            foreach ($seasonteams as $seasonteam) {
              $item = array();
              // fill out team registration info.
              $teamid = $seasonteam['TeamID'];
              $item['ID'] = $teamid;
              $seasonid = Db::name('bb_seasonteam')->where('teamid', $teamid)->find()['SeasonID'];
              $seasonname = Db::name('bb_season')->where('id', $seasonid)->find()['Name'];
              $item['SeasonID'] = $seasonid;
              $item['SeasonName'] = $seasonname;
              $item['TimePreference'] = $seasonteam['TimePreference'];
              $item['Approval'] = $seasonteam['Approval'];
              // fill out team info.
              $team = Db::name('bb_team')->where('id', $teamid)->find();
              $item['Name'] = $team['Name'];
              $item['ShortName'] = $team['ShortName'];
              $item['Captain'] = $team['Captain'];
              $item['Email'] = $team['Email'];
              $item['Tel'] = $team['Tel'];
              $item['Wechat'] = $team['Wechat'];
              $item['LogoSrc'] = $team['LogoSrc'];
              $item['PhotoSrc'] = $team['PhotoSrc'];
              $item['Description'] = $team['Description'];
              // fill out player info.
              $players = Db::name('bb_seasonteamplayer')
                  ->where('seasonid', $seasonid)
                  ->where('teamid', $teamid)->select();
              $playerIDs_arr = array();
              $playerNumbers_arr = array();
              foreach ($players as $player) {
                array_push($playerIDs_arr, strval($player['PlayerID']));
                array_push($playerNumbers_arr, strval($player['PlayerNumber']));
              }
              $item['PlayerIDs'] = implode(',', $playerIDs_arr);
              $item['PlayerNumbers'] = implode(',', $playerNumbers_arr);
              // Add item to list.
              array_push($list, $item);
            }
            // Display to admin page.

            /*$list = $list->paginate(input('pagesize'));
            $this->paginatedResult(
                $list->total(),
                $list->listRows(),
                $list->currentPage(),
          );*/
          $this->dataResult($list);
        } else if ($seasonid) {
            $competitionid = Db::name('bb_competitionseason_view')
                ->where('SeasonID', $seasonid)
                ->find()['CompetitionID'];
            if (!$competitionid) goto  notfound;


            if ($competitionid <= 2)
                $this->headerAndFooter('competition' . $competitionid);
            else
                $this->headerAndFooter('competition');

            $exp = new \think\Db\Expression('field(SeasonID,' . $seasonid . '),StartTime DESC');
            $seasons = Db::name('bb_competitionseason_view')->where('CompetitionID', $competitionid)
                ->order($exp)->select();
            $seasons = array_reverse($seasons);
            $otherseasons = array_slice($seasons, 1);
            $teams = Db::name('bb_seasonteam')
                ->where('SeasonID', $seasonid)
                ->where('Approval', '<>', 0)
                ->select('TeamID,TimePreference');
            $bbteams = array();
            foreach ($teams as $i => $team) {
              $item = Db::name('bb_team')->where('ID', $team['TeamID'])->find();
              $item['TimePreference'] = $team['TimePreference'];
              array_push($bbteams, $item);
            }


            $this->view->assign('thisseason', $seasons[0]);
            $this->view->assign('otherseasons', $otherseasons);
            $this->view->assign('bbteams', $bbteams);

            return $this->view->fetch('bbteam/lists');


        }


        notfound:
        header("HTTP/1.0 404 Not Found");
        die;
    }

    public function rank($seasonid)
    {
        $competitionid = Db::name('bb_competitionseason_view')
            ->where('SeasonID', $seasonid)
            ->find()['CompetitionID'];
        if (!$competitionid) goto  notfound;


        if ($competitionid <= 2)
            $this->headerAndFooter('competition' . $competitionid);
        else
            $this->headerAndFooter('competition');

        $exp = new \think\Db\Expression('field(SeasonID,' . $seasonid . '),StartTime DESC');
        $seasons = Db::name('bb_competitionseason_view')->where('CompetitionID', $competitionid)
            ->order($exp)->select();
        $seasons = array_reverse($seasons);
        $otherseasons = array_slice($seasons, 1);
        $this->view->assign('otherseasons', $otherseasons);

        $database_teams = Db::name('bb_team')
            ->where('SeasonID', $seasonid)
            ->select();

        // An array of TeamRankInfo
        $teams = array();
        foreach ($database_teams as $team) {
          $team_info = new TeamRankInfo($team['ID'], $team['Name']);
          array_push($teams, $team_info);
        };

        // Update team rank info from matches.
        $matches = Db::name('bb_match')
            ->where('SeasonID', $seasonid)
            ->where('State', 1)
            ->select();
        foreach ($matches as $match) {
          foreach ($teams as $team) {
            if ($team->ID == $match['TeamAID']) {
              # Determine forfeit
              if ($match['ScoreTeamA'] == 0 && $match['ScoreTeamB'] == 15) {
                $team->AddForfeits();
              } else if ($match['ScoreTeamA'] > $match['ScoreTeamB']) {
                $team->AddWins();
              } else {
                $team->AddLoses();
              }
              $team->AddPointWins($match['ScoreTeamA']);
              $team->AddPointLoses($match['ScoreTeamB']);
            }
            if ($team->ID == $match['TeamBID']) {
              # Determine forfeit
              if ($match['ScoreTeamB'] == 0 && $match['ScoreTeamA'] == 15) {
                $team->AddForfeits();
              } else if ($match['ScoreTeamB'] > $match['ScoreTeamA']) {
                $team->AddWins();
              } else {
                $team->AddLoses();
              }
              $team->AddPointWins($match['ScoreTeamB']);
              $team->AddPointLoses($match['ScoreTeamA']);
            }
          }
        }

        // Divide to groups based on rank points.
        // rank_point => same_rank_point_teams
        $rank_point_standing = array();
        foreach ($teams as $team) {
          $found_same_rank_point_team = false;
          foreach ($rank_point_standing as $rank_point => $same_rank_point_teams) {
            if ($team->getRankPoint() == $rank_point) {
              array_push($rank_point_standing[$rank_point], $team);
              $found_same_rank_point_team = true;
              break;
            }
          }
          if ($found_same_rank_point_team == false) {
            // Form a new group.
            $rank_point_standing[$team->getRankPoint()] = array($team);
          }
        }

        // Rank groups.
        krsort($rank_point_standing);

        // Rank each group.
        $final_ranking = array();
        foreach ($rank_point_standing as $rank_point => $same_rank_point_teams) {
          if (AllMet($same_rank_point_teams) == false) {
            $same_rank_point_teams_ranking = SimpleRank($same_rank_point_teams);
          } else {
            $same_rank_point_teams_ranking = ComplexRank($same_rank_point_teams, $matches);
          }
          foreach ($same_rank_point_teams_ranking as $team_to_be_merged) {
            array_push($final_ranking, $team_to_be_merged);
          }
        }

        // Reformat final ranking.
        $display_teams = array();
        foreach ($final_ranking as $team) {
          $display_team = array();
          $display_team['ID'] = $team->ID;
          $display_team['ShortName'] = $team->Name;
          $display_team['RankPoints'] = $team->getRankPoint();
          $display_team['Wins'] = $team->Wins;
          $display_team['Losses'] = $team->Loses;
          $display_team['Forfeits'] = $team->Forfeits;
          $display_team['PointGet'] = $team->Pt_win;
          $display_team['PointLose'] = $team->Pt_lose;
          $display_team['PointDiff'] = $team->GetPointDiff();
          array_push($display_teams, $display_team);
        }

        $this->view->assign('thisseason', $seasons[0]);
        $this->view->assign('otherseasons', $otherseasons);
        $this->view->assign('teamrankresult', $display_teams);

        return $this->view->fetch('bbteam/rank');

        notfound:
        header("HTTP/1.0 404 Not Found");
        die;
    }

    public function update($id)
    {
        $this->checkauthorization();

        $data = request()->only(self::UPDATE_TEAM_FIELD, 'post');
        $this->makeNull($data);
//        $validator = validate('Bb_team');
//        $result = $validator->check($data);
//        if (!$result){
//            $this->result(0);
//        }
        // Update bb_team.
        $team_data = array();
        $team_data['Name'] = $data['Name'];
        $team_data['ShortName'] = $data['ShortName'];
        $team_data['Captain'] = $data['Captain'];
        $team_data['Email'] = $data['Email'];
        $team_data['Tel'] = $data['Tel'];
        $team_data['Wechat'] = $data['Wechat'];
        $team_data['LogoSrc'] = $data['LogoSrc'];
        $team_data['PhotoSrc'] = $data['PhotoSrc'];
        $team_data['Description'] = $data['Description'];
        $result = Db::name('bb_team')->where('ID', $id)->update($team_data);
        // Update bb_seasonteam.
        $seasonteam_data = array();
        $seasonteam_data['TimePreference'] = $data['TimePref'];
        $seasonteam_data['PlayoffID'] = $data['PlayoffID'];
        $result += Db::name('bb_seasonteam')->where('TeamID', $id)->update($seasonteam_data);
        $this->affectedRowsResult($result);
    }

    public function passApplication()
    {
        $this->checkauthorization();

        $data = request()->only('TeamIDs,Passed', 'post');
        $teamIDs = urldecode($data['TeamIDs']);
        $passed = isset($data['Passed']) ? boolval($data['Passed']) : true;

        $result = 0;
        $teamIDsarr = explode(',', $teamIDs);
        $emailRes = 0;

        if ($passed) {
            foreach ($teamIDsarr as $teamID) {
                //$seasonteam = Db::name('bb_seasonteam')->where('TeamID', $teamID)->find();

                //if($seasonteam['Approval']==1) continue;

                /*$playerIDarr = array_filter(explode(',', $team['PlayerIDs']),'arrfiltrfun');
                $playerNumberarr = array_filter(explode(',', $team['PlayerNumbers']),'arrfiltrfun');

                $playerIDs = '';
                $playerNumbers = '';

                foreach ($playerIDarr as $k => $playerID) {
                    $playerdata = Db::name('bb_player')->where('ID', $playerID)
                        ->find();

                    $playerdata['ID'] = null;
                    $playerdata['TeamID'] = $teamID;
                    $playerdata['Number'] = $playerNumberarr[$k];
                    $playerdata['SeasonID'] = $team['SeasonID'];

                    $result += Db::name('bb_player')->insert($playerdata);

                    $playerIDs = $playerIDs . Db::name('bb_player')->getLastInsID();
                    $playerNumbers = $playerNumbers . $playerNumberarr[$k];

                    if($k != count($playerIDarr)-1) {
                        $playerIDs = $playerIDs . ',';
                        $playerNumbers = $playerNumbers . ',';
                    }

                }*/

                $result += Db::name('bb_seasonteam')->where('TeamID', $teamID)
                    ->update(['Approval' => 1]);

//                $emailret = sendEmail([[
//                    'user_email' => $team['Email'],
//                    'content' => "<h1>SVCSA球队申请成功</h1><p>您好，您的球队" . $team['ShortName'] . "已经通过审核。请访问SVCSA.org查看吧。</p>"]]);

                if (isset($emailret['code']) && $emailret['code'] == 1)
                    $emailRes++;

            }
     }
      /* else {
            foreach ($teamIDsarr as $teamID) {
                $result += Db::name('bb_team')->where('ID', $teamID)
                    ->delete();
            }
        }*/

        $this->jsonResult(0, ['affectedRows' => $result, 'affectedEmails' => $emailRes]);
//        $this->affectedRowsResult($result);
    }


    public function setTeam()
    {
        $this->checkauthorization();

        $data = request()->only('PlayerIDs,TeamID', 'post');
        $playerIDs = urldecode($data['PlayerIDs']);
        $teamID = $data['TeamID'];

        if (!$teamID || $playerIDs == null) return $this->affectedRowsResult(0);


        $playerIDarr = explode(',', $playerIDs);

        $oldplayerIDs = Db::name('bb_team')->where('ID', $teamID)->find()['PlayerIDs'];
        $oldplayerIDarr = explode(',', $oldplayerIDs);

        $result = 0;

        foreach ($oldplayerIDarr as $playerID) {
            $result += Db::name('bb_player')->where('ID', $playerID)
                ->update(['TeamID' => '']);
        }


        if (count($playerIDarr) == 0) $this->affectedRowsResult(0);

        $result = Db::name('bb_team')->where('ID', $teamID)
            ->update(['PlayerIDs' => $playerIDs]);

        foreach ($playerIDarr as $playerID) {
            $result += Db::name('bb_player')->where('ID', $playerID)
                ->update(['TeamID' => $teamID]);
        }

        $this->affectedRowsResult($result);

    }
}

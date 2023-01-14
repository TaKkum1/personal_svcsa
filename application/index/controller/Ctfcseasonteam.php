<?php
/**
 * Created by Yu Lu
 * Date: Jan 1, 2023
 * Time: 13:30
 */

namespace app\index\controller;

require_once(__DIR__ . '/../../../thinkphp/library/think/db/Expression.php');

use think\Db;
use think\Session;


class Ctfcseasonteam extends Base
{
    const APPLY_SEASONTEAM_FIELD = 'SeasonID, TeamID, Approve';
   
    public function add($seasonid = null)
    {
        $this->checkauthorization();

        $data = request()->only(self::APPLY_SEASONTEAM_FIELD, 'post');
        $this->makeNull($data);
        if (!isset($data["SeasonID"]))
            $data["SeasonID"] = $seasonid;
        $validator = validate('Ctfc_season_team');
        $result = $validator->check($data);
        if (!$result) {
            $this->affectedRowsResult(0);
        }

        $seasonteam_data = array();
        $seasonteam_data['SeasonID'] = $data['SeasonID'];
        // Get the team ID just inserted.
        $sql =
            'select ID '.
            'from Ctfc_season_team '.
            'where Name="'.$data['Name'].'" '.
            'order by ID desc '.
            'limit 1';
        $seasonteam_data['TeamID'] = Db::query($sql)[0]["ID"];
        $seasonteam_data['Approval'] = $data['Approval'];
        // $seasonteam_data['TimePreference'] = $data['TimePreference'];
        $result += Db::name('cftc_seasonteam')->insert($seasonteam_data);
        $this->affectedRowsResult($result);
    }






    public function delete($id)
    {
        $this->checkauthorization();
    
        $result += Db::name('ctfc_seasonteam')->where('TeamID', $id)->delete();
        
        $result += Db::name('ctfc_team')->where('ID', $id)->delete();
        $this->affectedRowsResult($result);
    }

    public function read($id)
    {
        $result = Db::name('ctfc_seasonteam')->where('TeamID', $id)->find();
        if ($this->jsonRequest()) {

            $this->dataResult($result);
        }

        $seasonid = $result['SeasonID'];

        $team = Db::name('bb_team')->where('ID', $id)->find();

        $playercount = Db::name('bb_seasonteamplayer')
            ->where('SeasonID', $seasonid)
            ->where('TeamID', $id)
            ->count();
        // $this->view->assign('playercount', $playercount);

        $this->view->assign('thisseason', $thisseason);
        $this->view->assign('team', $team);
        $team_info = Db::name('ctfc_seasonteam')
            ->where('SeasonID', $seasonid)
            ->where('TeamID', $id)
            ->where('Approval', 1)
            ->find();
        
    }

    public function lists($seasonid = null)
    {
        if ($this->jsonRequest()) {
            $seasonteams = Db::name('ctfc_seasonteam')->order('TeamID');
            if ($seasonid) $seasonteams = $seasonteams->where('seasonid', $seasonid);
            $seasonteams = $seasonteams->select();
            $list = array();
            foreach ($seasonteams as $seasonteam) {
              $item = array();
              // fill out team registration info.
              $teamid = $seasonteam['TeamID'];
              $teamname = Db::name('ctfc_team')->where('id', $teamid)->find()['Name'];
              $item['ID'] = $teamid;
              $seasonid = Db::name('ctfc_seasonteam')->where('teamid', $teamid)->find()['SeasonID'];
              $seasonname = Db::name('ctfc_season')->where('id', $seasonid)->find()['Name'];
              $item['SeasonID'] = $seasonid;
              $item['SeasonName'] = $seasonname;
              $item['TeamID'] = $teamid;
              $item['TeamName'] = $teamname;
      
              $item['Approve'] = $seasonteam['Approve'];
       
              $team = Db::name('ctfc_team')->where('id', $teamid)->find();
          
              array_push($list, $item);
            }
        
          $this->dataResult($list);
        } 
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
        $seasonteam_data['TimePreference'] = $data['TimePreference'];
        //$seasonteam_data['PlayoffID'] = $data['PlayoffID'];
        $seasonteam_data['GroupID'] = $data['GroupID'];
        $seasonteam_data['PlayoffGroupID'] = $data['PlayoffGroupID'];
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
               

                $result += Db::name('ctfc_seasonteam')->where('TeamID', $teamID)
                    ->update(['Approve' => 1]);

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

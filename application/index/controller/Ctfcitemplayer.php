<?php
/**
 * Created by Yu Lu
 * Date: Fed 17, 2023
 * Time: 13:30
 */

namespace app\index\controller;

require_once(__DIR__ . '/../../../thinkphp/library/think/db/Expression.php');

use think\Db;
use think\Session;


class Ctfcitemplayer extends Base
{
    const FIELD = 'ID,SeasonID,TeamID,Sex,AgeGroupID,ItemID,PlayerID1,PlayerID2,PlayerID3,PlayerID4,PlayerID5,PlayerID6';
   
    public function lists($seasonid = null, $teamid = null)
    {
       
        if ($this->jsonRequest()) {
            $itemplayers = Db::name('ctfc_itemplayer')->order('ID');
            if ($seasonid && $teamid ) $itemplayers = $itemplayers->where('seasonid', $seasonid)->where('teamid', $teamid);
            $itemplayers = $itemplayers->select();
            $list = array();
            foreach ($itemplayers as $itemplayer) {
              $col = array();
              $col['ID'] = $itemplayer['ID'];
              
              $seasonid = $itemplayer['SeasonID'];
              $seasonname = Db::name('ctfc_season')->where('ID', $seasonid)->find()['Name'];
              $col['SeasonID'] = $seasonid;
              $col['SeasonName'] = $seasonname;
              
              $teamid = $itemplayer['TeamID'];
              $teamname = Db::name('ctfc_team')->where('ID', $teamid)->find()['Name']; 
              $col['TeamID'] = $teamid;
              $col['TeamName'] = $teamname;

              $col['Sex'] = $itemplayer['Sex'];

              $agegroupid = $itemplayer['AgeGroupID'];
              $agegroupname = Db::name('ctfc_agegroup')->where('ID', $agegroupid)->find()['Name'];
              $col['AgeGroupID'] = $agegroupid;
              $col['AgeGroupName'] = $agegroupname;

              $itemid = $itemplayer['ItemID'];
              $itemname = Db::name('ctfc_item')->where('ID', $itemid)->find()['Name'];
              $col['ItemID']= $itemid;
              $col['ItemName'] = $itemname;

   
              $playerid1 = $itemplayer['PlayerID1'];
              $playername1 = Db::name('ctfc_player')->where('ID', $playerid1)->find()['Name'];
              $col['PlayerID1']= $playerid1;
              $col['PlayerName1'] = $playername1;
    
              $playerid2 = $itemplayer['PlayerID2'];
              $playername2 = Db::name('ctfc_player')->where('ID', $playerid2)->find()['Name'];
              $col['PlayerID2']= $playerid2;
              $col['PlayerName2'] = $playername2;
    
              $playerid3 = $itemplayer['PlayerID3'];
              $playername3 = Db::name('ctfc_player')->where('ID', $playerid3)->find()['Name'];
              $col['PlayerID3']= $playerid3;
              $col['PlayerName3'] = $playername3;
    
              $playerid4 = $itemplayer['PlayerID4'];
              $playername4 = Db::name('ctfc_player')->where('ID', $playerid4)->find()['Name'];
              $col['PlayerID4']= $playerid4;
              $col['PlayerName4'] = $playername4;
    
              $playerid5 = $itemplayer['PlayerID5'];
              $playername5 = Db::name('ctfc_player')->where('ID', $playerid5)->find()['Name'];
              $col['PlayerID5']= $playerid5;
              $col['PlayerName5'] = $playername5;

              $playerid6 = $itemplayer['PlayerID6'];
              $playername6 = Db::name('ctfc_player')->where('ID', $playerid6)->find()['Name'];
              $col['PlayerID6']= $playerid6;
              $col['PlayerName6'] = $playername6;

              
       
        //       $team = Db::name('ctfc_team')->where('id', $teamid)->find();
          
              array_push($list, $col);
            }
        
          $this->dataResult($list);
        } 
    }


    public function add()
    {
        $this->checkauthorization();
        $data = request()->only(self::FIELD, 'post');
        $result = Db::name('ctfc_itemplayer')->insert($data);
        $this->affectedRowsResult($result);
    }

    public function update($seasonid, $teamid)
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data); 
        $seasonteam_data = array();
        $seasonteam_data['SeasonID'] = $data['SeasonID'];
        $seasonteam_data['TeamID'] = $data['TeamID'];
        $seasonteam_data['Approve'] = $data['Approve'];

        $result = Db::name('ctfc_itemplayer')->where('SeasonID', $seasonid)->where('TeamID', $teamid)->update($seasonteam_data);
      
        $this->affectedRowsResult($result);
    }


    public function delete($seasonid, $teamid)
    {
        $this->checkauthorization();
        $result = Db::name('ctfc_itemplayer')->where('SeasonID', $seasonid)->where('TeamID', $teamid)->delete();
        $this->affectedRowsResult($result);
    }

    public function getItemType()
    {
        $this->checkauthorization();
        $data = request()->only('ItemID', 'get');
        $itemid = urldecode($data['ItemID']);
        $ctfc_item = Db::name('ctfc_item')->where('ID', $itemid);
        $ctfc_items = $ctfc_item->select();
        $list = array();
        foreach ($ctfc_items as $ctfc_item) {
            $issingle = $ctfc_item['IsSingle'];
            array_push($list, $issingle);
        }
        $this->jsonResult(0, ['affectedRows' => $list[0]]);
    }


    public function YougetSeasonTeamPlayers()
    {
        
        $this->checkauthorization();
        $data = request()->only('SeasonID,TeamID', 'get');
        $teamid = urldecode($data['TeamID']);
        $seasonid = urldecode($data['SeasonID']);
        
        $aaa = Db::name('ctfc_playernumber')->where('SeasonID', $seasonid)->where('TeamID', $teamid);
        $aaa = $aaa->select();
        $list = array();
        foreach ($aaa as $ctfc_itemplayer) {
            $players = $ctfc_itemplayer['PlayerID'];
            array_push($list, $players);
        }
        $this->jsonResult(0, ['affectedRows' => $list]);
    }
    
    function calculateAge($dateOfBirth) {
        $currentYear = date('Y');
        $birthYear = date('Y', strtotime($dateOfBirth));
        $age = $currentYear - $birthYear;
        // if (date('md') < date('md', strtotime($dateOfBirth))) {
            $age--;
        // }
        return $age;
    }
    function getAgeGroup($age, $agegroups) {
        foreach ($agegroups as $agegroup) {
            if ($age >= $agegroup['MinAge'] && $age <= $agegroup['MaxAge']) {
                return $agegroup['ID'];
            }
        }
        // If age does not match any age group, return null or an appropriate value.
        return null;
    }

    public function getPlayerAgeSex() {
        $this->checkauthorization();
        $data = request()->only('PlayerID', 'get');
        $playerid = urldecode($data['PlayerID']);
        
        
        $player_db = Db::name('ctfc_player')->where('ID', $playerid);
        $players = $player_db->select();
        $birthdays = array();
        $sex = array();
        foreach ($players as $player) {
            $player_birthday = $player['Birthday'];
            $player_sex = $player['Sex'];
            array_push($birthdays, $player_birthday);
            array_push($sex, $player_sex);
        }

        $currentAge = $this->calculateAge($birthdays[0]);

        $agegroup_db = Db::name('ctfc_agegroup');
        $agegroups = $agegroup_db->select();
        $agegrouplist =array();
        foreach ($agegroups as $agegroup) {
            $agegroup_item =array();
            $agegroup_item['ID'] = $agegroup['ID'];
            $agegroup_item['MinAge'] = $agegroup['MinAge'];
            $agegroup_item['MaxAge'] = $agegroup['MaxAge'];
            array_push($agegrouplist, $agegroup_item);
        }

        $currentagegroup = $this->getAgeGroup($currentAge, $agegrouplist);
        
        $result = array();
        array_push($result, $currentagegroup);
        array_push($result, $sex[0]);
        
        $this->jsonResult(0, ['affectedRows' => $result]);
    }
}



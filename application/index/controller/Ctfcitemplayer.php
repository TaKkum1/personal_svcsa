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
              $playername1 = Db::name('ctfc_player')->where('ID', $playerid1)->order('Name')->find()['Name'];
              $col['PlayerID1']= $playerid1;
              $col['PlayerName1'] = $playername1;
    
              $playerid2 = $itemplayer['PlayerID2'];
              $playername2 = Db::name('ctfc_player')->where('ID', $playerid2)->order('Name')->find()['Name'];
              $col['PlayerID2']= $playerid2;
              $col['PlayerName2'] = $playername2;
    
              $playerid3 = $itemplayer['PlayerID3'];
              $playername3 = Db::name('ctfc_player')->where('ID', $playerid3)->order('Name')->find()['Name'];
              $col['PlayerID3']= $playerid3;
              $col['PlayerName3'] = $playername3;
    
              $playerid4 = $itemplayer['PlayerID4'];
              $playername4 = Db::name('ctfc_player')->where('ID', $playerid4)->order('Name')->find()['Name'];
              $col['PlayerID4']= $playerid4;
              $col['PlayerName4'] = $playername4;
    
              $playerid5 = $itemplayer['PlayerID5'];
              $playername5 = Db::name('ctfc_player')->where('ID', $playerid5)->order('Name')->find()['Name'];
              $col['PlayerID5']= $playerid5;
              $col['PlayerName5'] = $playername5;

              $playerid6 = $itemplayer['PlayerID6'];
              $playername6 = Db::name('ctfc_player')->where('ID', $playerid6)->order('Name')->find()['Name'];
              $col['PlayerID6']= $playerid6;
              $col['PlayerName6'] = $playername6;

              
       
        //       $team = Db::name('ctfc_team')->where('id', $teamid)->find();
          
              array_push($list, $col);
            }
        
          $this->dataResult($list);
        } 
    }

    public function listview($seasonid = null) 
    {
        $list = Db::name('ctfc_seasonitem_agegroup_view')->paginate(input('pagesize'));
        $this->paginatedResult(
            $list->total(),
            $list->listRows(),
            $list->currentPage(),
            $list->items()
        );

    }

    public function add()
    {
        $this->checkauthorization();
        // Step 1: Insert data into ctfc_itemplayer
        $data = request()->only(self::FIELD, 'post');
        $result = Db::name('ctfc_itemplayer')->insert($data);
        // Step 2: Get the itemplayerID of the inserted row
        if ($result) {
            $itemplayerID = Db::name('ctfc_itemplayer')->getLastInsID();
            // Step 3: Insert the itemplayerID into ctfc_heat
            $heat_data = [
                'ItemPlayerID' => $itemplayerID,
                'LaneNumber' => 'by default',
                'Result' => 'by default',
                'Note' => 'by default',
                'EventID' => 'by default',
                'HeatID' => 'by default'
            ];
            $heat_result = Db::name('ctfc_heat')->insert($heat_data);
            $this->affectedRowsResult($heat_result);
        } else {
            // Handle the case when the insertion into ctfc_itemplayer fails
            // You can raise an error, log the issue, or handle it based on your application logic.
            // For this example, we will just print an error message.
            echo "Failed to insert data into ctfc_itemplayer.";
        }
    }

    public function update($id)
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data); 
        $itemplayer_data = array();
        $itemplayer_data['SeasonID'] = $data['SeasonID'];
        $itemplayer_data['TeamID'] = $data['TeamID'];
        $itemplayer_data['Sex'] = $data['Sex'];
        $itemplayer_data['ItemID'] = $data['ItemID'];
        $itemplayer_data['AgeGroupID'] = $data['AgeGroupID'];
        $itemplayer_data['PlayerID1'] = $data['PlayerID1'];
        $itemplayer_data['PlayerID2'] = $data['PlayerID2'];
        $itemplayer_data['PlayerID3'] = $data['PlayerID3'];
        $itemplayer_data['PlayerID4'] = $data['PlayerID4'];
        $itemplayer_data['PlayerID5'] = $data['PlayerID5'];
        $itemplayer_data['PlayerID6'] = $data['PlayerID6'];
   

        $result = Db::name('ctfc_itemplayer')->where('ID', $id)->update($itemplayer_data);
      
        $this->affectedRowsResult($result);
    }

    public function delete($id)
    {
        $this->checkauthorization();

        $result = Db::name('ctfc_itemplayer')->where('ID', $id)->delete();
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

    function calculateAge($dateOfBirth) 
    {
        $currentYear = date('Y');
        $birthYear = date('Y', strtotime($dateOfBirth));
        $age = $currentYear - $birthYear;
        // if (date('md') < date('md', strtotime($dateOfBirth))) {
            // $age--;
        // }
        return $age;
    }
   

    function getAgeGroup($age, $agegroups) 
    {
        foreach ($agegroups as $agegroup) {
            if ($age >= $agegroup['MinAge'] && $age <= $agegroup['MaxAge']) {
                return $agegroup['ID'];
            }
        }
        // If age does not match any age group, return null or an appropriate value.
        return null;
    }

    public function getPlayerAgeSex() 
    {
        $this->checkauthorization();
        $data = request()->only('PlayerID', 'get');
        $playerid = urldecode($data['PlayerID']);
        
        
        $player_db = Db::name('ctfc_player')->where('ID', $playerid)->order('Name');
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

    function checkSignleItem($itemid) 
    {
        $item_is_single = Db::name('ctfc_item')->where('ID', $itemid)->find()['IsSingle'];
        if($item_is_single == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function getAgeRange() {
        $this->checkauthorization();
        $data = request()->only('SiAgeGroupID', 'get');
        $seasonagegroupID = urldecode($data['SiAgeGroupID']);
        
        $agegroup_ordered_list = Db::name('ctfc_agegroup')->order('MinAge')->select();

        $list = array();
        $length_of_agegroup_ordered_list = count($agegroup_ordered_list);
        $boundary = -1;
        $seasonagegroupID_index_order = -1;
        for($index = 0; $index < $length_of_agegroup_ordered_list; $index++) {
            if($agegroup_ordered_list[$index]['MinAge'] == 19 && $agegroup_ordered_list[$index]['MaxAge'] == 35) {
                $boundary = $agegroup_ordered_list[$index]['ID'];   
                $boundary_index = $index;   
            }
            // Get $seasonagegroupID's index in the agegroup_ordered_list.
            if($agegroup_ordered_list[$index]['ID'] == $seasonagegroupID) {
                $seasonagegroupID_index_order = $index;
            }
        }
        
        if($seasonagegroupID == $boundary) {
            // return full age range from 0's MinAge to n's MaxAge
            $list = [$agegroup_ordered_list[0]['MinAge'], $agegroup_ordered_list[$length_of_agegroup_ordered_list - 1]['MaxAge']];
        } else if($seasonagegroupID_index_order > $boundary_index) {
            // return age range between seasonagegroupID to n
            for($i = 0; $i < $length_of_agegroup_ordered_list; $i++){
                if($agegroup_ordered_list[$i]['ID'] == $seasonagegroupID){
                    $list = [$agegroup_ordered_list[$i]['MinAge'], $agegroup_ordered_list[$length_of_agegroup_ordered_list - 1]['MaxAge']];
                    break;
                }
            }
        } else if($seasonagegroupID_index_order < $boundary_index) {
                // return age range between 0 to seasonagegroupID
            for($i = 0; $i < $length_of_agegroup_ordered_list; $i++){
                if($agegroup_ordered_list[$i]['ID'] == $seasonagegroupID){
                    $list = [$agegroup_ordered_list[0]['MinAge'], $agegroup_ordered_list[$i]['MaxAge']];
                    break;
                }
            }
        } 
        $this->jsonResult(0, ['affectedRows' => $list]);
    }
    

    public function GetPlayersList()
    {
        $this->checkauthorization();
        $data = request()->only('SiMinAG,SiMaxAG,SiID,SiSex,Tmid,SsID', 'get');
        $SeasonitemMinAG= urldecode($data['SiMinAG']);
        $SeasonitemMaxAG = urldecode($data['SiMaxAG']);
        $CurrentSelecteditemID = urldecode($data['SiID']);
        $SeasonitemSex = urldecode($data['SiSex']);
        $CurrentSelectedTeamID = urldecode($data['Tmid']);
        $CurrentSeasonID = urldecode($data['SsID']);
        
        $all_players = Db::name('ctfc_player')->order('Name');
        $all_players = $all_players->select();

        $minAgeBondary=$SeasonitemMinAG;
        $maxAgeBondary=$SeasonitemMaxAG;



        // Create a list of player IDs within the age group and sex group
        $list = array();
        foreach ($all_players as $player) {
            // SeasonitemSex can be M, F, or mix, when SeasonitemSex is mix, we can ignore M or F
            if (strtolower($SeasonitemSex) == "mix" || strtolower($SeasonitemSex) == strtolower($player['Sex'])) {
                $currentPlayerAge = $this->calculateAge($player['Birthday']); //TODO (check if need put it into a list)
                if($currentPlayerAge >= $minAgeBondary && $currentPlayerAge <= $maxAgeBondary) {
                    array_push($list, $player['ID']);
                }
            }
        }

        $list_reference = $list;
        // Filter the list of player IDs that's not duplicated from other team.
        foreach ($list_reference as $player_id) {
            $re = [];

            for ($i = 1; $i <= 6; $i++) {
                $re[$i] = Db::name('ctfc_itemplayer')->where("PlayerID{$i}", $player_id)->select();
            }

            if (!empty($re)) {
                // First check if current player_id already registered, let check if it's in other team
                // Second check if curent player is in current season.
                foreach ($re as $player_results) {
                    foreach ($player_results as $itemplayer) {
                        if (($itemplayer['TeamID'] != $CurrentSelectedTeamID) && ($itemplayer['SeasonID'] == $CurrentSeasonID)) {
                            // This player is in another team, remove this one from the return list
                            // Find the index of this player to be removed.
                            $index = array_search($player_id, $list);
                            // Remove the element if it exists in the array
                            if ($index !== false) {
                                unset($list[$index]);
                            }
                            // Re-index the array
                            $list = array_values($list);
                        }

                        if (($itemplayer['ItemID'] == $CurrentSelecteditemID) && (strtolower($itemplayer['Sex']) == strtolower($SeasonitemSex)) && ($itemplayer['SeasonID'] == $CurrentSeasonID)) {
                            // This player has already registered the same item
                            $index_b = array_search($player_id, $list);
                            // Remove the element if it exists in the array
                            if ($index_b !== false) {
                                unset($list[$index_b]);
                            }
                            // Re-index the array
                            $list = array_values($list);
                        }
                    }
                }                
            }
        }

        // Check if the player has 3 single items in the current season aleady.
        $final_list = $list;
        if ($this->checkSignleItem($CurrentSelecteditemID)) {
            foreach ($list as $itemplayer_id) {
                //get all ctfc_itemplayer of one single player.
                $data = Db::name('ctfc_itemplayer')->where("SeasonID", $CurrentSeasonID)->where("PlayerID1", $itemplayer_id)->select();
                
                // Add all singleitem to an array for this player.
                $players_items_list = [];
                foreach($data as $one_data) {
                    if ($this->checkSignleItem($one_data['ItemID'])) {
                        array_push($players_items_list, $one_data['ItemID']);
                    }
                }

                // count how many singleitems for this player, skip this player if >=3.
                if(count($players_items_list) >=3) {
                    // Find the index of this player to be removed.
                    $index = array_search($itemplayer_id, $final_list);
                    // Remove the element if it exists in the array
                    if ($index !== false) {
                        unset($final_list[$index]);
                    }
                    // Re-index the array
                    $final_list = array_values($final_list);
                }

            }
        }
        $this->jsonResult(0, ['affectedRows' => $final_list]);
    }

    public function registeritemplayer($seasonid)
    {
        $this->headerAndFooter('ctfc');

        $season = Db::name('ctfc_season')
            ->where('ID', $seasonid)->find(); 
        if (!$season) goto notfound;
        
        $this->view->assign('season', $season);
        return $this->view->fetch('ctfcitemplayer/registeritemplayer');

        notfound:
        header("HTTP/1.0 404 Not Found");
        die;

    }

    public function apply($seasonid)
    {

    }
}



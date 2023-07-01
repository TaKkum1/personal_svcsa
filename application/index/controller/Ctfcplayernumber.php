<?php


namespace app\index\controller;

require_once(__DIR__ . '/../../../thinkphp/library/think/db/Expression.php');


use think\Db;
use think\Session;
use think\Db\Expression;

class Ctfcplayernumber extends Base
{
  const FIELD = 'SeasonID,TeamID,PlayerID,PlayerNumber';

  public function add()
  {
    $this->checkauthorization();

    $data = request()->only(self::FIELD, 'post');
    $this->makeNull($data);
    $result = Db::name('ctfc_playernumber')->insert($data);
    $this->affectedRowsResult($result);
  }


  public function delete($seasonid, $teamid, $playerid)
  {
    $this->checkauthorization();
    $result = Db::name('ctfc_playernumber')->where('SeasonID', $seasonid)->where('TeamID', $teamid)->where('PlayerID', $playerid)->delete();
    $this->affectedRowsResult($result);
  }


  public function lists($teamid = null)
  {
    if ($this->jsonRequest()) {
      $playernumbers = Db::name('ctfc_playernumber')->order('SeasonID');
      $maxseasonID = Db::name('ctfc_playernumber')->max('SeasonID');
      $playernumbers = $playernumbers->where('seasonid', $maxseasonID);
      if ($teamid)
        $playernumber = $playernumbers->where('seasonid', $maxseasonID)->where('teamid', $teamid);
      $currentseasonteams = Db::name('ctfc_seasonteam')->where('seasonid', $maxseasonID);

      $playernumbers = $playernumbers->select();
      $list = array();
      $teamidlist = array();

      $currentseasonteams = $currentseasonteams->select();
      foreach ($currentseasonteams as $seasonteam) {
        $teamidlist[] = $seasonteam['TeamID'];
      }
      foreach ($playernumbers as $playernumber) {
        $element = array();
        // fill out team registration info.
        $teamid = $playernumber['TeamID'];
        if (in_array($teamid, $teamidlist)) {
          $seasonid = $playernumber['SeasonID'];
          $seasonname = Db::name('ctfc_season')->where('ID', $seasonid)->find()['Name'];
          $playerid = $playernumber['PlayerID'];
          $playername = Db::name('ctfc_player')->where('ID', $playerid)->find()["Name"];
          $element['SeasonID'] = $maxseasonID;
          $element['SeasonName'] = $seasonname;
          $element['TeamID'] = $teamid;
          $teamname = Db::name('ctfc_team')->where('ID', $teamid)->find()['Name'];
          $element['TeamName'] = $teamname;
          $element['PlayerID'] = $playerid;
          $element['PlayerName'] = $playername;
          $element['PlayerNumber'] = $playernumber['PlayerNumber'];
          array_push($list, $element);
        }
      }

      $this->dataResult($list);
    }
  }

  public function update($seasonid, $teamid, $playerid){
    $this->checkauthorization();

    $data = request()->only(self::FIELD, 'post');
    $this->makeNull($data);
    $result = Db::name('ctfc_playernumber')->where('SeasonID', $seasonid)->where('TeamID', $teamid)->where('PlayerID', $playerid)->update($data);
    $this->affectedRowsResult($result);
}

  public function reassign()
  {
    // read all data from ctfc_itemplayer
    $maxseasonID = Db::name('ctfc_season')->max('ID');
    $itemPlayers = Db::name('ctfc_itemplayer')->where('SeasonID', $maxseasonID)->order('TeamID')->select();


    // build the map: key is teamID, value is set of playerID of that team
    $teamPlayerMap = array();
    foreach ($itemPlayers as $itemPlayer) {
      $teamID = $itemPlayer['TeamID'];
      // get the playerIDs set of the team
      $playerIDs = null;
      if (array_key_exists($teamID, $teamPlayerMap)) {
        $playerIDs = $teamPlayerMap[$teamID];
      } else {
        $playerIDs = array();
      }
      // add the playerID 1 - 6 to the set (if not 0)
      for ($i = 1; $i <= 6; $i++) {
        $playerColumnName = "PlayerID" . $i;
        if ($itemPlayer[$playerColumnName] && !isset($playerIDs[$itemPlayer[$playerColumnName]])) {
          $playerIDs[] = $itemPlayer[$playerColumnName];
        }
      }
      // update the map
      $teamPlayerMap[$teamID] = $playerIDs;
    }

    // HardCode available player number array
    // 每行两个数，表示可用号码的范围，如[400, 401]表示可用号码为400和401
    // 行数可以任意增减
    $availableNumerRange = [
      [100, 200],
      [260, 280],
      [400, 800]
    ];

    // generate the list of available player numbers
    $availableNumbers = array();
    foreach ($availableNumerRange as $row) {
      $start = $row[0];
      $end = $row[1];
      for ($i = $start; $i <= $end; $i++) {
        $availableNumbers[] = $i;
      }
    }

    $count = 0;
    // assign number to players
    $playerNumber = 0;
    foreach ($teamPlayerMap as $teamID => $playerIDs) {    
      foreach ($playerIDs as $playerID) {
        $data = array();
        $data['SeasonID'] = $maxseasonID;
        $data['TeamID'] = $teamID;
        $data['PlayerID'] = $playerID;
        $data['PlayerNumber'] = $availableNumbers[$playerNumber];

        // find existing record
        $existingRecord = Db::name('ctfc_playernumber')
          ->where('SeasonID', $maxseasonID)
          ->where('TeamID', $teamID)
          ->where('PlayerID', $playerID)
          ->find();

        // if existing record, update it
        if ($existingRecord) {
          $result = Db::name('ctfc_playernumber')
            ->where('SeasonID', $maxseasonID)
            ->where('TeamID', $teamID)
            ->where('PlayerID', $playerID)
            ->update($data);
        } else {
          // if not existing record, insert it
          $result = Db::name('ctfc_playernumber')->insert($data);
        }
        $count++;
        $playerNumber++;
      }
    }
    $this->dataResult($count);
  }
}
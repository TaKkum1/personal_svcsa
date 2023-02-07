<?php


namespace app\index\controller;

require_once (__DIR__ . '/../../../thinkphp/library/think/db/Expression.php');


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
            if($teamid) $playernumber = $playernumbers->where('seasonid', $maxseasonID)->where('teamid', $teamid);
            $currentseasonteams = Db::name('ctfc_seasonteam')->where('seasonid', $maxseasonID);

            $playernumbers = $playernumbers->select();
            $list = array();
            $teamidlist = array();

            $currentseasonteams = $currentseasonteams->select();
            foreach($currentseasonteams as $seasonteam){
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
}
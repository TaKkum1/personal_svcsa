<?php
/**
 * Created by PhpStorm.
 * User: Aven
 * Date: 2018/10/28
 * Time: 1:30
 */

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


    public function lists($seasonid = null)
    {
        if ($this->jsonRequest()) {
            $playernumbers = Db::name('ctfc_playernumber')->order('SeasonID');
            if ($seasonid) $playernumbers = $playernumbers->where('seasonid', $seasonid);
            $playernumbers = $playernumbers->select();
            $list = array();
            foreach ($playernumbers as $playernumber) {
              $element = array();
              // fill out team registration info.
              $teamid = $playernumber['TeamID'];
              $teamname = Db::name('ctfc_team')->where('ID', $teamid)->find()['Name'];
              $seasonid = $playernumber['SeasonID'];
              $seasonname = Db::name('ctfc_season')->where('ID', $seasonid)->find()['Name'];
              $playerid = $playernumber['PlayerID'];
              $playername = Db::name('ctfc_player')->where('ID', $playerid)->find()["Name"];
              $element['ID'] = $seasonid."-".$seasonid;
              $element['SeasonID'] = $seasonid;
              $element['SeasonName'] = $seasonname;
              $element['TeamID'] = $teamid;
              $element['TeamName'] = $teamname;
              $element['PlayerID'] = $playerid;
              $element['PlayerName'] = $playername;
              $element['PlayerNumber'] = $playernumber['PlayerNumber'];
            //   $element['Sex'] = $playernumber['Sex'];
            //   $element['MinAgeGroupID'] = $playernumber['MinAgeGroupID'];
            //   $element['MaxAgeGroupID'] = $playernumber['MaxAgeGroupID'];
            //   $min_agegroup_id = $playernumber['MinAgeGroupID'];
            //   $max_agegroup_id = $playernumber['MaxAgeGroupID'];
            //   $min_agegroup_name = Db::name('ctfc_agegroup')->where('ID', $min_agegroup_id)->find()['Name'];
            //   $max_agegroup_name = Db::name('ctfc_agegroup')->where('ID', $max_agegroup_id)->find()['Name'];
            //   $element['MinAgeGroupName'] = $min_agegroup_name;
            //   $element['MaxAgeGroupName'] = $max_agegroup_name;
                 
              array_push($list, $element);
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
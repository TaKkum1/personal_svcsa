<<<<<<< HEAD
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
    const FIELD = 'SeasonID,TeamID,Approve';
   

    public function add()
    {
        $this->checkauthorization();
        $data = request()->only(self::FIELD, 'post');
        $result = Db::name('ctfc_seasonteam')->insert($data);
        $this->affectedRowsResult($result);
    }



    public function delete($seasonid, $teamid)
    {
        $this->checkauthorization();
        $result = Db::name('ctfc_seasonteam')->where('SeasonID', $seasonid)->where('TeamID', $teamid)->delete();
        $this->affectedRowsResult($result);
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
              $teamname = Db::name('ctfc_team')->where('ID', $teamid)->find()['Name'];
              $seasonid = $seasonteam['SeasonID'];
              $seasonname = Db::name('ctfc_season')->where('ID', $seasonid)->find()['Name'];
              $item['ID'] = $seasonid."-".$teamid;
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



    public function update($seasonid, $teamid)
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data); 
        $seasonteam_data = array();
        $seasonteam_data['SeasonID'] = $data['SeasonID'];
        $seasonteam_data['TeamID'] = $data['TeamID'];
        $seasonteam_data['Approve'] = $data['Approve'];

        $result = Db::name('ctfc_seasonteam')->where('SeasonID', $seasonid)->where('TeamID', $teamid)->update($seasonteam_data);
      
        $this->affectedRowsResult($result);
    }

    public function passApplication()
    {
        $this->checkauthorization();

        $data = request()->only('SeasonIDTeamIDs,Passed', 'post'); //SeasonIDTeamIDs
        $SeasonIDTeamIDs = urldecode($data['SeasonIDTeamIDs']);
        //TODO: split SeasonIDTeamIDs to TeamIDs
        $SeasonIDTeamIDs_arr = explode(',', $SeasonIDTeamIDs);

        // $teamIDs = urldecode($data['TeamIDs']);
        $passed = isset($data['Passed']) ? boolval($data['Passed']) : true;

        $result = 0;
        $emailRes = 0;

        if ($passed) {
            foreach ($SeasonIDTeamIDs_arr as $SeasonIDTeamID) {

                $tmparr = explode("-", $SeasonIDTeamID);
                $result += Db::name('ctfc_seasonteam')->where('SeasonID', $tmparr[0])->where('TeamID', $tmparr[1])
                ->update(['Approve' => 1]);
                    
            }
     }


        $this->jsonResult(0, ['affectedRows' => $result, 'affectedEmails' => $emailRes]);

    }



}
=======
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
    const FIELD = 'SeasonID,TeamID,Approve';
   

    public function add()
    {
        $this->checkauthorization();
        $data = request()->only(self::FIELD, 'post');
        $result = Db::name('ctfc_seasonteam')->insert($data);
        $this->affectedRowsResult($result);
    }



    public function delete($seasonid, $teamid)
    {
        $this->checkauthorization();
        $result = Db::name('ctfc_seasonteam')->where('SeasonID', $seasonid)->where('TeamID', $teamid)->delete();
        $this->affectedRowsResult($result);
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
              $teamname = Db::name('ctfc_team')->where('ID', $teamid)->find()['Name'];
              $seasonid = $seasonteam['SeasonID'];
              $seasonname = Db::name('ctfc_season')->where('ID', $seasonid)->find()['Name'];
              $item['ID'] = $seasonid."-".$teamid;
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



    public function update($seasonid, $teamid)
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data); 
        $seasonteam_data = array();
        $seasonteam_data['SeasonID'] = $data['SeasonID'];
        $seasonteam_data['TeamID'] = $data['TeamID'];
        $seasonteam_data['Approve'] = $data['Approve'];

        $result = Db::name('ctfc_seasonteam')->where('SeasonID', $seasonid)->where('TeamID', $teamid)->update($seasonteam_data);
      
        $this->affectedRowsResult($result);
    }

    public function passApplication()
    {
        $this->checkauthorization();

        $data = request()->only('SeasonIDTeamIDs,Passed', 'post'); //SeasonIDTeamIDs
        $SeasonIDTeamIDs = urldecode($data['SeasonIDTeamIDs']);
        //TODO: split SeasonIDTeamIDs to TeamIDs
        $SeasonIDTeamIDs_arr = explode(',', $SeasonIDTeamIDs);

        // $teamIDs = urldecode($data['TeamIDs']);
        $passed = isset($data['Passed']) ? boolval($data['Passed']) : true;

        $result = 0;
        $emailRes = 0;

        if ($passed) {
            foreach ($SeasonIDTeamIDs_arr as $SeasonIDTeamID) {

                $tmparr = explode("-", $SeasonIDTeamID);
                $result += Db::name('ctfc_seasonteam')->where('SeasonID', $tmparr[0])->where('TeamID', $tmparr[1])
                ->update(['Approve' => 1]);
                    
            }
     }


        $this->jsonResult(0, ['affectedRows' => $result, 'affectedEmails' => $emailRes]);

    }



}
>>>>>>> 37313bc (Initial commit)

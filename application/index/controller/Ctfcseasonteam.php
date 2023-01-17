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



    public function delete($id)
    {
        $this->checkauthorization();
        $result += Db::name('ctfc_seasonteam')->where('TeamID', $id)->delete();
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

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data); 
        $seasonteam_data = array();
        $seasonteam_data['SeasonID'] = $data['SeasonID'];
        $seasonteam_data['TeamID'] = $data['TeamID'];
        $seasonteam_data['Approve'] = $data['Approve'];

        $result = Db::name('ctfc_seasonteam')->where('ID', $id)->update($seasonteam_data);
      
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


        $this->jsonResult(0, ['affectedRows' => $result, 'affectedEmails' => $emailRes]);

    }



}

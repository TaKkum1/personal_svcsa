<?php
/**
 * Created by Yu Lu
 * Date: Fed 07, 2023
 * Time: 13:30
 */

namespace app\index\controller;

require_once(__DIR__ . '/../../../thinkphp/library/think/db/Expression.php');

use think\Db;
use think\Session;


class Ctfcitemplayer extends Base
{
    const FIELD = 'ID,SeasonID,TeamID,Sex,AgeGroupID,ItemID,PlayerID1,PlayerID2,PlayerID3,PlayerID4,PlayerID5,PlayerID6';
   

    public function add()
    {
        $this->checkauthorization();
        $data = request()->only(self::FIELD, 'post');
        $result = Db::name('ctfc_itemplayer')->insert($data);
        $this->affectedRowsResult($result);
    }



    public function delete($seasonid, $teamid)
    {
        $this->checkauthorization();
        $result = Db::name('ctfc_itemplayer')->where('SeasonID', $seasonid)->where('TeamID', $teamid)->delete();
        $this->affectedRowsResult($result);
    }



    public function lists(){
        $list = Db::name('ctfc_itemplayer')->paginate(input('pagesize'));
        $this->paginatedResult(
            $list->total(),
            $list->listRows(),
            $list->currentPage(),
            $list->items()
        );
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

    // public function passApplication()
    // {
    //     $this->checkauthorization();

    //     $data = request()->only('SeasonIDTeamIDs,Passed', 'post'); //SeasonIDTeamIDs
    //     $SeasonIDTeamIDs = urldecode($data['SeasonIDTeamIDs']);
    //     //TODO: split SeasonIDTeamIDs to TeamIDs
    //     $SeasonIDTeamIDs_arr = explode(',', $SeasonIDTeamIDs);

    //     // $teamIDs = urldecode($data['TeamIDs']);
    //     $passed = isset($data['Passed']) ? boolval($data['Passed']) : true;

    //     $result = 0;
    //     $emailRes = 0;

    //     if ($passed) {
    //         foreach ($SeasonIDTeamIDs_arr as $SeasonIDTeamID) {

    //             $tmparr = explode("-", $SeasonIDTeamID);
    //             $result += Db::name('ctfc_itemplayer')->where('SeasonID', $tmparr[0])->where('TeamID', $tmparr[1])
    //             ->update(['Approve' => 1]);
                    
    //         }
    //  }


    //     $this->jsonResult(0, ['affectedRows' => $result, 'affectedEmails' => $emailRes]);

    // }



}

<?php

namespace app\index\controller;

require_once (__DIR__ . '/../../../thinkphp/library/think/db/Expression.php');


use think\Db;
use think\Session;
use think\Db\Expression;

class Ctfcheat extends Base
{
    const FIELD = 'ID,EventID,HeatID,LaneNumber,TeamName,ItemAgeGroupSex,Player1,Player2,Player3,Player4,Player5,Player6,Result,Note,IsSingle,HeatSize,ItemName,Gender,AgeGroupNumber,ItemPlayerID,TeamID,ItemID';
    public function lists($IAGSid = null, $IsSingle = null){
        if($IAGSid) {
            $list = Db::name('ctfc_heat_view')->where('ItemAgeGroupSex', $IAGSid)->paginate(input('pagesize'));
        }elseif($IsSingle != null){
                $list = Db::name('ctfc_heat_view')->where('IsSingle', $IsSingle)->paginate(input('pagesize'));
        }
        else {
            $list = Db::name('ctfc_heat_view')->paginate(input('pagesize'));
        }


        // Modify the player1-6 fields to combine them into a single column

        $modifiedList = [];

        foreach ($list as $heateachrow) {
            $newTable = [];
            $newTable['ID'] = $heateachrow['ID'];
            $newTable['EventID'] = $heateachrow['EventID'];
            // Set default value of 1 to HeatID if null or empty
            $newTable['HeatID'] = $heateachrow['HeatID'] ? $heateachrow['HeatID'] : 1;
            $newTable['LaneNumber'] = $heateachrow['LaneNumber'];
            $newTable['TeamName'] = $heateachrow['TeamName'];
            $newTable['ItemAgeGroupSex'] = $heateachrow['ItemAgeGroupSex'];

            $players = [];
            for ($i = 1; $i <= 6; $i++) {
                if ($heateachrow["Player{$i}"]) {
                    $players[] = $heateachrow["Player{$i}"];
                }
            }
            $newTable['Players'] = implode(' , ', $players);
            $newTable['Result'] = $heateachrow['Result'];
            $newTable['Note'] = $heateachrow['Note'];

            // passing data for 项目 filter 
            $newTable['ItemID'] = $heateachrow['ItemID'];
            $newTable['ItemName'] = $heateachrow['ItemName'];
            // passing data for 队伍 filter
            $newTable['TeamID'] = $heateachrow['TeamID'];
     
            $modifiedList[] = $newTable;
        }
            // Extract the values to be sorted into separate arrays
            $eventIDs = array_column($modifiedList, 'EventID');
            $heatIDs = array_column($modifiedList, 'HeatID');
            $laneNumbers = array_column($modifiedList, 'LaneNumber');

            // Sort the modified list using the extracted values
            array_multisort($eventIDs, SORT_ASC, $heatIDs, SORT_ASC, $laneNumbers, SORT_ASC, $modifiedList);

        $this->dataResult($modifiedList);

    }

    public function update($id){
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        $count = Db::name('ctfc_heat')->where('ID', $id)->count();

        if($count>0) {
            $result = Db::name('ctfc_heat')->where('ID', $id)->update($data);           
        } else {
            $result = Db::name('ctfc_heat')->where('ID', $id)->insert($data);
        }
        $this->affectedRowsResult($result);
    }
}
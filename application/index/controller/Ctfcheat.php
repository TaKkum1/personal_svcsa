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
            $newTable['HeatID'] = $heateachrow['HeatID'];
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

        $this->dataResult($modifiedList);

    }

    public function update($id){
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        $result = Db::name('ctfc_heat')->where('ID', $id)->update($data);
        $this->affectedRowsResult($result);
    }
}
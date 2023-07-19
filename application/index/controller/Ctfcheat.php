<?php

namespace app\index\controller;

require_once(__DIR__ . '/../../../thinkphp/library/think/db/Expression.php');


use think\Db;
use think\Session;
use think\Db\Expression;

class Ctfcheat extends Base
{
    const FIELD = 'ID,EventID,HeatID,LaneNumber,TeamName,ItemAgeGroupSex,Player1,Player2,Player3,Player4,Player5,Player6,Result,Note,IsSingle,HeatSize,ItemName,Gender,AgeGroupNumber,ItemPlayerID,TeamID,ItemID';
    public function lists($IAGSid = null, $IsSingle = null)
    {
        if ($IAGSid) {
            $list = Db::name('ctfc_heat_view')->where('ItemAgeGroupSex', $IAGSid)->paginate(input('pagesize'));
        } elseif ($IsSingle != null) {
            $list = Db::name('ctfc_heat_view')->where('IsSingle', $IsSingle)->paginate(input('pagesize'));
        } else {
            $list = Db::name('ctfc_heat_view')->paginate(input('pagesize'));
        }
        $list = Db::name('ctfc_heat_view')->order(['EventID', 'HeatID', 'LaneNumber'])->paginate(input('pagesize'));
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


        $this->dataResult($modifiedList);

    }

    public function update($id)
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        $count = Db::name('ctfc_heat')->where('ID', $id)->count();

        if ($count > 0) {
            $result = Db::name('ctfc_heat')->where('ID', $id)->update($data);
        } else {
            $result = Db::name('ctfc_heat')->where('ID', $id)->insert($data);
        }
        $this->affectedRowsResult($result);
    }
    public function edit()
    {
        $this->checkauthorization();

        $data = request()->post();

        // Get data from the request
        $PlayerName = $data['PlayerName'];
        $ItemID = $data['ItemID'];
        $EventID = $data['EventID'];
        $HeatID = $data['HeatID'];
        $LaneNumber = $data['LaneNumber'];
        $Result = $data['Result'];
        $Note = $data['Note'];

        // Find the player ID based on the PlayerName
        $player = Db::name('ctfc_player')->where('Name', $PlayerName)->find();
        if (!$player) {
            // Player not found
            return json(['success' => false, 'message' => 'Player not found']);
        }
        $playerID = $player['ID'];

        // Find the item player ID based on the player ID and item ID
        $itemPlayer = Db::name('ctfc_itemplayer')
            ->where('ItemID', $ItemID)
            ->where(function ($query) use ($playerID) {
                $query->where('PlayerID1', $playerID)
                    ->whereOr('PlayerID2', $playerID)
                    ->whereOr('PlayerID3', $playerID)
                    ->whereOr('PlayerID4', $playerID)
                    ->whereOr('PlayerID5', $playerID)
                    ->whereOr('PlayerID6', $playerID);
            })
            ->find();

        if (!$itemPlayer) {
            // Item player not found
            return json(['success' => false, 'message' => 'Item player not found']);
        }

        $itemPlayerID = $itemPlayer['ID'];

        // Prepare the update data

        $updateData = [
            'LaneNumber' => $LaneNumber,
            'Result' => $Result,
            'Note' => $Note,
            'EventID' => $EventID,
            'HeatID' => $HeatID,
        ];

        // Update the ctfc_heat table

        // Check if the itemPlayerID exists in the ctfc_heat table
        $count = Db::name('ctfc_heat')->where('ItemPlayerID', $itemPlayerID)->count();

        if ($count > 0) {
            // If it exists, update the row
            $result = Db::name('ctfc_heat')->where('ItemPlayerID', $itemPlayerID)->update($updateData);
        } else {
            // If it doesn't exist, insert a new row
            $updateData['ItemPlayerID'] = $itemPlayerID;
            $result = Db::name('ctfc_heat')->insert($updateData);
        }

        if ($result !== false) {
            // Successfully updated the database
            return json(['success' => true]);
        } else {
            // Failed to update the database
            return json(['success' => false, 'message' => 'Failed to update the heat']);
        }
    }
}
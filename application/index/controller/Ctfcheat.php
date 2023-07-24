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
        // Saved for future pagenage() use 
        // $list = Db::name('ctfc_heat_view')->order(['EventID', 'HeatID', 'LaneNumber'])->paginate(input('pagesize'));

        if ($IAGSid) {
            $list = Db::name('ctfc_heat_view')->where('ItemAgeGroupSex', $IAGSid)->order(['EventID', 'HeatID', 'LaneNumber'])->select();
        } elseif ($IsSingle != null) {
            $list = Db::name('ctfc_heat_view')->where('IsSingle', $IsSingle)->order(['EventID', 'HeatID', 'LaneNumber'])->select();
        } else {
            $list = Db::name('ctfc_heat_view')->order(['EventID', 'HeatID', 'LaneNumber'])->select();
        }
        $modifiedList = [];

        if ($list) {
            // Modify the player1-6 fields to combine them into a single column
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

    public function reassign()
    {
            // 调用 lists 方法获取数据列表
            $list = Db::name('ctfc_heat_view')->select();

            // 建立空的映射数组
            $eventid_map = [];

            foreach ($list as $heateachrow) {
                // 逐行读取数据并建立映射
                $ItemAgeGroupSex = $heateachrow['ItemAgeGroupSex'];
                $ItemPlayerID = $heateachrow['ItemPlayerID'];

                // 如果映射数组中不存在该键，创建一个空数组作为值
                if (!isset($eventid_map[$ItemAgeGroupSex])) {
                    $eventid_map[$ItemAgeGroupSex] = [];
                }

                // 将当前行的ID添加到对应键的数组中
                $eventid_map[$ItemAgeGroupSex][] = $ItemPlayerID;
            }        
            $predefined_events = [
                '5000m(少年乙组 F)',
                '5000m(少年乙组 M)',
                '5000m(少年甲组 F)',
                '5000m(少年甲组 M)',
                '5000m(青年组 F)',
                '5000m(青年组 M)',
                '5000m(公开组 F)',
                '5000m(公开组 M)',
                '5000m(壮年组 F)',
                '5000m(壮年组 M)',
                '5000m(常青组 F)',
                '5000m(常青组 M)',
                '100m(儿童男女 Mix)',
                '100m(儿童甲组 F)',
                '100m(儿童甲组 M)',
                '100m(少年乙组 F)',
                '100m(少年乙组 M)',
                '100m(少年甲组 F)',
                '100m(少年甲组 M)',
                '100m(青年组 F)',
                '100m(青年组 M)',
                '100m(公开组 F)',
                '100m(公开组 M)',
                '100m(壮年组 F)',
                '100m(壮年组 M)',
                '100m(常青组 F)',
                '100m(常青组 M)',
                '400m(儿童男女 Mix)',
                '400m(儿童甲组 F)',
                '400m(儿童甲组 M)',
                '400m(少年乙组 F)',
                '400m(少年乙组 M)',
                '400m(少年甲组 F)',
                '400m(少年甲组 M)',
                '400m(青年组 F)',
                '400m(青年组 M)',
                '400m(公开组 F)',
                '400m(公开组 M)',
                '400m(壮年组 F)',
                '400m(壮年组 M)',
                '400m(常青组 F)',
                '400m(常青组 M)',
                '4x100m relay(儿童男女 Mix)',
                '4x100m relay(儿童甲组 F)',
                '4x100m relay(儿童甲组 M)',
                '4x100m relay(少年乙组 F)',
                '4x100m relay(少年乙组 M)',
                '4x100m relay(少年甲组 F)',
                '4x100m relay(少年甲组 M)',
                '4x100m relay(青年组 F)',
                '4x100m relay(青年组 M)',
                '4x100m relay(公开组 F)',
                '4x100m relay(公开组 M)',
                '4x100m relay(壮年组 F)',
                '4x100m relay(壮年组 M)',
                '4x100m relay(常青组 F)',
                '4x100m relay(常青组 M)',
                '800m(儿童男女 Mix)',
                '800m(儿童甲组 F)',
                '800m(儿童甲组 M)',
                '800m(少年乙组 F)',
                '800m(少年乙组 M)',
                '800m(少年甲组 F)',
                '800m(少年甲组 M)',
                '800m(青年组 F)',
                '800m(青年组 M)',
                '800m(公开组 F)',
                '800m(公开组 M)',
                '800m(壮年组 F)',
                '800m(壮年组 M)',
                '800m(常青组 F)',
                '800m(常青组 M)',
                '200m(儿童男女 Mix)',
                '200m(儿童甲组 F)',
                '200m(儿童甲组 M)',
                '200m(少年乙组 F)',
                '200m(少年乙组 M)',
                '200m(少年甲组 F)',
                '200m(少年甲组 M)',
                '200m(青年组 F)',
                '200m(青年组 M)',
                '200m(公开组 F)',
                '200m(公开组 M)',
                '200m(壮年组 F)',
                '200m(壮年组 M)',
                '200m(常青组 F)',
                '200m(常青组 M)',
                '1500m(少年乙组 F)',
                '1500m(少年乙组 M)',
                '1500m(少年甲组 F)',
                '1500m(少年甲组 M)',
                '1500m(青年组 F)',
                '1500m(青年组 M)',
                '1500m(公开组 F)',
                '1500m(公开组 M)',
                '1500m(壮年组 F)',
                '1500m(壮年组 M)',
                '1500m(常青组 F)',
                '1500m(常青组 M)',
                '4x400m mixed relay(儿童男女 Mix)',
                '4x400m mixed relay(儿童甲组 Mix)',
                '4x400m mixed relay(少年乙组 Mix)',
                '4x400m mixed relay(少年甲组 Mix)',
                '4x400m mixed relay(青年组 Mix)',
                '4x400m mixed relay(公开组 Mix)',
                '4x400m mixed relay(壮年组 Mix)',
                '4x400m mixed relay(常青组 Mix)'
                 // 其他预定义的事件
              ];
              
                // 初始化一个 EventID 计数器
                $next_event_id = 1;

                foreach ($predefined_events as $ItemAgeGroupSex) {
                    if (isset($eventid_map[$ItemAgeGroupSex])) {
                        // 事件存在于事件ID映射中。更新 ctfc_heat 表
                        // 注意，映射的值是与该事件关联的 itemplayer ids 的列表

                        foreach ($eventid_map[$ItemAgeGroupSex] as $ItemPlayerID) {
                            // 使用 $next_event_id 更新 ctfc_heat 表的 EventID
                            Db::name('ctfc_heat')->where('ItemPlayerID', $ItemPlayerID)->update(['EventID' => $next_event_id]);
                        }
                        // 这个事件ID已经使用，增加 next_event_id
                        $next_event_id++;
                    }
                }
                $this->dataResult($list);
        }
}




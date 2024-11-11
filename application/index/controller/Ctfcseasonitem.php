<?php
/**
 * Created by Jiayu Guo, Yu Lu
 * Date: Jan 30, 2023
 * Time: 22:30
 */

namespace app\index\controller;

require_once(__DIR__ . '/../../../thinkphp/library/think/db/Expression.php');

use think\Db;
use think\Session;


class Ctfcseasonitem extends Base
{
    const FIELD = 'SeasonID,ItemID,Sex,MinAgeGroupID,MaxAgeGroupID';
   

    public function add()
    {
        $this->checkauthorization();
        $data = request()->only(self::FIELD, 'post');
        $result = Db::name('ctfc_seasonitem')->insert($data);
        $this->affectedRowsResult($result);
    }

    public function lists($seasonid = null)
    {
        if ($this->jsonRequest()) {
            $seasonitems = Db::name('ctfc_seasonitem')->order('SeasonID');
            if ($seasonid) $seasonitems = $seasonitems->where('seasonid', $seasonid);
            $seasonitems = $seasonitems->select();
            $list = array();
            foreach ($seasonitems as $seasonitem) {
              $element = array();
              // fill out team registration info.
              $itemid = $seasonitem['ItemID'];
              $itemname = Db::name('ctfc_item')->where('ID', $itemid)->find()['Name'];
              $seasonid = $seasonitem['SeasonID'];
              $seasonname = Db::name('ctfc_season')->where('ID', $seasonid)->find()['Name'];
              $element['SeasonID'] = $seasonid;
              $element['SeasonName'] = $seasonname;
              $element['ItemID'] = $itemid;
              $element['ItemName'] = $itemname;
              $element['Sex'] = $seasonitem['Sex'];
              $element['MinAgeGroupID'] = $seasonitem['MinAgeGroupID'];
              $element['MaxAgeGroupID'] = $seasonitem['MaxAgeGroupID'];
              $min_agegroup_id = $seasonitem['MinAgeGroupID'];
              $max_agegroup_id = $seasonitem['MaxAgeGroupID'];
              $min_agegroup_name = Db::name('ctfc_agegroup')->where('ID', $min_agegroup_id)->find()['Name'];
              $max_agegroup_name = Db::name('ctfc_agegroup')->where('ID', $max_agegroup_id)->find()['Name'];
              $element['MinAgeGroupName'] = $min_agegroup_name;
              $element['MaxAgeGroupName'] = $max_agegroup_name;
                 
              array_push($list, $element);
            }
        
          $this->dataResult($list);
        } 
    }

    public function delete($seasonid, $itemid, $sex)
    {
        $this->checkauthorization();
        $result = Db::name('ctfc_seasonitem')->where('SeasonID', $seasonid)->where('ItemID', $itemid)->where('Sex', $sex)->delete();
        $this->affectedRowsResult($result);
    }

    public function update($seasonid, $itemid, $sex)
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data); 
        $seasonitem_data = array();
        $seasonitem_data['SeasonID'] = $data['SeasonID'];
        $seasonitem_data['ItemID'] = $data['ItemID'];
        $seasonitem_data['Sex'] = $data['Sex'];
        $seasonitem_data['MinAgeGroupID'] = $data['MinAgeGroupID'];
        $seasonitem_data['MaxAgeGroupID'] = $data['MaxAgeGroupID'];

        $result = Db::name('ctfc_seasonitem')->where('SeasonID', $seasonid)->where('ItemID', $itemid)->where('Sex', $sex)->update($seasonitem_data);
      
        $this->affectedRowsResult($result);
    }


}

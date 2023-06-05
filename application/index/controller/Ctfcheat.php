<?php

namespace app\index\controller;

require_once (__DIR__ . '/../../../thinkphp/library/think/db/Expression.php');


use think\Db;
use think\Session;
use think\Db\Expression;

class Ctfcheat extends Base
{
    const FIELD = 'ID,EventID,HeatNumber,LaneNumber,TeamName,ItemAgeGroupSex,Player1,Player2,Player3,Player4,Player5,Player6,Result,Note,IsSingle,HeatSize,ItemName,Gender,AgeGroupNumber,ItemPlayerID,ItemID';
    public function lists($itemid = null){
        if($itemid) {
            $list = Db::name('ctfc_heat_view')->where('ItemID', $itemid)->paginate(input('pagesize'));
        }else {
            $list = Db::name('ctfc_heat_view')->paginate(input('pagesize'));
        }
        $this->paginatedResult(
            $list->total(),
            $list->listRows(),
            $list->currentPage(),
            $list->items()
        );
    }

    // public function add()
    // {
    //     $this->checkauthorization();

    //     $data = request()->only(self::FIELD, 'post');
    //     $this->makeNull($data);
    //     $result = Db::name('ctfc_agegroup')->insert($data);
    //     $this->affectedRowsResult($result);
    // }

    // public function delete($id){
    //     $this->checkauthorization();

    //     $result = Db::name('ctfc_agegroup')->where('ID', $id)->delete();
    //     $this->affectedRowsResult($result);
    // }

 
    // public function update($id){
    //     $this->checkauthorization();

    //     $data = request()->only(self::FIELD, 'post');
    //     $this->makeNull($data);
    //     $result = Db::name('ctfc_agegroup')->where('ID', $id)->update($data);
    //     $this->affectedRowsResult($result);
    // }
}
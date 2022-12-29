<?php
/**
 * Created by PhpStorm.
 * User: Aven
 * Date: 2018/10/28
 * Time: 1:30
 */

namespace app\index\controller;



use think\Db;
use think\Session;

class Ctfcevent extends Base
{
    const FIELD = 'Name,IsSingle,IsTrack,HeatSize';

    public function add()
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        // $validator = validate('ctfc_item');
        // $result = $validator->check($data);
        // if (!$result){
        //     $this->affectedRowsResult(0);
        // }
        $result = Db::name('ctfc_item')->insert($data);
        $this->affectedRowsResult($result);
    }

    public function delete($id){
        $this->checkauthorization();

        $result = Db::name('ctfc_item')->where('ID', $id)->delete();
        $this->affectedRowsResult($result);
    }

    public function read($id){
        $result = Db::name('ctfc_item')->where('ID', $id)->find();
        $this->dataResult($result);
    }

    public function lists(){
        $pagesize = input('pagesize');
        $list = Db::name('ctfc_item')->paginate($pagesize);
        $this->paginatedResult(
            $list->total(),
            $list->listRows(),
            $list->currentPage(),
            $list->items()
          );
      }

    public function update($id){
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
//        $validator = validate('ctfc_item');
//        $result = $validator->check($data);
//        if (!$result){
//            $this->result(0);
//        }
        $result = Db::name('ctfc_item')->where('ID', $id)->update($data);
        $this->affectedRowsResult($result);
    }
}

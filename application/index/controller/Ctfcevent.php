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
    const FIELD = 'Name,Individual';

    public function add()
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        $validator = validate('Ctfc_event');
        $result = $validator->check($data);
        if (!$result){
            $this->affectedRowsResult(0);
        }
        $result = Db::name('ctfc_event')->insert($data);
        $this->affectedRowsResult($result);
    }

    public function delete($id){
        $this->checkauthorization();

        $result = Db::name('ctfc_event')->where('ID', $id)->delete();
        $this->affectedRowsResult($result);
    }

    public function read($id){
        $result = Db::name('ctfc_event')->where('ID', $id)->find();
        $this->dataResult($result);
    }

    public function lists(){
        $list = Db::name('ctfc_event')->paginate(input('pagesize'));
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
//        $validator = validate('Ctfc_event');
//        $result = $validator->check($data);
//        if (!$result){
//            $this->result(0);
//        }
        $result = Db::name('ctfc_event')->where('ID', $id)->update($data);
        $this->affectedRowsResult($result);
    }
}
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

class BbCompetition extends Base
{
    const FIELD = 'Name,Picture,Description';

    public function add()
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        $validator = validate('Bb_competition');
        $result = $validator->check($data);
        if (!$result){
            $this->affectedRowsResult(0);
        }
        $result = Db::name('bb_competition')->insert($data);
        $this->affectedRowsResult($result);
    }

    public function delete($id){
        $this->checkauthorization();

        $result = Db::name('bb_competition')->where('ID', $id)->delete();
        $this->affectedRowsResult($result);
    }

    public function read($id){
        if($this->jsonRequest()) {
            $result = Db::name('bb_competition')->where('ID', $id)->find();
            $this->dataResult($result);
        }else{
            $this->redirect('bbcompetition/'. $id .'/recentseason',302);
        }
    }

    public function lists(){
      $pagesize = input('pagesize');
      $list = Db::name('bb_competition')->paginate($pagesize);
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
//        $validator = validate('Bb_competition');
//        $result = $validator->check($data);
//        if (!$result){
//            $this->result(0);
//        }
        $result = Db::name('bb_competition')->where('ID', $id)->update($data);
        $this->affectedRowsResult($result);
    }
}

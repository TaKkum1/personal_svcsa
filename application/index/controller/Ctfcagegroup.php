<?php
/**
 * Created by PhpStorm.
 * User: Aven
 * Date: 2018/10/28
 * Time: 1:30
 */

namespace app\index\controller;

require_once (__DIR__ . '/../../../thinkphp/library/think/db/Expression.php');


use think\Db;
use think\Session;
use think\Db\Expression;

class Ctfcagegroup extends Base
{
    const FIELD = 'Name,MinAge,MaxAge';

    public function add()
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        $result = Db::name('ctfc_agegroup')->insert($data);
        $this->affectedRowsResult($result);
    }

    public function delete($id){
        $this->checkauthorization();

        $result = Db::name('ctfc_agegroup')->where('ID', $id)->delete();
        $this->affectedRowsResult($result);
    }

    public function lists($id=null){
        $list = Db::name('ctfc_agegroup')->paginate(input('pagesize'));
        if($id) $list = Db::name('ctfc_agegroup')->where('ID', $id)->paginate(input('pagesize'));
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
        $result = Db::name('ctfc_agegroup')->where('ID', $id)->update($data);
        $this->affectedRowsResult($result);
    }
}
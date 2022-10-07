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

class Authorization extends Base
{
    const FIELD = 'Username,Password,Level';

    public function login($username,$password)
    {
        $password = sha1(substr(md5($password),1,27).'svcsa');
        $result = Db::name('authorization')
            ->where('Username', $username)
            ->where('Password', $password)->find();
        if($result) {
            Session::set('username',$username);
            $this->jsonResult(1, ['login' => '1']);
        }
        else {
            $this->jsonResult(0, ['login' => '0']);
        }
    }

    public function logout()
    {
        if(Session::get('username')) {
            Session::delete('username');
            $this->jsonResult(1, ['logout' => '1']);
        }
        else {
            $this->jsonResult(0, ['logout' => '0']);
        }
    }

    public function status()
    {
        if(Session::get('username')) {
            $this->jsonResult(1, ['status' => '1']);
        }
        else {
            $this->jsonResult(1, ['status' => '0']);
        }
    }

    public function add()
    {
        $this->checkauthorization();


        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        $validator = validate('Authorization');
        $result = $validator->check($data);
        if (!$result){
            $this->affectedRowsResult(0);
        }
        $data['Password'] = sha1(substr(md5($data['Password']),1,27).'svcsa');
        $result = Db::name('authorization')->insert($data);
        $this->affectedRowsResult($result);
    }

    public function delete($id){
        $this->checkauthorization();


        $result = Db::name('authorization')->where('ID', $id)->delete();
        $this->affectedRowsResult($result);
    }

    public function read($id){
        $this->checkauthorization();
        $result = Db::name('authorization')->where('ID', $id)->find();
        $this->dataResult($result);
    }

    public function lists(){
        $this->checkauthorization();
        $pagesize = input('pagesize');
        $list = Db::name('authorization')->paginate($pagesize);
        $this->paginatedResult(
            $list->total(),
            $list->listRows(),            $list->currentPage(),
            $list->items()
        );
    }

    public function update($id){
        $this->checkauthorization();

        $data = request()->only(self::FIELD . ',OldPassword', 'post');
        $this->makeNull($data);

        if ((!isset($data['Username']) || !$data['Username']) && (!isset($data['Password']) || !$data['Password']) && (!isset($data['Level']) || '' === $data['Level'])){
            $this->affectedRowsResult(0);
        }
        $oldPassword = sha1(substr(md5($data['OldPassword']),1,27).'svcsa');
        $findOld = Db::name('authorization')
            ->where('ID', $id)
            ->where('Password',$oldPassword)
            ->find();
        if(!$findOld) $this->affectedRowsResult(0);

        $data_to_save = [];

        if (isset($data['Username']) && $data['Username']){
            $data_to_save['Username'] = $data['Username'];
        }

        if (isset($data['Password']) && $data['Password']){
            $data_to_save['Password'] = sha1(substr(md5($data['Password']),1,27).'svcsa');
        }

        if (isset($data['Level']) && $data['Level'] !== ''){
            $data_to_save['Level'] = $data['Level'];
        }

        $result = Db::name('authorization')->where('ID', $id)->update($data_to_save);
        $this->affectedRowsResult($result);
    }
}
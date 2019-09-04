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

class Ctfcmatch extends Base
{
    const FIELD = 'EventID,SeasonID,State,StartTime,Report,VideoSrc';

    public function add()
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        $validator = validate('CtfcMatch');
        $result = $validator->check($data);
        if (!$result){
            $this->affectedRowsResult(0);
        }
        $result = Db::name('ctfc_match')->insert($data);
        $this->affectedRowsResult($result);
    }

    public function setMatch()
    {
        $this->checkauthorization();

        $data = request()->only('PlayerIDs,MatchID', 'post');
        $playerIDs = $data['PlayerIDs'];
        $matchID = $data['MatchID'];

        if(!$matchID || !$playerIDs) return $this->affectedRowsResult(0);

        $playerIDarr = explode(',',$playerIDs);

        if(count($playerIDarr)==0) $this->affectedRowsResult(0);

        $result = Db::name('ctfc_match')->where('ID', $matchID )
            ->update(['PlayerIDs'=>$playerIDs]);

        foreach ($playerIDarr as $playerID) {
            $result += Db::name('ctfc_player')->where('ID', $playerID)
                ->update(['MatchID'=>$matchID]);
        }

        $this->affectedRowsResult($result);

    }

    public function delete($id){
        $this->checkauthorization();

        $result = Db::name('ctfc_match')->where('ID', $id)->delete();
        $this->affectedRowsResult($result);
    }

    public function read($matchid){
        $result = Db::name('ctfc_matchevent')->where('MatchID', $matchid)->find();
        if($this->jsonRequest()) {
            $this->dataResult($result);
        } else if(count($result)>0) {
            $this->headerAndFooter('ctfc');

            $this->view->assign('result',$result);

            return $this->view->fetch('ctfcmatch/report');
        }

notfound:
        header("HTTP/1.0 404 Not Found");
        die;
    }

    public function lists(){
        $list = Db::name('ctfc_matchevent')->paginate(input('pagesize'));
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
//        $validator = validate('CtfcMatch');
//        $result = $validator->check($data);
//        if (!$result){
//            $this->result(0);
//        }
        $result = Db::name('ctfc_match')->where('ID', $id)->update($data);
        $this->affectedRowsResult($result);
    }
}
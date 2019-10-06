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

class Ctfcplayer extends Base
{
    const FIELD = 'Name,Leader,Individual,PhotoSrc,Email,Tel,Wechat,Track,MatchID,Flag';

    public function add()
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        $validator = validate('Ctfc_player');
        $result = $validator->check($data);
        if (!$result){
            $this->affectedRowsResult(0);
        }
        $result = Db::name('ctfc_player')->insert($data);
        $this->affectedRowsResult($result);
    }

    public function delete($id){
        $this->checkauthorization();

        $result = Db::name('ctfc_player')->where('ID', $id)->where('Flag',1)->delete();
        $this->affectedRowsResult($result);
    }

    public function getapply($matchid)
    {
        $this->headerAndFooter('ctfc');
        return $this->view->fetch('ctfcplayer/apply');
    }

    public function apply($matchid)
    {
        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        if(!isset($data["MatchID"]))
            $data["MatchID"]=$matchid;
        $validator = validate('ctfc_player');
        $result = $validator->check($data);
        if (!$result){
            $this->affectedRowsResult(0);
        }
        $data["Flag"] = 0;
        $assetUrl = getAssetUploadUrl();

        $infophotosrc = request()->file('Photo')
            ->move( __DIR__ . $assetUrl);

        if($infophotosrc) {
            $data["PhotoSrc"] = $infophotosrc->getSaveName();
        }

        $result = Db::name('ctfc_player')->insert($data);

        if($this->jsonRequest())
            $this->affectedRowsResult($result);

        $this->headerAndFooter('ctfc');

        $applyresult='';
        if($result>0) $applyresult = '您的申请已提交，审核后将会给您发邮箱或者短信通知，请关注！';
        $this->view->assign('applyresult',$applyresult);
        return $this->view->fetch('ctfcplayer/applyres');
    }

    public function read($id){
        $result = Db::name('ctfc_playermatch')->where('ID', $id)->find();
        if ($this->jsonRequest())
            $this->dataResult($result);

        if(!$result) goto notfound;

        $this->headerAndFooter('player');


        $seasonid = $result['SeasonID'];

        $players = Db::name('ctfc_playermatch')
            ->where('SeasonID', $seasonid)->order('ID desc')
            ->limit(0,10)->select();

        $this->view->assign('player',$result);
        $this->view->assign('players',$players);

        return $this->view->fetch('player/ctfcread');

notfound:
        header("HTTP/1.0 404 Not Found");
        die;
    }

    public function lists(){
        $list = Db::name('ctfc_playermatch');

        if(input('seasonid'))
            $list = $list->where('seasonid',input('seasonid'));

        $list = $list->paginate(input('pagesize'));
        if ($this->jsonRequest())
            $this->paginatedResult(
                $list->total(),
                $list->listRows(),
                $list->currentPage(),
                $list->items()
            );


//        if(count($list->items())==0) goto notfound;

        $this->headerAndFooter('player');

        $this->view->assign('playertitle','田径比赛');
        $this->view->assign('playercategory','ctfc');
        $this->view->assign('pagerender',$list->render());
        $this->view->assign('players',$list->items());

        return $this->view->fetch('player/lists');


notfound:
        header("HTTP/1.0 404 Not Found");
        die;

    }

    public function update($id){
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
//        $validator = validate('Ctfc_player');
//        $result = $validator->check($data);
//        if (!$result){
//            $this->result(0);
//        }
        $result = Db::name('ctfc_player')->where('ID', $id)->update($data);
        $this->affectedRowsResult($result);
    }


    public function passApplication()
    {
        $this->checkauthorization();

        $data = request()->only('PlayerIDs,Passed', 'post');
        $playerIDs = urldecode($data['PlayerIDs']);
        $passed = isset($data['Passed'])?boolval($data['Passed']):true;

        $result = 0;
        $playerIDsarr = explode(',',$playerIDs);

        if($passed) {
            foreach ($playerIDsarr as $playerID) {
                $result += Db::name('ctfc_player')->where('ID', $playerID)
                    ->update(['Flag'=>1]);
            }
        } else {
            foreach ($playerIDsarr as $playerID) {
                $result += Db::name('ctfc_player')->where('ID', $playerID)
                    ->delete();
            }
        }

        $this->affectedRowsResult($result);
    }
}
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
    const APPLY_PLAYER_FIELD = 'Name,Birthday,Email,Sex,PhotoSrc';
    const FIELD = 'Name,Sex,Birthday,PhotoSrc,Email';

    public function add()
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        $validator = validate('ctfc_player');
        // $result = $validator->check($data);
        // if (!$result){
        //     $this->affectedRowsResult(0);
        // }
        $result = Db::name('ctfc_player')->insert($data);
        $this->affectedRowsResult($result);
    }

    public function delete($id){
        $this->checkauthorization();

        $result = Db::name('ctfc_player')->where('ID', $id)->where('ID', $id)->delete();
        $this->affectedRowsResult($result);
    }

    public function getapply()
    {
        $this->headerAndFooter('ctfc');
        return $this->view->fetch('ctfcplayer/apply');
    }

    public function apply()
    {
        $data = request()->only(self::APPLY_PLAYER_FIELD, 'post');
        $this->makeNull($data);

        $player = array();
        $player["Name"] = $data["Name"];
        $player["Birthday"] = $data["Birthday"];
        $player["Email"] = $data["Email"];
        $player["Sex"] = $data["Sex"];

        // Check if player with the same name and birthday already exists
        $existingPlayer = Db::name('ctfc_player')
        ->where('Name', $player["Name"])
        ->where('Birthday', $player["Birthday"])
        ->find();

        if ($existingPlayer) {
            // Player with the same name and birthday already exists
            $this->headerAndFooter('ctfc');
            $existingPlayerInfo = "<div class='card text-left'><div class='card-body text-left'><small><br>姓名: " . $existingPlayer["Name"] . "<br>生日: " . $existingPlayer["Birthday"] . "<br>Email: " . $existingPlayer["Email"]."</small></div></div>";
            $applyresult = '该用户已存在！如有误，请联系管理员(svcba.svcsa@gmail.com)修改。<br>'.$existingPlayerInfo;
            $this->view->assign('applyresult', $applyresult);
            return $this->view->fetch('ctfcplayer/applyres');
        }

        $validator = validate('ctfc_player');
        $result = $validator->check($player);
        if (!$result){
            $this->affectedRowsResult(0);
        }

        $player["PhotoSrc"] = "";
        $assetUrl = getAssetUploadUrl();
        $infophotofile = request()->file('Photo');

        if($infophotofile)
            $player["PhotoSrc"] = $infophotofile->move(__DIR__ . $assetUrl)
                ->getSaveName();

        $result = Db::name('ctfc_player')->insert($player);

        $this->headerAndFooter('ctfc');

        $applyresult='';
        if($result > 0) 
            $applyresult = '您的申请已提交，审核后将会给您发邮箱或者短信通知，请关注！';
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

    public function lists($seasonid = null, $teamid = null, $competitionid = null)
    {
        // Get some basic info from input.
        $pagesize = (!input('pagesize')) ? 100 : input('pagesize');
        if (!$seasonid && input('seasonid')) $seasonid = input('seasonid');
        if (!$teamid && input('teamid')) $teamid = input('teamid');
        if (!$competitionid && input('competitionid')) {
          $competitionid = input('CompetitionID');
        }
        if (input('freeagant')) {
          // List all the free agents for a particular season.
          $sql =
              'select * '.
              'from ctfc_player '.
              'where ctfc_player.ID not in ('.
                  'select distinct ctfc_seasonteamplayer.PlayerID '.
                  'from ctfc_seasonteamplayer '.
                  'where ctfc_seasonteamplayer.SeasonID='.strval($seasonid).')'.
              'order by Name asc';
          $list = Db::query($sql);
          if ($this->jsonRequest())
            $this->dataResult($list);
          //$list = Db::name('ctfc_player')->order('Name asc');
        } else if (input('all')) {
          // List all players in the database.
          $list = Db::name('ctfc_player');
          $list = $list->paginate($pagesize, false, [
            'query' => input('param.'),
          ]);
          if ($this->jsonRequest())
            $this->paginatedResult($list->total(), $pagesize, $list->currentPage(), $list->items());
        } else {
          // List players of a particular season.
          $list = Db::name('ctfc_seasonplayer_view')->order('PlayerName asc');

          if ($competitionid and $seasonid)
              $list = $list->where('seasonid', $seasonid);
          else if ($teamid)
              $list = $list->where('teamid', $teamid);
          else if ($competitionid)
              $list = $list->where('CompetitionID', $competitionid);
          else if (input('playerids'))
              $list = $list->whereIn('PlayerID',
                  explode(',', input('playerids')));

          $list = $list->paginate($pagesize, false, [
            'query' => input('param.'),
          ]);

          if ($this->jsonRequest())
            $this->paginatedResult($list->total(), $pagesize, $list->currentPage(), $list->items());
        }

        $this->headerAndFooter('player');

        $playertitle = '';
        $CompetitionName = Db::name('ctfc_competition')
            ->where('ID', $competitionid)
            ->find()['Name'];
        if ($seasonid && count($list->items())>0) {
            $playertitle = $list->items()[0]['SeasonName'];
          }
        else if ($teamid && count($list->items())>0) {
            $playertitle = Db::name('ctfc_team')
                ->where('ID', $teamid)
                ->find()['Name'];
        }
        else
            $playertitle = '优秀';

        $this->view->assign('playertitle', $playertitle);
        $this->view->assign('CompetitionName', $CompetitionName);
        $this->view->assign('SeasonID', $seasonid);
        $this->view->assign('pagerender', $list->render());
        $this->view->assign('players', $list->items());

        return $this->view->fetch('player/lists');


        notfound:
        header("HTTP/1.0 404 Not Found");
        die;
    }

    public function update($id){
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        // $validator = validate('Ctfc_player');
        // $result = $validator->check($data);
        // if (!$result){
        //     $this->result(0);
        // }
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
                    ->update(['Approval'=>1]);
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
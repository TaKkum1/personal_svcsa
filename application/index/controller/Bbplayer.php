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



class Bbplayer extends Base
{
    const FIELD = 'Name,Number,Birth,Height,Weight,PhotoSrc,Email,TeamID,SeasonID,Sex';

    public function add($seasonid = null)
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        if (!isset($data["SeasonID"]))
            $data["SeasonID"] = $seasonid;

        if (!isset($data["Birth"]))
            $data["Birth"] = strtotime($data["Birth"], 'MM-dd');

        if (!isset($data["PhotoSrc"])) {
            $file = request()->file('file');
            if (!$file) $this->affectedRowsResult(0);
            $data["PhotoSrc"] = $file
                ->move(__DIR__ . '/../../../public/uploads')->getSaveName();
        }

        $validator = validate('Bb_player');
        $result = $validator->check($data);
        if (!$result) {
            $this->affectedRowsResult(0);
        }
        $result = Db::name('bb_player')->insert($data);
        $this->affectedRowsResult($result);
    }

    public function setTeam()
    {
        $this->checkauthorization();

        $data = request()->only('PlayerIDs,TeamID,PlayerNumbers', 'post');
        $playerIDs = urldecode($data['PlayerIDs']);
        $playerNumbers = urldecode($data['PlayerNumbers']);
        $teamID = $data['TeamID'];

        if (!$teamID) return $this->affectedRowsResult(0);


        $playerIDarr = array_filter(explode(',', $playerIDs),'arrfiltrfun');
        $playerNumberarr = array_filter(explode(',', $playerNumbers),'arrfiltrfun');
//        $newPlayerIDs = '';
        $team = Db::name('bb_team')->where('ID', $teamID)->find();


        $result = Db::name('bb_player')->where('TeamID', $teamID)
            ->update(['TeamID' => -$teamID]);

        $playerIDs = '';
        $playerNumbers = '';

        foreach ($playerIDarr as $k => $playerID) {

            $playerdata = Db::name('bb_player')->where('ID', $playerID)
                ->find();

            if($playerdata['TeamID']==0) {
                $playerdata['ID'] = null;
                $playerdata['TeamID'] = $teamID;
                $playerdata['Number'] = $playerNumberarr[$k];
                $playerdata['SeasonID'] = $team['SeasonID'];


                $result += Db::name('bb_player')->insert($playerdata);
                $playerIDs = $playerIDs . Db::name('bb_player')->getLastInsID();
            } else {
                $result += Db::name('bb_player')->where('ID', $playerID)
                    ->update(['TeamID' => $teamID, 'Number'=>$playerNumberarr[$k]]);
                $playerIDs = $playerIDs . $playerID;
            }

            $playerNumbers = $playerNumbers . $playerNumberarr[$k];

            if($k != count($playerIDarr)-1) {
                $playerIDs = $playerIDs . ',';
                $playerNumbers = $playerNumbers . ',';
            }

        }

        $result += Db::name('bb_player')->where('TeamID', -$teamID)->delete();

        $result += Db::name('bb_team')->where('ID', $teamID)
            ->update(['PlayerIDs' => $playerIDs, 'PlayerNumbers' => $playerNumbers]);

        $this->affectedRowsResult($result);

    }

    public function delete($id)
    {
        $this->checkauthorization();

        $result = Db::name('bb_player')->where('ID', $id)->delete();
        $this->affectedRowsResult($result);
    }


    public function read($id)
    {
        $result = Db::name('bb_playerteam')->where('PlayerID', $id)->find();
        if ($this->jsonRequest())
            $this->dataResult($result);

        if (!$result) goto notfound;

        $this->headerAndFooter('player');

//        $playerage = getAge(strtotime($result['Birth']));

        $seasonid = $result['SeasonID'];

        $players = Db::name('bb_playerteam')
            ->where('SeasonID', $seasonid)->order('PlayerID desc')
            ->limit(0, 10)->select();

        $this->view->assign('player', $result);
//        $this->view->assign('playerage',$playerage);
        $this->view->assign('players', $players);


        return $this->view->fetch('player/bbread');

        notfound:
        header("HTTP/1.0 404 Not Found");
        die;
    }

    public function lists($seasonid = null, $teamid = null, $competitionid = null)
    {
        $pagesize = (!input('pagesize')) ? 100 : input('pagesize');
        $list = Db::name('bb_playerteam')->order('PlayerID asc');

        if (!$seasonid && input('seasonid')) $seasonid = input('seasonid');
        if (!$teamid && input('teamid')) $teamid = input('teamid');
        if (!$competitionid && input('competitionid')) {
          $competitionid = input('CompetitionID');
        }
        if ($competitionid and $seasonid)
            $list = $list->where('seasonid', $seasonid);
        else if ($teamid)
            $list = $list->where('teamid', $teamid);
        else if ($competitionid)
            $list = $list->where('CompetitionID', $competitionid);
        else if (input('freeagant'))
            $list = $list->where('teamid', 0)->order('PlayerName asc');
        else if (input('playerids'))
            $list = $list->whereIn('PlayerID',
                explode(',', input('playerids')));

        $list = $list->paginate($pagesize, false, [
          'query' => input('param.'),
        ]);

        if ($this->jsonRequest())
            $this->paginatedResult($list->total(), $pagesize, $list->currentPage(), $list->items());



        $this->headerAndFooter('player');

        $playertitle = '';
        $CompetitionName = $list->items()[0]['CompetitionName'];
        if ($seasonid && count($list->items())>0) {
            $playertitle = $list->items()[0]['SeasonName'];
          }
        else if ($teamid && count($list->items())>0)
            $playertitle = $list->items()[0]['TeamName'];
        else
            $playertitle = '优秀';

        $this->view->assign('playertitle', $playertitle);
        $this->view->assign('CompetitionName', $CompetitionName);
        $this->view->assign('pagerender', $list->render());
        $this->view->assign('players', $list->items());

        return $this->view->fetch('player/lists');


        notfound:
        header("HTTP/1.0 404 Not Found");
        die;
    }

    public function update($id)
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        $validator = validate('Bb_player');
//        $result = $validator->check($data);
//        if (!$result){
//            $this->result(0);
//        }
        if (!isset($data["PhotoSrc"]) || !$data["PhotoSrc"]) {
            $file = request()->file('file');
            if (!$file) $this->affectedRowsResult(0);
            $data["PhotoSrc"] = $file
                ->move(__DIR__ . '/../../../public/uploads')->getSaveName();
        }
        $result = Db::name('bb_player')->where('ID', $id)->update($data);
        $this->affectedRowsResult($result);
    }

    public function copyToSeason($newSeasonID)
    {
        $data = request()->only('OldSeasonID,TeamID,PlayerNumbers', 'post');
        $copySQL = 'insert into bb_player (Name,Number,Birth,Height,Weight,PhotoSrc,Email,SeasonID,Sex)';
        $copySQL = $copySQL . 'select Name,Number,Birth,Height,Weight,PhotoSrc,Email,Sex' . $newSeasonID;
        $copySQL = $copySQL . ' from bb_player where SeasonID = ' . $this->request('OldSeasonID');

    }
}

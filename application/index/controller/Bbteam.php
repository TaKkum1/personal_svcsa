<?php
/**
 * Created by PhpStorm.
 * User: Aven
 * Date: 2018/10/28
 * Time: 1:30
 */

namespace app\index\controller;

require_once(__DIR__ . '/../../../thinkphp/library/think/db/Expression.php');

use think\Db;
use think\Session;

class Bbteam extends Base
{
    const FIELD = 'Name,ShortName,Captain,Email,Tel,Wechat,LogoSrc,PhotoSrc,Wins,
    Losses,ScoreInBoard,Flag,SeasonID,Flag,PlayerIDs,PlayerNumbers,Description';

    public function add($seasonid = null)
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        if (!isset($data["SeasonID"]))
            $data["SeasonID"] = $seasonid;
        $validator = validate('Bb_team');
        $result = $validator->check($data);
        if (!$result) {
            $this->affectedRowsResult(0);
        }
        $result = Db::name('bb_team')->insert($data);
        $this->affectedRowsResult($result);
    }

    public function getapply($seasonid)
    {
        $this->headerAndFooter('competition');

        $competitionseason = Db::name('bb_competitionseason')
            ->where('SeasonID', $seasonid)->find();
        if (!$competitionseason) goto notfound;

        $playersinseason = Db::query("SELECT * FROM bb_player WHERE" .
            "(TeamID IS NULL or TeamID=0) ". "ORDER BY Name ASC ");


        $this->view->assign('playersinseason', $playersinseason);
        $this->view->assign('competitionseason', $competitionseason);

        return $this->view->fetch('bbteam/apply');

        notfound:
        header("HTTP/1.0 404 Not Found");
        die;
    }

    public function apply($seasonid)
    {
        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        if (!isset($data["SeasonID"]))
            $data["SeasonID"] = $seasonid;
        $validator = validate('Bb_team');
        $result = $validator->check($data);
        if (!$result) {
            $this->affectedRowsResult(0);
        }
        $data["Flag"] = 0;
        $data["LogoSrc"] = "";
        $data["PhotoSrc"] = "";

        $infologofile = request()->file('Logo');
        $infophotofile = request()->file('Photo');

        if($infologofile)
            $data["LogoSrc"] = $infologofile->move(__DIR__ . '/../../../public/uploads')
                ->getSaveName();

        if($infophotofile)
            $data["PhotoSrc"] = $infophotofile->move(__DIR__ . '/../../../public/uploads')
                ->getSaveName();

        $result = Db::name('bb_team')->insert($data);


        if ($this->jsonRequest())
            $this->affectedRowsResult($result);

        $this->headerAndFooter('competition');

        $applyresult = '';
        if ($result > 0) $applyresult = '您的球队申请已提交，审核后将会给您发邮箱或者短信通知，请关注！';
        $this->view->assign('applyresult', $applyresult);
        return $this->view->fetch('bbteam/applyres');
    }

    public function delete($id)
    {
        $this->checkauthorization();

        $result = Db::name('bb_team')->where('ID', $id)->delete();
        $this->affectedRowsResult($result);
    }

    public function read($id)
    {
        $result = Db::name('bb_team')->where('ID', $id)->find();
        if ($this->jsonRequest()) {

            $this->dataResult($result);
        }

        $seasonid = $result['SeasonID'];

        $thisseason = Db::name('bb_competitionseason')
            ->where('SeasonID', $seasonid)
            ->find();
        $competitionid = $thisseason['CompetitionID'];
        if (!$competitionid) goto  notfound;


        if ($competitionid <= 2)
            $this->headerAndFooter('competition' . $competitionid);
        else
            $this->headerAndFooter('competition');


        $team = Db::name('bb_team')->where('ID', $id)->find();

        $playerIDarr = explode(',', $team['PlayerIDs']);

        $this->view->assign('playercount', count($playerIDarr));


        $this->view->assign('thisseason', $thisseason);
        $this->view->assign('team', $team);

        return $this->view->fetch('bbteam/read');

        notfound:
        header("HTTP/1.0 404 Not Found");
        die;
    }

    public function lists($seasonid = null)
    {


        if ($this->jsonRequest()) {
            $list = Db::name('bb_team');
            if ($seasonid) $list = $list->where('seasonid', $seasonid);
            $list = $list->paginate(input('pagesize'));
            $this->paginatedResult(
                $list->total(),
                $list->listRows(),
                $list->currentPage(),
                $list->items()
            );
        } else if ($seasonid) {
            $competitionid = Db::name('bb_competitionseason')
                ->where('SeasonID', $seasonid)
                ->find()['CompetitionID'];
            if (!$competitionid) goto  notfound;


            if ($competitionid <= 2)
                $this->headerAndFooter('competition' . $competitionid);
            else
                $this->headerAndFooter('competition');

            $exp = new \think\Db\Expression('field(SeasonID,' . $seasonid . '),StartTime DESC');
            $seasons = Db::name('bb_competitionseason')->where('CompetitionID', $competitionid)
                ->order($exp)->select();
            $seasons = array_reverse($seasons);
            $otherseasons = array_slice($seasons, 1);
            $bbteams = Db::name('bb_team')->where('seasonid', $seasonid)
                ->where('Flag', '<>', 0)->select();


            $this->view->assign('thisseason', $seasons[0]);
            $this->view->assign('otherseasons', $otherseasons);
            $this->view->assign('bbteams', $bbteams);

            return $this->view->fetch('bbteam/lists');


        }


        notfound:
        header("HTTP/1.0 404 Not Found");
        die;
    }

    public function rank($seasonid)
    {
        $competitionid = Db::name('bb_competitionseason')
            ->where('SeasonID', $seasonid)
            ->find()['CompetitionID'];
        if (!$competitionid) goto  notfound;


        if ($competitionid <= 2)
            $this->headerAndFooter('competition' . $competitionid);
        else
            $this->headerAndFooter('competition');

        $exp = new \think\Db\Expression('field(SeasonID,' . $seasonid . '),StartTime DESC');
        $seasons = Db::name('bb_competitionseason')->where('CompetitionID', $competitionid)
            ->order($exp)->select();
        $seasons = array_reverse($seasons);
        $otherseasons = array_slice($seasons, 1);
        $this->view->assign('otherseasons', $otherseasons);

        // $teams = Db::name('bb_team')
        //     ->where('SeasonID', $seasonid)
        //     ->order('Wins desc, ScoreInBoard desc')
        //     ->select();
        $SQL_QUERY_STR = 
            'SELECT * FROM 
                bb_team LEFT JOIN 
                (SELECT TeamAID AS TeamID, ScoreTeamA - ScoreTeamB AS Score FROM bb_match UNION SELECT TeamBID AS TeamID, ScoreTeamB - ScoreTeamA AS Score FROM bb_match) foo 
                ON bb_team.ID = foo.TeamID
                WHERE SeasonID ='.$seasonid.'
                ORDER BY Wins DESC, Score DESC';

        $teams = Db::query($SQL_QUERY_STR);

        $this->view->assign('thisseason', $seasons[0]);
        $this->view->assign('otherseasons', $otherseasons);
        $this->view->assign('teamrankresult', $teams);

        return $this->view->fetch('bbteam/rank');

        notfound:
        header("HTTP/1.0 404 Not Found");
        die;
    }

    public function update($id)
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
//        $validator = validate('Bb_team');
//        $result = $validator->check($data);
//        if (!$result){
//            $this->result(0);
//        }
        $result = Db::name('bb_team')->where('ID', $id)->update($data);
        $this->affectedRowsResult($result);
    }

    public function passApplication()
    {
        $this->checkauthorization();

        $data = request()->only('TeamIDs,Passed', 'post');
        $teamIDs = urldecode($data['TeamIDs']);
        $passed = isset($data['Passed']) ? boolval($data['Passed']) : true;

        $result = 0;
        $teamIDsarr = explode(',', $teamIDs);
        $emailRes = 0;

        if ($passed) {
            foreach ($teamIDsarr as $teamID) {
                $team = Db::name('bb_team')->where('ID', $teamID)->find();

                if($team['Flag']==1) continue;

                $playerIDarr = array_filter(explode(',', $team['PlayerIDs']),'arrfiltrfun');
                $playerNumberarr = array_filter(explode(',', $team['PlayerNumbers']),'arrfiltrfun');

                $playerIDs = '';
                $playerNumbers = '';

                foreach ($playerIDarr as $k => $playerID) {
                    $playerdata = Db::name('bb_player')->where('ID', $playerID)
                        ->find();

                    $playerdata['ID'] = null;
                    $playerdata['TeamID'] = $teamID;
                    $playerdata['Number'] = $playerNumberarr[$k];
                    $playerdata['SeasonID'] = $team['SeasonID'];

                    $result += Db::name('bb_player')->insert($playerdata);

                    $playerIDs = $playerIDs . Db::name('bb_player')->getLastInsID();
                    $playerNumbers = $playerNumbers . $playerNumberarr[$k];

                    if($k != count($playerIDarr)-1) {
                        $playerIDs = $playerIDs . ',';
                        $playerNumbers = $playerNumbers . ',';
                    }

                }

                $result += Db::name('bb_team')->where('ID', $teamID)
                    ->update(['Flag' => 1, 'PlayerIDs' => $playerIDs,
                        'PlayerNumbers' => $playerNumbers]);

//                $emailret = sendEmail([[
//                    'user_email' => $team['Email'],
//                    'content' => "<h1>SVCSA球队申请成功</h1><p>您好，您的球队" . $team['ShortName'] . "已经通过审核。请访问SVCSA.org查看吧。</p>"]]);

                if (isset($emailret['code']) && $emailret['code'] == 1)
                    $emailRes++;

            }
        } else {
            foreach ($teamIDsarr as $teamID) {
                $result += Db::name('bb_team')->where('ID', $teamID)
                    ->delete();
            }
        }

        $this->jsonResult(0, ['affectedRows' => $result, 'affectedEmails' => $emailRes]);
//        $this->affectedRowsResult($result);
    }


    public function setTeam()
    {
        $this->checkauthorization();

        $data = request()->only('PlayerIDs,TeamID', 'post');
        $playerIDs = urldecode($data['PlayerIDs']);
        $teamID = $data['TeamID'];

        if (!$teamID || $playerIDs == null) return $this->affectedRowsResult(0);


        $playerIDarr = explode(',', $playerIDs);

        $oldplayerIDs = Db::name('bb_team')->where('ID', $teamID)->find()['PlayerIDs'];
        $oldplayerIDarr = explode(',', $oldplayerIDs);

        $result = 0;

        foreach ($oldplayerIDarr as $playerID) {
            $result += Db::name('bb_player')->where('ID', $playerID)
                ->update(['TeamID' => '']);
        }


        if (count($playerIDarr) == 0) $this->affectedRowsResult(0);

        $result = Db::name('bb_team')->where('ID', $teamID)
            ->update(['PlayerIDs' => $playerIDs]);

        foreach ($playerIDarr as $playerID) {
            $result += Db::name('bb_player')->where('ID', $playerID)
                ->update(['TeamID' => $teamID]);
        }

        $this->affectedRowsResult($result);

    }
}
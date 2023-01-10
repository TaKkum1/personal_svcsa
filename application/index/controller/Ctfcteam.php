<?php
/**
 * Created by Yu Lu.
 * Date: Dec 28, 2022
 * Time: 14:30
 */

namespace app\index\controller;



use think\Db;
use think\Session;

class Ctfcteam extends Base
{
    const FIELD = 'Name,ShortName,LogoSrc,PhotoSrc,,Description,CaptainName,CaptainEmail,CaptainPhone';

    public function add()
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        $validator = validate('ctfc_team');
        $result = Db::name('ctfc_team')->insert($data);
        $this->affectedRowsResult($result);
    }

    public function delete($id){
        $this->checkauthorization();

        $result = Db::name('ctfc_team')->where('ID', $id)->where('ID', $id)->delete();
        $this->affectedRowsResult($result);
    }

    public function getapply($matchid)
    {
        $this->headerAndFooter('ctfc');
        return $this->view->fetch('ctfcteam/apply');
    }

    public function apply($matchid)
    {
        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        if(!isset($data["MatchID"]))
            $data["MatchID"]=$matchid;
        $validator = validate('ctfc_team');
        $result = $validator->check($data);
        if (!$result){
            $this->affectedRowsResult(0);
        }
        $data["Flag"] = 0;
        $assetUrl = getAssetUploadUrl();

        $infophotosrc = request()->file('Photo')
            ->move( __DIR__ . $assetUrl);

        if($infophotosrc) {
            $data["LogoSrc"] = $infophotosrc->getSaveName();
        }

        $result = Db::name('ctfc_team')->insert($data);

        if($this->jsonRequest())
            $this->affectedRowsResult($result);

        $this->headerAndFooter('ctfc');

        $applyresult='';
        if($result>0) $applyresult = '您的申请已提交，审核后将会给您发邮箱或者短信通知，请关注！';
        $this->view->assign('applyresult',$applyresult);
        return $this->view->fetch('ctfcteam/applyres');
    }

    public function read($id){
        $result = Db::name('ctfc_team')->where('ID', $id)->find();
        if ($this->jsonRequest())
            $this->dataResult($result);

        if(!$result) goto notfound;

        $this->headerAndFooter('team');


        $seasonid = $result['SeasonID'];

        $teams = Db::name('ctfc_team')
            ->where('teamID', $teams)->order('ID desc')
            ->limit(0,10)->select();

        $this->view->assign('team',$result);
        $this->view->assign('teams',$teams);

        return $this->view->fetch('team/ctfcread');

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
              'from ctfc_team '.
              'where ctfc_team.ID not in ('.
                  'select distinct ctfc_team.TeamID '.
                  'from ctfc_seasonteam '.
                  'where ctfc_seasonteam.SeasonID='.strval($seasonid).')'.
              'order by Name asc';
          $list = Db::query($sql);
          if ($this->jsonRequest())
            $this->dataResult($list);
        } else if (input('all')) {
          // List all players in the database.
          $list = Db::name('ctfc_team');
          $list = $list->paginate($pagesize, false, [
            'query' => input('param.'),
          ]);
          if ($this->jsonRequest())
            $this->paginatedResult($list->total(), $pagesize, $list->currentPage(), $list->items());
        } else {
          // List players of a particular season.
          $list = Db::name('ctfc_seasonteam_view')->order('TeamName asc');

          if ($competitionid and $seasonid)
              $list = $list->where('seasonid', $seasonid);
          else if ($teamid)
              $list = $list->where('teamid', $teamid);
          else if ($competitionid)
              $list = $list->where('CompetitionID', $competitionid);
          else if (input('teamids'))
              $list = $list->whereIn('TeamID',
                  explode(',', input('Teamids')));

          $list = $list->paginate($pagesize, false, [
            'query' => input('param.'),
          ]);

          if ($this->jsonRequest())
            $this->paginatedResult($list->total(), $pagesize, $list->currentPage(), $list->items());
        }

        $this->headerAndFooter('Team');

        $teamtitle = '';
        $CompetitionName = Db::name('ctfc_competition')
            ->where('ID', $competitionid)
            ->find()['Name'];
        if ($seasonid && count($list->items())>0) {
            $teamtitle = $list->items()[0]['SeasonName'];
          }
        else if ($teamid && count($list->items())>0) {
            $teamtitle = Db::name('ctfc_team')
                ->where('ID', $teamid)
                ->find()['Name'];
        }
          $playertitle = '优秀';

        $this->view->assign('teamtitle', $teamtitle);
        $this->view->assign('CompetitionName', $CompetitionName);
        $this->view->assign('SeasonID', $seasonid);
        $this->view->assign('pagerender', $list->render());
        $this->view->assign('team', $list->items());

        return $this->view->fetch('team/lists');


        notfound:
        header("HTTP/1.0 404 Not Found");
        die;
    }

    public function update($id){
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data); 
        $result = Db::name('ctfc_team')->where('ID', $id)->update($data);
        $this->affectedRowsResult($result);
    }


    public function passApplication()
    {
        $this->checkauthorization();

        $data = request()->only('TeamIDs,Passed', 'post');
        $TeamIDs = urldecode($data['TeamIDs']);
        $passed = isset($data['Passed'])?boolval($data['Passed']):true;

        $result = 0;
        $TeamIDsarr = explode(',',$teamIDs);

        if($passed) {
            foreach ($teamIDsarr as $teamID) {
                $result += Db::name('ctfc_team')->where('ID', $teamID)
                    ->update(['Flag'=>1]);
            }
        } else {
            foreach ($teamIDsarr as $teamID) {
                $result += Db::name('ctfc_team')->where('ID', $teamID)
                    ->delete();
            }
        }

        $this->affectedRowsResult($result);
    }
}
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
    const FIELD = 
        'TeamID,Name,ShortName,CaptainName,CaptainEmail,CaptainPhone,Description,LogoSrc,PhotoSrc';

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

    public function getapply($seasonid)
    {
        $this->headerAndFooter('ctfc');
        $season = Db::name('ctfc_season')
            ->where('ID', $seasonid)->find(); 
            if (!$season) goto notfound;
        $this->view->assign('season', $season);

        $teams = Db::name('ctfc_team')->select();
        $this->view->assign('teams', $teams);

        return $this->view->fetch('ctfcteam/apply');

        notfound:
        header("HTTP/1.0 404 Not Found");
        die;
    }

    public function apply($seasonid)
    {
        $this->headerAndFooter('ctfc');
        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);

        // recapcha validation
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_secret = '6Let_GUpAAAAAH7wc4AWDM7oPeRqBfHjaME0QNOU'; // Secret key
        $recaptcha_response = $_POST['recaptchaResponse']; // Response from reCAPTCHA server, added to the form during processing
        $recaptcha = file_get_contents($recaptcha_url.'?secret='.$recaptcha_secret.'&response='.$recaptcha_response); // Send request to the server
        $recaptcha = json_decode($recaptcha); // Decode the JSON response
        echo json_encode($recaptcha);

        // 未通过人机验证
        if ($recaptcha->success == false) {
          $this->headerAndFooter('competition');
          $applyresult = '您的申请未通过人机验证，请重试或联系管理员！';
          $this->view->assign('applyresult', $applyresult);
          return $this->view->fetch('ctfcteam/applyres');
        }

        // If the team is new, insert it into table ctfc_team
        if ($data["TeamID"] === 0) {
            $team = array();
            $team["Name"] = $data["Name"];
            $team["ShortName"] = $data["ShortName"];
            $team["CaptainName"] = $data["CaptainName"];
            $team["CaptainEmail"] = $data["CaptainEmail"];
            $team["CaptainPhone"] = $data["CaptainPhone"];
            $team["Description"] = $data["Description"];
    
            $validator = validate('ctfc_team');
            $result = $validator->check($data, []);
            if (!$result){
                $this->affectedRowsResult(0);
                goto error;
            }
    
            $team["LogoSrc"] = "";
            $team["PhotoSrc"] = "";
            $assetUrl = getAssetUploadUrl();
            $infologofile = request()->file('Logo');
            $infophotofile = request()->file('Photo');
    
            if($infologofile)
                $team["LogoSrc"] = $infologofile->move(__DIR__ . $assetUrl)
                    ->getSaveName();
    
            if($infophotofile)
                $team["PhotoSrc"] = $infophotofile->move(__DIR__ . $assetUrl)
                    ->getSaveName();
    
            $check_duplicates = Db::name('ctfc_team')->where("Name", $team["Name"])->find();
            if ($check_duplicates > 0) {
                goto duplicates;
            }
            $result = Db::name('ctfc_team')->insert($team);
        }

        // Update ctfc_seasonteam
        $seasonteam = array();
        $row = Db::name('ctfc_team')->where("Name", $data["Name"])->find();
        
        $teamid = $row['ID'];
        $seasonteam["TeamID"] = $teamid;
        $seasonteam["SeasonID"] = $seasonid;
        $seasonteam["Approve"] = 0;

        $seasonteam_result = Db::name('ctfc_seasonteam')->insert($seasonteam);

        if($this->jsonRequest())
            $this->affectedRowsResult($result + $seasonteam_result);

        $applyresult='';
        if($seasonteam_result > 0) {
            $applyresult = '您的申请已提交，审核后将会给您发邮箱或者短信通知，请关注！';
        }
        $this->view->assign('applyresult',$applyresult);
        return $this->view->fetch('ctfcteam/applyres');

    error:
        $applyresult = '无法组建队伍，请重新创建队伍';
        $this->view->assign('applyresult',$applyresult);
        return $this->view->fetch('ctfcteam/applyres');

    duplicates:
        $applyresult = '队伍已經存在，无法组建队伍';
        $this->view->assign('applyresult',$applyresult);
        return $this->view->fetch('ctfcteam/applyres');
        
    }

    public function read($id){ 
        if ($this->jsonRequest()) {
            $result = Db::name('ctfc_team')->where('ID', $id)->find();
            $this->dataResult($result);
        }

        $result = Db::name('ctfc_seasonteam')->where('TeamID', $id)->find();
        $seasonid = $result['SeasonID'];

        $thisseason = Db::name('ctfc_season')
            ->where('ID', $seasonid)
            ->find();

        $this->headerAndFooter('competition');


        $team = Db::name('ctfc_team')->where('ID', $id)->find();
        
        $playercount = Db::name('ctfc_seasonplayer_view')
            ->where('SeasonID', $seasonid)
            ->where('TeamID', $id)
            ->count();
        $this->view->assign('playercount', $playercount);

        $this->view->assign('thisseason', $thisseason);
        $this->view->assign('team', $team);
        $team_info = Db::name('bb_seasonteam')
            ->where('SeasonID', $seasonid)
            ->where('TeamID', $id)
            ->where('Approval', 1)
            ->find();

        $matches = Db::name('ctfc_heat_view')->where('TeamID', $id)
            ->select();

        $this->view->assign('matches', $matches);

        return $this->view->fetch('ctfcteam/read');

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

        if (input('freeagant')) {
          // List all the free agents for a particular season.
          $sql =
              'select * '.
              'from ctfc_team'.
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
          $list = Db::name('ctfc_seasonteam_view');   

          if ($seasonid)
              $list = $list->where('seasonid', $seasonid)
                        ->where('Approve', 1);
          else if ($teamid)
              $list = $list->where('teamid', $teamid);
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

        if ($seasonid && count($list->items())>0) {
            $teamtitle = $list->items()[0]['TeamName'];
          }
        else if ($teamid && count($list->items())>0) {
            $teamtitle = Db::name('ctfc_team')
                ->where('ID', $teamid)
                ->find()['Name'];
        }
          $playertitle = '优秀';

        // Get the seasons for the dropdown.
        $exp = new \think\Db\Expression('field(ID,' . $seasonid . '),Date desc');
        $seasons = Db::name('ctfc_season')
          ->order($exp)->select();
        $seasons = array_reverse($seasons);
        $otherseasons = array_slice($seasons, 1);

        $this->view->assign('thisseason', $seasons[0]);
        $this->view->assign('otherseasons', $otherseasons);
        $this->view->assign('teamtitle', $teamtitle);
        $this->view->assign('SeasonID', $seasonid);
        $this->view->assign('pagerender', $list->render());
        $this->view->assign('ctfcteams', $list->items());

        return $this->view->fetch('ctfcteam/lists');


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
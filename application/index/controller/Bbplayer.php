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
    const APPLY_PLAYER_FIELD = 'Name,Birth,Height,Weight,Email,Sex,PhotoSrc';
    const FIELD = 'Name,Birth,Height,Weight,PhotoSrc,Email,Sex';

    public function add($seasonid = null)
    {
      $this->checkauthorization();
      $assetUrl = getAssetUploadUrl();
      $data = request()->only(self::FIELD, 'post');
      $this->makeNull($data);
      $validator = validate('Bb_player');

      if (!isset($data["PhotoSrc"]) || !$data["PhotoSrc"]) {
          $file = request()->file('file');
          if (!$file) $this->affectedRowsResult(0);
          $data["PhotoSrc"] = $file
              ->move(__DIR__ . $assetUrl)->getSaveName();
      }

      /*$result = $validator->check($data);
      if (!$result) {
          $this->affectedRowsResult(0);
      }*/
      $result = Db::name('bb_player')->insert($data);
      $this->affectedRowsResult($result);
    }

    public function update($id)
    {
        $this->checkauthorization();
        $assetUrl = getAssetUploadUrl();
        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        $validator = validate('Bb_player');
        // $result = $validator->check($data);
        // if (!$result){
        //     $this->result(0);
        // }
        if (!isset($data["PhotoSrc"]) || !$data["PhotoSrc"]) {
            $file = request()->file('file');
            if (!$file) $this->affectedRowsResult(0);
            $data["PhotoSrc"] = $file
                ->move(__DIR__ . $assetUrl)->getSaveName();
        }
        $result = Db::name('bb_player')->where('ID', $id)->update($data);
        $this->affectedRowsResult($result);
    }

    public function setTeam()
    {
        $this->checkauthorization();

        $data = request()->only('Mode,PlayerIDs,TeamID,PlayerNumbers,SeasonID', 'post');
        $mode = $data['Mode'];
        $playerIDs = urldecode($data['PlayerIDs']);
        $playerNumbers = urldecode($data['PlayerNumbers']);
        $playerIDarr = array_filter(explode(',', $playerIDs),'arrfiltrfun');
        $playerNumberarr = array_filter(explode(',', $playerNumbers),'arrfiltrfun');
        $teamID = $data['TeamID'];
        $seasonID = $data['SeasonID'];

        $result = 0;
        if ($mode == 'delete') {
          $result = Db::name('bb_seasonteamplayer')
              ->where('PlayerID', $playerIDarr[0])
              ->delete();
        } else if ($mode == 'insert') {
          foreach ($playerIDarr as $i => $playerID) {
            $playerNumber = $playerNumberarr[$i];
            // Check whether the player is already in the team.
            $player_info = Db::name('bb_seasonteamplayer')
                ->where('SeasonID', $seasonID)
                ->where('TeamID', $teamID)
                ->where('PlayerID', $playerID)
                ->find();
            if (!empty($player_info)) {
              // Player already exists. Just update the number.
              $result += Db::name('bb_seasonteamplayer')
                  ->where('SeasonID', $seasonID)
                  ->where('TeamID', $teamID)
                  ->where('PlayerID', $playerID)
                  ->update(['PlayerNumber' => $playerNumber]);
            } else {
              // Insert a new player.
              $result += Db::name('bb_seasonteamplayer')
                  ->insert(['SeasonID' => $seasonID, 'TeamID' => $teamID, 'PlayerID' => $playerID, 'PlayerNumber' => $playerNumber]);
            }
          }
        }
        $this->affectedRowsResult($result);


      /*  $result = Db::name('bb_seasonteamplayer')->where('TeamID', $teamID)
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


*/
    }

    public function delete($id)
    {
        $this->checkauthorization();

        $result = Db::name('bb_player')->where('ID', $id)->delete();
        $this->affectedRowsResult($result);
    }


    public function read($id, $season)
    {
        $result = Db::name('bb_teamplayer_view')->where('PlayerID', $id)->where('SeasonID', $season)->find();
        if ($this->jsonRequest())
            $this->dataResult($result);

        if (!$result) goto notfound;

        $this->headerAndFooter('player');

        // $playerage = getAge(strtotime($result['Birth']));

        $seasonid = $result['SeasonID'];
        $seasonname = Db::name('bb_season')
            ->where('ID', $seasonid)
            ->find()['Name'];
        /*$competitionname = Db::name('bb_competitionseason_view')
            ->where('SeasonID', $seasonid)
            ->find()['CompetitionName'];

        $players = Db::name('bb_teamplayer_view')
            ->where('SeasonID', $seasonid)->order('PlayerName asc')
            ->select();*/


        $this->view->assign('player_seasonname', $seasonname);
        //$this->view->assign('player_competitionname', $competitionname);
        $this->view->assign('player', $result);
        // $this->view->assign('playerage',$playerage);
        // $this->view->assign('players', $players);
        //   Get player statistics.
      $playerstats = Db::name('bb_seasonplayerstatistics_view')
          ->where('seasonid', $seasonid)
          ->where('PlayerID', $id)
          ->order('PlayerID desc=1')
          ->paginate(20, false)
          ->items();
      $this->view->assign('playerstats', $playerstats);


        return $this->view->fetch('player/bbread');

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
              'from bb_player '.
              'where bb_player.ID not in ('.
                  'select distinct bb_seasonteamplayer.PlayerID '.
                  'from bb_seasonteamplayer '.
                  'where bb_seasonteamplayer.SeasonID='.strval($seasonid).')'.
              'order by Name asc';
          $list = Db::query($sql);
          if ($this->jsonRequest())
            $this->dataResult($list);
          //$list = Db::name('bb_player')->order('Name asc');
        } else if (input('all')) {
          // List all players in the database. (For admin page only)
          $list = Db::name('bb_player')->order('Approval, ID');
          $list = $list->paginate($pagesize, false, [
            'query' => input('param.'),
          ]);
          if ($this->jsonRequest())
            $this->paginatedResult($list->total(), $pagesize, $list->currentPage(), $list->items());
        } else {
          // List players of a particular season.
          $list = Db::name('bb_seasonplayer_view')->order('PlayerName asc');

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
        $CompetitionName = Db::name('bb_competition')
            ->where('ID', $competitionid)
            ->find()['Name'];
        if ($seasonid && count($list->items())>0) {
            $playertitle = $list->items()[0]['SeasonName'];
          }
        else if ($teamid && count($list->items())>0) {
            $playertitle = Db::name('bb_team')
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
                $result += Db::name('bb_player')->where('ID', $playerID)
                    ->update(['Approval'=>1]);
            }
        } 
        else {
            foreach ($playerIDsarr as $playerID) {
                $result += Db::name('bb_player')->where('ID', $playerID)
                    ->delete();
            }
        }

        $this->affectedRowsResult($result);
    }
    
    public function getapply()
    {
        $this->headerAndFooter();
        return $this->view->fetch('player/bbplayerApply');
    }

    public function apply()
    {
        $data = request()->only(self::APPLY_PLAYER_FIELD, 'post');  // ???
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
        

        // Updated bb_team.
        $player = array();
        $player["Name"] = $data["Name"];
        $player["Birth"] = $data["Birth"];
        $player["Height"] = $data["Height"];
        $player["Weight"] = $data["Weight"];
        $player["Email"] = $data["Email"];
        $player["Sex"] = $data["Sex"];
        $player["Approval"] = 0;

        // Check if player with the same name and birthday already exists
        $existingPlayer = Db::name('bb_player')
            ->where('Name', $player["Name"])
            ->where('Birth', $player["Birth"])
            ->find();

        if ($existingPlayer) {
            // Player with the same name and birthday already exists
            $this->headerAndFooter('competition');
            $photoSrc = $existingPlayer["PhotoSrc"];
            $playerName = $existingPlayer["Name"];
            $birthday = $existingPlayer["Birth"];
            $email = $existingPlayer["Email"];
            // $existingPlayerInfo = "<div class='card text-left'><div class='card-body text-left'><small><br>姓名: " . $existingPlayer["Name"] . "<br>生日: " . $existingPlayer["Birth"] . "<br>Email: " . $existingPlayer["Email"]."</small></div></div>";
            
            $existingPlayerInfo = "
            <div class='card text-left'>
                <div class='card-body'>
                    <div class='row'>
                        <div class='col-sm-3'>
                            <img src='" . config('public_assets') . "/uploads/" . $photoSrc . "' alt='Player Photo' style='max-width: 100%; height: auto;'>
                        </div>
                        <div class='col-sm-9'>
                            <small>
                                <strong>姓名:</strong> $playerName<br>
                                <strong>生日:</strong> $birthday<br>
                                <strong>Email:</strong> $email
                            </small>
                        </div>
                    </div>
                </div>
            </div>";
            
            $applyresult = '该用户已存在！如有误，请联系管理员(svcba.svcsa@gmail.com)修改。<br><br>'.$existingPlayerInfo;
            $this->view->assign('applyresult', $applyresult);
            return $this->view->fetch('player/bbplayerApplyres');
        }


        $validator = validate('Bb_player');
        $team_result = $validator->check($player);
        if (!$team_result) {
          $this->affectedRowsResult(0);
        }

        $player["PhotoSrc"] = "";
        $assetUrl = getAssetUploadUrl();
        $infophotofile = request()->file('Photo');

        if($infophotofile)
            $player["PhotoSrc"] = $infophotofile->move(__DIR__ . $assetUrl)
                ->getSaveName();

        $player_result = Db::name('bb_player')->insert($player);


        // Jump to the apply result page.
        $this->headerAndFooter('competition');

        $applyresult = '';
        if ($player_result > 0)
          $applyresult = '您的球员申请已提交，审核后将会给您发邮箱或者短信通知，请关注！';
        $this->view->assign('applyresult', $applyresult);
        return $this->view->fetch('player/bbplayerApplyres');
    }
}

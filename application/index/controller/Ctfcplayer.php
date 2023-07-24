<?php


namespace app\index\controller;

use think\Db;
use think\Session;

class Ctfcplayer extends Base
{
    const APPLY_PLAYER_FIELD = 'Name,Birthday,Email,Sex,PhotoSrc';
    const FIELD = 'Name,Sex,Birthday,PhotoSrc,Email,Approval';

    public function add()
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        $validator = validate('ctfc_player');
        $result = Db::name('ctfc_player')->insert($data);
        $this->affectedRowsResult($result);
    }

    public function delete($id)
    {
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
        $player["Approval"] = 0;

        // Check if player with the same name and birthday already exists
        $existingPlayer = Db::name('ctfc_player')
            ->where('Name', $player["Name"])
            ->where('Birthday', $player["Birthday"])
            ->find();

        if ($existingPlayer) {
            // Player with the same name and birthday already exists
            $this->headerAndFooter('ctfc');
            $existingPlayerInfo = "
            <div class='card text-left'>
                <div class='card-body'>
                    <div class='row'>
                        <div class='col-sm-3'>
                            <img src='" . config('public_assets') . "/uploads/" . $existingPlayer["PhotoSrc"] . "' alt='Player Photo' style='max-width: 100%; height: auto;'>
                        </div>
                        <div class='col-sm-9'>
                            <small>
                                <strong>姓名:</strong>" . $existingPlayer["Name"] . "<br>
                                <strong>生日:</strong>" . $existingPlayer["Birthday"] . "<br>
                                <strong>Email:</strong>" . $existingPlayer["Email"] . "
                            </small>
                        </div>
                    </div>
                </div>
            </div>";
            $applyresult = '该用户已存在！如有误，请联系管理员(svcba.svcsa@gmail.com)修改。<br><br>' . $existingPlayerInfo;
            $this->view->assign('applyresult', $applyresult);
            return $this->view->fetch('ctfcplayer/applyres');
        }

        $validator = validate('ctfc_player');
        $result = $validator->check($player);
        if (!$result) {
            $this->affectedRowsResult(0);
        }

        $player["PhotoSrc"] = "";
        $assetUrl = getAssetUploadUrl();
        $infophotofile = request()->file('Photo');

        if ($infophotofile)
            $player["PhotoSrc"] = $infophotofile->move(__DIR__ . $assetUrl)
                ->getSaveName();

        $result = Db::name('ctfc_player')->order('Name')->insert($player);

        $this->headerAndFooter('ctfc');

        $applyresult = '';
        if ($result > 0)
            $applyresult = '您的申请已提交，审核后将会给您发邮箱或者短信通知，请关注！';
        $this->view->assign('applyresult', $applyresult);
        return $this->view->fetch('ctfcplayer/applyres');
    }

    public function read($id)
    {
        $result = Db::name('ctfc_player')->where('ID', $id)->find();
        if ($this->jsonRequest())
            $this->dataResult($result);

        if (!$result)
            goto notfound;
        $this->headerAndFooter('ctfc');
        $this->view->assign('player', $result);
        return $this->view->fetch('player/ctfcread');

        notfound:
        header("HTTP/1.0 404 Not Found");
        die;
    }

    public function lists($seasonid = null, $teamid = null)
    {
        // Get some basic info from input.
        $pagesize = (!input('pagesize')) ? 500 : input('pagesize');
        if (!$seasonid && input('seasonid'))
            $seasonid = input('seasonid');
        if (!$teamid && input('teamid'))
            $teamid = input('teamid');

        // TODO: 
        // 1. Clean up logic and combine duplicated code.
        // 2. Refine or remove the pagesize.
        // 3. Refine if condition of (!$seasonid and !$teamid) (for approved players and seasonplayers)
        // 4. tba.
        if (input('registeritem')) {
                // List all approved player. (For register item page.)
                $list = Db::name('ctfc_player')->where('Approval', 1)->orderRaw('CONVERT(Name USING gbk)')->paginate($pagesize);

                $this->paginatedResult(
                    $list->total(),
                    $list->listRows(),
                    $list->currentPage(),
                    $list->items()
                );
        } else {
            if (input('all')) {
                // List all players in the database. (For admin page only)
                // $list = Db::name('ctfc_player')->orderRaw('CONVERT(Name USING gbk)');
                $list = Db::name('ctfc_player')->order('Approval, ID');
                $list = $list->paginate($pagesize, false, [
                    'query' => input('param.'),
                ]);
                if ($this->jsonRequest())
                    $this->paginatedResult($list->total(), $pagesize, $list->currentPage(), $list->items());
            } else if (!$seasonid and !$teamid) {
                // List all approved player. (For player display page.)
                $list = Db::name('ctfc_player')->where('Approval', 1)->orderRaw('CONVERT(Name USING gbk)');
                $this->view->assign('showNumber', false);
            } else {
                // List all players in a team and a season. (For team display page.)
                // 华锦赛 -> 田径团队 -> (选择团队) -> 查看队员
                $list = Db::name('ctfc_seasonplayer_view')->orderRaw('CONVERT(PlayerName USING gbk)');
                $this->view->assign('showNumber', true);

                if ($seasonid)
                    $list = $list->where('seasonid', $seasonid);
                if ($teamid)
                    $list = $list->where('teamid', $teamid);
                else if (input('playerids'))
                    $list = $list->whereIn(
                        'PlayerID',
                        explode(',', input('playerids'))
                    );
            }
            $list = $list->paginate($pagesize, false, [
                'query' => input('param.'),
            ]);

            if ($this->jsonRequest())
                $this->paginatedResult($list->total(), $pagesize, $list->currentPage(), $list->items());


            $this->headerAndFooter('player');

            $playertitle = '';
            // $CompetitionName = Db::name('ctfc_competition')
            //     ->where('ID', $competitionid)
            //     ->find()['Name'];
            // if ($seasonid && count($list->items())>0) {
            //     $playertitle = $list->items()[0]['SeasonName'];
            //   }
            // else 
            if ($teamid && count($list->items()) > 0) {
                $playertitle = Db::name('ctfc_team')
                    ->where('ID', $teamid)
                    ->find()['Name'] . '的';
            } else
                $playertitle = '';

            $this->view->assign('playertitle', $playertitle);
            $this->view->assign('SeasonID', $seasonid);
            $this->view->assign('pagerender', $list->render());
            $this->view->assign('players', $list->items());
            $this->headerAndFooter('ctfc');

            return $this->view->fetch('ctfcplayer/lists');
    }

        notfound:
        header("HTTP/1.0 404 Not Found");
        die;
    }

    public function update($id)
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        $result = Db::name('ctfc_player')->where('ID', $id)->update($data);
        $this->affectedRowsResult($result);
    }


    public function passApplication()
    {
        $this->checkauthorization();

        $data = request()->only('PlayerIDs,Passed', 'post');
        $playerIDs = urldecode($data['PlayerIDs']);
        $passed = isset($data['Passed']) ? boolval($data['Passed']) : true;

        $result = 0;
        $playerIDsarr = explode(',', $playerIDs);

        if ($passed) {
            foreach ($playerIDsarr as $playerID) {
                $result += Db::name('ctfc_player')->where('ID', $playerID)
                    ->update(['Approval' => 1]);
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
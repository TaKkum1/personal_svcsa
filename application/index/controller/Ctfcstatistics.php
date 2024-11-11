<<<<<<< HEAD
<?php
/**
 * Created by PhpStorm.
 * User: Aven
 * Date: 2018/10/28
 * Time: 1:30
 */

namespace app\index\controller;

require_once (__DIR__ . '/../../../thinkphp/library/think/db/Expression.php');

use think\Db;
use think\Session;
use think\Db\Expression;

class Ctfcstatistics extends Base
{
    const FIELD = 'PlayerID,WindSpeed,Result,MatchID';

    public function add($matchid=null)
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        if(!isset($data["MatchID"]))
            $data["MatchID"]=$matchid;
        if(!isset($data["PlayerID"]))
            $data["PlayerID"]=input('playerid');
        $validator = validate('Ctfc_statistics');
        $result = $validator->check($data);
        if (!$result){
            $this->affectedRowsResult(0);
        }
        $result = Db::name('ctfc_statistics')->insert($data);
        $this->affectedRowsResult($result);
    }

    public function delete($id){
        $this->checkauthorization();

        $result = Db::name('ctfc_statistics')->where('ID', $id)->delete();
        $this->affectedRowsResult($result);
    }

    public function read($id){
        $result = Db::name('ctfc_statistics')->where('ID', $id)->find();
        $this->dataResult($result);
    }

    public function lists(){
        $list = Db::name('ctfc_statisticsmatchevent');
        if(input('matchid')) $list = $list->where('MatchID',input('matchid'));
        else if(input('eventid')) $list = $list->where('EventID',input('eventid'));
        $list = $list->order('Result Asc');
        $list = $list->paginate(input('pagesize'));

        $events= Db::name('ctfc_event')->select();

        if($this->jsonRequest())
            $this->paginatedResult(
                $list->total(),
                $list->listRows(),
                $list->currentPage(),
                $list->items()
            );

        $this->headerAndFooter('ctfc');

        if(input('matchid')) {
            if(count($list->items())==0) goto notfound;

            $exp = new Expression('field(ID,'.($list->items())[0]['SeasonID'].'),StartTime DESC');
            $result = Db::name('ctfc_season')
                ->order($exp)->select();
            $result = array_reverse($result);
            $otherseasons = array_slice($result,1);


            $this->view->assign('thisseason',$result[0]);
            $this->view->assign('otherseasons',$otherseasons);
            $this->view->assign('recenteventid',$events[0]['ID']);
            $this->view->assign('statsresult',$list->items());
            $this->view->assign('statsdescription','查看'.$list->items()[0]['MatchName'].'的比赛结果');

            return $this->view->fetch('ctfcstatistics/matchstats');
        } else if (input('eventid')) {
            //if(count($list->items())==0) goto notfound;

            $result = Db::name('ctfc_season')
                ->select();

            $thisevent = array();
            if(count($list->items())==0) {
                $thisevent = ['EventID'=>$events[0]['ID'],
                    'EventName'=>$events[0]['Name']];
            }
            else {
                $thisevent = ['EventID' => $list->items()[0]['EventID'],
                    'EventName' => $list->items()[0]['EventName']];
            }




            $this->view->assign('thisseason',['ID'=>'recent']);
            $this->view->assign('otherseasons',$result);
            $this->view->assign('events',$events);
            $this->view->assign('recenteventid',$thisevent['EventID']);
            $this->view->assign('thiseventid',$thisevent['EventID']);
            $this->view->assign('statsresult',$list->items());
            $this->view->assign('statsdescription','查看 '.$thisevent['EventName'].' 的历史纪录排行');

            return $this->view->fetch('ctfcstatistics/eventstats');
        }

notfound:
        header("HTTP/1.0 404 Not Found");
        die;
    }

    public function update($id){
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
//        $validator = validate('Ctfc_statistics');
//        $result = $validator->check($data);
//        if (!$result){
//            $this->result(0);
//        }
        $result = Db::name('ctfc_statistics')->where('ID', $id)->update($data);
        $this->affectedRowsResult($result);
    }
=======
<?php
/**
 * Created by PhpStorm.
 * User: Aven
 * Date: 2018/10/28
 * Time: 1:30
 */

namespace app\index\controller;

require_once (__DIR__ . '/../../../thinkphp/library/think/db/Expression.php');

use think\Db;
use think\Session;
use think\Db\Expression;

class Ctfcstatistics extends Base
{
    const FIELD = 'PlayerID,WindSpeed,Result,MatchID';

    public function add($matchid=null)
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        if(!isset($data["MatchID"]))
            $data["MatchID"]=$matchid;
        if(!isset($data["PlayerID"]))
            $data["PlayerID"]=input('playerid');
        $validator = validate('Ctfc_statistics');
        $result = $validator->check($data);
        if (!$result){
            $this->affectedRowsResult(0);
        }
        $result = Db::name('ctfc_statistics')->insert($data);
        $this->affectedRowsResult($result);
    }

    public function delete($id){
        $this->checkauthorization();

        $result = Db::name('ctfc_statistics')->where('ID', $id)->delete();
        $this->affectedRowsResult($result);
    }

    public function read($id){
        $result = Db::name('ctfc_statistics')->where('ID', $id)->find();
        $this->dataResult($result);
    }

    public function lists(){
        $list = Db::name('ctfc_statisticsmatchevent');
        if(input('matchid')) $list = $list->where('MatchID',input('matchid'));
        else if(input('eventid')) $list = $list->where('EventID',input('eventid'));
        $list = $list->order('Result Asc');
        $list = $list->paginate(input('pagesize'));

        $events= Db::name('ctfc_event')->select();

        if($this->jsonRequest())
            $this->paginatedResult(
                $list->total(),
                $list->listRows(),
                $list->currentPage(),
                $list->items()
            );

        $this->headerAndFooter('ctfc');

        if(input('matchid')) {
            if(count($list->items())==0) goto notfound;

            $exp = new Expression('field(ID,'.($list->items())[0]['SeasonID'].'),StartTime DESC');
            $result = Db::name('ctfc_season')
                ->order($exp)->select();
            $result = array_reverse($result);
            $otherseasons = array_slice($result,1);


            $this->view->assign('thisseason',$result[0]);
            $this->view->assign('otherseasons',$otherseasons);
            $this->view->assign('recenteventid',$events[0]['ID']);
            $this->view->assign('statsresult',$list->items());
            $this->view->assign('statsdescription','查看'.$list->items()[0]['MatchName'].'的比赛结果');

            return $this->view->fetch('ctfcstatistics/matchstats');
        } else if (input('eventid')) {
            //if(count($list->items())==0) goto notfound;

            $result = Db::name('ctfc_season')
                ->select();

            $thisevent = array();
            if(count($list->items())==0) {
                $thisevent = ['EventID'=>$events[0]['ID'],
                    'EventName'=>$events[0]['Name']];
            }
            else {
                $thisevent = ['EventID' => $list->items()[0]['EventID'],
                    'EventName' => $list->items()[0]['EventName']];
            }




            $this->view->assign('thisseason',['ID'=>'recent']);
            $this->view->assign('otherseasons',$result);
            $this->view->assign('events',$events);
            $this->view->assign('recenteventid',$thisevent['EventID']);
            $this->view->assign('thiseventid',$thisevent['EventID']);
            $this->view->assign('statsresult',$list->items());
            $this->view->assign('statsdescription','查看 '.$thisevent['EventName'].' 的历史纪录排行');

            return $this->view->fetch('ctfcstatistics/eventstats');
        }

notfound:
        header("HTTP/1.0 404 Not Found");
        die;
    }

    public function update($id){
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
//        $validator = validate('Ctfc_statistics');
//        $result = $validator->check($data);
//        if (!$result){
//            $this->result(0);
//        }
        $result = Db::name('ctfc_statistics')->where('ID', $id)->update($data);
        $this->affectedRowsResult($result);
    }
>>>>>>> 37313bc (Initial commit)
}
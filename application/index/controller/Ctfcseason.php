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
use think\Db\Expression;

class Ctfcseason extends Base
{
    const FIELD = 'Name,Date,Venue';

    public function add()
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        // $validator = validate('Ctfc_season');
        // $result = $validator->check($data);
        // if (!$result){
        //     $this->affectedRowsResult(0);
        // }
        $result = Db::name('ctfc_season')->insert($data);
        $this->affectedRowsResult($result);
    }

    public function delete($id)
    {
        $this->checkauthorization();

        $result = Db::name('ctfc_season')->where('ID', $id)->delete();
        $this->affectedRowsResult($result);
    }

    public function read($id)
    {

        $exp = new Expression('field(ID,' . $id . '),Date DESC');
        $result = Db::name('ctfc_season')
            ->order($exp)
            ->select();
        $result = array_reverse($result);

        $names = $this->fetchNames();

        if ($this->jsonRequest()) {
            $this->dataResult($result[0]);
        } else if (count($result) > 0) {
            $this->headerAndFooter('ctfc');

            $otherseasons = array_slice($result, 1);

            $matches = Db::name('ctfc_heat_view')->where('SeasonID', $result[0]['ID'])
                // ->order('StartTime','desc')
                ->select();

            $this->view->assign('matches', $matches);
            $this->view->assign('thisseason', $result[0]);
            $this->view->assign('otherseasons', $otherseasons);
            $this->view->assign('itemNames', $names['itemNames']);
            $this->view->assign('ageGroupNames', $names['ageGroupNames']);

            return $this->view->fetch('ctfcseason/read');
        } else {
            header("HTTP/1.0 404 Not Found");
            die;
        }

    }

    public function lists()
    {
        $list = Db::name('ctfc_season')->paginate(input('pagesize'));
        $this->paginatedResult(
            $list->total(),
            $list->listRows(),
            $list->currentPage(),
            $list->items()
        );
    }

    public function readRecent()
    {

        $result = Db::name('ctfc_season')
            ->order('Date', 'desc')
            ->select();

        $names = $this->fetchNames();

        if ($this->jsonRequest()) {
            $this->dataResult($result[0]);
        } else if (count($result) > 0) {
            $this->headerAndFooter('ctfc');

            $otherseasons = array_slice($result, 1);

            $matches = Db::name('ctfc_heat_view')->where('SeasonID', $result[0]['ID'])
                ->order(['EventID', 'HeatID', 'LaneNumber'])
                ->select();

            // $events= Db::name('ctfc_event')->select();
            // $this->view->assign('recenteventid',$events[0]['ID']);
            $this->view->assign('matches', $matches);
            $this->view->assign('thisseason', $result[0]);
            $this->view->assign('otherseasons', $otherseasons);
            $this->view->assign('itemNames', $names['itemNames']);
            $this->view->assign('ageGroupNames', $names['ageGroupNames']);

            return $this->view->fetch('ctfcseason/read');
        } else {
            header("HTTP/1.0 404 Not Found");
            die;
        }
    }

    public function update($id)
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        $this->makeNull($data);
        //        $validator = validate('Ctfc_season');
//        $result = $validator->check($data);
//        if (!$result){
//            $this->result(0);
//        }
        $result = Db::name('ctfc_season')->where('ID', $id)->update($data);
        $this->affectedRowsResult($result);
    }

    public function filter($id)
    {
        // Fetch the dropdown options from the database
        $names = $this->fetchNames();

        $exp = new Expression('field(ID,' . $id . '),Date DESC');
        $result = Db::name('ctfc_season')
            ->order($exp)
            ->select();

        $result = array_reverse($result);

        if ($this->jsonRequest()) {
            $this->dataResult($result[0]);
        } else if (count($result) > 0) {
            $this->headerAndFooter('ctfc');

            $otherseasons = array_slice($result, 1);

            // Construct the filter
            $itemName = input('get.itemName');
            $gender = input('get.gender');
            $ageGroupName = input('get.ageGroupName');

            $filter = [];
            if (!empty($itemName)) {
                $filter['itemName'] = $itemName;
            }
            if (!empty($gender)) {
                $filter['gender'] = $gender;
            }
            if (!empty($ageGroupName)) {
                $filter['ageGroupName'] = $ageGroupName;
            }

            // Get the matches
            $matches = Db::name('ctfc_heat_view')->where('SeasonID', $result[0]['ID'])->where($filter)->order(['EventID', 'HeatID', 'LaneNumber'])->select();

            $this->view->assign('filter', $filter);

            $this->view->assign('matches', $matches);
            $this->view->assign('thisseason', $result[0]);
            $this->view->assign('otherseasons', $otherseasons);
            $this->view->assign('itemNames', $names['itemNames']);
            $this->view->assign('ageGroupNames', $names['ageGroupNames']);

            return $this->view->fetch('ctfcseason/read');
        } else {
            header("HTTP/1.0 404 Not Found");
            die;
        }
    }
    protected function fetchNames()
    {
        return [
            'itemNames' => Db::name('ctfc_item')->column('Name'),
            'ageGroupNames' => Db::name('ctfc_agegroup')->column('Name'),
            'seasonNames' => Db::name('ctfc_season')->column('Name')
        ];
    }
    public function history()
    {
        // Fetch the dropdown options from the database
        $names = $this->fetchNames();

        // Fetch the season names
        $exp = new Expression('Date DESC');
        $result = Db::name('ctfc_season')
            ->order($exp)
            ->select();

        // $result = array_reverse($result);

        if ($this->jsonRequest()) {
            // 如果是json请求，干什么没看懂
            $this->dataResult($result[0]);
        } else if (count($result) > 0) {
            // 如果赛季查询结果不为空            
            $this->headerAndFooter('ctfc');

            $otherseasons = $result;

            // Construct the filter
            $itemName = input('get.itemName');
            $gender = input('get.gender');
            $ageGroupName = input('get.ageGroupName');
            $seasonName = input('get.seasonName');
            
            // lookup season id from database
            $seasonId = Db::name('ctfc_season')->where('Name', $seasonName)->value('ID');


            $filter = [];
            if (!empty($itemName)) {
                $filter['itemName'] = $itemName;
            }
            if (!empty($gender)) {
                $filter['gender'] = $gender;
            }
            if (!empty($ageGroupName)) {
                $filter['ageGroupName'] = $ageGroupName;
            }
            if (!empty($seasonId)) {
                $filter['seasonID'] = $seasonId;
            }
            
            // Get the matches
            $matches = Db::name('ctfc_heat_view')->where($filter)->order(['TimeSpan', 'Result DESC'])->select();
            // add a column for season name
            foreach ($matches as &$match) {
                $match['SeasonName'] = Db::name('ctfc_season')->where('ID', $match['SeasonID'])->value('Name');
                if ($match['TimeSpan'] == "" && $match['IsTrack'] == 1) {
                    $match['TimeSpan'] = '   DNS';  // add 3 more spaces for front-end alignment
                }
                else if ($match['Result'] == "" && $match['IsTrack'] == 0) {
                    $match['Result'] = 'DNS';
                }
            }
            // keep the original order and move the DNS to the end
            usort($matches, function($a, $b) {
                if ($a['IsTrack'] == 1 && $b['IsTrack'] == 1) {
                    if ($a['TimeSpan'] == '   DNS' && $b['TimeSpan'] != '   DNS') {
                        return 1;
                    }
                    else if ($a['TimeSpan'] != '   DNS' && $b['TimeSpan'] == '   DNS') {
                        return -1;
                    }
                    else {
                        return $a['TimeSpan'] > $b['TimeSpan'] ? 1 : -1;
                    }
                }
                else if ($a['IsTrack'] == 0 && $b['IsTrack'] == 0) {
                    if ($a['Result'] == 'DNS' && $b['Result'] != 'DNS') {
                        return 1;
                    }
                    else if ($a['Result'] != 'DNS' && $b['Result'] == 'DNS') {
                        return -1;
                    }
                    else {
                        return $a['Result'] > $b['Result'] ? -1 : 1;
                    }
                }
                else {
                    return 0;
                }
            });

            $filter['seasonName'] = $seasonName;
            
            // filter for page rendering
            $this->view->assign('filter', $filter);

            $this->view->assign('matches', $matches);
            $this->view->assign('thisseason', $result[0]);
            $this->view->assign('otherseasons', $otherseasons);
            $this->view->assign('itemNames', $names['itemNames']);
            $this->view->assign('ageGroupNames', $names['ageGroupNames']);
            $this->view->assign('seasonNames', $names['seasonNames']);

            return $this->view->fetch('ctfcseason/read_history');
        } else {
            header("HTTP/1.0 404 Not Found");
            die;
        }
    }
}
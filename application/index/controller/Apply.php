<?php
/**
 * Created by PhpStorm.
 * User: Aven
 * Date: 2018/10/28
 * Time: 1:30
 */

namespace app\index\controller;



use think\Db;


class Apply extends Base
{
    public function apply()
    {
        $this->headerAndFooter('competition');

        $sql = 'select a.SeasonID,a.SeasonName,a.CompetitionID,a.CompetitionName from bb_competitionseason_view a join (select max(SeasonID) SeasonID from bb_competitionseason_view group by CompetitionID) b on a.SeasonID=b.SeasonID';
        $recentbbseasons =  DB::query($sql);

        $sql = 'SELECT b.MatchID,b.EventName,b.SeasonName FROM ctfc_matchevent as b WHERE SeasonID=(SELECT max(a.SeasonID) from ctfc_matchevent as a)';
        $recentctfcmatches=  DB::query($sql);


        $this->view->assign('recentbbseasons',$recentbbseasons);
        $this->view->assign('recentctfcmatches',$recentctfcmatches);


        return $this->view->fetch('public/apply');
    }
}
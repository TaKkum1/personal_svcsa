<?php
namespace app\index\controller;

use think\Db;


class Index extends Base
{
    public function index()
    {
        $this->headerAndFooter('index');
        $view = $this->view;

        $top2competition = Db::name('bb_competition')
            ->limit(0,2)
            ->select();


        $topctfcseason = Db::name('ctfc_season')
            ->order('StartTime desc')->find();

        // $swipers = array(
        //     0=>['ID'=>$top2competition[0]['ID'],
        //         'Name'=>$top2competition[0]['Name'],
        //         'Picture'=>$top2competition[0]['Picture'],
        //         'Description'=>$top2competition[0]['Description']
        //     ],
        //     1=>['ID'=>$top2competition[1]['ID'],
        //         'Name'=>$top2competition[1]['Name'],
        //         'Picture'=>$top2competition[1]['Picture'],
        //         'Description'=>$top2competition[1]['Description']
        //     ],
        //     2=>['ID'=>$topctfcseason['ID'],
        //         'Name'=>$topctfcseason['Name'],
        //         'Picture'=>$topctfcseason['Picture'],
        //         'Description'=>$topctfcseason['Description']
        //     ]
        // );


        $recentnews = Db::name('article')
            ->where('Category',0)
            ->order('Posttime Desc')
            ->limit(0,3)
            ->select();

        $competitionids = Db::name('bb_competition')
            ->column('ID');

        $competitioncount = count($competitionids);

        $sql = 'select a.SeasonID,a.SeasonName,a.CompetitionID from bb_competitionseason_view a join (select max(SeasonID) SeasonID from bb_competitionseason_view group by CompetitionID) b on a.SeasonID=b.SeasonID';
        $recentseasons =  DB::query($sql);

 //       $recentseasonsids = array();
        $recentseasonsidsstr = '';
        $recentseasonid=$recentseasons[0]['SeasonID'];
        foreach ($recentseasons as $season) {
//            $recentseasonsids.array_push($season['SeasonID']);
            $recentseasonsidsstr = $recentseasonsidsstr . $season['SeasonID'];
            if($season!=end($recentseasons))
                $recentseasonsidsstr = $recentseasonsidsstr . ',';
        }

        $bbteams = DB::name('bb_seasonteam')
            ->where('Approval','<>',0)
            ->where('SeasonID','in',$recentseasonsidsstr)->order('TeamID desc')->select();

        $bbplayers = DB::name('bb_seasonplayer_view')->where('SeasonID','in',$recentseasonsidsstr)->order('CompetitionID asc')->select();

        $bbmatchcount = DB::name('bb_match')->where('SeasonID','in',$recentseasonsidsstr)->count();

        $bbplayersbycomp = array_group_by($bbplayers,'CompetitionID');

        $bbmanplayers = current($bbplayersbycomp);
        $bbwomanplayers = next($bbplayersbycomp);
        $bbyouthplayers = next($bbplayersbycomp);

        $ctfcplayers = DB::name('ctfc_playermatch')
            ->order('SeasonID desc')
            ->limit(0,5)
            ->select();

        // $this->view->assign('swipers',$swipers);
        $this->view->assign('recentnews',$recentnews);
        $this->view->assign('competitioncount',$competitioncount);
        $this->view->assign('bbteamcount',count($bbteams));
        $this->view->assign('bbplayercount',count($bbplayers));
        $this->view->assign('bbmatchcount',$bbmatchcount);
        $this->view->assign('recentseasonid',$recentseasonid);

        $this->view->assign('bbmanplayers',$bbmanplayers);
        $this->view->assign('bbwomanplayers',$bbwomanplayers);
        $this->view->assign('bbyouthplayers',$bbyouthplayers);
        $this->view->assign('ctfcplayers',$ctfcplayers);
        $this->view->assign('bbteams',$bbteams);

        return $view->fetch('public/index');
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }

    public function phpinfo()
    {
        return phpinfo();
    }

    public function mail()
    {
        $result = sendEmail([[
            'user_email'=>'770898033@qq.com',
            'content'=>'测试php发邮件。']]);

        return $result['msg'];
    }
}

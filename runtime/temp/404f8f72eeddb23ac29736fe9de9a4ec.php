<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:83:"/Users/xinliu-mbp/Git/svcsa_web/public/../application/index/view/bbseason/read.html";i:1566591634;s:66:"/Users/xinliu-mbp/Git/svcsa_web/application/index/view/layout.html";i:1566591634;s:73:"/Users/xinliu-mbp/Git/svcsa_web/application/index/view/public/header.html";i:1566591634;s:73:"/Users/xinliu-mbp/Git/svcsa_web/application/index/view/public/footer.html";i:1566591634;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>体育</title>
    <link rel="shortcut icon" href="<?php echo \think\Config::get('public_assets'); ?>/images/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="<?php echo \think\Config::get('public_assets'); ?>/css/common.css">
    <link rel="stylesheet" type="text/css" href="<?php echo \think\Config::get('public_assets'); ?>/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo \think\Config::get('public_assets'); ?>/css/font.css">
    <link rel="stylesheet" type="text/css" href="<?php echo \think\Config::get('public_assets'); ?>/css/index.css">
    <link rel="stylesheet" type="text/css" href="<?php echo \think\Config::get('public_assets'); ?>/css/jBox.css">
    <link rel="stylesheet" type="text/css" href="<?php echo \think\Config::get('public_assets'); ?>/css/jBox.Image.css">
    <link rel="stylesheet" type="text/css" href="<?php echo \think\Config::get('public_assets'); ?>/css/animate.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo \think\Config::get('public_assets'); ?>/css/swiper.min.css">

    <script type="text/javascript" src="<?php echo \think\Config::get('public_assets'); ?>/js/jquery-2.2.4.min.js"></script>
</head>
<body>
<div class="index-top">
    <div class="public-header">
        <div class="header-top block clearfix">
            <div class="logo-box"><a href="index.html"><img src="<?php echo \think\Config::get('public_assets'); ?>/images/logo.png"></a></div>
            <div class="right-box">
                <span class="title">联系方式</span>
                <a href="<?php echo url('index/system/contactus'); ?>"><span class="icon-wechat icon"></a>
                <a href="<?php echo url('index/system/contactus'); ?>"><span class="icon-facebook icon"></span></a>
                <a href="<?php echo url('index/system/contactus'); ?>"><span class="icon-twitter icon"></span></a>
                <a href="<?php echo url('index/system/contactus'); ?>"><span class="icon-youtube icon"></span></a>
                <a href="<?php echo url('index/system/contactus'); ?>"><span class="icon-vimeo icon"></span></a>
                <span class="ml20">
                        <a href="<?php echo url('index/system/contactus'); ?>"><span class="icon-earth icon"></span></a>
                        <a href="<?php echo url('index/system/contactus'); ?>"><span class="icon-user icon"></span></a>
                        <a href="<?php echo url('index/system/contactus'); ?>"><span class="icon icon-envelop"></span></a>
                    </span>
                <a href="" class="btn">比赛视频</a>
            </div>
        </div>
        <div class="body-box block clearfix">
            <div class="logo-box">
                <a href="index.html"><img src="<?php echo \think\Config::get('public_assets'); ?>/images/logo.png"></a>
            </div>
            <ul class="one-box">
                <li<?php if($nav == 'index'): ?> class="on"<?php endif; ?>>
                    <a href="<?php echo \think\Config::get('public_assets'); ?>/index">
                        <span>首页</span>
                        <span class="bg"></span>
                    </a>
                </li>
                <li<?php if($nav == 'news'): ?> class="on"<?php endif; ?>>
                    <a href="<?php echo \think\Config::get('public_assets'); ?>/news">
                        <span>新闻</span>
                        <span class="bg"></span>
                    </a>
                </li>
                <li<?php if($nav == 'competition'): ?> class="on"<?php endif; ?>>
                    <a href="#">
                        <span>比赛项目 <i class="icon-chevron-down"></i></span>
                        <span class="bg"></span>
                    </a>
                    <ul>
                        <?php if(is_array($bbcompetition) || $bbcompetition instanceof \think\Collection || $bbcompetition instanceof \think\Paginator): $i = 0; $__LIST__ = $bbcompetition;if( count($__LIST__)==0 ) : echo "暂时没有信息！" ;else: foreach($__LIST__ as $key=>$vo_bbcomp): $mod = ($i % 2 );++$i;?>
                        <li>
                            <a href="<?php echo \think\Config::get('public_assets'); ?>/bbcompetition/<?php echo $vo_bbcomp['ID']; ?>"><?php echo $vo_bbcomp['Name']; ?></a>
                        </li>
                        <?php endforeach; endif; else: echo "暂时没有信息！" ;endif; ?>
                    </ul>
                </li>
                <li<?php if($nav == 'competition1'): ?> class="on"<?php endif; ?>>
                    <a href="<?php echo \think\Config::get('public_assets'); ?>/bbcompetition/1">
                        <span>男篮</span>
                        <span class="bg"></span>
                    </a>
                </li>
                <li<?php if($nav == 'competition2'): ?> class="on"<?php endif; ?>>
                    <a href="<?php echo \think\Config::get('public_assets'); ?>/bbcompetition/2">
                        <span>女篮</span>
                        <span class="bg"></span>
                    </a>
                </li>
                <li<?php if($nav == 'ctfc'): ?> class="on"<?php endif; ?>>
                    <a href="<?php echo \think\Config::get('public_assets'); ?>/ctfc">
                        <span>田径</span>
                        <span class="bg"></span>
                    </a>
                </li>
                <li<?php if($nav == 'player'): ?> class="on"<?php endif; ?>>
                    <a href="<?php echo \think\Config::get('public_assets'); ?>/player">
                        <span>选手</span>
                        <span class="bg"></span>
                    </a>
                </li>
                <li<?php if($nav == 'gallery'): ?> class="on"<?php endif; ?>>
                    <a href="<?php echo url('index/picture/lists',['category'=>'all']); ?>">
                        <span>图集</span>
                        <span class="bg"></span>
                    </a>
                </li>
                <li<?php if($nav == 'faq'): ?> class="on"<?php endif; ?>>
                    <a href="<?php echo \think\Config::get('public_assets'); ?>/faq">
                        <span>FAQ</span>
                        <span class="bg"></span>
                    </a>
                </li>
                <li<?php if($nav == 'contactus'): ?> class="on"<?php endif; ?>>
                    <a href="<?php echo \think\Config::get('public_assets'); ?>/contactus">
                        <span>联系我们</span>
                        <span class="bg"></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
<div class="inside-banner img_3-banner block">
    <h3><?php echo $thisseason['CompetitionName']; ?>比赛</h3>
    <p>查看<?php echo $thisseason['SeasonName']; ?>比赛赛程</p>
</div>
<div class="site-box">
    <div class="block">
        <span class="icon-home home_icon icon"></span><a href="<?php echo url('index/index/index'); ?>">首页</a><span class="icon-arrow-right arrow_icon icon"></span><span class="active">篮球</span>
    </div>
</div>
</div>
<div class="pro-wrap">
    <div class="clearfix block">
        <div class="public-left">
            <table class="table">
                <thead>
                <tr>
                    <th>比赛时间</th>
                    <th>队伍VS队伍</th>
                    <th>比分</th>
                    <th>战报/前瞻</th>
                    <th>图集</th>
                    <th>技术统计</th>
                </tr>
                </thead>
                <tbody>
                <?php if(!$matches): ?>
                <tr>
                    <td>赛程暂时为空！</td>
                </tr>
                <?php endif; if(is_array($matches) || $matches instanceof \think\Collection || $matches instanceof \think\Paginator): $i = 0; $__LIST__ = $matches;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$match): $mod = ($i % 2 );++$i;?>
                <tr>
                    <td><?php echo date('m/d G:i',strtotime($match['StartTime'])); ?></td>
                    <td><?php echo $match['TeamAShortName']; ?> vs <?php echo $match['TeamBShortName']; ?></td>
                    <td><?php if($match['State'] == -1): ?>
                        未开赛
                        <?php elseif($match['State'] == 0): ?>
                        正在进行..
                        <?php else: ?>
                        <?php echo $match['ScoreTeamA']; ?>:<?php echo $match['ScoreTeamB']; endif; ?></td>
                    <td><a href="<?php echo url('index/bbmatch/read',['id'=>$match['MatchID']]); ?>">查看</a></td>
                    <td><a href="<?php echo url('index/bbpicture/lists',['matchid'=>$match['MatchID']]); ?>">图集</a></td>
                    <td><a href="<?php echo url('index/bbstatistics/lists') . '?matchid='. $match['MatchID']; ?>">技术统计</a></td>
                </tr>
                <?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>
        </div>
        <div class="public-right">
            <div class="tab-box">
                <h3>项目信息</h3>
                <ul>
                    <li class="active"><a href="<?php echo url('index/bbseason/read',['id'=>$thisseason['SeasonID']]); ?>"><span class="icon-chevron-right2 icon"></span>赛场列表</a></li>
                    <li><a href="<?php echo url('index/bbteam/lists',['seasonid'=>$thisseason['SeasonID']]); ?>"><span class="icon-chevron-right2 icon"></span>球队展示</a></li>
                    <li><a href="<?php echo url('index/bbplayer/lists'); ?>?seasonid=<?php echo $thisseason['SeasonID']; ?>"><span class="icon-chevron-right2 icon"></span>球员列表</a></li>
                    <li>
                        <dl>
                            <dt>排行榜</dt>
                            <dd><a href="<?php echo url('index/bbteam/rank',['seasonid'=>$thisseason['SeasonID']]); ?>">球队排行<span class="icon-chevron-right2 icon"></a></dd>
                            <dd><a href="<?php echo url('index/bbstatistics/lists'); ?>?seasonid=<?php echo $thisseason['SeasonID']; ?>">赛季统计<span class="icon-chevron-right2 icon"></a></dd>
                        </dl>
                    </li>
                    <li><a href="<?php echo url('index/bbteam/apply',['seasonid'=>$thisseason['SeasonID']]); ?>"><span class="icon-chevron-right2 icon"></span>加入赛事</a></li>
                    <li>
                        <dl>
                            <dt>其他赛季</dt>
                            <?php if(is_array($otherseasons) || $otherseasons instanceof \think\Collection || $otherseasons instanceof \think\Paginator): $i = 0; $__LIST__ = $otherseasons;if( count($__LIST__)==0 ) : echo "暂没其他赛季信息！" ;else: foreach($__LIST__ as $key=>$otherseason): $mod = ($i % 2 );++$i;?>
                            <dd><a href="<?php echo url('index/bbseason/read',['id'=>$otherseason['SeasonID']]); ?>"><?php echo $otherseason['SeasonName']; ?><span class="icon-chevron-right2 icon"></a></dd>
                            <?php endforeach; endif; else: echo "暂没其他赛季信息！" ;endif; ?>
                        </dl>
                    </li>
                </ul>
            </div>
            <div class="show-box">
                <div class="img-box"><img src="images/icon_10.png" alt=""></div>
                <h3>现在开始</h3>
                <p>加入协会并参与比赛!</p>
                <a href="<?php echo url('index/bbteam/apply',['seasonid'=>$thisseason['SeasonID']]); ?>">加入赛事<span class="icon-chevron-right icon"></span></a>
            </div>
        </div>
    </div>
</div>
<div class="public-footer">
    <div class="footer-body block">
        <span class="title-box">友情链接</span>
        <div class="all-item clearfix">
            <?php if(is_array($sponsor) || $sponsor instanceof \think\Collection || $sponsor instanceof \think\Paginator): $i = 0; $__LIST__ = $sponsor;if( count($__LIST__)==0 ) : echo "暂时没有信息！" ;else: foreach($__LIST__ as $key=>$vo_sponsor): $mod = ($i % 2 );++$i;?>
            <div class="item-box">
                <a href="<?php echo $vo_sponsor['Link']; ?>">
                    <img src="<?php echo \think\Config::get('public_assets'); ?>/uploads/<?php echo $vo_sponsor['Logo']; ?>" style="width:100px;height:75px">
                </a>
            </div>
            <?php endforeach; endif; else: echo "暂时没有信息！" ;endif; ?>
        </div>
    </div>
    <div class="bottom-box">
        Copyright © 2019 SVCSA All Rights Reserved
    </div>
</div>
<script type="text/javascript" src="<?php echo \think\Config::get('public_assets'); ?>/js/swiper.min.js"></script>
<script type="text/javascript" src="<?php echo \think\Config::get('public_assets'); ?>/js/wow.js"></script>
<script type="text/javascript" src="<?php echo \think\Config::get('public_assets'); ?>/js/comm.js"></script>
<script type="text/javascript">
    var swiper = new Swiper('.banner-container', {
        pagination: {
            el: '.banner-container .swiper-pagination',
            clickable: true
        },
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        }

    });
    $('.session_3 .tab-box span').click(function(){
        var index = $(this).index();
        $(this).addClass('active').siblings().removeClass('active');
        $('.session_3 .all-box .list-box').eq(index).addClass('active').siblings().removeClass('active')
        console.log(index);
    })
</script>
</body>
</html>
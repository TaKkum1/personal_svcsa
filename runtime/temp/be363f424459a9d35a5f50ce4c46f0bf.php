<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:82:"/Users/xinliu-mbp/Git/svcsa_web/public/../application/index/view/public/index.html";i:1566591634;s:66:"/Users/xinliu-mbp/Git/svcsa_web/application/index/view/layout.html";i:1566591634;s:73:"/Users/xinliu-mbp/Git/svcsa_web/application/index/view/public/header.html";i:1566591634;s:73:"/Users/xinliu-mbp/Git/svcsa_web/application/index/view/public/footer.html";i:1566591634;}*/ ?>
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
<div class="flexslider">
    <div class="swiper-container banner-container">
        <div class="swiper-wrapper">
            <?php if(is_array($swipers) || $swipers instanceof \think\Collection || $swipers instanceof \think\Paginator): $i = 0; $__LIST__ = $swipers;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$swiper): $mod = ($i % 2 );++$i;?>
            <div class="swiper-slide">
                <div class="txt-box">
                    <h3><?php echo $swiper['Name']; ?></h3>
                    <p><?php echo $swiper['Description']; ?></p>
                </div>
                <img src="<?php echo \think\Config::get('public_assets'); ?>/uploads/<?php echo $swiper['Picture']; ?>">
            </div>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <div class="swiper-pagination block"></div>
    </div>
</div>
<div class="arr"></div>
</div>
<div class="session_1 block">
    <div class="title-box clearfix">
        <a href="<?php echo url('index/article/newslists'); ?>" class="more">查看更多</a>
        最新新闻
    </div>
    <div class="row clearfix">
        <?php if(is_array($recentnews) || $recentnews instanceof \think\Collection || $recentnews instanceof \think\Paginator): $i = 0; $__LIST__ = $recentnews;if( count($__LIST__)==0 ) : echo "暂没最近新闻！" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?>
        <div class="col-md-4">
                <span class="img-box">
                    <div class="pd">
                        <img src="<?php echo getArticleImg($vo1['Content']); ?>">
                        <div class="time-box">
                            <span><?php echo date('D j',strtotime($vo1['Posttime'])); ?></span>
                        </div>
                    </div>
                </span>
            <div class="txt-box">
                <h3><a href="<?php echo url('index/article/newsread',['id' =>$vo1['ID']]); ?>" title="<?php echo $vo1['Title']; ?>"><?php echo msubstr($vo1['Title'],0,20); ?></a></h3>
                <p><?php echo getAbstract($vo1['Content'],330); ?>...</p>
                <div class="more">
                    <a href="<?php echo url('index/article/newsread',['id' =>$vo1['ID']]); ?>">阅读全文>><i class="icon-chevron-right"></i></a>
                </div>
            </div>
        </div>
        <?php endforeach; endif; else: echo "暂没最近新闻！" ;endif; ?>
    </div>
</div>
<div class="session_2">
    <div class="block clearfix">
        <div class="left-box clearfix">
            <div class="col-md-6">
                <div class="item-box">
                    <h3><?php echo $competitioncount; ?></h3>
                    <p>个项目</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="item-box br">
                    <h3><?php echo $bbteamcount; ?></h3>
                    <p>球队</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="item-box br">
                    <h3><?php echo $bbplayercount; ?></h3>
                    <p>个球员</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="item-box">
                    <h3><?php echo $bbmatchcount; ?></h3>
                    <p>场比赛</p>
                </div>
            </div>
        </div>
        <div class="right-box">
            <div class="title-box">
                <p>硅谷中的华人篮球</p>
                <h3>WE ARE LEADING THE WAY IN BASKETBALL</h3>
            </div>
            <p>BASKETBALL are the governing body for BASKETBALL in USA, with the game also being played in strongholds worldwide such as USA, Canada, Mexico, Puerto Rico, UK, and Spain; .</p>
            <div class="btns-box">
                <a href="<?php echo url('index/apply/apply'); ?>" class="show">申请球队<i class="icon-chevron-right"></i></a>
                <a href="<?php echo url('index/bbplayer/lists'); ?>" class="show">查看选手<i class="icon-chevron-right"></i></a>
            </div>
        </div>
    </div>
</div>
<div class="session_3">
    <div class="block">
        <div class="tab-box">
            <span class="active">男篮</span>
            <span>女篮</span>
            <span>青少年蓝</span>
            <span>田径</span>
        </div>
        <div class="all-box">
            <div class="list-box active">
                <div class="row clearfix">
                    <?php if(is_array($bbmanplayers) || $bbmanplayers instanceof \think\Collection || $bbmanplayers instanceof \think\Paginator): $i = 0;$__LIST__ = is_array($bbmanplayers) ? array_slice($bbmanplayers,0,5, true) : $bbmanplayers->slice(0,5, true); if( count($__LIST__)==0 ) : echo "此分类暂没球员" ;else: foreach($__LIST__ as $key=>$player): $mod = ($i % 2 );++$i;?>
                    <div class="col-md-2">
                    <a href="<?php echo url('index/bbplayer/read',['id'=>$player['PlayerID']]); ?>">
                                <div class="item-box">
                                    <div class="img-box">
                                        <img src="<?php echo \think\Config::get('public_assets'); ?>/uploads/<?php echo $player['PlayerPhotoSrc']; ?>">
                                    </div>
                                    <div class="txt-box">
                                        <?php echo $player['PlayerName']; ?>
                                    </div>
                                </div>
                            </a>
                    </div>
                    <?php endforeach; endif; else: echo "此分类暂没球员" ;endif; ?>
                    <!--<div class="col-md-2">
                        <a href="">
                            <div class="item-box">
                                <div class="img-box">
                                    <img src="images/sev_2.jpg">
                                </div>
                                <div class="txt-box">
                                    王福清
                                </div>
                            </div>
                        </a>
                    </div>-->
                </div>
                <div class="other-box">
                    <a href="<?php echo url('index/bbplayer/lists',['competitionid'=>1]); ?>" class="more">查看更多</a>
                </div>
            </div>
            <div class="list-box">
                <div class="row clearfix">
                    <?php if(is_array($bbwomanplayers) || $bbwomanplayers instanceof \think\Collection || $bbwomanplayers instanceof \think\Paginator): $i = 0;$__LIST__ = is_array($bbwomanplayers) ? array_slice($bbwomanplayers,0,5, true) : $bbwomanplayers->slice(0,5, true); if( count($__LIST__)==0 ) : echo "此分类暂没球员" ;else: foreach($__LIST__ as $key=>$player): $mod = ($i % 2 );++$i;?>
                    <div class="col-md-2">
                        <a href="<?php echo url('index/bbplayer/read',['id'=>$player['PlayerID']]); ?>">
                            <div class="item-box">
                                <div class="img-box">
                                    <img src="<?php echo \think\Config::get('public_assets'); ?>/uploads/<?php echo $player['PlayerPhotoSrc']; ?>">
                                </div>
                                <div class="txt-box">
                                    <?php echo $player['PlayerName']; ?>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endforeach; endif; else: echo "此分类暂没球员" ;endif; ?>
                </div>
                <div class="other-box">
                    <a href="" class="more">查看更多</a>
                </div>
            </div>
            <div class="list-box">
                <div class="row clearfix">
                    <?php if(is_array($bbyouthplayers) || $bbyouthplayers instanceof \think\Collection || $bbyouthplayers instanceof \think\Paginator): $i = 0;$__LIST__ = is_array($bbyouthplayers) ? array_slice($bbyouthplayers,0,5, true) : $bbyouthplayers->slice(0,5, true); if( count($__LIST__)==0 ) : echo "此分类暂没球员" ;else: foreach($__LIST__ as $key=>$player): $mod = ($i % 2 );++$i;?>
                    <div class="col-md-2">
                        <a href="<?php echo url('index/bbplayer/read',['id'=>$player['PlayerID']]); ?>">
                            <div class="item-box">
                                <div class="img-box">
                                    <img src="<?php echo \think\Config::get('public_assets'); ?>/uploads/<?php echo $player['PlayerPhotoSrc']; ?>">
                                </div>
                                <div class="txt-box">
                                    <?php echo $player['PlayerName']; ?>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endforeach; endif; else: echo "此分类暂没球员" ;endif; ?>
                </div>
                <div class="other-box">
                    <a href="" class="more">查看更多</a>
                </div>
            </div>
            <div class="list-box">
                <div class="row clearfix">
                    <?php if(is_array($ctfcplayers) || $ctfcplayers instanceof \think\Collection || $ctfcplayers instanceof \think\Paginator): $i = 0;$__LIST__ = is_array($ctfcplayers) ? array_slice($ctfcplayers,0,5, true) : $ctfcplayers->slice(0,5, true); if( count($__LIST__)==0 ) : echo "此分类暂没球员" ;else: foreach($__LIST__ as $key=>$player): $mod = ($i % 2 );++$i;?>
                    <div class="col-md-2">
                        <a href="<?php echo url('index/ctfcplayer/read',['id'=>$player['ID']]); ?>">
                            <div class="item-box">
                                <div class="img-box">
                                    <img src="<?php echo \think\Config::get('public_assets'); ?>/uploads/<?php echo $player['PhotoSrc']; ?>">
                                </div>
                                <div class="txt-box">
                                    <?php echo $player['Name']; ?>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endforeach; endif; else: echo "此分类暂没球员" ;endif; ?>
                </div>
                <div class="other-box">
                    <a href="" class="more">查看更多</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="session_4">
    <div class="block">
        <div class="left-box">
            <div class="title-box">
                <p>协会中的比赛</p>
                <h3>查看选手、球队、赛程并加入我们</h3>
            </div>
            <p class="info">点击下方查看有关信息</p>
            <div class="btns-box">
                <a href="<?php echo url('index/apply/apply'); ?>">申请加入<i class="icon-chevron-right"></i></a>
                <a href="<?php echo url('index/bbseason/read',['id' => $recentseasonid]); ?>">赛程<i class="icon-chevron-right"></i></a>
                <a href="<?php echo url('index/bbteam/rank',['seasonid' => $recentseasonid]); ?>">排行榜<i class="icon-chevron-right"></i></a>
                <a href="<?php echo url('index/bbteam/lists',['seasonid' => $recentseasonid]); ?>">球队<i class="icon-chevron-right"></i></a>
            </div>
        </div>
    </div>
</div>
<div class="session_5">
    <div class="block">
        <div class="title-box"><a href="<?php echo url('index/bbteam/lists',['seasonid' => $recentseasonid]); ?>" class="more">查看更多</a>热门球队</div>
        <div class="list-box">
            <div class="row clearfix">
                <?php if(is_array($bbteams) || $bbteams instanceof \think\Collection || $bbteams instanceof \think\Paginator): $i = 0; $__LIST__ = $bbteams;if( count($__LIST__)==0 ) : echo "暂没球队信息！" ;else: foreach($__LIST__ as $key=>$bbteam): $mod = ($i % 2 );++$i;?>
                <div class="col-md-2">
                    <a href="<?php echo url('index/bbteam/read',['id'=>$bbteam['ID']]); ?>">
                        <div class="item-box">
                            <div class="img-box">
                                <img src="<?php echo \think\Config::get('public_assets'); ?>/uploads/<?php echo $bbteam['LogoSrc']; ?>">
                            </div>
                            <div class="txt-box">
                                <?php echo $bbteam['ShortName']; ?>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; endif; else: echo "暂没球队信息！" ;endif; ?>
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
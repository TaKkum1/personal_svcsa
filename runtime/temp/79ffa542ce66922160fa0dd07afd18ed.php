<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:83:"/Users/xinliu-mbp/Git/svcsa_web/public/../application/index/view/player/bbread.html";i:1566591634;s:66:"/Users/xinliu-mbp/Git/svcsa_web/application/index/view/layout.html";i:1566591634;s:73:"/Users/xinliu-mbp/Git/svcsa_web/application/index/view/public/header.html";i:1566591634;s:73:"/Users/xinliu-mbp/Git/svcsa_web/application/index/view/public/footer.html";i:1566591634;}*/ ?>
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
<div class="inside-banner block athlete-banner" style="background: url(<?php echo \think\Config::get('public_assets'); ?>/uploads/<?php echo $player['PlayerPhotoSrc']; ?>) right bottom no-repeat;background-size: contain;">
    <h3>球员简介</h3>
    <div class="introduce-box">
        <span class="name"><?php echo $player['PlayerName']; ?></span>
        <div class="info">
            <div class="item">
                号码<i><?php echo $player['Number']; ?></i>
            </div><!--
            <div class="item">
                年龄<i></i>
            </div>-->
            <div class="item">
                身高<i><?php echo $player['Height']; ?>cm</i>
            </div>
            <div class="item">
                体重<i><?php echo $player['Weight']; ?>Kg</i>
            </div>
        </div>
        <div class="session_1">
            <table>
                <thead>
                <tr>
                    <th>电子邮箱</th>
                    <th>所在球队</th>
                    <th>所属赛季</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?php echo $player['PlayerEmail']; ?></td>
                    <td><?php echo !empty($player['TeamName'])?$player['TeamName']:'未分配'; ?></td>
                    <td><?php echo $player['SeasonName']; ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
<div class="athlete-wrap">
    <div class="block">
        <div class="ath_des-box">
            <div class="tab-box">
                <span class="active"><?php echo $player['CompetitionName']; ?></span>
            </div>
        </div>
        <div class="all-box">
            <div class="list-box active">
                <div class="row clearfix">
                    <?php if(is_array($players) || $players instanceof \think\Collection || $players instanceof \think\Paginator): $i = 0; $__LIST__ = $players;if( count($__LIST__)==0 ) : echo "暂没球员信息！" ;else: foreach($__LIST__ as $key=>$player): $mod = ($i % 2 );++$i;?>
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
                    <?php endforeach; endif; else: echo "暂没球员信息！" ;endif; ?>
                </div>
                <div class="other-box">
                    <a href="<?php echo url('index/bbplayer/lists'); ?>?seasonid=<?php echo $player['SeasonID']; ?>" class="more">查看更多</a>
                </div>
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
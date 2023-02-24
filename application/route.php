<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

\think\Route::get('phpinfo', 'index/index/phpinfo');
\think\Route::get('', 'index/index/index');

\think\Route::rule('/:controller', 'index/Base/optionpass','OPTIONS', [], ['id' => '.+']);

\think\Route::get('system', 'index/system/read');
\think\Route::post('system', 'index/system/update');
\think\Route::get('contactus', 'index/system/contactus');
\think\Route::post('uploadimage', 'index/system/uploadImage');

\think\Route::get('mail', 'index/index/mail');

\think\Route::get('apply', 'index/apply/apply');

\think\Route::get('news/:id', 'index/article/newsread', [], ['id' => '\d+']);
\think\Route::get('news', 'index/article/newslists');

\think\Route::get('faq/:id', 'index/article/faqread', [], ['id' => '\d+']);
\think\Route::get('faq', 'index/article/faqlists');

\think\Route::get('player', 'index/bbplayer/lists',['competitionid'=>1]);


\think\Route::post('article/:id', 'index/article/update', [], ['id' => '\d+']);
\think\Route::post('article', 'index/article/add');
\think\Route::delete('article/:id', 'index/article/delete', [], ['id' => '\d+']);
\think\Route::get('article/:id', 'index/article/read', [], ['id' => '\d+']);
\think\Route::get('article', 'index/article/lists');


\think\Route::post('authorization/:id', 'index/authorization/update', [], ['id' => '\d+']);
\think\Route::post('authorization', 'index/authorization/add');
\think\Route::get('authorization/login', 'index/authorization/login');
\think\Route::get('authorization/logout', 'index/authorization/logout');
\think\Route::get('authorization/status', 'index/authorization/status');
\think\Route::delete('authorization/:id', 'index/authorization/delete', [], ['id' => '\d+']);
\think\Route::get('authorization/:id', 'index/authorization/read', [], ['id' => '\d+']);
\think\Route::get('authorization', 'index/authorization/lists');


\think\Route::post('bbmatch/:matchid/bbstatistics', 'index/bbstatistics/add',
    [], ['matchid' => '\d+']);
\think\Route::post('bbstatistics/:id', 'index/bbstatistics/update', [], ['id' => '\d+']);
\think\Route::post('bbstatistics', 'index/bbstatistics/add');
\think\Route::delete('bbstatistics/:id', 'index/bbstatistics/delete', [], ['id' => '\d+']);
\think\Route::get('bbstatistics/:id', 'index/bbstatistics/read', [], ['id' => '\d+']);
//\think\Route::get('bbstatistics?MatchID=:matchid&TeamID=:teamid',
//    'index/bbstatistics/readViewByMatchTeam', [], ['matchid' => '\d+','teamid' => '\d+']);
//\think\Route::get('bbstatistics?SeasonID=:seasonid&PlayerID=:playerid',
//    'index/bbstatistics/readViewBySeasonPlayer', [], ['seasonid' => '\d+','playerid' => '\d+']);
\think\Route::get('bbstatistics', 'index/bbstatistics/lists');


\think\Route::post('bbseason/:seasonid/bbmatch', 'index/bbmatch/add', [], ['seasonid' => '\d+']);
\think\Route::get('bbseason/:seasonid/bbmatch', 'index/bbmatch/lists', [], ['seasonid' => '\d+']);
\think\Route::post('bbmatch/:id', 'index/bbmatch/update', [], ['id' => '\d+']);
\think\Route::post('bbmatch', 'index/bbmatch/add');
\think\Route::get('bbmatch/:matchid/bbpicture', 'index/bbpicture/lists', [], ['matchid' => '\d+']);
\think\Route::delete('bbmatch/:id', 'index/bbmatch/delete', [], ['id' => '\d+']);
\think\Route::get('bbmatch/:id', 'index/bbmatch/read', [], ['id' => '\d+']);
\think\Route::get('bbmatch', 'index/bbmatch/lists');

\think\Route::get('gallery/:category', 'index/picture/lists', [], ['category' => '\S+']);
\think\Route::post('bbpicture/:id', 'index/bbpicture/update', [], ['id' => '\d+']);
\think\Route::post('bbpicture', 'index/bbpicture/add');
\think\Route::delete('bbpicture/:id', 'index/bbpicture/delete', [], ['id' => '\d+']);
\think\Route::get('bbpicture/:id', 'index/bbpicture/read', [], ['id' => '\d+']);
\think\Route::get('bbpicture', 'index/bbpicture/lists');

\think\Route::post('bbseason/:seasonid/bbplayer', 'index/bbplayer/add', [], ['seasonid' => '\d+']);
\think\Route::get('bbteam/:teamid/bbplayer', 'index/bbplayer/lists', [], ['teamid' => '\d+']);
\think\Route::get('bbseason/:seasonid/bbplayer', 'index/bbplayer/lists', [], ['seasonid' => '\d+']);
\think\Route::post('bbplayer/:id', 'index/bbplayer/update', [], ['id' => '\d+']);
\think\Route::post('bbplayer', 'index/bbplayer/add');
\think\Route::post('bbplayer/SetTeam', 'index/bbplayer/setTeam');
\think\Route::post('bbplayer/setteam', 'index/bbplayer/setTeam');
\think\Route::delete('bbplayer/:id', 'index/bbplayer/delete', [], ['id' => '\d+']);
\think\Route::get('bbplayer/:id', 'index/bbplayer/read', [], ['id' => '\d+']);
\think\Route::get('bbplayer', 'index/bbplayer/lists');
\think\Route::post('bbplayer/apply', 'index/bbplayer/apply');
\think\Route::get('bbplayer/apply', 'index/bbplayer/getapply');

\think\Route::get('bbseason/:seasonid/bbteam', 'index/bbteam/lists', [], ['seasonid' => '\d+']);
\think\Route::get('bbseason/:seasonid/bbteamrank', 'index/bbteam/rank', [], ['seasonid' => '\d+']);
\think\Route::get('bbseason/:seasonid/bbteamrankplayoff', 'index/bbteam/rankplayoff', [], ['seasonid' => '\d+']);
\think\Route::post('bbseason/:seasonid/bbteam', 'index/bbteam/add', [], ['seasonid' => '\d+']);
\think\Route::post('bbseason/:seasonid/apply', 'index/bbteam/apply', [], ['seasonid' => '\d+']);
\think\Route::get('bbseason/:seasonid/apply', 'index/bbteam/getapply', [], ['seasonid' => '\d+']);
\think\Route::get('bbseason/:id/playoff', 'index/bbseason/playoff', [], ['id' => '\d+']);

\think\Route::post('bbteam/passapp', 'index/bbteam/passApplication');
\think\Route::post('bbteam/:id', 'index/bbteam/update', [], ['id' => '\d+']);
\think\Route::post('bbteam', 'index/bbteam/add');
\think\Route::delete('bbteam/:id', 'index/bbteam/delete', [], ['id' => '\d+']);
\think\Route::get('bbteam/:id', 'index/bbteam/read', [], ['id' => '\d+']);
\think\Route::get('bbteam', 'index/bbteam/lists', [], ['id' => '\d+']);


\think\Route::post('bbcompetition/:competitionid/bbseason', 'index/bbseason/add', [], ['competitionid' => '\d+']);
\think\Route::get('bbcompetition/:competitionid/recentseason', 'index/bbseason/readRecent', [], ['competitionid' => '\d+']);
\think\Route::post('bbseason/:bbseasonid/bbplayer', 'index/bbplayer/add', [], ['bbseasonid' => '\d+']);
\think\Route::post('bbseason/:id', 'index/bbseason/update', [], ['id' => '\d+']);
\think\Route::post('bbseason', 'index/bbseason/add');
\think\Route::delete('bbseason/:id', 'index/bbseason/delete', [], ['id' => '\d+']);
\think\Route::get('bbseason/:id', 'index/bbseason/read', [], ['id' => '\d+']);
\think\Route::get('bbseason', 'index/bbseason/lists');

\think\Route::get('bbplayoff', 'index/bbplayoff/lists');
\think\Route::post('bbplayoff/:id', 'index/bbplayoff/update', [], ['id' => '\d+']);
\think\Route::post('bbplayoff', 'index/bbplayoff/add');
\think\Route::delete('bbplayoff/:id', 'index/bbplayoff/delete', [], ['id' => '\d+']);


\think\Route::post('bbcompetition/:id', 'index/bbcompetition/update', [], ['id' => '\d+']);
\think\Route::post('bbcompetition', 'index/bbcompetition/add');
\think\Route::delete('bbcompetition/:id', 'index/bbcompetition/delete', [], ['id' => '\d+']);
\think\Route::get('bbcompetition/:id', 'index/bbcompetition/read', [], ['id' => '\d+']);
\think\Route::get('bbcompetition', 'index/bbcompetition/lists');

\think\Route::post('bblog', 'index/bblog/add');

\think\Route::get('ctfc', 'index/ctfcseason/readrecent');

\think\Route::post('ctfcevent/:id', 'index/ctfcevent/update', [], ['id' => '\d+']);
\think\Route::post('ctfcevent', 'index/ctfcevent/add');
\think\Route::delete('ctfcevent/:id', 'index/ctfcevent/delete', [], ['id' => '\d+']);
\think\Route::get('ctfcevent/:id', 'index/ctfcevent/read', [], ['id' => '\d+']);
\think\Route::get('ctfcevent', 'index/ctfcevent/lists');



\think\Route::post('ctfcpicture/:id', 'index/ctfcpicture/update', [], ['id' => '\d+']);
\think\Route::post('ctfcpicture', 'index/ctfcpicture/add');
\think\Route::delete('ctfcpicture/:id', 'index/ctfcpicture/delete', [], ['id' => '\d+']);
\think\Route::get('ctfcpicture/:id', 'index/ctfcpicture/read', [], ['id' => '\d+']);
\think\Route::get('ctfcpicture', 'index/ctfcpicture/lists');

\think\Route::post('ctfcmatch/:matchid/apply', 'index/ctfcplayer/apply', [], ['matchid' => '\d+']);
\think\Route::get('ctfcmatch/:matchid/apply', 'index/ctfcplayer/getapply', [], ['matchid' => '\d+']);
\think\Route::post('ctfcplayer/passapp', 'index/ctfcplayer/passApplication');
\think\Route::post('ctfcplayer/:id', 'index/ctfcplayer/update', [], ['id' => '\d+']);
\think\Route::post('ctfcplayer', 'index/ctfcplayer/add');
\think\Route::delete('ctfcplayer/:id', 'index/ctfcplayer/delete', [], ['id' => '\d+']);
\think\Route::get('ctfcplayer/:id', 'index/ctfcplayer/read', [], ['id' => '\d+']);
\think\Route::get('ctfcplayer', 'index/ctfcplayer/lists');

# route for 田径运动队 
\think\Route::post('ctfcteam/passapp', 'index/ctfcteam/passApplication');
\think\Route::post('ctfcteam/:id', 'index/ctfcteam/update', [], ['id' => '\d+']);
\think\Route::post('ctfcteam', 'index/ctfcteam/add');
\think\Route::delete('ctfcteam/:id', 'index/ctfcteam/delete', [], ['id' => '\d+']);
\think\Route::get('ctfcteam/:id', 'index/ctfcteam/read', [], ['id' => '\d+']);
\think\Route::get('ctfcteam', 'index/ctfcteam/lists');

\think\Route::post('ctfcmatch/:matchid/ctfcstatistics', 'index/ctfcstatistics/add', [], ['matchid' => '\d+']);
\think\Route::post('ctfcmatch/:id', 'index/ctfcmatch/update', [], ['id' => '\d+']);
\think\Route::post('ctfcmatch', 'index/ctfcmatch/add');
\think\Route::delete('ctfcmatch/:id', 'index/ctfcmatch/delete', [], ['id' => '\d+']);
\think\Route::get('ctfcmatch/:matchid', 'index/ctfcmatch/read', [], ['matchid' => '\d+']);
\think\Route::get('ctfcmatch', 'index/ctfcmatch/lists');

\think\Route::post('ctfcseason/:id', 'index/ctfcseason/update', [], ['id' => '\d+']);
\think\Route::post('ctfcseason', 'index/ctfcseason/add');
\think\Route::delete('ctfcseason/:id', 'index/ctfcseason/delete', [], ['id' => '\d+']);
\think\Route::get('ctfcseason/:id', 'index/ctfcseason/read', [], ['id' => '\d+']);
\think\Route::get('ctfcseason', 'index/ctfcseason/lists');

\think\Route::post('ctfcstatistics/:id', 'index/ctfcstatistics/update', [], ['id' => '\d+']);
\think\Route::post('ctfcstatistics', 'index/ctfcstatistics/add');
\think\Route::delete('ctfcstatistics/:id', 'index/ctfcstatistics/delete', [], ['id' => '\d+']);
\think\Route::get('ctfcstatistics/:id', 'index/ctfcstatistics/read', [], ['id' => '\d+']);
\think\Route::get('ctfcstatistics', 'index/ctfcstatistics/lists');

\think\Route::post('sponsor/:id', 'index/sponsor/update', [], ['id' => '\d+']);
\think\Route::post('sponsor', 'index/sponsor/add');
\think\Route::delete('sponsor/:id', 'index/sponsor/delete', [], ['id' => '\d+']);
\think\Route::get('sponsor/:id', 'index/sponsor/read', [], ['id' => '\d+']);
\think\Route::get('sponsor', 'index/sponsor/lists');

\think\Route::post('ctfcseasonteam/passapp', 'index/ctfcseasonteam/passApplication');
\think\Route::post('ctfcseasonteam/:seasonid/:teamid', 'index/ctfcseasonteam/update', [], ['seasonid' => '\d+'],['teamid' => '\d+']);
\think\Route::post('ctfcseasonteam', 'index/ctfcseasonteam/add');
\think\Route::delete('ctfcseasonteam/:seasonid/:teamid', 'index/ctfcseasonteam/delete', [], ['seasonid' => '\d+'],['teamid' => '\d+']);
\think\Route::get('ctfcseasonteam/:id', 'index/ctfcseasonteam/read', [], ['id' => '\d+']);
\think\Route::get('ctfcseasonteam', 'index/ctfcseasonteam/lists');

\think\Route::post('ctfcagegroup/:id', 'index/ctfcagegroup/update', [], ['id' => '\d+']);
\think\Route::post('ctfcagegroup', 'index/ctfcagegroup/add');
\think\Route::delete('ctfcagegroup/:id', 'index/ctfcagegroup/delete', [], ['id' => '\d+']);
\think\Route::get('ctfcagegroup/:id', 'index/ctfcagegroup/read', [], ['id' => '\d+']);
\think\Route::get('ctfcagegroup', 'index/ctfcagegroup/lists');

\think\Route::post('ctfcseasonitem/:seasonid/:itemid/:sex', 'index/ctfcseasonitem/update', [], ['seasonid' => '\d+'],['itemid' => '\d+'],['sex' => '\d+']);
\think\Route::post('ctfcseasonitem', 'index/ctfcseasonitem/add');
\think\Route::delete('ctfcseasonitem/:seasonid/:itemid/:sex', 'index/ctfcseasonitem/delete', [], ['seasonid' => '\d+'],['itemid' => '\d+'],['sex' => '\d+']);
\think\Route::get('ctfcseasonitem/:id', 'index/ctfcseasonitem/read', [], ['id' => '\d+']);
\think\Route::get('ctfcseasonitem', 'index/ctfcseasonitem/lists');

\think\Route::post('ctfcplayernumber/:seasonid/:teamid/:playerid', 'index/ctfcplayernumber/update', [], ['seasonid' => '\d+'],['teamid' => '\d+'],['playerid' => '\d+']);
\think\Route::delete('ctfcplayernumber/:seasonid/:teamid/:playerid', 'index/ctfcplayernumber/delete', [], ['seasonid' => '\d+'],['teamid' => '\d+'],['playerid' => '\d+']);
\think\Route::post('ctfcplayernumber', 'index/ctfcplayernumber/add');
\think\Route::get('ctfcplayernumber/:id', 'index/ctfcplayernumber/read', [], ['id' => '\d+']);
\think\Route::get('ctfcplayernumber', 'index/ctfcplayernumber/lists');



\think\Route::post('ctfcitemplayer/:id', 'index/ctfcitemplayer/update', [], ['id' => '\d+']);
\think\Route::post('ctfcitemplayer', 'index/ctfcitemplayer/add');
\think\Route::delete('ctfcitemplayer/:id', 'index/ctfcitemplayer/delete', [], ['id' => '\d+']);
\think\Route::get('ctfcitemplayer/:id', 'index/ctfcitemplayer/read', [], ['id' => '\d+']);
\think\Route::get('ctfcitemplayer', 'index/ctfcitemplayer/lists');
\think\Route::get('ctfcitemplayer/getTypeOfItem', 'index/ctfcitemplayer/getItemType');
\think\Route::get('ctfcitemplayer/getSeasonTeamPlayers', 'index/ctfcitemplayer/YougetSeasonTeamPlayers');


return [

//    '[bbcompetition]' => [
//        ':id'   => ['index/bbcompetition/read', ['method' => 'get'], ['id' => '\d+']],
//        ':id'   => ['index/bbcompetition/delete', ['method' => 'delete'], ['id' => '\d+']],
//    ],

];

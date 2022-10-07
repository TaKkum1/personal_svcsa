<?php
/**
 * Created by PhpStorm.
 * User: Aven
 * Date: 2018/10/28
 * Time: 2:40
 */

namespace app\index\validate;


use think\Validate;

class BbSeason extends Validate
{
    protected $rule = [
        'Name'  => 'require',
        'TeamNumber'  => 'require',
        'CompetitionID'  => 'require',
    ];
}
<?php
/**
 * Created by PhpStorm.
 * User: Aven
 * Date: 2018/10/28
 * Time: 2:40
 */

namespace app\index\validate;


use think\Validate;

class BbStatistics extends Validate
{
    protected $rule = [
        'MatchID'  => 'require',
        'PlayerID'  => 'require'
    ];
}
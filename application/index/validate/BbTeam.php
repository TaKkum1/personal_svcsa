<?php
/**
 * Created by PhpStorm.
 * User: Aven
 * Date: 2018/10/28
 * Time: 2:40
 */

namespace app\index\validate;


use think\Validate;

class BbTeam extends Validate
{
    protected $rule = [
        'Name'  => 'require',
        'Captain'  => 'require',
    ];
}

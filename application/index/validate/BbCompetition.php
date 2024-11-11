<?php
/**
 * Created by PhpStorm.
 * User: Aven
 * Date: 2018/10/28
 * Time: 15:51
 */

namespace app\index\validate;


use think\Validate;

class BbCompetition extends Validate
{
    protected $rule = [
        'Name'  => 'require',
    ];
}
<?php
/**
 * Created by Yu Lu.
 * Date: Dec 28, 2022
 * Time: 15:50
 */

namespace app\index\validate;


use think\Validate;

class CtfcTeam extends Validate
{
    protected $rule = [
        'Name'  => 'require',
        'MatchID'  => 'require',
    ];
}
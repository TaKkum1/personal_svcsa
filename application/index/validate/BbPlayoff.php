<?php
/**
 * Created by PhpStorm.
 * User: Aven
 * Date: 2018/10/28
 * Time: 2:40
 */

namespace app\index\validate;


use think\Validate;

class BbPlayoff extends Validate
{
    protected $rule = [
        'Title'  => 'require',
        'Rule'  => 'require',
        'SeasonID' => 'require',
        'CompetitionID'  => 'require',
    ];
}
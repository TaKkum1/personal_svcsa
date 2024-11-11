<<<<<<< HEAD
<?php
/**
 * Created by PhpStorm.
 * User: Aven
 * Date: 2018/10/28
 * Time: 2:40
 */

namespace app\index\validate;


use think\Validate;

class CtfcStatistics extends Validate
{
    protected $rule = [
        'PlayerID'  => 'require',
        'MatchID'  => 'require'
    ];
=======
<?php
/**
 * Created by PhpStorm.
 * User: Aven
 * Date: 2018/10/28
 * Time: 2:40
 */

namespace app\index\validate;


use think\Validate;

class CtfcStatistics extends Validate
{
    protected $rule = [
        'PlayerID'  => 'require',
        'MatchID'  => 'require'
    ];
>>>>>>> 37313bc (Initial commit)
}
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

class CtfcMatch extends Validate
{
    protected $rule = [
        'EventID'  => 'require',
        'SeasonID'  => 'require'
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

class CtfcMatch extends Validate
{
    protected $rule = [
        'EventID'  => 'require',
        'SeasonID'  => 'require'
    ];
>>>>>>> 37313bc (Initial commit)
}
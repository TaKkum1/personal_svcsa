<<<<<<< HEAD
<?php
/**
 * Created by PhpStorm.
 * User: Aven
 * Date: 2018/10/28
 * Time: 1:30
 */

namespace app\index\controller;

require_once (__DIR__ . '/../../../thinkphp/library/think/db/Expression.php');

use think\Db;
use think\Session;
use think\Db\Expression;

class Bbplayoff extends Base
{
    const FIELD = 'Title,Rule,SeasonID,CompetitionID';

    public function add() {
      $this->checkauthorization();

      $data = request()->only(self::FIELD, 'post');
      $this->makeNull($data);
      $validator = validate('BbPlayoff');
      if (!$validator->check($data)) {
        $this->affectedRowsResult(0);
      }
      $result = Db::name('bb_playoff')->insert($data);
      $this->affectedRowsResult($result);
    }

    public function update($id) {
      $this->checkauthorization();

      $data = request()->only(self::FIELD, 'post');
      $this->makeNull($data);
      $result = Db::name('bb_playoff')->where('ID', $id)->update($data);
      $this->affectedRowsResult($result);
    }

    public function delete($id) {
      $this->checkauthorization();
      $result = Db::name('bb_playoff')->where('ID', $id)->delete();
      $this->affectedRowsResult($result);
    }

    public function lists() {
        $pagesize = input('pagesize');
        $list = Db::name('bb_playoff')->paginate($pagesize);
        if($this->jsonRequest()) {
            $this->paginatedResult(
                $list->total(),
                $list->listRows(),
                $list->currentPage(),
                $list->items()
            );
        }

notfound:
        header("HTTP/1.0 404 Not Found");
        die;
    }

}
=======
<?php
/**
 * Created by PhpStorm.
 * User: Aven
 * Date: 2018/10/28
 * Time: 1:30
 */

namespace app\index\controller;

require_once (__DIR__ . '/../../../thinkphp/library/think/db/Expression.php');

use think\Db;
use think\Session;
use think\Db\Expression;

class Bbplayoff extends Base
{
    const FIELD = 'Title,Rule,SeasonID,CompetitionID';

    public function add() {
      $this->checkauthorization();

      $data = request()->only(self::FIELD, 'post');
      $this->makeNull($data);
      $validator = validate('BbPlayoff');
      if (!$validator->check($data)) {
        $this->affectedRowsResult(0);
      }
      $result = Db::name('bb_playoff')->insert($data);
      $this->affectedRowsResult($result);
    }

    public function update($id) {
      $this->checkauthorization();

      $data = request()->only(self::FIELD, 'post');
      $this->makeNull($data);
      $result = Db::name('bb_playoff')->where('ID', $id)->update($data);
      $this->affectedRowsResult($result);
    }

    public function delete($id) {
      $this->checkauthorization();
      $result = Db::name('bb_playoff')->where('ID', $id)->delete();
      $this->affectedRowsResult($result);
    }

    public function lists() {
        $pagesize = input('pagesize');
        $list = Db::name('bb_playoff')->paginate($pagesize);
        if($this->jsonRequest()) {
            $this->paginatedResult(
                $list->total(),
                $list->listRows(),
                $list->currentPage(),
                $list->items()
            );
        }

notfound:
        header("HTTP/1.0 404 Not Found");
        die;
    }

}
>>>>>>> 37313bc (Initial commit)

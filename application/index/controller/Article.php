<<<<<<< HEAD
<?php
/**
 * Created by PhpStorm.
 * User: Aven
 * Date: 2018/10/28
 * Time: 1:30
 */

namespace app\index\controller;



use think\Db;
use think\Paginator;
use think\Session;

class Article extends Base
{
    const FIELD = 'Title,Content,Author,Posttime,Hits,Category,Top';

    public function add()
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        date_default_timezone_set("America/Los_Angeles");
        $data['Posttime'] = date("Y-m-d h:i:s");
        $this->makeNull($data);
        $validator = validate('Article');
        $result = $validator->check($data);
        if (!$result){
            $this->affectedRowsResult(0);
        }
        $result = Db::name('article')->insert($data);
        $this->affectedRowsResult($result);
    }

    public function delete($id){
        $this->checkauthorization();

        $result = Db::name('article')->where('ID', $id)->delete();
        $this->affectedRowsResult($result);
    }

    public function newsread($id){
        $this->headerAndFooter('news');


        $result = Db::name('article')->where('ID', $id)->find();
        if($this->jsonRequest()){
            $this->dataResult($result);
        } else if(count($result)>0){
            $view = $this->view;
            $view->assign('result',$result);
            Db::name('article')->where('ID', $id)->setInc('Hits');
            return $view->fetch();
        } else {
            header("HTTP/1.0 404 Not Found");
            die;
        }

    }

    public function faqread($id){
        $this->headerAndFooter('faq');


        $result = Db::name('article')->where('ID', $id)->find();
        if($this->jsonRequest()){
            $this->dataResult($result);
        } else if(count($result)>0){
            $view = $this->view;
            $view->assign('result',$result);
            Db::name('article')->where('ID', $id)->setInc('Hits');
            return $view->fetch();
        } else {
            header("HTTP/1.0 404 Not Found");
            die;
        }

    }

    public function lists()
    {
        $pagesize = input('pagesize');
        $list = Db::name('article')->paginate($pagesize);
        if ($this->jsonRequest()) {
            $this->paginatedResult(
                $list->total(),
                $list->listRows(),                $list->currentPage(),
                $list->items()
            );
        }

notfound:
        header("HTTP/1.0 404 Not Found");
        die;

    }

    public function newslists()
    {
        $this->headerAndFooter('news');

        $pagesize = input('pagesize');
        $page = input('page');
        $list = Db::name('article')->where('Category',0)->paginate($pagesize);
        $data = $list->items();
        if ($this->jsonRequest()) {
            $this->paginatedResult(
                $list->total(),
                $list->listRows(),
                $list->currentPage(),
                $list->items()
            );
        } else if(count($data)>0){
            $view = $this->view;

            $articlepaginate = Db::name('article')
                ->where('Category',0)
                ->order('Top desc')
                ->order('Posttime desc')
                ->paginate($pagesize);

            if(count($articlepaginate->items())<=0) goto notfound;

            $toparticle = array();
            $otherarticle= array();

            if(!$page || $page<=1) {
                $toparticle = array_slice($articlepaginate->items(),0,3);
                $otherarticle = array_slice($articlepaginate->items(),3);
            }else {
                $otherarticle = $articlepaginate->items();
            }


            $view->assign('pagerender',$articlepaginate->render());
            $view->assign('toparticle',$toparticle);
            $view->assign('otherarticle',$otherarticle);

            return $view->fetch();
        }

     notfound:
        header("HTTP/1.0 404 Not Found");
        die;

    }

    public function faqlists()
    {
        $this->headerAndFooter('faq');

        $pagesize = input('pagesize')?input('pagesize'):10;
        $page = input('page');
        $list = Db::name('article')->where('Category',-1)->paginate($pagesize);
        $data = $list->items();
        if ($this->jsonRequest()) {
            $this->paginatedResult(
                $list->total(),
                $list->listRows(),
                $list->currentPage(),
                $list->items()
            );
        } else if(count($data)>0){
            $view = $this->view;

            $articlepaginate = Db::name('article')
                ->where('Category',-1)
                ->order('Top desc')
                ->order('Posttime desc')
                ->paginate($pagesize);

            if(count($articlepaginate->items())<=0) goto notfound;


            $otherarticle = $articlepaginate->items();

            $view->assign('pagerender',$articlepaginate->render());
            $view->assign('otherarticle',$otherarticle);
            $view->assign('toparticle',null);

            return $view->fetch();
        }

        notfound:
        header("HTTP/1.0 404 Not Found");
        die;

    }

    public function update($id){
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        date_default_timezone_set("America/Los_Angeles");
        $data['Posttime'] = date("Y-m-d h:i:s");
        $this->makeNull($data);
//        $validator = validate('Article');
//        $result = $validator->check($data);
//        if (!$result){
//            $this->result(0);
//        }
        $result = Db::name('article')->where('ID', $id)->update($data);
        $this->affectedRowsResult($result);
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



use think\Db;
use think\Paginator;
use think\Session;

class Article extends Base
{
    const FIELD = 'Title,Content,Author,Posttime,Hits,Category,Top';

    public function add()
    {
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        date_default_timezone_set("America/Los_Angeles");
        $data['Posttime'] = date("Y-m-d h:i:s");
        $this->makeNull($data);
        $validator = validate('Article');
        $result = $validator->check($data);
        if (!$result){
            $this->affectedRowsResult(0);
        }
        $result = Db::name('article')->insert($data);
        $this->affectedRowsResult($result);
    }

    public function delete($id){
        $this->checkauthorization();

        $result = Db::name('article')->where('ID', $id)->delete();
        $this->affectedRowsResult($result);
    }

    public function newsread($id){
        $this->headerAndFooter('news');


        $result = Db::name('article')->where('ID', $id)->find();
        if($this->jsonRequest()){
            $this->dataResult($result);
        } else if(count($result)>0){
            $view = $this->view;
            $view->assign('result',$result);
            Db::name('article')->where('ID', $id)->setInc('Hits');
            return $view->fetch();
        } else {
            header("HTTP/1.0 404 Not Found");
            die;
        }

    }

    public function faqread($id){
        $this->headerAndFooter('faq');


        $result = Db::name('article')->where('ID', $id)->find();
        if($this->jsonRequest()){
            $this->dataResult($result);
        } else if(count($result)>0){
            $view = $this->view;
            $view->assign('result',$result);
            Db::name('article')->where('ID', $id)->setInc('Hits');
            return $view->fetch();
        } else {
            header("HTTP/1.0 404 Not Found");
            die;
        }

    }

    public function lists()
    {
        $pagesize = input('pagesize');
        $list = Db::name('article')->paginate($pagesize);
        if ($this->jsonRequest()) {
            $this->paginatedResult(
                $list->total(),
                $list->listRows(),                $list->currentPage(),
                $list->items()
            );
        }

notfound:
        header("HTTP/1.0 404 Not Found");
        die;

    }

    public function newslists()
    {
        $this->headerAndFooter('news');

        $pagesize = input('pagesize');
        $page = input('page');
        $list = Db::name('article')->where('Category',0)->paginate($pagesize);
        $data = $list->items();
        if ($this->jsonRequest()) {
            $this->paginatedResult(
                $list->total(),
                $list->listRows(),
                $list->currentPage(),
                $list->items()
            );
        } else if(count($data)>0){
            $view = $this->view;

            $articlepaginate = Db::name('article')
                ->where('Category',0)
                ->order('Top desc')
                ->order('Posttime desc')
                ->paginate($pagesize);

            if(count($articlepaginate->items())<=0) goto notfound;

            $toparticle = array();
            $otherarticle= array();

            if(!$page || $page<=1) {
                $toparticle = array_slice($articlepaginate->items(),0,3);
                $otherarticle = array_slice($articlepaginate->items(),3);
            }else {
                $otherarticle = $articlepaginate->items();
            }


            $view->assign('pagerender',$articlepaginate->render());
            $view->assign('toparticle',$toparticle);
            $view->assign('otherarticle',$otherarticle);

            return $view->fetch();
        }

     notfound:
        header("HTTP/1.0 404 Not Found");
        die;

    }

    public function faqlists()
    {
        $this->headerAndFooter('faq');

        $pagesize = input('pagesize')?input('pagesize'):10;
        $page = input('page');
        $list = Db::name('article')->where('Category',-1)->paginate($pagesize);
        $data = $list->items();
        if ($this->jsonRequest()) {
            $this->paginatedResult(
                $list->total(),
                $list->listRows(),
                $list->currentPage(),
                $list->items()
            );
        } else if(count($data)>0){
            $view = $this->view;

            $articlepaginate = Db::name('article')
                ->where('Category',-1)
                ->order('Top desc')
                ->order('Posttime desc')
                ->paginate($pagesize);

            if(count($articlepaginate->items())<=0) goto notfound;


            $otherarticle = $articlepaginate->items();

            $view->assign('pagerender',$articlepaginate->render());
            $view->assign('otherarticle',$otherarticle);
            $view->assign('toparticle',null);

            return $view->fetch();
        }

        notfound:
        header("HTTP/1.0 404 Not Found");
        die;

    }

    public function update($id){
        $this->checkauthorization();

        $data = request()->only(self::FIELD, 'post');
        date_default_timezone_set("America/Los_Angeles");
        $data['Posttime'] = date("Y-m-d h:i:s");
        $this->makeNull($data);
//        $validator = validate('Article');
//        $result = $validator->check($data);
//        if (!$result){
//            $this->result(0);
//        }
        $result = Db::name('article')->where('ID', $id)->update($data);
        $this->affectedRowsResult($result);
    }
}
>>>>>>> 37313bc (Initial commit)

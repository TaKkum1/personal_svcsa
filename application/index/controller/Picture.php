<?php
/**
 * Created by PhpStorm.
 * User: Aven
 * Date: 2018/10/28
 * Time: 1:30
 */

namespace app\index\controller;



use think\Db;

class Picture extends Base
{
    const FIELD = 'Src,Description';

    public function lists($category){
        $this->headerAndFooter('gallery');

        $pagesize = input('pagesize');
        $list = new Db\Query();
        $gallerytitle = '';
        if(strtolower($category)=='bb') {
            $list = Db::name('bb_picture')->order('Src Desc');
            $gallerytitle = '篮球图集';
        }else if(strtolower($category)=='ctfc'){
            $list = Db::name('ctfc_picture')->order('Src Desc');
            $gallerytitle = '田径图集';
        }else
        {
            $list = Db::name('picture')->order('Src Desc');
            $gallerytitle = '所有图集';
        }

        $list = $list->paginate($pagesize);

        $this->view->assign('gallerytitle',$gallerytitle);
        $this->view->assign('pagerender',$list->render());
        $this->view->assign('pictures',$list->items());

        return $this->view->fetch('picture/lists');
    }
}
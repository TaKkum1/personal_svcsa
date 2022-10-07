<?php
/**
 * Created by PhpStorm.
 * User: Aven
 * Date: 2018/10/28
 * Time: 1:30
 */

namespace app\index\controller;

use think\Db;
use think\Session;

class System extends Base
{
    public function update(){
        $this->checkauthorization();

        $field = 'Contact,SMTP,Email,EmailPassword,TwilioSid,TwilioAuth,TwilioNumber';
        $data = request()->only($field, 'post');
        if (Db::name('system')->find()){
            $result = Db::name('system')->where('1=1')->update($data);
        }else{
            $result = Db::name('system')->insert($data);
        }
        $this->affectedRowsResult($result);
    }

    public function read(){
        $this->checkauthorization();

        $data = Db::name('system')->find();
        $this->dataResult($data);
    }

    public function uploadImages(){
        $this->checkauthorization();

        // 获取表单上传文件
        $files = request()->file('image');

        $resultarr = array();
        $assetUrl = getAssetUploadUrl();

        foreach($files as $file){
            // 移动到框架应用根目录/uploads/ 目录下
            $info = $file->validate(['ext'=>'jpg,png,gif,jpeg'])->move( __DIR__ . $assetUrl);
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                $ext = $info->getExtension();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                $filename = $info->getFilename();

                $savename = $info->getFileName();

                array_push($resultarr,['savename'=>
                    str_replace('\\','/',$savename)]);

            }else{
                // 上传失败获取错误信息
                array_push($resultarr,['savename'=>null]);
            }
        }
        $this->dataResult($resultarr);
    }

    public function uploadImage(){
        $this->checkauthorization();

        // 获取表单上传文件
        $file = request()->file('file');

        $assetUrl = getAssetUploadUrl();
        $info = $file->validate(['ext'=>'jpg,png,gif,jpeg'])->move( __DIR__ . $assetUrl);
        if($info){
            // 成功上传后 获取上传信息
            // 输出 jpg
            $ext = $info->getExtension();
            // 输出 42a79759f284b767dfcb2a0197904287.jpg
            $filename = $info->getFilename();

            $savename = $info->getSaveName();

            $this->dataResult(['savename'=>str_replace('\\','/',$savename)]);


        }else{
            $this->dataResult(['savename'=>null]);
            //上传失败获取错误信息

        }


    }

    public function contactus(){
        $data = Db::name('system')->find();
        if($this->jsonRequest()) {
            $this->dataResult($data);
        } else if(count($data)>0) {
            $this->headerAndFooter('contactus');

            $this->view->assign('contactus',$data['Contact']);

            return $this->view->fetch('system/contactus');
        }

notfound:
        header("HTTP/1.0 404 Not Found");
        die;
    }

}

<?php
namespace app\admin\controller;


class Asset extends Adminbase
{

    function upload() {
        $file = request()->file('file');
        if (!$file) {
            $this->error('请上传文件');
            return resultArray(['error' => '请上传文件']);
        }
        $ds = '/';
        $root_path = SITE_PATH . DIRECTORY_SEPARATOR .'public';
        $info = $file->validate(['ext'=>'jpg,png,gif'])->move($root_path . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'avatar');
        if ($info) {
            $url = 'assets'. DIRECTORY_SEPARATOR . 'avatar'. DIRECTORY_SEPARATOR . $info->getSaveName();
            $url = str_replace('\\', '/', $url);
            $this->success('上传成功', '', ['url' =>  $url, 'link' => $this->request->rootUrl(true) . $ds . $url]);
        }


        $this->error($file->getError());
    }

}

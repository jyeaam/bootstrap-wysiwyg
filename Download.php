<?php
/**
* 下载
* ==============================================

* ==============================================
* @date: 2017年5月31日 下午4:41:04
* @author: tingting43
* @version:
*/
class ControllerDownload extends Abstract_Controller_Internal {
    protected $allow_no_cip = true;

    public function biaozhuAction(){
        $this->display('biaozhu',['user_group'=>$this->user_group,'user_name'=>$this->user_name]);
    }

    public function reportAction(){
        $log = new Data_Download_Report();
        $result=$log->getAllReport();
        $this->display('report',['user_group'=>$this->user_group,'user_name'=>$this->user_name,'result'=>$result]);
    }

    public function resultAction(){

        $log = new Data_Download_Report();
        $result=$log->getAllResult();
        $this->display('result',['user_group'=>$this->user_group,'user_name'=>$this->user_name,'result'=>$result]);
    }

    public function contentAction(){
        $title=$_GET['title'];
        $type=$_GET['type'];
        header('Content-Type: text/html; charset=UTF-8') ;
        $log = new Data_Download_Report();
        $result=$log->getDetail($title);
        $this->display('content',['user_group'=>$this->user_group,'user_name'=>$this->user_name,'result'=>$result,'type'=>$type]);
    }

    public function deleteidAction(){
        $id=$_POST['id'];
        $log = new Data_Download_Report();
        $result=$log->delete($id);
        $this->display('content',['user_group'=>$this->user_group,'user_name'=>$this->user_name,'result'=>$result]);
    }

    public function submitAction(){
        header('Content-Type: text/html; charset=UTF-8') ;
        $onlytext=$_POST['onlycontent'];
        $time=$_POST['time'];
        $content=$_POST['content'];
        $title=$_POST['title'];
        $type=$_POST['type'];
        $file=$_FILES['myfile'];
        $file_name = $file['name'];
//        $user_name = $this->user_name;
        $user_name=$_COOKIE['C_user'];
        $log = new Data_Download_Report();

        $ret_title=$log->isUnique($title);
        foreach ($ret_title as $key=>$value)
            if($value['title'] !=''){
                if($value['type']==$type){
                    echo "0";
                    exit();
                }
        }
        try {
            $ret = $log->add([
                'name' =>  $file_name ,
                'time' =>  $time,
                'content' =>   $content,
                'title' =>  $title,
                'username' =>$user_name,
                'text'=>$onlytext,
                'type'=>$type
            ]);
        } catch (Exception $e) {

        }

        $root='/data0/user/chengzhao1/php_static/assets/layouts/layout/file/file/';
        $ftp_server = "10.75.1.131";
        $ftp_user_name = "wtt";
        $ftp_user_pass = "123";
        $conn_id = ftp_connect($ftp_server) or die("Could not connect");

        $file_tmp_name = $file['tmp_name'];
        $remote_file = $root.$file_name;
        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

        if (ftp_put($conn_id, $remote_file, $file_tmp_name, FTP_BINARY)) {
            echo $file['name'];
        } else {
            echo "false";
        }
        ftp_close($conn_id);

    }

    public function editAction(){
        $type=$_GET['type'];
        $this->display('edit',['user_group'=>$this->user_group,'user_name'=>$this->user_name,'type'=>$type]);
    }
}
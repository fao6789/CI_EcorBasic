<?php
Class Admin extends F_Controller {

     function __construct()
    {
        parent::__construct();
        $this->load->model('admin_model');
    }
        // chạy sang view 
    function create(){
        $this->load->model('admin_model');
        $data=array();
        $data['username']='admin1';
        $data['password']='admin1';
        $data['name']='hocphp';
        if($this->admin_model->create($data)){
            echo ' thêm thành công';
        }else{
            echo 'không thêm thành công';
        }
    }

    function update(){
        $id='8';
        $data=array();
        $data['username']='admin2';
        $data['password']='admin2';
        $data['name']='hocphp 2';
        if($this->admin_model->update($id,$data)){
            echo 'cập nhật thành công';
        }else{
        echo 'cập nhật không thành công';
        }   
    }

    function delete(){
        $id='8'; 
        if($this->admin_model->delete($id)){
            echo 'xóa thành công';
        }else{
        echo ' không xóa thành công';
        }   
    }

    function get_info(){
        $id='1'; 
        $info = $this->admin_model->get_info($id);
        $info = $this->admin_model->get_info($id,'username,password');
        var_dump($info);
       
    }

    function get_list(){
        $input = array();
        $list = $this->admin_model->get_list($id);
        $info = $this->admin_model->get_info($id,'username,password');
        var_dump($info);
       
    }
   
}
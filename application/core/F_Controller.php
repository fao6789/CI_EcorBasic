<?php
Class F_Controller extends CI_Controller 
{
    //biến gửi dữ liệu sang vỉew
    public $data = array();
    
    function __construct()
    {
        // ke thua tu CI_constroller
        parent::__construct();
        // lấy thư mục admin 
        $controller = $this->uri->segment(1);
        switch ($controller){
            case 'admin':{
                // xử lý các dữ liệu khi truy cập vào trang admin
                $this->load->helper('admin');
                $this->_check_login();
            break;
            }
            default :{
                // xử lý dữ liệu ở ngoài admin
            }
        }
    }
    /**
     * kiểm  tra trang thái đăng nhập
     */
    private function _check_login(){
        $controller = $this->uri->rsegment('1');
        $controller= strtolower($controller);
        // nếu chưa đăng nhập mà truy cập 1 controller  khác login
        $login= $this->session->userdata('login');
        if(!$login && $controller!='login'){
            redirect(admin_url('login'));
        }
         // nếu đã đăng nhập thì ko vào login
        if($login && $controller =='login'){
            redirect(admin_url('home'));
        }
    }
}

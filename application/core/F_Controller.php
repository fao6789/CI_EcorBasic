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
            break;
            }
            default :{
                // xử lý dữ liệu ở ngoài admin
            }
        }
    }
    /**
     * kiểm  tra trang thái đang nhập
     */
}

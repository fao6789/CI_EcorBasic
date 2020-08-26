<?php
Class F_Controller extends CI_Controller 
{
    function __construct()
    {
        // ke thua tu CI_constroller
        parent::__construct();
        // lấy thư mục admin 
        $controller = $this->uri->segment(1);
        switch ($controller){
            case 'admin':{
                // xử lý trong admin
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

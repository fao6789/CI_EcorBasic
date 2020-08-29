<?php
Class Login extends F_Controller {
    function index()
    {
        // load các dữ liệu có sẵn của CI 
        $this->load->library('form_validation');
        $this->load->helper('form');
        if($this->input->post()){
            $this->form_validation->set_rules('login','login','callback_check_login');
            if($this->form_validation->run()){
                // kieemr tra dang nhap chua
                $this->session->set_userdata('login', true);
                redirect(admin_url('home'));
            }
        }
        // chạy sang view 
        $this->load->view('admin/login/index');
    }
    /**
     * kieemrm tra login
     */
    function check_login()
    {
        $username= $this->input->post('username');
        $password= $this->input->post('password');
        $password= md5($password);
        // trước khi xử lý phải load model admin
        $this->load->model('admin_model');
        $where = array('username'=>$username,'password'=>$password);
        if($this->admin_model->check_exists($where)){
            return true;
        }
        $this->form_validation->set_message(__FUNCTION__,'Bạn không đăng nhập thành công');
        return false;
    }
}

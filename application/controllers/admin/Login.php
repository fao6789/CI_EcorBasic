<?php
Class Login extends F_Controller {
    function index()
    {
        // chạy sang view 
        $this->load->view('admin/login/index');
    }
}

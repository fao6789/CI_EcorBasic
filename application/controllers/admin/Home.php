<?php
Class Home extends F_Controller {
        // chạy sang view 
        function index()
        {
            $this->data['temp']='admin/home/index';
            $this->load->view('admin/main',$this->data);
        }
}

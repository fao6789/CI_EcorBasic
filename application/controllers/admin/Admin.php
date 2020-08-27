<?php
Class Admin extends F_Controller {

     function __construct()
    {
        parent::__construct();
        $this->load->model('admin_model');
    }
    /**
     * lấy danh sách admin
     */
     function index()
    {
        $input =array();
        $list = $this->admin_model->get_list($input);
        $this->data['list']=$list;
        $total = $this->admin_model->get_total();
        $this->data['total']=$total;
        // lấy ra nội dung của biến message chuyển qua vỉew
        $message = $this->session->flashdata('message');
        $this->data['message'] =$message;
        //show
        $this->data['temp']='admin/admin/index';
        $this->load->view('admin/main',$this->data);
    }
    /**
     * Check username có bị trùng qua hàm callback_ funstion
     */

     function check_username()
    {
        $username = $this->input->post('username');
        $where=array('username'=>$username);
        if($this->admin_model->check_exists($where)){
            $this->form_validation->set_message(__FUNCTION__,'Tài khoản đã tồn tại');
            return false;
        }
        return true;
    }
    /**
     * Thêm admin mới
     */
     function add()
    {
        // load các dữ liệu có sẵn của CI 
        $this->load->library('form_validation');
        $this->load->helper('form');
        if($this->input->post()){
            $this->form_validation->set_rules('name','Tên','required|min_length[8]');
             $this->form_validation->set_rules('username','Username','required|callback_check_username');
              $this->form_validation->set_rules('password','Mật khẩu','required|min_length[6]');
               $this->form_validation->set_rules('re_password','Nhập lại mật khẩu','matches[password]');
               if($this->form_validation->run()){
                    $name = $this->input->post('name');
                    $username = $this->input->post('username');
                    $password = $this->input->post('password');

                    $data=array(
                        'name'=>$name,
                        'username'=>$username,
                        'password'=>md5($password),
                    );
                     if($this->admin_model->create($data)){
                         // tạo nội dung thông báo
                         $this->session->set_flashdata('message','Thêm mới dũ liệu thành công');
                    }else{
                         // tạo nội dung thông báo
                         $this->session->set_flashdata('message','Không thêm được');
                    }
                    // chuyển tới trang danh sách quản trị viên
                    redirect(admin_url('admin'));
               }
               
        }
        //show
        $this->data['temp']='admin/admin/add';
        $this->load->view('admin/main',$this->data);
    }

    /**
     * Chỉnh sửa
     */
    
     function edit()
     {
         // lấy  giá trị thứ 3 trên thanh url
         // lấy id
         $id = $this->uri->rsegment(3);
         $id= intval($id);
         // load các dữ liệu có sẵn của CI 
        $this->load->library('form_validation');
        $this->load->helper('form');
         // get thông tin
         $info = $this->admin_model->get_info($id);
         if(!$info){
              $this->session->set_flashdata('message','Không tồn tại quản trị viên này');
              redirect(admin_url('admin'));
         }
         $this->data['info']=$info;
        if($this->input->post()){
            $this->form_validation->set_rules('name','Tên','required|min_length[8]');
             $this->form_validation->set_rules('username','Username','required|callback_check_username');
              $password=$this->input->post('password');
              if($password){
                  $this->form_validation->set_rules('password','Mật khẩu','required|min_length[6]');
               $this->form_validation->set_rules('re_password','Nhập lại mật khẩu','matches[password]');
              }
               if($this->form_validation->run()){
                    $name = $this->input->post('name');
                    $username = $this->input->post('username');
                    $data=array(
                        'name'=>$name,
                        'username'=>$username,
                    );
                    if($password){
                        $data['password']=md5($password);
                    }
                     if($this->admin_model->update($id,$data)){
                         // tạo nội dung thông báo
                         $this->session->set_flashdata('message','Cập nhật dữ liệu thành công');
                    }else{
                         // tạo nội dung thông báo
                         $this->session->set_flashdata('message','Không cập nhật  được');
                    }
                    // chuyển tới trang danh sách quản trị viên
                    redirect(admin_url('admin'));
               }
               
        }
         //show
        $this->data['temp']='admin/admin/edit';
        $this->load->view('admin/main',$this->data);
         
     }

     function delete()
     {
         // lấy  giá trị thứ 3 trên thanh url
         // lấy id
       $id = $this->uri->rsegment(3);
         $id= intval($id);
          // get thông tin
         $info = $this->admin_model->get_info($id);
         if(!$info){
              $this->session->set_flashdata('message','Không tồn tại quản trị viên này');
              redirect(admin_url('admin'));
         }
         // thực hiện xóa
         $this->admin_model->delete($id);
          $this->session->set_flashdata('message','Xóa dữ liệu thành công');
           // chuyển tới trang danh sách quản trị viên
                    redirect(admin_url('admin'));
     }
   
}
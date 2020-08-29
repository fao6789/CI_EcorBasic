<?php
Class Category extends F_Controller {

     function __construct()
    {
        parent::__construct();
        $this->load->model('category_model');
    }
    /**
     * lấy danh sách admin
     */
     function index()
    {
      // lấy danh sách
        $list = $this->category_model->get_list();
        $this->data['list']=$list;
        // lấy tổng số sản phẩm
        // $total = $this->category_model->get_total();
        // $this->data['total']=$total;
        // lấy ra nội dung của biến message chuyển qua vỉew
        $message = $this->session->flashdata('message');
        $this->data['message'] =$message;
        //show
        $this->data['temp']='admin/category/index';
        $this->load->view('admin/main',$this->data);
    }
    /**
     * Check username có bị trùng qua hàm callback_ funstion
     */

     function check_username()
    {
        $username = $this->input->post('username');
        $where=array('username'=>$username);
        if($this->category_model->check_exists($where)){
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
            $this->form_validation->set_rules('name','Tên danh mục','required');
    
               if($this->form_validation->run()){
                    $name = $this->input->post('name');
                    $parent_id = $this->input->post('parent_id');
                    $sort_order = $this->input->post('sort_order');

                // lu du liệu
                    $data=array(
                        'name'=>$name,
                        'parent_id'=>$parent_id,
                        'sort_order'=>intval($sort_order),
                    );
                     if($this->category_model->create($data)){
                         // tạo nội dung thông báo
                         $this->session->set_flashdata('message','Thêm mới dữ liệu thành công');
                    }else{
                         // tạo nội dung thông báo
                         $this->session->set_flashdata('message','Không thêm được');
                    }
                    // chuyển tới trang danh sách danh mục
                    redirect(admin_url('category'));
               }
               
        }
        // lấy ra danh mục cha parent_id =0
        $input = array();
        $input['where']= array('parent_id'=> 0);
        $list= $this->category_model->get_list($input);
        $this->data['list']=$list;
        //show
        $this->data['temp']='admin/category/add';
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
         $info = $this->category_model->get_info($id);
         if(!$info){
              $this->session->set_flashdata('message','Không tồn tại danh mục này');
              redirect(admin_url('category'));
         }
         // gửi biến qua view
         $this->data['info']=$info;
         if($this->input->post()){
            $this->form_validation->set_rules('name','Tên danh mục','required');
    
               if($this->form_validation->run()){
                    $name = $this->input->post('name');
                    $parent_id = $this->input->post('parent_id');
                    $sort_order = $this->input->post('sort_order');

                // lu du liệu
                    $data=array(
                        'name'=>$name,
                        'parent_id'=>$parent_id,
                        'sort_order'=>intval($sort_order),
                    );
                     if($this->category_model->update($id,$data)){
                         // tạo nội dung thông báo
                         $this->session->set_flashdata('message','Thêm mới dữ liệu thành công');
                    }else{
                         // tạo nội dung thông báo
                         $this->session->set_flashdata('message','Không thêm được');
                    }
                    // chuyển tới trang danh sách danh mục
                    redirect(admin_url('category'));
               }
               
        }
        // lấy ra danh mục cha parent_id =0
        $input = array();
        $input['where']= array('parent_id'=> 0);
        $list= $this->category_model->get_list($input);
        $this->data['list']=$list;
         //show
        $this->data['temp']='admin/category/edit';
        $this->load->view('admin/main',$this->data);
         
     }

     function delete()
     {
         // lấy  giá trị thứ 3 trên thanh url
         // lấy id
       $id = $this->uri->rsegment(3);
         $id= intval($id);
          // get thông tin
         $info = $this->category_model->get_info($id);
         if(!$info){
              $this->session->set_flashdata('message','Không tồn tại quản trị viên này');
              redirect(admin_url('category'));
         }
         // thực hiện xóa
         $this->category_model->delete($id);
          $this->session->set_flashdata('message','Xóa dữ liệu thành công');
           // chuyển tới trang danh sách quản trị viên
                    redirect(admin_url('category'));
     } 
   
}
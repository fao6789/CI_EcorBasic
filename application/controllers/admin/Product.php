<?php
Class Product extends F_Controller {

     function __construct()
    {
        parent::__construct();
        $this->load->model('product_model');
    }
    /**
     * lấy danh sách admin
     */
     function index()
    {
        // lấy ra tổng số lượng các sản phẩm trên website
        $total_row = $this->product_model->get_total();
        $this->data['total_row']=$total_row;
       // lấy ra thư viện phân trang
       $this->load->library('pagination');
       $config= array();
       $config['total_rows'] = $total_row; // tông sẳn phẩm trên web site
       $config['base_url']= admin_url('product/index'); // link hiển thị dữ liệu theo danh sách sản phẩm
       $config['per_page'] = 4; // số lượng sản phẩm  hiển thị trên 1 trang
       $config['uri_segment']=4; // hiển thị số trang trên url
       $config['next_link']= 'Trang kế tiếp';
       $config['prev_link']='Trang trước';
       // Khởi tạo các cấu hình phân trang
       $this->pagination->initialize($config);
        $segment = $this->uri->segment(4);
        $segment = intval($segment);
       
       $input = array();
       $input['limit']= array($config['per_page'],$segment);

       // kiểm tra có thực hiện lọc dữ liệu
       $id= $this->input->get('id');
       $id= intval($id);
       $input['where'] = array() ;
       if($id >0){
            $input['where']['id']=$id;
       }
       // kiểm tra lọc tên
       $name= $this->input->get('name');
       if($name){
            $input['like']=array('name',$name);
       }
       // lọc theo thể loại sản phẩm
       $cate_id= $this->input->get('catalog');
       $cate_id = intval($cate_id);
       if($cate_id >0){
            $input['where']['catalog_id']=$cate_id;
       }
       // lấy ra danh sách sản phẩm
       $list = $this->product_model->get_list($input);
        $this->data['list']=$list;
        /// lấy danh mục sản phẩm
        $this->load->model('category_model');
        $input_cate = array();
        $input_cate['category'] = array('parent_id'=>0);
        $cate= $this->category_model->get_list($input_cate);
        foreach($cate as $row){
            $input_child['where']= array('parent_id'=>$row->id);
            $sub = $this->category_model->get_list($input_child);
            $row->sub= $sub;
        }
        $this->data['cate']= $cate;
        // lấy ra nội dung của biến message chuyển qua vỉew
        $message = $this->session->flashdata('message');
        $this->data['message'] =$message;
        //show
        $this->data['temp']='admin/product/index';
        $this->load->view('admin/main',$this->data);
    }
    /**
     * Check username có bị trùng qua hàm callback_ funstion
     */

     function check_username()
    {
        $username = $this->input->post('username');
        $where=array('username'=>$username);
        if($this->product_model->check_exists($where)){
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
                     if($this->product_model->create($data)){
                         // tạo nội dung thông báo
                         $this->session->set_flashdata('message','Thêm mới dữ liệu thành công');
                    }else{
                         // tạo nội dung thông báo
                         $this->session->set_flashdata('message','Không thêm được');
                    }
                    // chuyển tới trang danh sách danh mục
                    redirect(admin_url('product'));
               }
               
        }
        // lấy ra danh mục cha parent_id =0
        $input = array();
        $input['where']= array('parent_id'=> 0);
        $list= $this->product_model->get_list($input);
        $this->data['list']=$list;
        //show
        $this->data['temp']='admin/product/add';
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
         $info = $this->product_model->get_info($id);
         if(!$info){
              $this->session->set_flashdata('message','Không tồn tại danh mục này');
              redirect(admin_url('product'));
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
                     if($this->product_model->update($id,$data)){
                         // tạo nội dung thông báo
                         $this->session->set_flashdata('message','Thêm mới dữ liệu thành công');
                    }else{
                         // tạo nội dung thông báo
                         $this->session->set_flashdata('message','Không thêm được');
                    }
                    // chuyển tới trang danh sách danh mục
                    redirect(admin_url('product'));
               }
               
        }
        // lấy ra danh mục cha parent_id =0
        $input = array();
        $input['where']= array('parent_id'=> 0);
        $list= $this->product_model->get_list($input);
        $this->data['list']=$list;
         //show
        $this->data['temp']='admin/product/edit';
        $this->load->view('admin/main',$this->data);
         
     }

     function delete()
     {
         // lấy  giá trị thứ 3 trên thanh url
         // lấy id
       $id = $this->uri->rsegment(3);
         $id= intval($id);
          // get thông tin
         $info = $this->product_model->get_info($id);
         if(!$info){
              $this->session->set_flashdata('message','Không tồn tại quản trị viên này');
              redirect(admin_url('product'));
         }
         // thực hiện xóa
         $this->product_model->delete($id);
          $this->session->set_flashdata('message','Xóa dữ liệu thành công');
           // chuyển tới trang danh sách quản trị viên
                    redirect(admin_url('product'));
     } 
   
}
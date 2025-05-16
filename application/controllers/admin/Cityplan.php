<?php

defined('BASEPATH') or exit('No direct script access allowed');
@ini_set('memory_limit', '512M');
@ini_set('max_execution_time', 360);
@ini_set('post_max_size', '64M');
@ini_set('upload_max_filesize', '64M');
class Cityplan extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('cityplan_model');
        $this->load->model('Organization_model');
        $this->load->model('Department_model');

        // if (!has_permission('cityplan', '', 'view')) {
        //     access_denied('cityplan');
        // }
    }
  
    /* List all area */
    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('city_plan');
        }

        $data['title']                = 'City Plan';
        $this->load->view('admin/cityplan/manage', $data);
    }

    public function view()
    {
        // if ($this->input->is_ajax_request()) {
        //     $this->app->get_table_data('city_plan');
        // }
        $get_cp = $this->cityplan_model->get(false, true);
        $data['title']   = 'View City Plan';
        $city_Plan['data'] = $data;
        $city_Plan['city_plan_list'] = $get_cp;
        //  echo "<pre>";print_r($city_Plan);die();
        $this->load->view('admin/cityplan/view', $city_Plan);
    }


    /* Edit or add new area */
 

    public function area($id = '')
    {
        $file = NULL;
        if ($this->input->post()) {

            $message          = '';
            $data             = $this->input->post();
                $if_area_exist = [];
                if(trim($data['name']) == '')
                {
                    echo json_encode([
                        'success'              => false,
                        'message'              => 'Invalid City plan name.',
                    ]);
                }
                else if (!$this->input->post('id')) {

                    $if_area_exist = $this->cityplan_model->get_area(['name' => trim($data['name'])]);

                    if (count($if_area_exist) > 0) {

                    echo json_encode([
                        'success'              => false,
                        'message'              => 'City plan already in use.',
                    ]);
                  
                    }else{

                        if(isset($_FILES["file"]['name']) && $_FILES["file"]['name'] == '')
                        {
                            echo json_encode([
                                'success'              => false,
                                'message'              => 'City action plan is required.',
                            ]);
                            exit;
                        }elseif(isset($_FILES["logo"]['name']) && $_FILES["logo"]['name'] == '')
                        {
                            echo json_encode([
                                'success'              => false,
                                'message'              => 'Logo is required.',
                            ]);
                            exit;
                        }

                        if(!empty($_FILES["file"]['name'])){
                            $name = str_replace([' ', '.'], '_', basename($_FILES["file"]['name'], ".pdf"));
                            $file = time().'-'.$name.".pdf";  
                          
                            $upload = $this->file_upload($file);
                            if($upload != 1){
                                echo json_encode([
                                                'success'              => false,
                                                'message'              => $upload,
                                            ]);
                                exit; 
                            }else{
                            //     if(!empty($_FILES["logo"]['name'])){
                            //         $name = str_replace(' ', '_', $_FILES["logo"]['name']);
                            //         $logo = time().'-'.$name;  
                            //         $data['logo'] = $logo;
                            //         $file_size =$_FILES['logo']['size'];
                            //         $file_tmp =$_FILES['logo']['tmp_name'];
                            //         $file_ext=explode('.',$_FILES['logo']['name']);
                            //         $file_ext=end($file_ext);
                            //         $extensions= array("jpeg","jpg","png");
                            //         list($width, $height, $type, $attr) =  getimagesize($file_tmp); 

                            //         // if(!empty($width)  || !empty($height)) {
                            //         //     echo json_encode([
                            //         //         'success'              => false,
                            //         //         'message'              => "Image size must be 225px x 150px pixels..",
                            //         //     ]);
                            //         //     exit; 
                            //         // }
                                    
                            //         if(in_array($file_ext,$extensions)=== false){
                            //             echo json_encode([
                            //                 'success'              => false,
                            //                 'message'              => "Only JPEG or JPG or PNG file type is allowed.",
                            //             ]);
                            //             exit; 
                            //         }
                            //         if($file_size > 2097152){
                            //             echo json_encode([
                            //                 'success'              => false,
                            //                 'message'              => "File uploaded is greater than 2 MB",
                            //             ]);
                            //             exit; 
                            //         }
                                    
                            //         move_uploaded_file($file_tmp,FCPATH . "uploads/logo/".$logo);
                                  
                            //    }
                            }
                        }
                         
                        if ($GLOBALS['current_user']->area != '') {
                            $data['area'] = $GLOBALS['current_user']->area;
                        }
                        $id = $this->cityplan_model->add($data,$file);
                        if ($id) {
                            $success = true;
                            $message = _l('added_successfully', _l('cityplan'));
                            $this->allocate_category_to_new_area($id);
                        }
                        echo json_encode([
                            'success'              => $success,
                            'message'              => $message,
                        ]);
                    }

                  
                } else {
                    $id = $data['id'];
                  
                    $area = $this->cityplan_model->get_area(['id' => $data['id']])[0];

                    if(isset($_FILES["file"]['name']) && $_FILES["file"]['name'] == '' && $area['file'] == '')
                    {
                        echo json_encode([
                            'success'              => false,
                            'message'              => 'City action plan is required.',
                        ]);
                        exit;
                    }
                    // elseif(isset($_FILES["logo"]['name']) && $_FILES["logo"]['name'] == '' && $area['logo'] == '')
                    // {
                    //     echo json_encode([
                    //         'success'              => false,
                    //         'message'              => 'Logo is required.',
                    //     ]);
                    //     exit;
                    // }
                   
                    if ($area['name'] != trim($data['name'])) {
                        $if_area_exist = $this->cityplan_model->get_area(['name' => trim($data['name'])]);
                    }
                    if ($GLOBALS['current_user']->area != '') {
                        $data['area'] = $GLOBALS['current_user']->area;
                    }

                    if (count($if_area_exist) > 0) {
                        echo json_encode([
                            'success'              => false,
                            'message'              => 'City plan name already in use.',
                        ]);
                    }else{
                        unset($data['id']);
                        if(!empty($_FILES["file"]['name'])){
                            $name = str_replace([' ', '.'], '_', basename($_FILES["file"]['name'], ".pdf"));
                            $file = time().'-'.$name.".pdf"; 
                            $upload = $this->file_upload($file);
                            if($upload != 1){
                                echo json_encode([
                                                'success'              => false,
                                                'message'              => $upload,
                                            ]);
                                exit; 
                            }
                        }else{
                            $file = $area['file'];  
                        }

                        
                        if(!empty($_FILES["logo"]['name'])){
                             $name = str_replace(' ', '_', $_FILES["logo"]['name']);
                             $logo = time().'-'.$name;  
                             $data['logo'] = $logo;
                            $file_size =$_FILES['logo']['size'];
                            $file_tmp =$_FILES['logo']['tmp_name'];
                            $file_ext=explode('.',$_FILES['logo']['name']);
                            $file_ext=end($file_ext);
                            $extensions= array("jpeg","jpg","png");
                            list($width, $height, $type, $attr) =  getimagesize($file_tmp); 

                            if($width > "2" || $height > "1") {
                                echo json_encode([
                                    'success'              => false,
                                    'message'              => "Image size must be 225px X 150px pixels.",
                                ]);
                                exit; 
                            }
                            
                            if(in_array($file_ext,$extensions)=== false){
                            echo json_encode([
                                'success'              => false,
                                'message'              => "Only JPEG or PNG file type is allowed.",
                            ]);
                            exit; 
                            }
                            
                            if($file_size > 2097152){
                       
                            echo json_encode([
                                'success'              => false,
                                'message'              => "File uploaded is greater than 2 MB",
                            ]);
                            exit; 
                            }
                            
                            if(empty($errors)==true){
                            move_uploaded_file($file_tmp,FCPATH . "uploads/logo/".$logo);
                            }
                        }else{
                            $data['logo'] = $area['logo'];  
                        }
                        $success = $this->cityplan_model->update($data, $file, $id);
                        if ($success) {
                            $message = _l('updated_successfully', _l('client_city'));
                        }
                        echo json_encode([
                            'success'              => $success,
                            'message'              => $message,
                        ]);
                    }

                   
                }
                die;
           
        }
    }

    public function file_upload($file)
    {
        $config = array(
            'upload_path' =>  FCPATH . 'uploads/city_plan/',
            'allowed_types' => "pdf",
            'overwrite' => TRUE,
            'file_name' => $file,
            'max_size'      => '5000',
            );
            $this->load->library('upload', $config);
            
            if (!$this->upload->do_upload('file')) {
                $error = array('error' => $this->upload->display_errors());
                //return $error['error'];
                if($error['error'] == '<p>The filetype you are attempting to upload is not allowed.</p>'){
                 
                    return "Only PDF file type is allowed";
                }else if($error['error'] == '<p>The file you are attempting to upload is larger than the permitted size.</p>'){
                    return "File uploaded is greater than 5 MB";
                }else{
                    return "Something went wrong.";
                }
              
            }
            return true;
    }
    



    /* Delete area from database */
    public function delete($id)
    {
        if (!$id) {
            redirect(admin_url('cityplan'));
        }
        $response = $this->cityplan_model->delete($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('area')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('area')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('area')));
        }
        redirect(admin_url('cityplan'));
    }

   

    public function area_exists()
    {
        // First we need to check if the email is the same
        $areaid = $this->input->post('id');
        if ($areaid) {
            $this->db->where('id', $areaid);
            $_current_email = $this->db->get(db_prefix() . 'city_plan')->row();
            if ($_current_email->name == $this->input->post('email')) {
                echo json_encode(true);
                die();
            }
        }
        $exists = total_rows(db_prefix() . 'city_plan', [
            'name' => $this->input->post('name'),
        ]);
        
        if ($exists > 0) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

   /* Change status to area active or inactive / ajax */
   public function change_area_status()
   {
       if ($this->input->is_ajax_request()) {
           $this->load->model("projects_model");
           $this->load->model("staff_model");
           $this->load->model('region_model');
           $this->load->model('subregion_model');
           $data = $this->input->post();
           $id = $data['id'];
           $status = $data['status'];
        //    $area = $this->cityplan_model->get_area(['id' => $id])[0];
        //    if($status == 1 && ($area['file'] == ''  ||  $area['logo'] == '') ){
        //         echo json_encode([
        //             'success'              => false,
        //             'message'              => "This City/ union territory cannot be enabled until city action plan and logo are uploaded.",
        //         ]);
        //         exit; 
        //    }
       
           if (!$status || $status) {
               $update_status = $this->cityplan_model->change_area_status($id, $status);
               if ($update_status) {
                   $response = [
                       'success' => true,
                       'message' => "Status has been updated successfully.",
                       'check_status' => (int)$status
                   ];
                   $staff_ids = $this->staff_model->get_area_staff($id);
                   $this->staff_model->update_status_by_area($id, $status);
                   $this->region_model->update_status_by_area($id, $status);
                   $this->subregion_model->update_status_by_area($id, $status);

                    // insert log in project activity 
                    $projectids = array();
                    $projectids = $this->projects_model->getFrozenProjects(['area_id' => $id], $status);
                    if(!empty($projectids)){
                        $this->projects_model->updateFrozenStaus($projectids,$status);
                    }

                   $this->projects_model->set_project_frozen(["area_id" => $id], $status);
                   
                   if (count($staff_ids) > 0) {
                       // Deactivate staff users
                       //$this->staff_model->update_status($staff_ids, $status);
   
                       // Set respective project to frozen
                       
                   }
               }

               echo json_encode($response);
               die;
           }

           $update_status = $this->cityplan_model->change_area_status($id, $status);

           $response = [
               'success' => true,
               'message' => "Status has been updated successfully.",
               'check_status' => 1
           ];
           echo json_encode($response);
       }
   }
    /* Get status to area active or inactive / ajax */
    // public function get_status()
    // {
    //     $data               = $this->input->post();
    //     $id                 = $data['id'];

    //     $status = $this->cityplan_model->get_status($id);
    //     echo json_encode($status);
    // }

    public function get_admin_area()
    {
        $data = $this->input->post();

        if ($data['exclude_staff_area']) {
            $area_list = $this->cityplan_model->get(false, false, true, $data);
        } else {
            $area_list = $this->cityplan_model->get(false, true, false);
        }
        $organizationNew = $this->Organization_model->getOrganization();
        $departmentNew = $this->Department_model->getOrgDepartment($data['organizationId']);
        $alreadyDepartmentIds = $this->cityplan_model->getDepartmentIdFomStaff($data['staffId']);
        // print_r($alreadyDepartmentIds);
        echo json_encode(['success' => true, 'area_list' => $area_list,'departmentNew'=>$departmentNew,'organizationNew'=>$organizationNew ,'alreadyDepartmentIds'=>$alreadyDepartmentIds]);
        die;
    }

    public function allocate_category_to_new_area($id)
    {
        $this->load->model('issue_model');
        $rows = $this->issue_model->get_all_super_admin_categories();
        foreach($rows as $category){
            $categoryid=$category['id'];
            $milestones = $this->issue_model->get_all_super_admin_categories_milestone($categoryid);
            $data=[
                'issue_name'=>$category['issue_name'],
                'staff_id'=>$category['staff_id'],
                'parent_issue_id'=>$categoryid,
                'area_id'=>$id,
                'is_active'=>1,
            ];
        $insertid=$this->issue_model->add($data);
        foreach($milestones as $milestone){
            $milestonedata=[
                'issue_id'=>$insertid,
                'milestone_name'=>$milestone['milestone_name'],
                'days'=>$milestone['days'],
                'reminder_one'=>$milestone['reminder_one'],
                'reminder_two'=>$milestone['reminder_two'],
                'parent_issue_id'=>$category['id'],
                'area_id'=>$id,
                'is_active'=>1,
            ];
            $this->issue_model->addmilestone($milestonedata);
        }
        }
    }

    public function master_plan()
    {

        if ($this->input->is_ajax_request()) {
            $aColumns = [
                'name',
                'file',
                ];
            $sIndexColumn = 'id';
            $sTable       = db_prefix().'city_plan';
            $where        = ['AND is_deleted = 0'];
            $join         = [];  
            $result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['is_deleted','areaid']);
            $output  = $result['output'];
            $rResult = $result['rResult'];
 
            foreach ($rResult as $aRow) {
                $row = [];
                for ($i = 0; $i < count($aColumns); $i++) {
                    $_data = $aRow[$aColumns[$i]];

                    if ($aColumns[$i] == 'file') {
                        if($aRow['file'] != NULL){
                            $_data = '<div class="">
                            <p class="evidence_img" data-area_id="' . $aRow['id'] .'">
                                <a href="'. base_url('uploads/city_plan/'.$aRow['file']) .'" target="_blank" ><img src="'. base_url('assets/images/view-icon.png') .'" alt=""></a>
                                <span></span></p>        
                        </div>';
    
                         // For exporting
                         $_data .= '<span class="hide">'.$aRow['file'] .'</span>';
                        }
                    } 
                    $row[] = $_data;  
                }

                $output['aaData'][] = $row;
            }
            echo json_encode($output);
                die();
             
        }
        $data['title']                = 'City Action Plan';
        $this->load->view('admin/cityplan/master_plan', $data);
      
       
    }

  

}
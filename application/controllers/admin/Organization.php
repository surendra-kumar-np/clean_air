<?php

defined('BASEPATH') or exit('No direct script access allowed');
@ini_set('memory_limit', '512M');
@ini_set('max_execution_time', 360);
@ini_set('post_max_size', '64M');
@ini_set('upload_max_filesize', '64M');
class Organization extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Organization_model');
        $this->load->model('Department_model');

        if (!has_permission('organization', '', 'view')) {
            access_denied('Organization');
        }
    }
  
    /* List all Organization */
    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('organization');

        }

        $data['title']                = _l('organization');
    
        $this->load->view('admin/organization/manage', $data);
    }

    /* Edit or add new area */
 

    public function organization($id = '')
    {

      
        $file = NULL;
        if ($this->input->post()) {
            $if_organization_exist = [];
            $message          = '';
            $data             = $this->input->post();
                if(trim($data['name']) == '')
                {
                    echo json_encode([
                        'success'              => false,
                        'message'              => _l('invalid_organization_name'),
                    ]);
                }
                else if (!$this->input->post('id')) {

                    $if_organization_exist = $this->Organization_model->get_organization(['name' => trim($data['name'])]);

                    if (count($if_organization_exist) > 0) {

                    echo json_encode([
                        'success'              => false,
                        'message'              => _l('organization_already_in_use'),
                    ]);
                  
                    }else{

                        if(isset($_FILES["file"]['name']) && $_FILES["file"]['name'] == '')
                        {
                            echo json_encode([
                                'success'              => false,
                                'message'              => _l('kml_file_is_required'),
                            ]);
                            exit;
                        }

                        if(!empty($_FILES["file"]['name'])){
                            $name = str_replace([' ', '.'], '_', basename($_FILES["file"]['name'], ".kml"));
                            $file = time().'-'.$name.".kml";  
                          
                            $upload = $this->file_upload($file);
                            if($upload != 1){
                                echo json_encode([
                                                'success'              => false,
                                                'message'              => $upload,
                                            ]);
                                exit; 
                            }
                        }
                         
                        $id = $this->Organization_model->add($data,$file);
                        if ($id) {
                            $success = true;
                            $message = _l('added_successfully_organization');
                            // $this->allocate_category_to_new_area($id);
                        }
                        echo json_encode([
                            'success'              => $success,
                            'message'              => $message,
                        ]);
                    }

                  
                } else {
                    $id = $data['id'];
                  
                    $org = $this->Organization_model->get_organization(['id' => $data['id']])[0];
                   
                    if(isset($_FILES["file"]['name']) && $_FILES["file"]['name'] == '')
                    {
                        echo json_encode([
                            'success'              => false,
                            'message'              => _l('kml_file_is_required'),
                        ]);
                        exit;
                    }

                    if(!empty($_FILES["file"]['name'])){
                        $name = str_replace([' ', '.'], '_', basename($_FILES["file"]['name'], ".kml"));
                        $file = time().'-'.$name.".kml";  
                      
                        $upload = $this->file_upload($file);
                        if($upload != 1){
                            echo json_encode([
                                            'success'              => false,
                                            'message'              => $upload,
                                        ]);
                            exit; 
                        }
                    }
                    
                    if ($org['name'] != trim($data['name'])) {
                        $if_organization_exist = $this->Organization_model->get_organization(['name' => trim($data['name'])]);
                    }

                    if (count($if_organization_exist) > 0) {
                        echo json_encode([
                            'success'              => false,
                            'message'              => _l('organization_already_in_use'),
                        ]);
                    }else{
                        unset($data['id']);
                        if(!empty($_FILES["file"]['name'])){
                            $name = str_replace([' ', '.'], '_', basename($_FILES["file"]['name'], ".kml"));
                            $file = time().'-'.$name.".kml"; 
                            $upload = $this->file_upload($file);
                            if($upload != 1){
                                echo json_encode([
                                                'success'              => false,
                                                'message'              => $upload,
                                            ]);
                                exit; 
                            }
                        }else{
                            $file = $org['kml_file'];  
                        }
                        $success = $this->Organization_model->update($data, $file, $id);
                        if ($success) {
                            $message = _l('updated_successfully_organization');
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


    public function department(){
        $file = NULL;
        $if_organization_exist = [];
        $message          = '';
    
            if(trim($this->input->post('deparment_name')) == '')
            {
                echo json_encode([
                    'success'              => false,
                    'message'              => _l('invalid_deparment_name'),
                ]);
            }
            else if ($this->input->post('id')) {

                $if_department_exist = $this->Department_model->get_department(['depart_name' => trim($this->input->post('deparment_name'))]);

                if (count($if_department_exist) > 0) {

                echo json_encode([
                    'success'              => false,
                    'message'              => _l('deparment_already_in_use'),
                ]);
              
                }else{

                    if(isset($_FILES["file"]['name']) && $_FILES["file"]['name'] == '')
                    {
                        echo json_encode([
                            'success'              => false,
                            'message'              => _l('kml_file_is_required'),
                        ]);
                        exit;
                    }

                    if(!empty($_FILES["file"]['name'])){
                        $name = str_replace([' ', '.'], '_', basename($_FILES["file"]['name'], ".kml"));
                        $file = time().'-'.$name.".kml";  
                      
                        $upload = $this->file_upload($file);
                        if($upload != 1){
                            echo json_encode([
                                            'success'              => false,
                                            'message'              => $upload,
                                        ]);
                            exit; 
                        }
                    }
                    $data['depart_name']  = $this->input->post('deparment_name');
                    $data['org_id']  = $this->input->post('id');
                    $id = $this->Department_model->add($data,$file);
                    if ($id) {
                        $success = true;
                        $message = _l('added_successfully_department');
                        // $this->allocate_category_to_new_area($id);
                    }
                    echo json_encode([
                        'success'              => $success,
                        'message'              => $message,
                    ]);
                }
        }
    }

    public function file_upload($file)
    {
        $config = array(
            'upload_path' =>  FCPATH . 'uploads/organization/',
            'allowed_types' => "kml",
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
            redirect(admin_url('area'));
        }
        $response = $this->area_model->delete($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('area')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('area')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('area')));
        }
        redirect(admin_url('area'));
    }

   

    public function area_exists()
    {
        // First we need to check if the email is the same
        $areaid = $this->input->post('areaid');
        if ($areaid) {
            $this->db->where('areaid', $areaid);
            $_current_email = $this->db->get(db_prefix() . 'area')->row();
            if ($_current_email->name == $this->input->post('email')) {
                echo json_encode(true);
                die();
            }
        }
        $exists = total_rows(db_prefix() . 'area', [
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
           $data = $this->input->post();
           $id = $data['id'];
           $status = $data['status'];
           $update_status = $this->Organization_model->change_area_status($id, $status);

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
    //     $id                 = $data['areaid'];

    //     $status = $this->area_model->get_status($id);
    //     echo json_encode($status);
    // }

    public function get_admin_area()
    {
        $data = $this->input->post();

        if ($data['exclude_staff_area']) {
            $area_list = $this->area_model->get(false, false, true, $data);
        } else {
            $area_list = $this->area_model->get(false, true, false);
        }
        echo json_encode(['success' => true, 'area_list' => $area_list]);
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
            $sIndexColumn = 'areaid';
            $sTable       = db_prefix().'area';
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
                            <p class="evidence_img" data-area_id="' . $aRow['areaid'] .'">
                                <a href="'. base_url('uploads/area/'.$aRow['file']) .'" target="_blank" ><img src="'. base_url('assets/images/view-icon.png') .'" alt=""></a>
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
        $this->load->view('admin/area/master_plan', $data);
      
       
    }

    public function getOrganizationDepartment(){
        $postData = $this->input->post();
        $data = $this->Department_model->getOrgDepartment($postData);
        echo json_encode($data);
    }

}
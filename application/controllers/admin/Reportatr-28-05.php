<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Reportatr extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('report_model');

        $this->load->model('atr_report_model');

        $this->load->model('issue_model');
        $this->load->model('projects_model');
        $this->load->model('staff_model');
        $this->load->model('dashboard_model');
        $this->load->model('department_model');
    }


    public function index_old()
    {
        // echo '<pre>'; print_r($GLOBALS['current_user']); exit;
        $post_data = $this->input->post();

        //pre($post_data);

        $get_area = $this->input->get('area') ? base64_decode($this->input->get('area')) : "";
        $get_status = $this->input->get('status') ? base64_decode($this->input->get('status')) : "";
        $get_city = $this->input->get('city') ? base64_decode($this->input->get('city')) : "";
        $get_zone = $this->input->get('zone') ? base64_decode($this->input->get('zone')) : "";
        $role = $GLOBALS['current_user']->role;
        $role_slug =  $GLOBALS['current_user']->role_slug_url;
        $slug_url = $this->input->get('role');

        if ($role_slug == "ap-sa") {
            access_denied('Report');
            exit;
        }

        //pre($post_data['ticket']);

        $area = $GLOBALS['current_user']->area;
        $tableView = "report_managment_ae";
        $siteTitle = ["ae-area" => "Action Taken Report - " . @$GLOBALS['current_user']->location['area'] . " State Observer", "ar" => "Action Taken Report - Reviewer", "at" => "Action Taken Report - Project Leader", "ata" => "Action Taken Report - Project Support", "aa" => "Action Taken Report - State Admin"];

        
        $areaid = (!empty($post_data['areaid'])) ? $post_data['areaid'] : '';
        $regionid = (!empty($post_data['region'])) ? $post_data['region'] : '';

        if ($role_slug == 'ae-global') {
            $data['title']         = 'Action Taken Report - National Observer';
            $data['action_taker'] = $this->atr_report_model->get_action_taker();
            $data['action_reviewer'] = $this->atr_report_model->get_action_reviewer();
            $data['areas'] = $this->atr_report_model->get_area(); 
            $data['region'] = $this->atr_report_model->get_region('',$areaid);
            $data['subregion'] = $this->atr_report_model->get_subregion('',$regionid);
      
        } else {
            $data['title']         = $siteTitle[$role_slug];
            $data['action_taker'] = $this->atr_report_model->get_action_taker($area);
            $data['action_reviewer'] = $this->atr_report_model->get_action_reviewer($area);
            //if($role == 7 || $role == 3 || $role == 8){
            //    $data['region'] = $this->report_model->get_ae_region();
            //    $data['subregion'] = $this->report_model->get_ae_subregion();
            //}else{
            
            $data['region'] = $this->atr_report_model->get_region($area);
            $data['subregion'] = $this->atr_report_model->get_subregion($area,$regionid);
            
            //}
        }
        $data['role_slug']    = $role_slug;
        // if ($role == 4 || $role == 6 || $role == 7) {
        //     $data['ticket'] = $this->report_model->get_ar_ticket();
        // } else {
        $data['ticket'] = $this->atr_report_model->get_ticket();
        // }


        $region = [];
        $subregion = [];
        foreach ($data['region'] as $var) {
            $region[] = $var['id'];
        }
        foreach ($data['subregion'] as $var) {
            $subregion[] = $var['id'];
        }

        $cats = [];
        if (!empty($post_data['category'])) {
            if (!empty($post_data['category'][0])) {

                if (strpos($post_data['category'][0], ",") != false) {
                    $cats = explode(",", $post_data['category'][0]);
                } else {
                    $cats = $post_data['category'];
                }
            } else {
                $cats = [];
            }
        }

        $range = [];
        if (!empty($post_data['duration'])) {
            if (!empty($post_data['duration'][0])) {
                if (strpos($post_data['duration'][0], ",") != false) {
                    $range = explode(",", $post_data['duration'][0]);
                } else {
                    $range = $post_data['duration'];
                }
            } else {
                $range = [];
            }
        }
        // pre($range);

        $tableParams = [
            'areaid' => (!empty($post_data['areaid'])) ? $post_data['areaid'] : '',
            'region' => (!empty($post_data['region'])) ? $post_data['region'] : '',
            'subregion' => (!empty($post_data['subregion'])) ? $post_data['subregion'] : '',
            'category' => (!empty($cats)) ? $cats : '',
            'bug' => (!empty($post_data['bug'])) ? $post_data['bug'] : '',
            'action_taker' => (!empty($post_data['action_taker'])) ? $post_data['action_taker'] : '',
            'action_reviewer' => (!empty($post_data['action_reviewer'])) ? $post_data['action_reviewer'] : '',
            'report_date' => (!empty($post_data['report_months'])) ? $post_data['report_months'] : '',
            'to_date' => (!empty($post_data['report-to'])) ? $post_data['report-to'] : '',
            'from_date' => (!empty($post_data['report-from'])) ? $post_data['report-from'] : '',
            'statusIds' => (!empty($post_data['ticket'])) ? $post_data['ticket'] : "",
            'duration' => (!empty($range)) ? $range : '',
        ];


        if ($get_area != "") {
            $get_area_name = $this->atr_report_model->get_area_name([$get_area]);
            if ($get_area_name)
                $tableParams['areaid'] = ['areaid' => $get_area, "name" => $get_area_name];
        }

        if ($get_status != "") {
            $get_project_status = $this->atr_report_model->get_status(['id' => $get_status]);
            // pre($get_project_status);
            if ($get_project_status)
                $tableParams['statusIds'] = ['id' => $get_status, "name" => $get_project_status[0]['name']];
        }
        if ($get_city != "") {
            $get_city_data = $this->atr_report_model->get_region($get_area, '', $get_city)[0];
            if ($get_city_data)
                $tableParams['region'] = $get_city_data;
        }

        if ($get_zone != "") {
            $get_zone_data = $this->atr_report_model->get_subregion($get_area, $get_city, $get_zone)[0];
            if ($get_zone_data)
                $tableParams['subregion'] = $get_zone_data;
        }
        // pre($areas);

        //}
        // pre([$get_area, $get_status, $tableParams['statusIds']]);

        if ($this->input->post()) {
            $data['filter'] = json_encode($post_data);
        }else{
            $data['filter'] = 0;
        }

        //for testing
        $tableParams[ 'limit_start' ]   = (!empty($post_data['start']) ? $post_data['start'] : 0);
        $tableParams[ 'record_length' ] = (!empty($post_data['length']) ? $post_data['length'] : 250);

        $data['projects'] = $this->atr_report_model->get_report($tableParams);
        
        //pre($data);exit;

        $data['statuses'] = $this->atr_report_model->get_report_summary($tableParams,1);
        $data['totals'] = $this->atr_report_model->get_report_summary($tableParams);
        // $data['totals'] = $this->report_model->get_report_total($tableParams);
        $data['tableParams'] = $tableParams;
        $categories = $this->issue_model->get_area_issues($area,'',$staff_id);
        $data['categories'] = (!empty($categories)) ? $categories : [];
       
        $data["durations"] = [
            ["id" => "30", "duration" => "< 1 month"],
            ["id" => "31 - 183", "duration" => "1 month - 6 months"],
            ["id" => "184 - 365", "duration" => "6 months - 1 year"],
            ["id" => "366 - 1095", "duration" => "1 year - 3 years"],
            ["id" => "1096 - 1825", "duration" => "3 years - 5 years"],
            ["id" => "1826", "duration" => "> 5 years"],
        ];
        // $data['total'] = 4;

        //pre($data);
        $this->load->view('admin/reportatr/index_old', $data);
    }


    public function index()
    {
        //echo '<pre>'; print_r($GLOBALS['current_user']); exit;
        $staffid = $GLOBALS['current_user']->staffid;
        $post_data = $this->input->post();
        
        //pre($post_data);
        $get_area = $this->input->get('area') ? base64_decode($this->input->get('area')) : "";
        $get_status = $this->input->get('status') ? base64_decode($this->input->get('status')) : "";
        $get_city = $this->input->get('city') ? base64_decode($this->input->get('city')) : "";
        $get_organization = $this->input->get('organization') ? base64_decode($this->input->get('organization')) : "";
        $get_projectsupport = $this->input->get('projectsupport') ? base64_decode($this->input->get('projectsupport')) : "";
        $get_department = $this->input->get('department') ? base64_decode($this->input->get('department')) : "";
        $get_zone = $this->input->get('zone') ? base64_decode($this->input->get('zone')) : "";
        $get_ward = $this->input->get('ward') ? base64_decode($this->input->get('ward')) : "";
        $role = $GLOBALS['current_user']->role;
        $role_slug =  $GLOBALS['current_user']->role_slug_url;
        
        $slug_url = $this->input->get('role');
        //pre($this->input->get());
        if ($role_slug == "ap-sa") {
            //access_denied('Report');
            //exit;
        }

       // pre($post_data);

        $area = $GLOBALS['current_user']->area;
        $tableView = "report_managment_ae";
        $siteTitle = ["ae-area" => "Action Taken Report - " . @$GLOBALS['current_user']->location['area'] . " State Observer", "ar" => "Action Taken Report - Reviewer", "at" => "Action Taken Report - Project Leader", "ata" => "Action Taken Report - Project Support", "aa" => "Action Taken Report - State Admin", "qc" => "Surveyor - Quality Check"];
        // if(!empty($post_data['region'])){
        //     $region = $post_data['region'];
        // }else{
        //     $region = $area; 
        // }
        $departmentids = (!empty($post_data['department'])) ? $post_data['department'] : '';
        $organizationid = (!empty($post_data['organization'])) ? $post_data['organization'] : '';
        $user_at  = $this->staff_model->get_userDetails($GLOBALS['current_user']->staffid);
       
        if($role==6){
            $org_id =$organizationid ;
        }else{
            $org_id = (!empty($user_at->org_id)) ? array($user_at->org_id) : $organizationid;
           
            
        }
        $region_at = (!empty($user_at->region)) ? array($user_at->region) : '';
        $subregion_at = (!empty($user_at->sub_region)) ?  array($user_at->sub_region) : '';
        $region = !empty($post_data['region']) ? $post_data['region'] : $area;
        
        $areaid = (!empty($post_data['areaid'])) ? $post_data['areaid'] : '';
        $regionid = (!empty($post_data['region'])) ? $post_data['region'] : $region_at;
        $organizationid = (!empty($post_data['organization'])) ? $post_data['organization'] : '';
        $departmentid = (!empty($post_data['department'])) ? $post_data['department'] : '';
        $projectsupportid = (!empty($post_data['projectsupport'])) ? $post_data['projectsupport'] : '';
        $subregionid = (!empty($post_data['subregion'])) ? $subregion_at : '';
        
        $wardid = (!empty($post_data['ward'])) ? $post_data['ward'] : '';

        if ($role_slug == 'ae-global') {
            $data['title']         = 'Action Taken Report - National Observer';
            $data['action_taker'] = $this->atr_report_model->get_action_taker('', $wardid);
            $data['action_reviewer'] = $this->atr_report_model->get_action_reviewer();
            $data['areas'] = $this->atr_report_model->get_area(); 
            $data['organization'] = $this->atr_report_model->get_organization($organizationid,'');
            $data['department'] = $this->atr_report_model->get_department($departmentid);
            $data['projectsupport'] = $this->atr_report_model->get_project_support($areaid,$projectsupportid,'');
            $data['region'] = $this->atr_report_model->get_region('',$areaid);
            $data['subregion'] = $this->atr_report_model->get_subregion('',$regionid);
            $data['ward'] = $this->atr_report_model->get_wards('', $subregionid);
      
        } else {
            $data['title']         = $siteTitle[$role_slug];
            if($role!=6){
                $data['action_taker'] = $this->atr_report_model->get_action_taker($area, $wardid,$staffid,$regionid,$subregionid);
                
            }else{
                $data['action_taker'] = $this->atr_report_model->get_action_taker($area, $wardid,'',$regionid,$subregionid);
                
            }
            
            $data['action_reviewer'] = $this->atr_report_model->get_action_reviewer($area,$wardid,$regionid,$subregionid);
            //if($role == 7 || $role == 3 || $role == 8){
            //    $data['region'] = $this->report_model->get_ae_region();
            //    $data['subregion'] = $this->report_model->get_ae_subregion();
            //}else{
                
            // $data['organization'] = $this->atr_report_model->get_organization($org_id,$area,$regionid);
            $data['organization'] = $this->atr_report_model->get_organization($org_id='',$area,$regionid);

            $getOrgide = array();
            $data['department'] = array();
            if($role==6){
            $getOrgids = $this->atr_report_model->get_organization($org_id,$area,$regionid);
                    if(count($getOrgids)>0){
                    foreach($getOrgids as $val){
                        $getOrgide []= $val['id'];
                    }}
                    if(!empty($getOrgide)){
                        $data['department'] = $this->department_model->getOrgDepartment($getOrgide);
                        
                    }
                }else{

                     $department_ids = (!empty($user_at->desig_id)) ? array($user_at->desig_id) : $departmentids;
                    $data['department'] = $this->department_model->getOrgDepartmentbyid($department_ids);
                }
            if($role!=6){
                if($role==4){
                    $plStaffId = array();
                    $data_projectsupport = array();
                    $getPLids = $this->atr_report_model->get_action_taker($area, $wardid,$staffid,$regionid,$subregionid);
                    if(count($getPLids)>0){
                    foreach($getPLids as $val){
                        $plStaffId []= $val['staffid'];
                    }}
                    $data['projectsupport'] = $this->atr_report_model->get_project_support('','','',$plStaffId,$regionid);
                    $dataprojectsupport = $this->atr_report_model->get_project_support('','','',$plStaffId,$regionid);
                    if(count($dataprojectsupport)>0){
                        foreach($dataprojectsupport as $val){
                            $data_projectsupport []= $val['staffid'];
                        }}
                        
                            $categories = $this->issue_model->get_area_issues($area,'',$data_projectsupport);//show all categories for reporting
                              
                }else{
                    $data['projectsupport'] = $this->atr_report_model->get_project_support('','','',$staffid,$regionid);
                   
                    $categories = $this->issue_model->get_area_issues($area,'',$staffid, $GLOBALS['current_user']->location['region_id']);//show all categories for reporting
                }
                
            }else{
                
                $data['projectsupport'] = $this->atr_report_model->get_project_support($area,'','','',$regionid); 
                $categories = $this->issue_model->get_area_issues($area,1,$projectsupportid,$regionid);//show all categories for reporting 
            }
            if($role==9){
                $categories = $this->issue_model->get_area_issues($area, 1, '', $GLOBALS['current_user']->location['region_id']);
            }
            // pre($categories);
            //if(!empty($area)){
                if(($slug_url !='aa' && $slug_url !='ae-area') && ($GLOBALS['current_user']->role != 6)){
                    $staff_city = $this->atr_report_model->get_currentuser_city($staffid);
                
                    if($staff_city){
                        $data['region'] = $this->atr_report_model->get_region($area,'',$staff_city,'');
                    }
                }else{
                    $data['region'] = $this->atr_report_model->get_region($area,'','','');
                }
                
             //}else{
                //$data['region'] = $this->atr_report_model->get_region('','','11',$this->session->userdata('staff_user_id'));
            // }
            
            $data['subregion'] = $this->atr_report_model->get_subregion($area,$regionid);
            //if(!empty($subregionid)){
                $data['ward'] = $this->atr_report_model->get_wards($area, $subregionid, $wardid, $staffid,$regionid);
            // }else{
            //     $data['ward'] = $this->atr_report_model->get_wards('','','',$this->session->userdata('staff_user_id'));
            // }
            
            
        }
       
        $data['role_slug']    = $role_slug;
        // if ($role == 4 || $role == 6 || $role == 7) {
        //     $data['ticket'] = $this->report_model->get_ar_ticket();
        // } else {
        $data['ticket'] = $this->atr_report_model->get_ticket();
        // }


        $region = [];
        $subregion = [];
        if(!empty($data['region'])){
            foreach ($data['region'] as $var) {
                $region[] = $var['id'];
            }
        }
        if(!empty($data['subregion'])){
        foreach ($data['subregion'] as $var) {
            $subregion[] = $var['id'];
        }
    }
        $cats = [];
        if (!empty($post_data['category'])) {
            if (!empty($post_data['category'][0])) {

                if (strpos($post_data['category'][0], ",") != false) {
                    $cats = explode(",", $post_data['category'][0]);
                } else {
                    $cats = $post_data['category'];
                }
            } else {
                $cats = [];
            }
        }

        $range = [];
        if (!empty($post_data['duration'])) {
            if (!empty($post_data['duration'][0])) {
                if (strpos($post_data['duration'][0], ",") != false) {
                    $range = explode(",", $post_data['duration'][0]);
                } else {
                    $range = $post_data['duration'];
                }
            } else {
                $range = [];
            }
        }
       if(!empty($post_data['ticket'])){
         if(in_array("7", $post_data['ticket'])){
             $post_data_ticket = array(1,3,4,5,6,7,9,10,11,12,13,15,16);
         }else{
            $post_data_ticket = (!empty($post_data['ticket'])) ? $post_data['ticket'] : "";
        }
    }else{
        $post_data_ticket = (!empty($post_data['ticket'])) ? $post_data['ticket'] : "";  
    }

        $qcTicket = (!empty($post_data['ticket'])) ? $post_data['ticket'] : ""; 
        //pre($qcTicket);

        $tableParams = [
            'areaid' => (!empty($post_data['areaid'])) ? $post_data['areaid'] : '',
            'region' => (!empty($post_data['region'])) ? $post_data['region'] : '',
            'organization' => (!empty($post_data['organization'])) ? $post_data['organization'] : '',
            'department' => (!empty($post_data['department'])) ? $post_data['department'] : '',
            'projectsupport' => (!empty($post_data['projectsupport'])) ? $post_data['projectsupport'] : '',
            'subregion' => (!empty($post_data['subregion'])) ? $post_data['subregion'] : '',
            'ward' => (!empty($post_data['ward'])) ? $post_data['ward'] : '',
            'category' => (!empty($cats)) ? $cats : '',
            'bug' => (!empty($post_data['bug'])) ? $post_data['bug'] : '',
            'action_taker' => (!empty($post_data['action_taker'])) ? $post_data['action_taker'] : '',
            'action_reviewer' => (!empty($post_data['action_reviewer'])) ? $post_data['action_reviewer'] : '',
            'report_date' => (!empty($post_data['report_months'])) ? $post_data['report_months'] : '',
            'to_date' => (!empty($post_data['report-to'])) ? $post_data['report-to'] : '',
            'from_date' => (!empty($post_data['report-from'])) ? $post_data['report-from'] : '',
            'statusIds' => $post_data_ticket,
            'duration' => (!empty($range)) ? $range : '',
        ];


        if ($get_area != "") {
            $get_area_name = $this->atr_report_model->get_area_name([$get_area]);
            if ($get_area_name)
                $tableParams['areaid'] = ['areaid' => $get_area, "name" => $get_area_name];
        }

        if ($get_status != "") {
            $get_project_status = $this->atr_report_model->get_status(['id' => $get_status]);
            // pre($get_project_status);
            if ($get_project_status)
                $tableParams['statusIds'] = ['id' => $get_status, "name" => $get_project_status[0]['name']];
        }
        if ($get_city != "") {
            $get_city_data = $this->atr_report_model->get_region($get_area, '', $get_city)[0];
            if ($get_city_data)
                $tableParams['region'] = $get_city_data;
        }
        if ($get_organization != "") {
            $get_organization_data = $this->atr_report_model->get_organization($get_organization, '')[0];
            if ($get_organization_data)
                $tableParams['organization'] = $get_organization_data;
        }
        if ($get_department != "") {
            $get_department_data = $this->atr_report_model->get_organization($get_department)[0];
            if ($get_department_data)
                $tableParams['department'] = $get_department_data;
        }
        if ($get_projectsupport != "") {
            $get_projectsupport_data = $this->atr_report_model->get_project_support('',$get_projectsupport, '')[0];
            if ($get_organization_data)
                $tableParams['projectsupport'] = $get_projectsupport_data;
        }

        if ($get_zone != "") {
            $get_zone_data = $this->atr_report_model->get_subregion($get_area, $get_city, $get_zone)[0];
            if ($get_zone_data)
                $tableParams['subregion'] = $get_zone_data;
        }

        if ($get_ward != "") {
            $get_wards_data = $this->atr_report_model->get_wards($get_area, $get_city, $get_zone,$get_ward)[0];
            if ($get_wards_data)
                $tableParams['wards'] = $get_wards_data;
        }
        // pre($areas);

        //}
        // pre([$get_area, $get_status, $tableParams['statusIds']]);

        if ($this->input->post()) {
            $data['filter'] = json_encode($post_data);
        }else{
            $data['filter'] = 0;
        }


        /*$tableParams = [
            'limit_start'   => (!empty($post_data['start'])) ? $post_data['start'] : 0,
            'record_length' => (!empty($post_data['length'])) ? $post_data['length'] : 15,
        ];

        $projects_data_array = $this->atr_report_model->get_report($tableParams);

        $data['projects'] = $projects_data_array;
        */


        //pre($data);exit;
       
        $data['statuses'] = $this->atr_report_model->get_report_summary($tableParams,1);
       
        $data['totals'] = $this->atr_report_model->get_report_summary($tableParams);//report data
        // $data['totals'] = $this->report_model->get_report_total($tableParams);

        //$data['post_data'] = $post_data;
        $data['tableParams'] = $tableParams;
        
       
        $data['categories'] = (!empty($categories)) ? $categories : [];
    //    pre($data);
        //$data['department'] = $this->atr_report_model->get_department();
        $data["durations"] = [
            ["id" => "30", "duration" => "< 1 month"],
            ["id" => "31 - 183", "duration" => "1 month - 6 months"],
            ["id" => "184 - 365", "duration" => "6 months - 1 year"],
            ["id" => "366 - 1095", "duration" => "1 year - 3 years"],
            ["id" => "1096 - 1825", "duration" => "3 years - 5 years"],
            ["id" => "1826", "duration" => "> 5 years"],
                ];

        // pre($data);
        // die();
        // $data['total'] = 4;

        $this->load->view('admin/reportatr/index', $data);
    }
    public function atr_role_wise_report(){
        $area = $GLOBALS['current_user']->area;
        $role = $GLOBALS['current_user']->role;
        $role_slug =  $GLOBALS['current_user']->role_slug_url;
        $user_at  = $this->staff_model->get_userDetails($GLOBALS['current_user']->staffid);
        if($role==6){
            $org_id ='';
        }else{
            $org_id = (!empty($user_at->org_id)) ? array($user_at->org_id) : '';
        }
        
        $post_data = $this->input->post('report_months');
        $org = $this->input->post('organization');
        $department = $this->input->post('department');
        $staffid = $GLOBALS['current_user']->staffid;
        // pre($staffid);
        
       
        $pllist = array();
        $revlist = array();
        $pslist = array();
        $data['organization'] = $this->atr_report_model->get_organization($org_id,$area);
    $getOrgide = array();
            $getOrgids = $this->atr_report_model->get_organization($org_id,$area);
            $data['department']=array();
            if($role==6){
                    if(count($getOrgids)>0){
                    foreach($getOrgids as $val){
                        $getOrgide []= $val['id'];
                    }}
                    if(!empty($getOrgide)){
                        $data['department'] = $this->department_model->getOrgDepartment($getOrgide);
                    }
                }else{

                    $department_ids = (!empty($user_at->desig_id)) ? array($user_at->desig_id) : '';
                   $data['department'] = $this->department_model->getOrgDepartmentbyid($department_ids);
               }
                    // else{
                    //     $data['department'] = $this->atr_report_model->get_department();
                    // }
        if($role==8){
        $data['atr_role_project_support'] = $this->atr_report_model->atr_rolewise_report(8,$area,'',$staffid,$post_data,$org,$department);
        $data['atr_role_project_leader'] = $this->atr_report_model->atr_rolewise_report(3,$area,$staffid,'',$post_data,$org,$department);
        if(!empty($data['atr_role_project_leader'])){
            foreach($data['atr_role_project_leader'] as $valpl){
                $pllist []=$valpl['staffid'];
            }
    
            $reviewres = $this->staff_model->getReviewersfor_atr($pllist);
            foreach($reviewres as $valprev){
                $revlist []=$valprev;
            }
        }
        $data['atr_rolewise_report'] = $this->atr_report_model->atr_rolewise_report(4,$area,'',$revlist,$post_data,$org,$department);
    }else if($role==3){
        
        $data['atr_role_project_leader'] = $this->atr_report_model->atr_rolewise_report(3,$area,'',$staffid,$post_data,$org,$department);
            $reviewres = $this->staff_model->getReviewersfor_atr($staffid);
            if(!empty($reviewres)){
        foreach($reviewres as $valprev){
            $revlist []=$valprev;
        }
    }
        $data['atr_rolewise_report'] = $this->atr_report_model->atr_rolewise_report(4,$area,'',$revlist,$post_data,$org,$department);
        $ps = $this->staff_model->get_staff_projectsupport($staffid);
        if(!empty($ps)){
        foreach($ps as $psval){
            $pslist []=$psval;
        }
    }
        $data['atr_role_project_support'] = $this->atr_report_model->atr_rolewise_report(8,$area,'',$pslist,$post_data,$org,$department);
    }else if($role==4){
        $data['atr_rolewise_report'] = $this->atr_report_model->atr_rolewise_report(4,$area,'',$staffid,$post_data,$org,$department);
        $pl = $this->staff_model->get_staff_projectleader($staffid);
        if(!empty($pl)){
            foreach($pl as $plval){
                $revlist []=$plval;
            }
        }
        
        $data['atr_role_project_leader'] = $this->atr_report_model->atr_rolewise_report(3,$area,'',$revlist,$post_data,$org,$department);
        
        $ps = $this->staff_model->get_staff_projectsupport($revlist);
        if(!empty($ps)){
        foreach($ps as $psval){
            $pslist []=$psval;
        }
    }
        $data['atr_role_project_support'] = $this->atr_report_model->atr_rolewise_report(8,$area,'',$pslist,$post_data,$org,$department);
    }else{
        $data['atr_role_project_support'] = $this->atr_report_model->atr_rolewise_report(8,$area,'',$pslist,$post_data,$org,$department);
        $data['atr_role_project_leader'] = $this->atr_report_model->atr_rolewise_report(3,$area,'',$pslist,$post_data,$org,$department);
        $data['atr_rolewise_report'] = $this->atr_report_model->atr_rolewise_report(4,$area,'',$pslist,$post_data,$org,$department);
    }
    
         $tableParams['organization'] = $org;
         $tableParams['department'] = $department;
         $tableParams['custom_period'] = $post_data;
         $data['tableParams'] = $tableParams;
        //  pre($data);
        $this->load->view('admin/reportatr/atr_rolewise_report', $data);
    }
    public function soft_delete(){
        $post = $_POST['selectedRows'];
        if(!empty($post)){
            $json = $this->atr_report_model->soft_delete($post);
        }else{
            $json = "please post delete data. ";
        }
       
       echo json_decode($json, true);
    }

    public function reopen_ticket(){
        $post = $_POST;

        if(!empty($post)){
            $json = $this->atr_report_model->reopen_ticket($post);
        }else{
            $json = "please post delete data. ";
        }
       
       echo json_decode($json, true);
    }
    public function approve_ticket(){
        $post = $_POST;

        if(!empty($post)){
            $projectId = $post['project_id'];
            $staffId = !empty(getProjectAssignedPreviousUser($projectId,'lt'))?getProjectAssignedPreviousUser($projectId,'lt'):'';
            $reAssignStatus = 10;
            $this->projects_model->updateAssignedUser($projectId, $staffId,$reAssignStatus);
            
            $json = $this->atr_report_model->approve_ticket($post);
        }else{
            $json = "please post delete data. ";
        }
       
       echo json_decode($json, true);
    }
    public function get_atr_projects() {
            $locInfo = [];
            $locationMarkers = [];
        $post_data = $this->input->post();

        //pre($post_data['form']);


        $post_data2 = $post_data['form'];
        //pre($post_data2);
        
        // $search_data = (trim($post_data->search['value']) ? trim($post_data->search['value']) : '');


        $get_area   = $this->input->get('area') ? base64_decode($this->input->get('area')) : "";
        $get_status = $this->input->get('status') ? base64_decode($this->input->get('status')) : "";
        $get_city   = $this->input->get('city') ? base64_decode($this->input->get('city')) : "";
        $get_zone   = $this->input->get('zone') ? base64_decode($this->input->get('zone')) : "";
        $role       = $GLOBALS['current_user']->role;
        $role_slug  = $GLOBALS['current_user']->role_slug_url;
        $slug_url   = $this->input->get('role');
        $get_organization = $this->input->get('organization') ? base64_decode($this->input->get('organization')) : "";
        $get_projectsupport = $this->input->get('projectsupport') ? base64_decode($this->input->get('projectsupport')) : "";
        $get_department = $this->input->get('department') ? base64_decode($this->input->get('department')) : "";
        $cats = [];
        if (!empty($post_data2['category'])) {
            if (!empty($post_data2['category'][0])) {

                if (strpos($post_data2['category'][0], ",") != false) {
                    $cats = explode(",", $post_data2['category'][0]);
                } else {
                    $cats = $post_data2['category'];
                }
            } else {
                $cats = [];
            }
        }

        $range = [];
        if (!empty($post_data2['duration'])) {
            if (!empty($post_data2['duration'][0])) {
                if (strpos($post_data2['duration'][0], ",") != false) {
                    $range = explode(",", $post_data2['duration'][0]);
                } else {
                    $range = $post_data2['duration'];
                }
            } else {
                $range = [];
            }
        }
        
        if(!empty($post_data2['ticket'])){
            if(in_array("7", $post_data2['ticket'])){
                $post_data_ticket = array(1,3,5,6,7,9,10,11,12,13,15,16);
            }else{
               $post_data_ticket = (!empty($post_data2['ticket'])) ? $post_data2['ticket'] : "";
           }
       }else{
           $post_data_ticket = (!empty($post_data2['ticket'])) ? $post_data2['ticket'] : "";  
       }
         $search_data = '';
        if(!empty($post_data['search']['value'])){
            $search_data = (trim($post_data['search']['value']) ? trim($post_data['search']['value']) : '');
        }
        if($role==6){
            $columns = array(
                1 => 'projects.id',
                2 => 'projects.name',
                3 => 'projects.status',
                4 => 'project_assigned',
            );
        }else{
            $columns = array(
                0 => 'projects.id',
                1 => 'projects.name',
                2 => 'projects.status',
                4 => 'project_assigned',
            );
        }
        
        $data_collname = '';
        $data_collascdsc = '';
        if(!empty($columns[$post_data['order'][0]['column']]) && !empty($post_data['order'][0]['dir'])){
            
            $data_collname = (trim($columns[$post_data['order'][0]['column']]) ? trim($columns[$post_data['order'][0]['column']]) : '');
            $data_collascdsc = (trim($post_data['order'][0]['dir']) ? trim($post_data['order'][0]['dir']) : '');
        }
       
        $tableParams = [
            'areaid'        => (!empty($post_data2['areaid'])) ? $post_data2['areaid'] : '',
            'region'        => (!empty($post_data2['region'])) ? $post_data2['region'] : '',
            'subregion'     => (!empty($post_data2['subregion'])) ? $post_data2['subregion'] : '',
            'category'      => (!empty($cats)) ? $cats : '',
            'bug'           => (!empty($post_data2['bug'])) ? $post_data2['bug'] : $search_data,

            'action_taker'  => (!empty($post_data2['action_taker'])) ? $post_data2['action_taker'] : '',
            'action_reviewer' => (!empty($post_data2['action_reviewer'])) ? $post_data2['action_reviewer'] : '',
            'report_date'   => (!empty($post_data2['report_months'])) ? $post_data2['report_months'] : '',
            'to_date'       => (!empty($post_data2['report-to'])) ? $post_data2['report-to'] : '',
            'from_date'     => (!empty($post_data2['report-from'])) ? $post_data2['report-from'] : '',
            // 'statusIds'     => (!empty($post_data2['ticket'])) ? $post_data2['ticket'] : "",
            'statusIds'     => $post_data_ticket,
            'duration'      => (!empty($range)) ? $range : '',

            //'search_data'   => $search_data,
            'organization' => (!empty($post_data2['organization'])) ? $post_data2['organization'] : '',
            'department' => (!empty($post_data2['department'])) ? $post_data2['department'] : '',
            'projectsupport' => (!empty($post_data2['projectsupport'])) ? $post_data2['projectsupport'] : '',
            'columnname' => (!empty($data_collname)) ? $data_collname : '',
            'ascdesc' => (!empty($data_collascdsc)) ? $data_collascdsc : '',
        ];


        
        if ($get_area != "") {
            $get_area_name = $this->atr_report_model->get_area_name([$get_area]);
            if ($get_area_name)
                $tableParams['areaid'] = ['areaid' => $get_area, "name" => $get_area_name];
        }

        if ($get_status != "") {
            $get_project_status = $this->atr_report_model->get_status(['id' => $get_status]);
            // pre($get_project_status);
            if ($get_project_status)
                $tableParams['statusIds'] = ['id' => $get_status, "name" => $get_project_status[0]['name']];
        }
        if ($get_city != "") {
            $get_city_data = $this->atr_report_model->get_region($get_area, '', $get_city)[0];
            if ($get_city_data)
                $tableParams['region'] = $get_city_data;
        }

        if ($get_zone != "") {
            $get_zone_data = $this->atr_report_model->get_subregion($get_area, $get_city, $get_zone)[0];
            if ($get_zone_data)
                $tableParams['subregion'] = $get_zone_data;
        }

        if ($get_organization != "") {
            $get_organization_data = $this->atr_report_model->get_organization($get_organization, '')[0];
            if ($get_organization_data)
                $tableParams['organization'] = $get_organization_data;
        }
        if ($get_department != "") {
            $get_department_data = $this->atr_report_model->get_organization($get_department)[0];
            if ($get_department_data)
                $tableParams['department'] = $get_department_data;
        }
        if ($get_projectsupport != "") {
            $get_projectsupport_data = $this->atr_report_model->get_project_support('',$get_projectsupport, '')[0];
            if ($get_organization_data)
                $tableParams['projectsupport'] = $get_projectsupport_data;
        }
        $tableParams[ 'limit_start' ]   = (!empty($post_data['start']) ? $post_data['start'] : 0);
        $tableParams[ 'record_length' ] = (!empty($post_data['length']) ? $post_data['length'] : 10);
        
        
        $projects = $this->atr_report_model->get_report( $tableParams );
        
        
        //get project count
        $tableParams['total_records'] = true;
        $project_count = $this->atr_report_model->get_report( $tableParams );
        
        // echo'<pre>'; print_r( $project_count );

        $projects_data_array = [];
        foreach($projects as $k => $val) {

            $project_id = $val['id'];
            $ticketDetails = $this->atr_report_model->get_project_details( $project_id);
            $longterm = getProjectActivityLongTerm($project_id);

            $assignedUser = getProjectAssignedUser($project_id);

            
            $assignedUserDetails = [];
            $assignedUserDetails = (object)$assignedUserDetails;
            if($assignedUser) {
                $assignedUserDetails = $this->staff_model->get_userDetails($assignedUser);
            }
            
            $tasks = $this->projects_model->get_task_details( $project_id);
            $milestone = $this->atr_report_model->get_current_milestone( $project_id);
            
            $milestone = !empty($milestone[0]) ? $milestone[0] : []; 
            $milestone_name = '';

            // if(!empty($milestone['task_name']) && in_array($val['status'],[2,4,6]) && $val['frozen'] == 0){
            //     $milestone_name = $milestone['task_name'];
            // }
            if(!empty($milestone['task_name']) && $val['frozen'] == 0){
                $milestone_name = $milestone['task_name'];
            }
            // if($val['status'] == 3){
            //     $milestone_name = 'NA';
            // }

            if(in_array($val['status'], [2, 4, 6])){
                $resolvedMilestone = $this->atr_report_model->get_current_milestone($project_id,4);
                
                $milestone['task_id'] = !empty($resolvedMilestone[0]['task_id']) ? $resolvedMilestone[0]['task_id'] : (!empty($milestone['task_id'])?$milestone['task_id']:'');
            }

            $taskId = (!empty($milestone['task_id'])) ? $milestone['task_id'] : '';
            $latestImages = array();

            //$latest_image ='';
            //$latest_location = '';

            $resolved_evidence = '';
            $resolved_location = '';
            
            if(!empty($taskId)){
                $latestImages = $this->dashboard_model->get_evidence_image($project_id, $taskId);
                if(!empty($latestImages[0]['file_name']) && in_array( $val['status'], [ 2, 4, 6, 3]) ){

                    $resolved_evidence = base_url('uploads/tasks/' . $taskId . '/') . $latestImages[0]['file_name'];

                    //$resolved_evidence = '<a href="'. $resolved_evidence .'" target="_blank" class="report-location" ><i class="fa fa-eye" aria-hidden="true"></i></a>';
                
                    $resolved_location = '';
                    if(!empty($latestImages[0]['latitude']) && $latestImages[0]['latitude'] != 0 && !empty($latestImages[0]['longitude']) &&  $latestImages[0]['longitude'] != 0){
                        $resolved_location = 'https://maps.google.com/maps?q=' . $latestImages[0]['latitude'] . ',' . $latestImages[0]['longitude'];

                        //$resolved_location = '<a href="'. $resolved_location .'" target="_blank" class="report-location"><i class="fa fa-map-marker" aria-hidden="true"></i></a>';
                    }
                }
            }

            $projectNotes = project_latest_notes($project_id);
            $projectNote_content = !empty($projectNotes->content) ? $projectNotes->content : '';
            //2, 4, 6, 
            if(!empty($projectNote_content) && $val['frozen'] == 0 && (in_array( $val['status'], [3,5]) || !empty($val['sub_ticket_id']) || is_project_reopened($project_id))){
                $resolved_evidence = !empty($resolved_evidence) ? $resolved_evidence : '';
                $resolved_location = !empty($resolved_location) ? $resolved_location : '';
            }
            
            $evidence = $this->atr_report_model->get_evidence($project_id);
            $location = $this->atr_report_model->get_location($project_id);

            $ticket_id = !empty( $val['sub_id']) ?  $val['sub_id'] :  $project_id;   
            $img_type = '';

            if($val['status'] == 3){
                $img_type = "closed";
            }else{
                $img_type = "original";
            }
        
            if(!empty( $assignedUserDetails->full_name) && !empty( $assignedUserDetails->organisation)){
                $assign = $assignedUserDetails->full_name  ." (".  $assignedUserDetails->organisation.")";
            }else{
                $assign = '';
            }
            $status = (!empty( $ticketDetails->status_name)) ?  $ticketDetails->status_name : '';
            $assign_email = (!empty( $assignedUserDetails->email)) ?  $assignedUserDetails->email : '';
            

            $raised_name = "";
            $raised_email = "";
            $raised_phone = "";
            $user_type = "";
            
            if(!empty( $val['user_type']) && $val['user_type'] == 'Call-Center'){
                $user_type = "Call Center Executive";
                $raised_name = (!empty( $val['rname'])) ?  $val['rname'] : '';
                $raised_email = (!empty( $val['remail'])) ?  $val['remail'] : '';
                $raised_phone = (!empty( $val['rphonenumber'])) ?  $val['rphonenumber'] : '';
            }else if(!empty( $val['user_type']) && $val['user_type'] == 'Surveyor'){
                $user_type = "Surveyor";
                $raised_name = (!empty( $val['firstname'])) ?  $val['firstname'] : '';
                $raised_email = (!empty( $val['email'])) ?  $val['email'] : '';
                $raised_phone = (!empty( $val['phonenumber'])) ?  $val['phonenumber'] : '';
            }

            // project leader
            $leader_name = '';
            $leadername = $this->atr_report_model->get_report_leader($project_id);
          
            
            if(!empty($leadername)){
                $leader_name = $leadername->firstname;
            }

            // reviewer
            $reviewer_name = '';
            if(!empty($leadername)){
                $reviewername = $this->staff_model->get_reporting_person($leadername->staff_id);
                if(!empty($reviewername)){
                    $reviewer_name = $reviewername->firstname;
                }
            }

            // status
            $status_tag = '';
            $statusname = '';
        
            if($val['status'] == 9 && $val['is_assigned'] == 0 && $val['frozen'] == 0 ){
                $status_tag = 'Unassigned';
            }else  if (in_array( $val['status'], [ 2])  && $val['action_date'] >= date('Y-m-d') && $val['frozen'] == 0  ) {
                $status_tag = 'In Progress';
            }else if ((in_array( $val['status'], [6])) ) {
                $status_tag = 'Re-opened';
            }else if( $val['status'] == 5 && $val['frozen'] == 0 ){
                $status_tag = 'Referred';
            }else if( $val['status'] == 1 && $val['frozen'] == 0 ){
                $status_tag = 'Assigned';
            }else if( $val['status'] == 3 && $val['frozen'] == 0 ){
                $status_tag = 'Closed';
            }else if( $val['status'] == 4 && $val['frozen'] == 0 ){
                $status_tag = 'Resolved';
            } else if($val['frozen'] == 1){
                $status_tag = 'Frozen';
            }else if($val['status'] == 11 && $val['frozen'] == 0 || $val['status'] == 12 && $val['frozen'] == 0  ){
                if($role==8){ 
                    $status_tag = 'Submited for approval';
                 }else{ 
                    $status_tag = 'Pending for approval';
                 } 
                
            }else if( $val['status'] == 10 && $val['frozen'] == 0 ){
                $status_tag = 'Long Term';
            }else if( $val['status'] == 15 && $val['frozen'] == 0 ){
                $status_tag = 'Unresolved';
            }else if( $val['status'] == 16 && $val['frozen'] == 0 ){
                $status_tag = 'Partially Resolved';
            }else if( $val['status'] == 13 && $val['frozen'] == 0 ){
                $status_tag = 'Verified';
            }

            // if(!empty($status_tag)){
                
            //     //  
            //     if ($status_tag == "Delayed" && $ticketDetails->project_status == 1 ) {
            //         $statusname = 'Unaccepted';
            //     }else if ( $status_tag == "Delayed" && ($ticketDetails->project_status == 2 ||  $ticketDetails->project_status == 6 || $ticketDetails->project_status == 4) ) {
            //         $statusname = 'Overdue';
            //     }else if ($status_tag == 'In Progress' && $val['reassigned'] == 1 ) {
            //         $statusname = 'Reassigned';
            //     }else if($status_tag == 'In Progress' && $ticketDetails->project_status == '2'){
            //         $statusname = '';
            //     }else if( $status_tag == 'In Progress' && $ticketDetails->project_status == '4'){
            //         $statusname = '';
            //     }else if( $status_tag == 'Rejected' && $ticketDetails->project_status == '5'){
            //         $statusname = '';
            //     }else if( $status_tag == 'In Progress' && $ticketDetails->project_status == '6'){
            //         $statusname = 'Reopened';
            //     }
            // }

            // if(!empty($statusname)){
            //     $status_name = $status_tag."-".$statusname;
            // }else{
            //     $status_name = $status_tag;
            // }
            $ticket_counter = $this->atr_report_model->ticket_counter($project_id);
            $statusId = 5;
            $ticket_transfer_counter = $this->atr_report_model->ticket_transfer_counter($project_id,$statusId);
            $viewed_projects = $this->dashboard_model->getViewStatus($project_id);
            $projects_data_array[$k]['project_id']         = $project_id;
            $projects_data_array[$k]['view']               =  $viewed_projects;
            $projects_data_array[$k]['ticket_id']          = $ticket_id;
            $projects_data_array[$k]['action_items']       = (!empty( $val['name']) ? strip_tags($val['name']) : '');

            $projects_data_array[$k]['status_name']        = $status_tag;
            $projects_data_array[$k]['status_code']        = $val['status'];

            $projects_data_array[$k]['current_user_role_slug_url'] = $GLOBALS['current_user']->role_slug_url;

            $projects_data_array[$k]['assigned_to']        = $assign;

            $projects_data_array[$k]['contact']            = (!empty( $assignedUserDetails->phonenumber) ?  $assignedUserDetails->phonenumber : '');
            $projects_data_array[$k]['longterm']            = (!empty( $longterm) ?  $longterm : '');
            $projects_data_array[$k]['Transferred']            =(!empty($ticket_transfer_counter) ?  $ticket_transfer_counter : '0');
            $projects_data_array[$k]['due_date']           = (!empty( $val['deadline']) ? date('d-m-Y', strtotime($val['deadline'])) : '');

            $projects_data_array[$k]['municipal_zone']     = (!empty( $ticketDetails->sub_region_name) ? $ticketDetails->sub_region_name : '' );
            $projects_data_array[$k]['landmark']           =  (!empty( $val['landmark']) ? $val['landmark'] : '');

            $projects_data_array[$k]['city_corporation']   =  (!empty( $ticketDetails->region_name) ? $ticketDetails->region_name : '');

            $projects_data_array[$k]['area_name']          =  (!empty( $ticketDetails->area_name) ? $ticketDetails->area_name : '');
            $projects_data_array[$k]['project_created']    =  date('d-m-Y', strtotime($val['project_created']));
            $projects_data_array[$k]['description']        =  (!empty( $val['description']) ? $val['description'] : '');
            $projects_data_array[$k]['evidence']           =  (!empty( $evidence[0]) ? $evidence[0] : '');
            $projects_data_array[$k]['location']           =  (!empty( $location[0]) ? $location[0] : '');

            $projects_data_array[$k]['projectNote_content']    = (!empty( $projectNote_content) ? $projectNote_content : '');
            $projects_data_array[$k]['resolved_evidence']      = (!empty( $resolved_evidence) ? $resolved_evidence : '');
            $projects_data_array[$k]['resolved_location']      = (!empty( $resolved_location) ? $resolved_location : '');

            $projects_data_array[$k]['role_name']              =  (!empty( $assignedUserDetails->role_name) ? $assignedUserDetails->role_name : '');
            $projects_data_array[$k]['assign_email']           =  (!empty( $assign_email) ? $assign_email : '');
            $projects_data_array[$k]['leader_name']            =  (!empty( $leader_name) ? $leader_name : '');
            $projects_data_array[$k]['reviewer_name']          =  (!empty( $reviewer_name) ? $reviewer_name : '');
            $projects_data_array[$k]['milestone_name']         =  (!empty( $milestone_name) ? $milestone_name : '');

            $projects_data_array[$k]['date_finished']          =  ((!empty( $val['date_finished'] && $val['date_finished'] != '0000-00-00 00:00:00')) ? date("d-m-Y", strtotime($val['date_finished'])) : '');

            $projects_data_array[$k]['user_type']              =  (!empty( $user_type) ? $user_type : '');
            $projects_data_array[$k]['raised_name']            =  (!empty( $raised_name) ? $raised_name : '');

            $projects_data_array[$k]['raised_phone']           =  (!empty( $raised_phone) ? $raised_phone : '');
            $projects_data_array[$k]['raised_email']           =  (!empty( $raised_email) ? $raised_email : '');
            $projects_data_array[$k]['img_type']               =  (!empty( $img_type) ? $img_type : '');



            //googlemap start
            $arr_files_data = $this->dashboard_model->get_latest_project_files_data($project_id);

                    if ($arr_files_data) {
                        $ticketDetails = $this->projects_model->get_project_details($project_id);
                        ;

                        $img = '';
                        $file = 'uploads/projects/' . $project_id . '/' . $arr_files_data->thumbnail_link;
                        if (file_exists($file)) {

                            $img = "<img src=" . base_url($file) . " style=height:200px; />";
                        }

                        $locationMarkers[] = [$ticketDetails->project_name, $arr_files_data->latitude, $arr_files_data->longitude, '#FFFF00'];

                        $locInfo[] = [
                            "<div class=ticket_details data-project_id=" . $project_id
                            . " ><h4> Project Id : " . $project_id . "</h4><h4>" . $ticketDetails->project_name . "</h4><p>(" . $ticketDetails->landmark . ")</p><p style=text-align:center;>" . $img . "</p></div>"
                        ];
                    }
            // google map end

        }
    
        echo json_encode( ['draw' => $post_data['draw'], 'recordsTotal' => $project_count, 'recordsFiltered' => $project_count, 'data' => $projects_data_array, 'locationMarkers' => $locationMarkers, 'locInfo' => $locInfo] );
        exit;

    }

    public function get_atr_projects_mapviewss() {
        $locInfo = [];
        $locationMarkers = [];
    $post_data = $this->input->post();

    // pre($post_data);


    //$post_data2 = $post_data['form'];
    parse_str($this->input->post('form'), $post_data2);
    //pre($post_data2);
    
    //$search_data = (trim($post_data->search['value']) ? trim($post_data->search['value']) : '');
    

    $get_area   = $this->input->get('area') ? base64_decode($this->input->get('area')) : "";
    $get_status = $this->input->get('status') ? base64_decode($this->input->get('status')) : "";
    $get_city   = $this->input->get('city') ? base64_decode($this->input->get('city')) : "";
    $get_zone   = $this->input->get('zone') ? base64_decode($this->input->get('zone')) : "";
    $role       = $GLOBALS['current_user']->role;
    $role_slug  = $GLOBALS['current_user']->role_slug_url;
    $slug_url   = $this->input->get('role');
    $get_organization = $this->input->get('organization') ? base64_decode($this->input->get('organization')) : "";
    $get_projectsupport = $this->input->get('projectsupport') ? base64_decode($this->input->get('projectsupport')) : "";
    $get_department = $this->input->get('department') ? base64_decode($this->input->get('department')) : "";
    $cats = [];
    if (!empty($post_data2['category'])) {
        if (!empty($post_data2['category'][0])) {

            if (strpos($post_data2['category'][0], ",") != false) {
                $cats = explode(",", $post_data2['category'][0]);
            } else {
                $cats = $post_data2['category'];
            }
        } else {
            $cats = [];
        }
    }

    $range = [];
    if (!empty($post_data2['duration'])) {
        if (!empty($post_data2['duration'][0])) {
            if (strpos($post_data2['duration'][0], ",") != false) {
                $range = explode(",", $post_data2['duration'][0]);
            } else {
                $range = $post_data2['duration'];
            }
        } else {
            $range = [];
        }
    }
    
    if(!empty($post_data2['ticket'])){
        if(in_array("7", $post_data2['ticket'])){
            $post_data_ticket = array(1,3,5,6,7,9,10,11,12,13,15,16);
        }else{
           $post_data_ticket = (!empty($post_data2['ticket'])) ? $post_data2['ticket'] : "";
       }
   }else{
       $post_data_ticket = (!empty($post_data2['ticket'])) ? $post_data2['ticket'] : "";  
   }

    $tableParams = [
        'areaid'        => (!empty($post_data2['areaid'])) ? $post_data2['areaid'] : '',
        'region'        => (!empty($post_data2['region'])) ? $post_data2['region'] : '',
        'subregion'     => (!empty($post_data2['subregion'])) ? $post_data2['subregion'] : '',
        'category'      => (!empty($cats)) ? $cats : '',
        'bug'           => (!empty($post_data2['bug'])) ? $post_data2['bug'] : '',
        'action_taker'  => (!empty($post_data2['action_taker'])) ? $post_data2['action_taker'] : '',
        'action_reviewer' => (!empty($post_data2['action_reviewer'])) ? $post_data2['action_reviewer'] : '',
        'report_date'   => (!empty($post_data2['report_months'])) ? $post_data2['report_months'] : '',
        'to_date'       => (!empty($post_data2['report-to'])) ? $post_data2['report-to'] : '',
        'from_date'     => (!empty($post_data2['report-from'])) ? $post_data2['report-from'] : '',
        // 'statusIds'     => (!empty($post_data2['ticket'])) ? $post_data2['ticket'] : "",
        'statusIds'     => $post_data_ticket,
        'duration'      => (!empty($range)) ? $range : '',

        //'search_data'   => $search_data,
        'organization' => (!empty($post_data2['organization'])) ? $post_data2['organization'] : '',
            'department' => (!empty($post_data2['department'])) ? $post_data2['department'] : '',
            'projectsupport' => (!empty($post_data2['projectsupport'])) ? $post_data2['projectsupport'] : '',
    ];

    if ($get_organization != "") {
        $get_organization_data = $this->atr_report_model->get_organization($get_organization, '')[0];
        if ($get_organization_data)
            $tableParams['organization'] = $get_organization_data;
    }
    if ($get_department != "") {
        $get_department_data = $this->atr_report_model->get_organization($get_department)[0];
        if ($get_department_data)
            $tableParams['department'] = $get_department_data;
    }
    if ($get_projectsupport != "") {
        $get_projectsupport_data = $this->atr_report_model->get_project_support('',$get_projectsupport, '')[0];
        if ($get_organization_data)
            $tableParams['projectsupport'] = $get_projectsupport_data;
    }
    if ($get_area != "") {
        $get_area_name = $this->atr_report_model->get_area_name([$get_area]);
        if ($get_area_name)
            $tableParams['areaid'] = ['areaid' => $get_area, "name" => $get_area_name];
    }

    if ($get_status != "") {
        $get_project_status = $this->atr_report_model->get_status(['id' => $get_status]);
        // pre($get_project_status);
        if ($get_project_status)
            $tableParams['statusIds'] = ['id' => $get_status, "name" => $get_project_status[0]['name']];
    }
    if ($get_city != "") {
        $get_city_data = $this->atr_report_model->get_region($get_area, '', $get_city)[0];
        if ($get_city_data)
            $tableParams['region'] = $get_city_data;
    }

    if ($get_zone != "") {
        $get_zone_data = $this->atr_report_model->get_subregion($get_area, $get_city, $get_zone)[0];
        if ($get_zone_data)
            $tableParams['subregion'] = $get_zone_data;
    }


    $tableParams[ 'limit_start' ]   = (!empty($post_data['start']) ? $post_data['start'] : 0);
    $tableParams[ 'record_length' ] = (!empty($post_data['length']) ? $post_data['length'] : 1000);
    
    
    $projects = $this->atr_report_model->get_report( $tableParams );
    
    
    //get project count
    $tableParams['total_records'] = true;
    $project_count = $this->atr_report_model->get_report( $tableParams );
    
    // echo'<pre>'; print_r( $project_count );

    $projects_data_array = [];
    foreach($projects as $k => $val) {

        $project_id = $val['id'];
        $ticketDetails = $this->atr_report_model->get_project_details( $project_id);
        $longterm = getProjectActivityLongTerm($project_id);

        $assignedUser = getProjectAssignedUser($project_id);

        
        $assignedUserDetails = [];
        $assignedUserDetails = (object)$assignedUserDetails;
        if($assignedUser) {
            $assignedUserDetails = $this->staff_model->get_userDetails($assignedUser);
        }
        
        $tasks = $this->projects_model->get_task_details( $project_id);
        $milestone = $this->atr_report_model->get_current_milestone( $project_id);
        
        $milestone = !empty($milestone[0]) ? $milestone[0] : []; 
        $milestone_name = '';

        if(!empty($milestone['task_name']) && in_array($val['status'],[2,4,6]) && $val['frozen'] == 0){
            $milestone_name = $milestone['task_name'];
        }

        if($val['status'] == 3){
            $milestone_name = 'NA';
        }

        if(in_array($val['status'], [2, 4, 6])){
            $resolvedMilestone = $this->atr_report_model->get_current_milestone($project_id,4);
            
            $milestone['task_id'] = !empty($resolvedMilestone[0]['task_id']) ? $resolvedMilestone[0]['task_id'] : (!empty($milestone['task_id'])?$milestone['task_id']:'');
        }

        $taskId = (!empty($milestone['task_id'])) ? $milestone['task_id'] : '';
        $latestImages = array();

        //$latest_image ='';
        //$latest_location = '';

        $resolved_evidence = '';
        $resolved_location = '';
        
        if(!empty($taskId)){
            $latestImages = $this->dashboard_model->get_evidence_image($project_id, $taskId);
            if(!empty($latestImages[0]['file_name']) && in_array( $val['status'], [ 2, 4, 6, 3]) ){

                $resolved_evidence = base_url('uploads/tasks/' . $taskId . '/') . $latestImages[0]['file_name'];

                //$resolved_evidence = '<a href="'. $resolved_evidence .'" target="_blank" class="report-location" ><i class="fa fa-eye" aria-hidden="true"></i></a>';
            
                $resolved_location = '';
                if(!empty($latestImages[0]['latitude']) && $latestImages[0]['latitude'] != 0 && !empty($latestImages[0]['longitude']) &&  $latestImages[0]['longitude'] != 0){
                    $resolved_location = 'https://maps.google.com/maps?q=' . $latestImages[0]['latitude'] . ',' . $latestImages[0]['longitude'];

                    //$resolved_location = '<a href="'. $resolved_location .'" target="_blank" class="report-location"><i class="fa fa-map-marker" aria-hidden="true"></i></a>';
                }
            }
        }

        $projectNotes = project_latest_notes($project_id);
        $projectNote_content = !empty($projectNotes->content) ? $projectNotes->content : '';
        //2, 4, 6, 
        if(!empty($projectNote_content) && $val['frozen'] == 0 && (in_array( $val['status'], [3,5]) || !empty($val['sub_ticket_id']) || is_project_reopened($project_id))){
            $resolved_evidence = !empty($resolved_evidence) ? $resolved_evidence : '';
            $resolved_location = !empty($resolved_location) ? $resolved_location : '';
        }
        
        $evidence = $this->atr_report_model->get_evidence($project_id);
        $location = $this->atr_report_model->get_location($project_id);

        $ticket_id = !empty( $val['sub_id']) ?  $val['sub_id'] :  $project_id;   
        $img_type = '';

        if($val['status'] == 3){
            $img_type = "closed";
        }else{
            $img_type = "original";
        }
    
        if(!empty( $assignedUserDetails->full_name) && !empty( $assignedUserDetails->organisation)){
            $assign = $assignedUserDetails->full_name  ." (".  $assignedUserDetails->organisation.")";
        }else{
            $assign = '';
        }
        $status = (!empty( $ticketDetails->status_name)) ?  $ticketDetails->status_name : '';
        $assign_email = (!empty( $assignedUserDetails->email)) ?  $assignedUserDetails->email : '';
        

        $raised_name = "";
        $raised_email = "";
        $raised_phone = "";
        $user_type = "";
        
        if(!empty( $val['user_type']) && $val['user_type'] == 'Call-Center'){
            $user_type = "Call Center Executive";
            $raised_name = (!empty( $val['rname'])) ?  $val['rname'] : '';
            $raised_email = (!empty( $val['remail'])) ?  $val['remail'] : '';
            $raised_phone = (!empty( $val['rphonenumber'])) ?  $val['rphonenumber'] : '';
        }else if(!empty( $val['user_type']) && $val['user_type'] == 'Surveyor'){
            $user_type = "Surveyor";
            $raised_name = (!empty( $val['firstname'])) ?  $val['firstname'] : '';
            $raised_email = (!empty( $val['email'])) ?  $val['email'] : '';
            $raised_phone = (!empty( $val['phonenumber'])) ?  $val['phonenumber'] : '';
        }

        // project leader
        $leader_name = '';
        $leadername = $this->atr_report_model->get_report_leader($project_id);
      
        
        if(!empty($leadername)){
            $leader_name = $leadername->firstname;
        }

        // reviewer
        $reviewer_name = '';
        if(!empty($leadername)){
            $reviewername = $this->staff_model->get_reporting_person($leadername->staff_id);
            if(!empty($reviewername)){
                $reviewer_name = $reviewername->firstname;
            }
        }

        // status
        $status_tag = '';
        $statusname = '';
    
        if($val['status'] == 9 && $val['is_assigned'] == 0 && $val['frozen'] == 0 ){
            $status_tag = 'Unassigned';
        }else  if (in_array( $val['status'], [ 2])  && $val['action_date'] >= date('Y-m-d') && $val['frozen'] == 0  ) {
            $status_tag = 'In Progress';
        }else if ((in_array( $val['status'], [6]))) {
            $status_tag = 'Re-opened';
        }else if( $val['status'] == 5 && $val['frozen'] == 0 ){
            $status_tag = 'Referred';
        }else if( $val['status'] == 1 && $val['frozen'] == 0 ){
            $status_tag = 'Assigned';
        }else if( $val['status'] == 3 && $val['frozen'] == 0 ){
            $status_tag = 'Closed';
        } else if($val['frozen'] == 1){
            $status_tag = 'Frozen';
        }else if($val['status'] == 11 && $val['frozen'] == 0 || $val['status'] == 12 && $val['frozen'] == 0  ){
            if($role==8){ 
                $status_tag = 'Submited for approval';
             }else{ 
                $status_tag = 'Pending for approval';
             } 
            
        }else if( $val['status'] == 10 && $val['frozen'] == 0 ){
            $status_tag = 'Long Term';
        }else if( $val['status'] == 15 && $val['frozen'] == 0 ){
            $status_tag = 'Unresolved';
        }else if( $val['status'] == 16 && $val['frozen'] == 0 ){
            $status_tag = 'Partially Resolved';
        }else if( $val['status'] == 13 && $val['frozen'] == 0 ){
            $status_tag = 'Verified';
        }

        // if(!empty($status_tag)){
            
        //     //  
        //     if ($status_tag == "Delayed" && $ticketDetails->project_status == 1 ) {
        //         $statusname = 'Unaccepted';
        //     }else if ( $status_tag == "Delayed" && ($ticketDetails->project_status == 2 ||  $ticketDetails->project_status == 6 || $ticketDetails->project_status == 4) ) {
        //         $statusname = 'Overdue';
        //     }else if ($status_tag == 'In Progress' && $val['reassigned'] == 1 ) {
        //         $statusname = 'Reassigned';
        //     }else if($status_tag == 'In Progress' && $ticketDetails->project_status == '2'){
        //         $statusname = '';
        //     }else if( $status_tag == 'In Progress' && $ticketDetails->project_status == '4'){
        //         $statusname = '';
        //     }else if( $status_tag == 'Rejected' && $ticketDetails->project_status == '5'){
        //         $statusname = '';
        //     }else if( $status_tag == 'In Progress' && $ticketDetails->project_status == '6'){
        //         $statusname = 'Reopened';
        //     }
        // }

        // if(!empty($statusname)){
        //     $status_name = $status_tag."-".$statusname;
        // }else{
        //     $status_name = $status_tag;
        // }
        
            if($val['status'] == 1){
                $status_color ='#FF0000';
            }else if($val['status'] == 3){
                $status_color ='#008000';
            }else{
                $status_color = '#FFFF00';
            }

        //  $status_color = $this->report_model->get_project_status($val['status']);
         
        //googlemap start
        $arr_files_data = $this->dashboard_model->get_latest_project_files_data($project_id);

                if ($arr_files_data) {
                    $ticketDetails = $this->projects_model->get_project_details($project_id);
                    ;

                    $img = '';
                    $file = 'uploads/projects/' . $project_id . '/' . $arr_files_data->thumbnail_link;
                    if (file_exists($file)) {

                        $img = "<img src=" . base_url($file) . " style=height:200px; />";
                    }

                    $locationMarkers[] = [$ticketDetails->project_name, $arr_files_data->latitude, $arr_files_data->longitude,$status_color];

                    $locInfo[] = [
                        "<div class=ticket_details data-project_id=" . $project_id
                        . " ><h4> Project Id : " . $project_id . "</h4><h4>" . $ticketDetails->project_name . "</h4><p>(" . $ticketDetails->landmark . ")</p><p style=text-align:center;>" . $img . "</p></div>"
                    ];
                }
        // google map end

    }
    $staffid = $GLOBALS['current_user']->staffid;
    $org_ids = $this->staff_model->get_staff_orgid($staffid);
    if(!empty($org_ids)){
        $orgids = $org_ids->org_id;
    }else{
        $orgids = '';
    }
    $organization   = (!empty($post_data2['organization'])) ? $post_data2['organization'] : $orgids;
    $kmlfilesData ='';
    if(!empty($organization)){
        $kmlfile = $this->report_model->get_organisation_kml_file($organization);
        $kmlfilesData = $kmlfile->kml_file;
    }
    echo json_encode( ['success' => true,'locationMarkers' => $locationMarkers, 'locInfo' => $locInfo,'kml_file'=>$kmlfilesData] );
    exit;

}

    public function create_pdf()
    {
        $post_data = $this->input->get();
        //pre($post_data['category'][0]);
        $category = '';
        $duration = '';
        if(!empty($post_data['dashboard']) && $post_data['dashboard'] == 1){
            if(!empty($post_data['category'][0])){
                $category = explode(",",$post_data['category'][0]);
            }
            if(!empty($post_data['duration'][0])){
                $duration = explode(",",$post_data['duration'][0]);
            }

        }else{
            $category = (!empty($post_data['category'])) ? $post_data['category'] : '';
            $duration = (!empty($post_data['duration'])) ? $post_data['duration'] : '';
        }
        $tableParams = [
            'areaid' => (!empty($post_data['areaid'])) ? $post_data['areaid'] : '',
            'region' => (!empty($post_data['region'])) ? $post_data['region'] : '',
            'subregion' => (!empty($post_data['subregion'])) ? $post_data['subregion'] : '',
            'category' => $category,
            'bug' => (!empty($post_data['bug'])) ? $post_data['bug'] : '',
            'action_taker' => (!empty($post_data['action_taker'])) ? $post_data['action_taker'] : '',
            'action_reviewer' => (!empty($post_data['action_reviewer'])) ? $post_data['action_reviewer'] : '',
            'report_date' => (!empty($post_data['report_months'])) ? $post_data['report_months'] : '',
            'to_date' => (!empty($post_data['report-to'])) ? $post_data['report-to'] : '',
            'from_date' => (!empty($post_data['report-from'])) ? $post_data['report-from'] : '',
            'statusIds' => (!empty($post_data['ticket'])) ? $post_data['ticket'] : "",
            'duration' => $duration,
            'organization' => (!empty($post_data['organization'])) ? $post_data['organization'] : '',
            'department' => (!empty($post_data['department'])) ? $post_data['department'] : '',
            'projectsupport' => (!empty($post_data['projectsupport'])) ? $post_data['projectsupport'] : '',
        ];
        $data['report_date'] = (!empty($post_data['report_months'])) ? $post_data['report_months'] : '';
        $data['location'] = $this->report_model->get_user_location();
        $data['html'] =  $this->get_filter_table($post_data);
        $data['statuses'] = $this->atr_report_model->get_report_summary($tableParams,1);
       
        $data['totals'] = $this->atr_report_model->get_report_summary($tableParams);//report data
        // $data['statuses'] = $this->report_model->get_report_summary($tableParams, 1);
        // $data['totals'] = $this->report_model->get_report_summary($tableParams);
        // pre($data);
        $this->load->library('pdf');
        $html = $this->load->view('admin/report/report_summary', $data, true);
        $filename = 'ATR-' . date('dmyhis');
        $this->pdf->createPDF($html, $filename, true, 'A3');
        //  die;
    }

    public function get_region()
    {
        if ($this->input->post()) {
            $area = $GLOBALS['current_user']->area;
            $areaids = $this->input->post('area_id');
            $role = $this->input->post('role');
            if ($role == 5) {
                $data['region'] = $this->report_model->get_region($area = '', $areaids);
            } else {
                $data['region'] = $this->report_model->get_region($area, $areaids);
            }

            if (count($data['region']) > 0) {
                echo json_encode([
                    'success' => true,
                    'message' => "Successfully fetched the region list.",
                    'data' => $data['region']
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => "No region found.",
                ]);
            }
        }
        die;
    }

    public function get_subregion()
    {
        if ($this->input->post()) {
            $area = $GLOBALS['current_user']->area;
            $regionds = $this->input->post('region_id');
            $role = $this->input->post('role');
            if ($role == 5) {
                $data['subregion'] = $this->report_model->get_subregion($area = '', $regionds);
            } else {
                $data['subregion'] = $this->report_model->get_subregion($area, $regionds);
            }

            if (count($data['subregion']) > 0) {
                echo json_encode([
                    'success' => true,
                    'message' => "Successfully fetched the sub-region list.",
                    'data' => $data['subregion']
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => "No sub-region found.",
                ]);
            }
        }
        die;
    }
    public function get_wards()
    {
        if ($this->input->post()) {
            $area = $GLOBALS['current_user']->area;
            $subregionds = $this->input->post('region_id');
            $role = $this->input->post('role');
            if ($role == 5) {
                $data['wards'] = $this->report_model->get_wards($subregionds);
            } else {
                $data['wards'] = $this->report_model->get_wards($subregionds);
            }

            if (count($data['wards']) > 0) {
                echo json_encode([
                    'success' => true,
                    'message' => "Successfully fetched the wards list.",
                    'data' => $data['wards']
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => "No wards found.",
                ]);
            }
        }
        die;
    }

    public function send_email()
    {
        $role = $GLOBALS['current_user']->role;
        $area = $GLOBALS['current_user']->area;
        if ($this->input->is_ajax_request()) {

            $post_data = $this->input->get();

            //$filename = $post_data['filename'];
            //$html =  $this->get_filter($post_data);
            $html =  $this->get_filter_table($post_data);

            $url = base_url('admin/report/download_export?') . $_SERVER['QUERY_STRING'];

            $send = send_mail_template('report_download_taker', $GLOBALS['current_user']->email, $GLOBALS['current_user']->staffid, $url, $html);

            // if ($role == 5) {
            //     $send = send_mail_template('report_download', $GLOBALS['current_user']->email, $GLOBALS['current_user']->staffid, $url, $html['bug'], $html['area'], $html['region'], $html['subregion'], $html['category'], $html['ticket_status'], $html['duration'], $html['at'], $html['ar']);
            // } else {
            //     $send = send_mail_template('report_download_area', $GLOBALS['current_user']->email, $GLOBALS['current_user']->staffid, $url, $html['bug'], '', $html['region'], $html['subregion'], $html['category'], $html['ticket_status'], $html['duration'], $html['at'], $html['ar']);
            // }

            // $export = $this->export($filename, $post_data);
            echo "1";
        }
    }

    public function show_filter()
    {

        $role = $GLOBALS['current_user']->role;
        $area = $GLOBALS['current_user']->area;
        if ($this->input->is_ajax_request()) {

            $post_data = $this->input->get();
            $tableParams = [
                'areaid' => (!empty($post_data['areaid'])) ? $post_data['areaid'] : '',
                'region' => (!empty($post_data['region'])) ? $post_data['region'] : '',
                'subregion' => (!empty($post_data['subregion'])) ? $post_data['subregion'] : '',
                'category' => (!empty($post_data['category'])) ? $post_data['category'] : '',
                'bug' => (!empty($post_data['bug'])) ? $post_data['bug'] : '',
                'action_taker' => (!empty($post_data['action_taker'])) ? $post_data['action_taker'] : '',
                'action_reviewer' => (!empty($post_data['action_reviewer'])) ? $post_data['action_reviewer'] : '',
                'report_date' => (!empty($post_data['report_months'])) ? $post_data['report_months'] : '',
                'to_date' => (!empty($post_data['report_to'])) ? $post_data['report_to'] : '',
                'from_date' => (!empty($post_data['report_from'])) ? $post_data['report_from'] : '',
                'statusIds' => (!empty($post_data['ticket'])) ? $post_data['ticket'] : "",
                'duration' => (!empty($post_data['duration'])) ? $post_data['duration'] : '',
            ];

            $filename = (!empty($post_data['filename'])) ? $post_data['filename'] : "";
            $this->report_model->save_report($tableParams, $filename);

            send_mail_template('report_download_confirmation', $GLOBALS['current_user']->email, $GLOBALS['current_user']->staffid);

            echo "1";
        }
    }

    public function get_filter($post_data)
    {
        $role = $GLOBALS['current_user']->role;
        $tableParams = [
            'areaid' => (!empty($post_data['areaid'])) ? $post_data['areaid'] : '',
            'region' => (!empty($post_data['region'])) ? $post_data['region'] : '',
            'subregion' => (!empty($post_data['subregion'])) ? $post_data['subregion'] : '',
            'category' => (!empty($post_data['category'])) ? $post_data['category'] : '',
            'bug' => (!empty($post_data['bug'])) ? $post_data['bug'] : '',
            'action_taker' => (!empty($post_data['action_taker'])) ? $post_data['action_taker'] : '',
            'action_reviewer' => (!empty($post_data['action_reviewer'])) ? $post_data['action_reviewer'] : '',
            'report_date' => (!empty($post_data['report_months'])) ? $post_data['report_months'] : '',
            'to_date' => (!empty($post_data['report_to'])) ? $post_data['report_to'] : '',
            'from_date' => (!empty($post_data['report_from'])) ? $post_data['report_from'] : '',
            'statusIds' => (!empty($post_data['ticket'])) ? $post_data['ticket'] : "",
            'duration' => (!empty($post_data['duration'])) ? $post_data['duration'] : '',
        ];

        $html = array();
        if ($role == 5) {
            if ($tableParams['areaid']) {
                $area_name = $this->report_model->get_area_name($tableParams['areaid']);
                $html['area'] = $area_name;
            } else {
                $html['area'] = "All Areas";
            }
        }

        //if( $role == 5 || $role == 7 || $role == 6){ 
        if (!empty($post_data['region'])) {
            $region_name = $this->report_model->get_region_name($post_data['region']);
            $html['region'] = $region_name;
        } else {
            $html['region'] = "All Regions";
        }
        //}

        //if( $role == 5 || $role == 7 || $role == 6 || $role == 4){ 
        if (!empty($post_data['subregion'])) {
            $subregion_name = $this->report_model->get_subregion_name($post_data['subregion']);
            $html['subregion'] = $subregion_name;
        } else {
            $html['subregion'] = "All Sub-regions";
        }
        //}


        if ($tableParams['category']) {
            $category = $this->report_model->get_category_name($tableParams['category']);
            $html['category'] =  $category;
        } else {
            $html['category'] = "All Action Items";
        }


        //if( $role == 5 || $role == 7 || $role == 4 || $role == 6){ 
        if ($tableParams['action_taker']) {
            $action_taker = $this->report_model->get_staff_name($tableParams['action_taker']);
            $html['at'] = $action_taker;
        } else {
            $html['at'] = "All Action Takers";
        }
        //}

        //if( $role == 5 || $role == 7 || $role == 6){ 
        if ($tableParams['action_reviewer']) {
            $action_reviewer = $this->report_model->get_staff_name($tableParams['action_reviewer']);
            $html['ar'] = $action_reviewer;
        } else {
            $html['ar'] = "All Action Reviewers";
        }
        //}


        if ($tableParams['statusIds']) {
            $status = $this->report_model->get_status_name($tableParams['statusIds']);
            $html['ticket_status'] = $status;
        } else {
            $html['ticket_status'] = "All Ticket Status except CLOSED ";
        }

        if ($tableParams['bug']) {
            $html['bug'] = $tableParams['bug'];
        } else {
            $html['bug'] = " All Project Ids";
        }

        if ($tableParams['report_date'] && $tableParams['report_date'] != 'custom') {


            if ($tableParams['report_date'] == 'this_month') {
                $time =  date('Y-m-01') . " - " . date('Y-m-t');
            } else if ($tableParams['report_date'] == 'last_month') {
                $time =  date('Y-m-01', strtotime("-1 MONTH")) . " - " . date('Y-m-t', strtotime("-1 MONTH"));
            } else if ($tableParams['report_date'] == 'this_year') {
                $time =  date('Y-01-01') . " - " . date('Y-12-31');
            } else if ($tableParams['report_date'] == 'last_year') {
                $time =  date('Y-01-01', strtotime("-1 YEAR")) . " - " . date('Y-12-31', strtotime("-1 YEAR"));
            } else if ($tableParams['report_date'] == '3') {
                $time =  date('Y-m-01', strtotime("-2 MONTH")) . " - " . date('Y-m-t');
            } else if ($tableParams['report_date'] == '6') {
                $time =  date('Y-m-01', strtotime("-5 MONTH")) . " - " . date('Y-m-t');
            } else if ($tableParams['report_date'] == '12') {
                $time =  date('Y-m-01', strtotime("-11 MONTH")) . " - " . date('Y-m-t');
            }

            $html['duration'] = $time;
        } else if (!empty($tableParams['report_date']) && $tableParams['report_date'] == 'custom' && !empty($tableParams['to_date']) && !empty($tableParams['from_date'])) {
            $html['duration'] =  $tableParams['from_date'] . " - " . $tableParams['to_date'];
            $html['duration'] = $time;
        } else {
            $html['duration'] = "All Time";
        }

        return $html;
    }

    public function get_filter_table($post_data)
    {
        $role = $GLOBALS['current_user']->role;
        $category = '';
        $duration = '';
        if(!empty($post_data['dashboard']) && $post_data['dashboard'] == 1){
            if(!empty($post_data['category'][0])){
                $category = explode(",",$post_data['category'][0]);
            }
            if(!empty($post_data['duration'][0])){
                $duration = explode(",",$post_data['duration'][0]);
            }

        }else{
            $category = (!empty($post_data['category'])) ? $post_data['category'] : '';
            $duration = (!empty($post_data['duration'])) ? $post_data['duration'] : '';
        }
        $tableParams = [
            'areaid' => (!empty($post_data['areaid'])) ? $post_data['areaid'] : '',
            'region' => (!empty($post_data['region'])) ? $post_data['region'] : '',
            'subregion' => (!empty($post_data['subregion'])) ? $post_data['subregion'] : '',
            'category' => $category,
            'bug' => (!empty($post_data['bug'])) ? $post_data['bug'] : '',
            'action_taker' => (!empty($post_data['action_taker'])) ? $post_data['action_taker'] : '',
            'action_reviewer' => (!empty($post_data['action_reviewer'])) ? $post_data['action_reviewer'] : '',
            'report_date' => (!empty($post_data['report_months'])) ? $post_data['report_months'] : '',
            'to_date' => (!empty($post_data['report-to'])) ? $post_data['report-to'] : '',
            'from_date' => (!empty($post_data['report-from'])) ? $post_data['report-from'] : '',
            'statusIds' => (!empty($post_data['ticket'])) ? $post_data['ticket'] : "",
            'duration' => $duration,
        ];

        $html = array();
        if ($role == 5) {
            if ($tableParams['areaid']) {
                $area_name = $this->report_model->get_area_name($tableParams['areaid']);
                $html['area'] = $area_name;
            } else {
                $html['area'] = "";
            }
        }

        if (!empty($post_data['duration'])) {
            $duration_name = $this->report_model->get_duration_name($post_data['duration']);
            $html['duration_name'] = $duration_name;
        } else {
            $html['duration_name'] = "";
        }


        if (!empty($post_data['region'])) {
            $region_name = $this->report_model->get_region_name($post_data['region']);
            $html['region'] = $region_name;
        } else {
            $html['region'] = "";
        }


        if (!empty($post_data['subregion'])) {
            $subregion_name = $this->report_model->get_subregion_name($post_data['subregion']);
            $html['subregion'] = $subregion_name;
        } else {
            $html['subregion'] = "";
        }

        if ($tableParams['category']) {
            $category = $this->report_model->get_category_name($tableParams['category']);
            $html['category'] =  $category;
        } else {
            $html['category'] = "";
        }


        if ($tableParams['action_taker']) {
            $action_taker = $this->report_model->get_staff_name($tableParams['action_taker']);
            $html['at'] = $action_taker;
        } else {
            $html['at'] = "";
        }

        if ($tableParams['action_reviewer']) {
            $action_reviewer = $this->report_model->get_staff_name($tableParams['action_reviewer']);
            $html['ar'] = $action_reviewer;
        } else {
            $html['ar'] = "";
        }


        if ($tableParams['statusIds']) {
            $status = $this->report_model->get_status_name($tableParams['statusIds']);
            $html['ticket_status'] = $status;
        } else {
            $html['ticket_status'] = "";
        }

        if ($tableParams['bug']) {
            $html['bug'] = $tableParams['bug'];
        } else {
            $html['bug'] = "";
        }

        if ($tableParams['report_date'] && $tableParams['report_date'] != 'custom') {

            $time ='';
            if ($tableParams['report_date'] == 'this_month') {
                $time =  "This Month (" . date('01-m-Y') . " - " . date('t-m-Y').")";
            } else if ($tableParams['report_date'] == 'last_month') {
                $time =  "Last Month (" .date('01-m-Y', strtotime("-1 MONTH")) . " - " . date('t-m-Y', strtotime("-1 MONTH")).")";
            } else if ($tableParams['report_date'] == 'this_year') {
                $time =  "This Year (" .date('01-01-Y') . " - " . date('31-12-Y').")";
            } else if ($tableParams['report_date'] == 'last_year') {
                $time =  "Last Year (" .date('01-01-Y', strtotime("-1 YEAR")) . " - " . date('31-12-Y', strtotime("-1 YEAR")).")";
            } else if ($tableParams['report_date'] == '3') {
                $time =  "Last 3 Months (" .date('01-m-Y', strtotime("-2 MONTH")) . " - " . date('t-m-Y').")";
            } else if ($tableParams['report_date'] == '6') {
                $time =  "Last 6 Months (" .date('01-m-Y', strtotime("-5 MONTH")) . " - " . date('t-m-Y').")";
            } else if ($tableParams['report_date'] == '12') {
                $time =  "Last 12 Months (" .date('01-m-Y', strtotime("-11 MONTH")) . " - " . date('t-m-Y').")";
            }

            $html['duration'] = $time;
        } else if (!empty($tableParams['report_date']) && $tableParams['report_date'] == 'custom' && !empty($tableParams['to_date']) && !empty($tableParams['from_date'])) {
            //$html['duration'] =  $tableParams['from_date'] . " - " . $tableParams['to_date'];
            $html['duration'] =  "Custom Period (" .$tableParams['from_date'] . " - " . $tableParams['to_date'].")";
            //$html['duration'] = $time;
        } else {
            $html['duration'] = "";
        }

        if (empty($html['area']) && empty($html['region']) && empty($html['subregion']) && empty($html['at']) && empty($html['ar'])  && empty($html['category']) && empty($html['ticket_status']) && empty($html['duration_name']) && empty($html['bug']) && empty($html['duration'])) {
            $table = 'This report contains projects across <b>all values</b> as filtered.';
        } else {
            $table = 'This report contains projects across the following values as filtered:<br/><br/>';
            $table .= '<table border="1" ><thead>
            <tr><th>Field</th><th>Values</th></tr> </thead><tbody>';
            if ($role == 5 && !empty($html['area'])) {
                $table .= '<tr><td>State</td><td>' . $html['area'] . '</td></tr>';
            }
            if (!empty($html['region'])) {
                $table .= '<tr><td>Cities/ Corporation</td><td>' . $html['region'] . '</td></tr>';
            }
            if (!empty($html['subregion'])) {
                $table .= '<tr><td>Municipal Zone</td><td>' . $html['subregion'] . '</td></tr>';
            }
            if (!empty($html['at'])) {
                $table .= '<tr><td>Project Leader</td><td>' . $html['at'] . '</td></tr>';
            }
            if (!empty($html['ar'])) {
                $table .= '<tr><td>Reviewer</td><td>' . $html['ar'] . '</td></tr>';
            }
            if (!empty($html['duration_name'])) {
                $table .= '<tr><td>Duration</td><td>' . $html['duration_name'] . '</td></tr>';
            }
            if (!empty($html['category'])) {
                $table .= '<tr><td>Action Items</td><td>' . $html['category'] . '</td></tr>';
            }
            if (!empty($html['ticket_status'])) {
                $table .= '<tr><td>Project Status</td><td>' . $html['ticket_status'] . '</td></tr>';
            }
            if (!empty($html['bug'])) {
                $table .= '<tr><td>Project ID</td><td>' . $html['bug'] . '</td></tr>';
            }
            if (!empty($html['duration'])) {
                $table .= '<tr><td>Date Range</td><td>' . $html['duration'] . '</td></tr>';
            }
            $table .= ' </tbody></table>';
        }

        return $table;
    }

    public function download_export()
    {

        $role = $_SESSION['staff_role'];
        $area = $GLOBALS['current_user']->area;
        $post_data = $this->input->get();

        $category = '';
        $duration = '';
        if(!empty($post_data['dashboard']) && $post_data['dashboard'] == 1){
            if(!empty($post_data['category'][0])){
                $category = explode(",",$post_data['category'][0]);
            }
            if(!empty($post_data['duration'][0])){
                $duration = explode(",",$post_data['duration'][0]);
            }

        }else{
            $category = (!empty($post_data['category'])) ? $post_data['category'] : '';
            $duration = (!empty($post_data['duration'])) ? $post_data['duration'] : '';
        }

        $tableParams = [
            'areaid' => (!empty($post_data['areaid'])) ? $post_data['areaid'] : '',
            'region' => (!empty($post_data['region'])) ? $post_data['region'] : '',
            'subregion' => (!empty($post_data['subregion'])) ? $post_data['subregion'] : '',
            'category' => $category,
            'bug' => (!empty($post_data['bug'])) ? $post_data['bug'] : '',
            'action_taker' => (!empty($post_data['action_taker'])) ? $post_data['action_taker'] : '',
            'action_reviewer' => (!empty($post_data['action_reviewer'])) ? $post_data['action_reviewer'] : '',
            'report_date' => (!empty($post_data['report_months'])) ? $post_data['report_months'] : '',
            'to_date' => (!empty($post_data['report-to'])) ? $post_data['report-to'] : '',
            'from_date' => (!empty($post_data['report-from'])) ? $post_data['report-from'] : '',
            'statusIds' => (!empty($post_data['ticket'])) ? $post_data['ticket'] : "",
            'duration' => $duration ,
        ];

        $data = $this->report_model->get_report($tableParams);

        if (count($data) > 0) {

            $filename = 'ATR-' . date('dmyhis') . ".csv";
            $content = array();
            $title = array("Project ID", "Action Items", "Status", "Assigned To", "Contact", "Due Date", "Municipal Zone", "Landmark", "City/ Corporation", "State", "Raised On", "Raised Comment", "Raised Evidence", "Raised Location", "Latest Comment", "Latest Evidence", "Latest Location", "Role", "Email ID", "Project Leader","Reviewer","Milestone", "Closed On", "Raised By", "Name", "Contact ", "Email ID");

            //header("Content-Type: application/xls");
            //header("Content-Type: application/vnd.ms-excel");

            header('Content-Type: text/csv; charset=utf-8');
            header("Content-Disposition: attachment; filename=$filename");
            header("Pragma: no-cache");
            header("Expires: 0");

            foreach ($data as $rs) {

                $ticketDetails = $this->report_model->get_project_details($rs['id']);
                // $assignedUser = !empty($ticketDetails->assigned_user_id) ? $ticketDetails->assigned_user_id : '';
                $assignedUser = !empty(getProjectAssignedUser($rs['id'])) ?  getProjectAssignedUser($rs['id']) : '';
                $assignedUserDetails = $this->staff_model->get_userDetails($assignedUser);
                $tasks = $this->projects_model->get_task_details($rs['id']);
                $milestone = $this->report_model->get_current_milestone($rs['id']);
                $milestone = $milestone[0];
                $evidence = $this->report_model->get_evidence($rs['id']);
                $location = $this->report_model->get_location($rs['id']);
                //$latest = $this->report_model->get_current_milestone( $rs['id'],4);
                //$latest = $latest[0];

                if(in_array($rs['status'], [2, 4, 6])){
                    $resolvedMilestone = $this->report_model->get_current_milestone($rs['id'],4);
                    $milestone['task_id'] = !empty($resolvedMilestone[0]['task_id']) ? $resolvedMilestone[0]['task_id'] : (!empty($milestone['task_id'])?$milestone['task_id']:'');
                }

                $taskId = (!empty($milestone['task_id'])) ? $milestone['task_id'] : '';
                $latestImages = array();
                $latest_image = '';
                $latest_location = '';
                $resolved_evidence = '';
                $resolved_location = '';
                if(!empty($taskId)){
                    $latestImages = $this->dashboard_model->get_evidence_image($rs['id'], $taskId);
                    if(!empty($latestImages[0]['file_name']) && in_array($rs['status'], [ 2, 4, 6, 3]) ){
                        $resolved_evidence = base_url('uploads/tasks/' . $taskId . '/') . $latestImages[0]['file_name'];

                        // $resolved_evidence =  '<a href="'. $latest_image .'" target="_blank" class="report-location" ><i class="fa fa-eye" aria-hidden="true"></i> View </a>';
                    
                        $resolved_location = 'NA';
                        if(!empty($latestImages[0]['latitude']) && $latestImages[0]['latitude'] != 0 && !empty($latestImages[0]['longitude']) &&  $latestImages[0]['longitude'] != 0){
                            $resolved_location = 'https://maps.google.com/maps?q=' . $latestImages[0]['latitude'] . ',' . $latestImages[0]['longitude'] . '"';

                            // $resolved_location = '<a href="'. $latest_location .'" target="_blank" class="report-location"><i class="fa fa-map-marker" aria-hidden="true"></i> View </a>';
                        }
                    }
                }



                $projectNotes = project_latest_notes($rs['id']);
                $projectNote_content = !empty($projectNotes->content) ? $projectNotes->content : '';
                // if(!empty($projectNote_content)){
                // if(!empty($projectNote_content) && (in_array( $rs['status'], [ 2, 4, 6, 3,5]) || !empty($rs['sub_ticket_id']) )){
                if(!empty($projectNote_content) && $rs['frozen'] == 0 && (in_array( $rs['status'], [3,5]) || !empty($rs['sub_ticket_id']) || is_project_reopened($rs['id']))){
                    $resolved_evidence = !empty($resolved_evidence)?$resolved_evidence:'NA';
                    $resolved_location = !empty($resolved_location)?$resolved_location:'NA';
                }

                $raised_name = "";
                $raised_email = "";
                $raised_phone = "";
                $user_type = "";
                if (!empty($rs['user_type']) && $rs['user_type'] == 'Call-Center') {
                    $user_type = "Call Center Executive";
                    $raised_name = (!empty($rs['rname'])) ?  $rs['rname'] : '';
                    $raised_email = (!empty($rs['remail'])) ?  $rs['remail'] : '';
                    $raised_phone = (!empty($rs['rphonenumber'])) ?  $rs['rphonenumber'] : '';
                } else if (!empty($rs['user_type']) && $rs['user_type'] == 'Surveyor') {
                    $user_type = "Surveyor";
                    $raised_name = (!empty($rs['firstname'])) ?  $rs['firstname'] : '';
                    $raised_email = (!empty($rs['email'])) ?  $rs['email'] : '';
                    $raised_phone = (!empty($rs['phonenumber'])) ?  $rs['phonenumber'] : '';
                }

                 // project leader
                 $leader_name = '';
                 $leadername = $this->report_model->get_report_leader($rs['id']);
                 if(!empty($leadername)){
                     $leader_name = $leadername->firstname;
                 }

                 // reviewer
                 $reviewer_name = '';
                 if(!empty($leadername)){
                    $reviewername = $this->staff_model->get_reporting_person($leadername->staff_id);
                    if(!empty($reviewername)){
                        $reviewer_name = $reviewername->firstname;
                    }
                }


                $status = (!empty($ticketDetails->status_name)) ?  $ticketDetails->status_name : '';

                $status_tag = '';
                $statusname = '';

                if ($rs['status'] == 9 && $rs['is_assigned'] == 0 && $rs['frozen'] == 0) {
                    $status_tag = 'Unassigned';
                } else  if (in_array($rs['status'], [2, 4, 6])  && $rs['action_date'] >= date('Y-m-d') && $rs['frozen'] == 0) {
                    $status_tag = 'In Progress';
                } else if ((in_array($rs['status'], [2, 4, 6]) && $rs['action_date'] < date('Y-m-d') && $rs['frozen'] == 0) || ($rs['status'] == 1 && $rs['action_date'] <= date('Y-m-d') && $rs['frozen'] == 0)) {
                    $status_tag = 'Delayed';
                } else if ($rs['status'] == 5 && $rs['frozen'] == 0) {
                    $status_tag = 'Rejected';
                } else if ($rs['status'] == 1 && $rs['frozen'] == 0) {
                    $status_tag = 'New';
                } else if ($rs['status'] == 3 && $rs['frozen'] == 0) {
                    $status_tag = 'Closed';
                } else if ($rs['frozen'] == 1) {
                    $status_tag = 'Frozen';
                }


                if (!empty($status_tag)) {

                    if ($status_tag == "Delayed" && $ticketDetails->project_status == 1) {
                        $statusname = 'Unaccepted';
                    } else if ($status_tag == "Delayed" &&  ($ticketDetails->project_status == 2 ||  $ticketDetails->project_status == 6 || $ticketDetails->project_status == 4)) {
                        $statusname = 'Overdue';
                    } else if ($status_tag == 'In Progress' && $ticketDetails->project_status == '2') {
                        $statusname = '';
                    } else if ($status_tag == 'In Progress'  && $rs['reassigned'] == 1) {
                        $statusname = 'In Progress';
                    } else if ($status_tag == 'In Progress' && $ticketDetails->project_status == '4') {
                        $statusname = '';
                    } else if ($status_tag == 'In Progress' && $ticketDetails->project_status == '5') {
                        $statusname = '';
                    } else if ($status_tag == 'In Progress' && $ticketDetails->project_status == '6') {
                        $statusname = 'Reopened';
                    }
                }

                if (!empty($statusname)) {
                    $status_name = $status_tag . "-" . $statusname;
                } else {
                    $status_name = $status_tag;
                }

                //print_r($location); exit;

                $row = array();

                if (!empty($rs['id'])) $row[] = stripslashes($rs['id']);
                else  $row[] = "";
                if (!empty($rs['name'])) $row[] = stripslashes($rs['name']);
                else $row[] = "";
                if (!empty($status_name)) $row[] = $status_name;
                else $row[] = "";
                if (!empty($assignedUserDetails->firstname)) $row[] = $assignedUserDetails->firstname . " " . $assignedUserDetails->organisation;
                else $row[] = "";
                if (!empty($assignedUserDetails->phonenumber)) $row[] = $assignedUserDetails->phonenumber;
                else $row[] = "";
                if (!empty($rs['deadline'])) $row[] = stripslashes($rs['deadline']);
                else  $row[] = "";
                if (!empty($ticketDetails->sub_region_name)) $row[] = $ticketDetails->sub_region_name;
                else $row[] = "";
                if (!empty($rs['landmark'])) $row[] = stripslashes($rs['landmark']);
                else $row[] = "";
                if (!empty($ticketDetails->region_name)) $row[] = $ticketDetails->region_name;
                else $row[] = "";
                if (!empty($ticketDetails->area_name)) $row[] = $ticketDetails->area_name;
                else $row[] = "";
                if (!empty($rs['project_created'])) $row[] = stripslashes($rs['project_created']);
                else  $row[] = "";
                if (!empty($rs['description'])) $row[] = stripslashes($rs['description']);
                else $row[] = "";
                if (!empty($evidence[0])) $row[] = stripslashes($evidence[0]);
                else $row[] = "NA";
                if (!empty($location[0])) $row[] = stripslashes($location[0]);
                else $row[] = "NA";
                if (!empty($projectNote_content)) $row[] = stripslashes($projectNote_content);
                else $row[] = "";

                if (!empty($resolved_evidence)) $row[] = stripslashes($resolved_evidence);
                else $row[] = "";
                if (!empty($resolved_location)) $row[] = stripslashes($resolved_location);
                else $row[] = "";

                // if (!empty($latest_image)) $row[] = stripslashes($latest_image);
                // else $row[] = "NA";
                // if (!empty($latest_location)) $row[] = stripslashes($latest_location);
                // else $row[] = "NA";

                if (!empty($assignedUserDetails->role_name)) $row[] = $assignedUserDetails->role_name;
                else $row[] = "";
                if (!empty($assignedUserDetails->email)) $row[] = $assignedUserDetails->email;
                else $row[] = "";

                if (!empty($leader_name)) $row[] = $leader_name;
                else $row[] = "";
                if (!empty($reviewer_name)) $row[] = $reviewer_name;
                else $row[] = "";
                // if (!empty($milestone['tag'])) $row[] = $milestone['tag'];
                // else $row[] = "";
                // if (!empty($milestone['task_name'])) $row[] = $milestone['task_name'];
                // else $row[] = "NA";

                if(!empty($milestone['task_name']) && in_array($rs['status'],[2,4,6]) && $rs['frozen'] == 0){
                    // $milestone_name = $milestone['task_name'];
                    $row[] = $milestone['task_name'];
                }else if($rs['status'] == 3){
                    $row[] = 'NA';
                }else{
                    $row[] = '';
                }

                if (!empty($rs['date_finished'] && $rs['date_finished'] != '0000-00-00 00:00:00')) $row[] = stripslashes(date("d-m-Y", strtotime($rs['date_finished'])));
                else  $row[] = "";
                if (!empty($user_type)) $row[] = stripslashes($user_type);
                else $row[] = "";
                if (!empty($raised_name)) $row[] = stripslashes($raised_name);
                else $row[] = "";
                if (!empty($raised_phone)) $row[] = stripslashes($raised_phone);
                else $row[] = "";
                if (!empty($raised_email)) $row[] = stripslashes($raised_email);
                else $row[] = "";


                $content[] = $row;
            }


            $output = fopen('php://output', 'w');
            fputcsv($output, $title);
            foreach ($content as $con) {
                fputcsv($output, $con);
            }


            exit;
        } else {
            echo 'No data found';
            exit;
        }
    }


    public function export($filename, $post_data)
    {

        $path = FCPATH . 'uploads/report/';
        $role = $_SESSION['staff_role'];
        $area = $GLOBALS['current_user']->area;

        $tableParams = [
            'role' => (!empty($post_data['role'])) ? $post_data['role'] : '',
            'area' => (!empty($post_data['area'])) ? $post_data['area'] : '',
            'areaid' => (!empty($post_data['areaid'])) ? $post_data['areaid'] : '',
            'region' => (!empty($post_data['region'])) ? $post_data['region'] : '',
            'subregion' => (!empty($post_data['subregion'])) ? $post_data['subregion'] : '',
            'category' => (!empty($post_data['category'])) ? $post_data['category'] : '',
            'bug' => (!empty($post_data['bug'])) ? $post_data['bug'] : '',
            'action_taker' => (!empty($post_data['action_taker'])) ? $post_data['action_taker'] : '',
            'action_reviewer' => (!empty($post_data['action_reviewer'])) ? $post_data['action_reviewer'] : '',
            'report_date' => (!empty($post_data['report_months'])) ? $post_data['report_months'] : '',
            'to_date' => (!empty($post_data['report_to'])) ? $post_data['report_to'] : date('Y-m-d'),
            'from_date' => (!empty($post_data['report_from'])) ? $post_data['report_from'] : '',
            'statusIds' => (!empty($post_data['ticket'])) ? $post_data['ticket'] : "",
        ];


        $data = $this->report_model->get_report($tableParams);

        if (count($data) > 0) {


            $content = array();
            $title = array("Project ID", "Action Items", "Status", "Assigned To", "Contact", "Due Date", "Municipal Zone", "Landmark", "City", "State", "Raised On", "Comment(s)", "Evidence", "Role", "Email ID", "Type", "Milestone", "Completed On", "Raised By", "Name", "Contact ", "Email ID");

            foreach ($data as $rs) {

                $ticketDetails = $this->report_model->get_project_details($rs['id']);
                $assignedUser = !empty($ticketDetails->assigned_user_id) ? $ticketDetails->assigned_user_id : '';
                $assignedUserDetails = $this->staff_model->get_userDetails($assignedUser);
                $tasks = $this->projects_model->get_task_details($rs['id']);
                $milestone = $this->report_model->get_current_milestone($rs['id']);
                $milestone = $milestone[0];
                $evidence = $this->report_model->get_evidence($rs['id']);
                //print_r($evidence); exit;

                $row = array();

                if (!empty($rs['id'])) $row[] = stripslashes($rs['id']);
                else  $row[] = "";
                if (!empty($rs['issue_name'])) $row[] = stripslashes($rs['issue_name']);
                else $row[] = "";
                if (!empty($ticketDetails->status_name)) $row[] = $ticketDetails->status_name;
                else $row[] = "";
                if (!empty($assignedUserDetails->firstname)) $row[] = $assignedUserDetails->firstname . " " . $assignedUserDetails->organisation;
                else $row[] = "";
                if (!empty($assignedUserDetails->phonenumber)) $row[] = $assignedUserDetails->phonenumber;
                else $row[] = "";
                if (!empty($rs['deadline'])) $row[] = stripslashes($rs['deadline']);
                else  $row[] = "";
                if (!empty($ticketDetails->sub_region_name)) $row[] = $ticketDetails->sub_region_name;
                else $row[] = "";
                if (!empty($rs['landmark'])) $row[] = stripslashes($rs['landmark']);
                else $row[] = "";
                if (!empty($ticketDetails->region_name)) $row[] = $ticketDetails->region_name;
                else $row[] = "";
                if (!empty($ticketDetails->area_name)) $row[] = $ticketDetails->area_name;
                else $row[] = "";
                if (!empty($rs['project_created'])) $row[] = stripslashes($rs['project_created']);
                else  $row[] = "";
                if (!empty($rs['description'])) $row[] = stripslashes($rs['description']);
                else $row[] = "";
                if (!empty($evidence)) $row[] = stripslashes($evidence);
                else $row[] = "";
                if (!empty($assignedUserDetails->role_name)) $row[] = $assignedUserDetails->role_name;
                else $row[] = "";
                if (!empty($assignedUserDetails->email)) $row[] = $assignedUserDetails->email;
                else $row[] = "";
                if (!empty($milestone['tag'])) $row[] = $milestone['tag'];
                else $row[] = "";
                if (!empty($milestone['task_name'])) $row[] = $milestone['task_name'];
                else $row[] = "";
                if (!empty($rs['date_finished'])) $row[] = stripslashes($rs['date_finished']);
                else  $row[] = "";
                if (!empty($rs['user_type'])) $row[] = stripslashes($rs['user_type']);
                else $row[] = "";
                if (!empty($rs['firstname'])) $row[] = stripslashes($rs['firstname']);
                else $row[] = "";
                if (!empty($rs['phonenumber'])) $row[] = stripslashes($rs['phonenumber']);
                else $row[] = "";
                if (!empty($rs['email'])) $row[] = stripslashes($rs['email']);
                else $row[] = "";

                $content[] = $row;
            }

            $output = fopen($path . $filename, 'w');

            fputcsv($output, $title);
            foreach ($content as $con) {
                fputcsv($output, $con);
            }
            fclose($output);
            //header("Content-Type: application/xls");
            //header("Content-Type: application/vnd.ms-excel");
            header('Content-Type: text/csv; charset=utf-8');
            header("Content-Disposition: attachment; filename=$filename");
            header("Pragma: no-cache");
            header("Expires: 0");


            readfile($path . $filename);

            $html =  $this->get_filter($post_data);

            $url = base_url() . 'uploads/report/' . $filename;

            if ($role == 5) {
                $send = send_mail_template('report_download', $GLOBALS['current_user']->email, $GLOBALS['current_user']->staffid, $url, $html['bug'], $html['area'], $html['region'], $html['subregion'], $html['category'], $html['ticket_status'], $html['duration'], $html['at'], $html['ar']);
            } else {
                $send = send_mail_template('report_download_area', $GLOBALS['current_user']->email, $GLOBALS['current_user']->staffid, $url, $html['bug'], '', $html['region'], $html['subregion'], $html['category'], $html['ticket_status'], $html['duration'], $html['at'], $html['ar']);
            }
            // if($role == 6 || $role == 7){
            //     $send=send_mail_template('report_download_area',$GLOBALS['current_user']->email,$GLOBALS['current_user']->staffid,$url,$html['bug'],'',$html['region'],$html['subregion'],$html['category'],$html['ticket_status'],$html['duration'],$html['at'],$html['ar']);
            // }
            // if($role == 4){
            //     $send=send_mail_template('report_download_reviewer',$GLOBALS['current_user']->email,$GLOBALS['current_user']->staffid,$url,$html['bug'],'','',$html['subregion'],$html['category'],$html['ticket_status'],$html['duration'],$html['at'],'');
            // }
            // if($role == 3 || $role == 8){
            //     $send=send_mail_template('Report_download_taker',$GLOBALS['current_user']->email,$GLOBALS['current_user']->staffid,$url,$html['bug'],'','','',$html['category'],$html['ticket_status'],$html['duration']);
            // }

            return true;
        } else {
            echo '';
            return false;
        }


        return true;
    }


    /* List all region */
    public function region()
    {
        $role = $_SESSION['staff_role'];

        if ($this->input->is_ajax_request()) {

            $aColumns = [
                db_prefix() . 'area.name',
                db_prefix() . 'region.region_name',
                db_prefix() . 'sub_region.region_name',

            ];
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'sub_region';
            $where        = ['AND ' . db_prefix() . 'sub_region.status = 1  AND ' . db_prefix() . 'region.status = 1'];
            $join         = ['LEFT JOIN ' . db_prefix() . 'area ON ' . db_prefix() . 'area.areaid = ' . db_prefix() . 'sub_region.area_id LEFT JOIN ' . db_prefix() . 'region ON ' . db_prefix() . 'region.id = ' . db_prefix() . 'sub_region.region_id'];

            $result  = data_tables_init($aColumns, $sIndexColumn, $sTable,  $join, $where, [db_prefix() . 'sub_region.id', db_prefix() . 'sub_region.area_id', db_prefix() . 'sub_region.region_id']);
            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row = [];
                for ($i = 0; $i < count($aColumns); $i++) {
                    $_data = $aRow[$aColumns[$i]];
                    $row[] = $_data;
                }

                $output['aaData'][] = $row;
            }
            echo json_encode($output);
            die();
        }
        $data['title']                = 'States, Cities/Corporation and Municipal Zones list';
        $this->load->view('admin/report/region', $data);
    }

    public function demo_report()
    {
        $this->load->view('admin/report/demo');
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Staff extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Organization_model');
        $this->load->model('Department_model');
        $this->load->model('area_model');
    }
    /* List all staff members */
    public function index()
    {

        //print_r($_POST['search']['value']);exit;
        if (!has_permission('staff', '', 'view')) {
            access_denied('staff');
        }

		
        $this->load->model('issue_model');
        //$message=$this->load->view('sms_templates/onboard');
        $this->sendSms('919650544900',913,'created');
        $tableView = ["ae-global" => "staff", "ae-area" => "staff_ae", "aa" => "staff", "ar" => "staff_ar", "at" => "staff_at", "ata" => "staff_ata", "qc" => "staff_qc","sv"=>"staff_sv"];
        $siteTitle = ["ae-global" => "National Observers", "ae-area" => "State Observer", "aa" => "State Admins", "ar" => "Reviewer", "at" => "Project Leader", "ata" => "Project Support", "qc" => "Surveyor Quality Check", "sv" => "Surveyor"];

        // fetching the slug for the staff
        if($this->input->get('role')){
            $role = $this->input->get('role');
        }else{
            $role = $this->input->post('role');
        }

        if (!empty($role)) {
            $slug_url = $role;
            $role_data = $this->roles_model->get_role_from_slug($slug_url);
            $view_file = 'manage_' . $slug_url;
            $permission = 'staff_' . $slug_url;
            $tableViewData = (isset($tableView[$slug_url])) ? $tableView[$slug_url] : 'staff';
        } else {
            $view_file = 'manage';
            $permission = 'staff';
            $role_data = $this->roles_model->get_role_from_slug('aa');
            $tableViewData = 'staff';
            $slug_url = 'aa';
        }

        if (!has_permission($permission, '', 'view') && !has_permission($permission, '', 'view_own')) {
            access_denied('staff');
        }
        $org_id = $this->input->post('organization');
        if(!empty($this->input->post('organization'))){
            $tableParams = [
                'role' => $role_data->roleid,
                'active' => 1,
                'role_slug' => $role_data->slug_url,
                'name' => $org_id,
                
            ];
           //print_r($tableParams);
            $this->app->get_table_data($tableViewData, $tableParams);
        }else{
            if ($this->input->is_ajax_request()) {
                $tableParams = [
                    'role' => $role_data->roleid,
                    'active' => 1,
                    'role_slug' => $role_data->slug_url,
                    'name' => $org_id,
                    
                ];
               //print_r($tableParams);
                $this->app->get_table_data($tableViewData, $tableParams);
            }
        }
       
		
        $all_issues = $this->issue_model->get_area_issues($GLOBALS['current_user']->area, 1);
        //pre($all_issues);
        $data['staff_members'] = $this->staff_model->get('', ['role' => $role_data->roleid, 'active' => 1]);
        $data['title']         = $siteTitle[$slug_url];
        $data['role']          = $role_data;
        $data['permission']    = $permission;
        $data['categories']    = $all_issues;
        $this->load->view('admin/staff/' . $view_file, $data);
    }

    public function reviewer_filter()
    {
        $role = $_SESSION['staff_role'];

        if ($this->input->is_ajax_request()) {
            $org_id = $this->input->post('org_id');
            $aColumns = [
                'staff.firstname',
                'staff.email',
                'staff.phonenumber',
                'organization.name',
                'staff.active',
            ];
            $sIndexColumn = 'staffid';
            $sTable       = db_prefix() . 'staff';
              //if(!empty($org_id)){
                //  $where        = ['AND role = ' . $role . ' And area =  '.$GLOBALS['current_user']->area.' And org_id = "'.$org_id.'"'];
            //   }else{
                 $where        = ['AND role = 4 And area =  '.$GLOBALS['current_user']->area];
            // }
            
            // $join         = [' LEFT JOIN ' . db_prefix() . 'roles ON ' . db_prefix() . 'roles.roleid = staff.role'];
            $join         = [' LEFT JOIN ' . db_prefix() . 'roles ON ' . db_prefix() . 'roles.roleid = staff.role LEFT JOIN ' . db_prefix() . 'organization ON ' . db_prefix() . 'organization.id = staff.org_id'];
            
            $result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['staffid', 'area','org_id', 'roles.slug_url','phonenumber']);
            //$result  = data_tables_init_category($aColumns, $sIndexColumn, $sTable, $join = [], $where = [], $additionalSelect = [], $sGroupBy = '', $searchAs = [], $addOrderBy = '');
            $output  = $result['output'];
            $rResult = $result['rResult'];

            // foreach ($rResult as $aRow) {

            //     $row = [];
            //     for ($i = 0; $i < count($aColumns); $i++) {
            
            //         $_data = $aRow[$aColumns[$i]];
                   
            
            //         if ($aColumns[$i] == 'staff.active') {
            //             $checked = '';
            //             if ($aRow['staff.active'] == 1) {
            //                 $checked = 'checked';
            //             }
            //             $slug_url = trim("'" . $aRow['slug_url'] . "'");
            //             $_data = '<div class="onoffswitch">
            //                         <input type="checkbox" onclick="changeStatus(this,' . $aRow['staffid'] . ',' . $slug_url . ')" class="onoffswitch-checkbox" id="c_' . $aRow['staffid'] . '" data-id="' . $aRow['staffid'] . '" data-status="' . $aRow['staff.active'] . '" ' . $checked . '>
            //                         <label class="onoffswitch-label" for="c_' . $aRow['staffid'] . '"></label>
            //                     </div>';
            
            //             $_data .= '<span class="hide">' . ($checked == 'checked' ? _l('active') : _l('inactive')) . '</span>';
            //         }
            
            //         $row[] = $_data;
            //     }
            //     $options = icon_btn('staff/edit_profile/' . $aRow['staffid'], 'pencil-square-o', 'btn-default', [
            
            //         'onclick' => 'edit_admin(this,' . $aRow['staffid'] . ','.$aRow['org_id'].'); return false', 'data-name' => $aRow['staff.firstname'], 'data-email' => $aRow['staff.email'], 'data-status' => $aRow['staff.active'], 'data-department' => $aRow['area'], 'data-staffid' => $aRow['staffid'], 'data-phone' => $aRow['staff.phonenumber'], 'organization.name' => $aRow['organization.name']
            //     ]);
            //     $row[] = $options;
            
            //     $output['aaData'][] = $row;
            // }
            echo json_encode($output);
            die();
        }
        
    }
    public function get_action_takers()
    {
        $response = [
            "success" => false,
            "message" => "Something went wrong."
        ];
        if ($this->input->post()) {
            $reviewer_id = $this->input->post('id');
            $action_takers = $this->staff_model->get_action_reviewer_takers($reviewer_id);
            if (count($action_takers) > 0) {
                $response = [
                    "success" => true,
                    "message" => "AT data fetched successfully.",
					'updated_csrf_token' => $this->security->get_csrf_hash(),
                    "action_takers" => $action_takers
                ];
            }
        }
        echo json_encode($response);
        die;
    }

    //get ward list 
    public function get_ward_list()
    {
        // $response = [
        //     "success" => false,
        //     "message" => "Something went wrong."
        // ];
        if ($this->input->post()) {
            $subregion_id = $this->input->post('sub_region');
            $wardList = $this->staff_model->get_ward_list($subregion_id);
            if (count($wardList) > 0) {
                $response = [
                    "success" => true,
                    "message" => "Ward data fetched successfully.",
					'updated_csrf_token' => $this->security->get_csrf_hash(),
                    "ward_list" => $wardList
                ];
            } else{
                $response = [
                    "success" => true,
                    "message" => "Ward data fetched successfully.",
					'updated_csrf_token' => $this->security->get_csrf_hash(),
                    "ward_list" => $wardList
                ];
            }
        }
        echo json_encode($response);
        die;
    }

    //get ward list by org and dept
    public function get_ward_list_by_org_dept()
    {
        if ($this->input->post()) {
            $organisation_id = $this->input->post('organisation_id');
            $department_id = $this->input->post('department_id');
            $region = $this->input->post('region');
            $edit_id = $this->input->post('id');
            //pre($department_id);

            if(!isset($edit_id)){
                $wlist = $this->staff_model->get_ward_list_by_org_dept_nonexist($region,$department_id,$organisation_id);
            } else {
                $wlist = [];

                $wardList = $this->staff_model->get_ward_list_by_org_dept_nonexist($region,$department_id,$organisation_id);
                $wardList_existing = $this->staff_model->get_ward_list_by_org_dept_existing_id($region,$department_id,$organisation_id,$edit_id);

                $wlist = array_merge($wardList,$wardList_existing);
                
                // foreach($wardList as $list){
                //     foreach($wardList_existing as $existing){
                //         if($list['id'] !== $existing['id']){
                //             array_push($wlist,$list);
                //         }
                //     }
                // }
            }

            //pre($wlist);
            

            if (count($wlist) > 0) {
                $response = [
                    "success" => true,
                    "message" => "Ward data fetched successfully.",
					'updated_csrf_token' => $this->security->get_csrf_hash(),
                    "ward_list" => $wlist
                ];
            } else{
                $response = [
                    "success" => true,
                    "message" => "Ward data fetched successfully.",
					'updated_csrf_token' => $this->security->get_csrf_hash(),
                    "ward_list" => $wlist
                ];
            }
        }
        echo json_encode($response);
        die;
    }

    // get subregion by organization and department
    function getsubregion2(){

        $regionid = $this->input->post('regionid');
        $organisation_id = $this->input->post('organisation_id');
        $department_id = $this->input->post('department_id');

        $CI = & get_instance();
        $CI->db->select('region_name');
        $CI->db->select('id');  
        $CI->db->where('region_id', $regionid);
        if($organisation_id){
            $CI->db->where('organisation_id', $organisation_id);
        }
        if($department_id){
            $CI->db->where('department_id', $department_id);
        }
        $CI->db->where('is_deleted',0);
        $CI->db->where('status', 1);
        $CI->db->order_by('region_name', 'asc');

        $rows = $CI->db->get(db_prefix().'sub_region')->result_array();

        if(count($rows)>0){
            echo json_encode([
                'success' => true,
                'message' => "Successfully fetched the sub region list.",
                'sub_region_list' => $rows
            ]);
        }
        else{
            echo json_encode([
                'success' => false,
                'message' => "No Municipal Zone found.",
            ]);
        }
    }

    /* Get memeber table data */
    public function get_member_table_data()
    {
        $data = $this->staff_model->get_member_table_data();
        if ($this->input->is_ajax_request()) {
            return ['success' => true, 'table_Data' => $data];
        }
    }

    /* Add new staff member or edit existing */
    public function member($id = '')
    {
        if (!has_permission('staff', '', 'view')) {
            access_denied('staff');
        }
        hooks()->do_action('staff_member_edit_view_profile', $id);

        $this->load->model('departments_model');
        $this->load->model('area_model');
        if ($this->input->post()) {
            $data = $this->input->post();
            // Don't do XSS clean here.
            $data['email_signature'] = $this->input->post('email_signature', false);
            $data['email_signature'] = html_entity_decode($data['email_signature']);

            if ($data['email_signature'] == strip_tags($data['email_signature'])) {
                // not contains HTML, add break lines
                $data['email_signature'] = nl2br_save_html($data['email_signature']);
            }

            $data['password'] = $this->input->post('password', false);

            if ($id == '') {
                if (!has_permission('staff', '', 'create')) {
                    access_denied('staff');
                }
                $id = $this->staff_model->add($data);
                if ($id) {
                    handle_staff_profile_image_upload($id);
                    set_alert('success', _l('added_successfully', _l('staff_member')));
                    redirect(admin_url('staff/member/' . $id));
                }
            } else {
                if (!has_permission('staff', '', 'edit')) {
                    access_denied('staff');
                }
                handle_staff_profile_image_upload($id);
                $response = $this->staff_model->update($data, $id);
                if (is_array($response)) {
                    if (isset($response['cant_remove_main_admin'])) {
                        set_alert('warning', _l('staff_cant_remove_main_admin'));
                    } elseif (isset($response['cant_remove_yourself_from_admin'])) {
                        set_alert('warning', _l('staff_cant_remove_yourself_from_admin'));
                    }
                } elseif ($response == true) {
                    set_alert('success', _l('updated_successfully', _l('staff_member')));
                }
                redirect(admin_url('staff/member/' . $id));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('staff_member_lowercase'));
        } else {
            $member = $this->staff_model->get($id);
            if (!$member) {
                blank_page('Staff Member Not Found', 'danger');
            }
            $data['member']            = $member;
            $title                     = $member->firstname . ' ' . $member->lastname;
            $data['staff_departments'] = $this->departments_model->get_staff_departments($member->staffid);

            $ts_filter_data = [];
            if ($this->input->get('filter')) {
                if ($this->input->get('range') != 'period') {
                    $ts_filter_data[$this->input->get('range')] = true;
                } else {
                    $ts_filter_data['period-from'] = $this->input->get('period-from');
                    $ts_filter_data['period-to']   = $this->input->get('period-to');
                }
            } else {
                $ts_filter_data['this_month'] = true;
            }

            $data['logged_time'] = $this->staff_model->get_logged_time_data($id, $ts_filter_data);
            $data['timesheets']  = $data['logged_time']['timesheets'];
        }
       // $this->load->model('currencies_model');
       // $data['base_currency'] = $this->currencies_model->get_base_currency();
        $data['base_currency'] = [];
        $data['roles']         = $this->roles_model->get();
        $data['user_notes']    = $this->misc_model->get_notes($id, 'staff');
        $data['departments']   = $this->departments_model->get();
        $data['area']          = $this->area_model->get(false, true);
        $data['title']         = $title;

        $this->load->view('admin/staff/member', $data);
    }

    /* Add new staff member or edit existing */
    public function create($id = '')
    {
        if (!has_permission('staff', '', 'view')) {
            access_denied('staff');
        }
        //  hooks()->do_action('staff_member_edit_view_profile', $id);

        //  $this->load->model('departments_model');
        if ($this->input->post()) {
            $data = $this->input->post();
            $orgNewKey = 'org_id';
            $orgName = 'organisation';
            // Replace the key
            if (array_key_exists('organization_new', $data)) {
                $data[$orgNewKey] = $data['organization_new'];
                $org_get = $this->staff_model->get_organisation($data['organization_new']);

                if($org_get){
                    $org_name = $org_get->name;
                } else {
                    $org_name = ' ';
                }
                $data[$orgName] = $org_name;
                
                unset($data['organization_new']);
            }
         
            // Don't do XSS clean here.
            $data['email_signature'] = $this->input->post('email_signature', false);
            $data['email_signature'] = html_entity_decode($data['email_signature']);

            if ($data['email_signature'] == strip_tags($data['email_signature'])) {
                // not contains HTML, add break lines
                $data['email_signature'] = nl2br_save_html($data['email_signature']);
            }

            $data['password'] = random_password();

            if (isset($data['id'])) {
                $id = $data['id'];
            }

            if ($id == '') {
                if (!has_permission('staff', '', 'create')) {
                    access_denied('staff');
                }
                $data["send_welcome_email"] = "on";
                $id = $this->staff_model->add($data);
                if (is_array($id)) {
                    if (!$id['success']) {
                        echo json_encode($id);
                        die;
                    }
                }
                if ($id) {
                    handle_staff_profile_image_upload($id);
                    set_alert('success', _l('added_successfully', _l('staff_member')));
                    $success = true;
                    $message = _l('added_successfully', _l('National Observer'));
                // sms api
                // $this->sendSms($data['phonenumber'],$id,'created');
                // sms api
                    echo json_encode([
                        'success'              => $success,
                        'message'              => $message,
                    ]);
                    die;
                    //redirect(admin_url('staff/member/' . $id));
                }
            } else {
                if (!has_permission('staff', '', 'edit')) {
                    access_denied('staff');
                }
                handle_staff_profile_image_upload($id);
            
                $response = $this->staff_model->update($data, $id);

                if (is_array($response)) {
                    if (!$response['success']) {
                        echo json_encode($response);
                        die;
                    }
                }

                if (is_array($response)) {
                    if (isset($response['cant_remove_main_admin'])) {
                        set_alert('warning', _l('staff_cant_remove_main_admin'));
                    } elseif (isset($response['cant_remove_yourself_from_admin'])) {
                        set_alert('warning', _l('staff_cant_remove_yourself_from_admin'));
                    }
                } elseif ($response == true) {
                    set_alert('success', _l('updated_successfully', _l('staff_member')));
                }
                //redirect(admin_url('staff/member/' . $id));
                $message = _l('updated_successfully', _l('National Observer'));
                echo json_encode([
                    'success'              => true,
                    'message'              => $message,
                ]);
                die;
            }
        }
    }


    /* Get role permission for specific role id */
    public function role_changed($id)
    {
        if (!has_permission('staff', '', 'view')) {
            ajax_access_denied('staff');
        }

        echo json_encode($this->roles_model->get($id)->permissions);
    }

    public function save_dashboard_widgets_order()
    {
        hooks()->do_action('before_save_dashboard_widgets_order');

        $post_data = $this->input->post();
        foreach ($post_data as $container => $widgets) {
            if ($widgets == 'empty') {
                $post_data[$container] = [];
            }
        }
        update_staff_meta(get_staff_user_id(), 'dashboard_widgets_order', serialize($post_data));
    }

    public function save_dashboard_widgets_visibility()
    {
        hooks()->do_action('before_save_dashboard_widgets_visibility');

        $post_data = $this->input->post();
        update_staff_meta(get_staff_user_id(), 'dashboard_widgets_visibility', serialize($post_data['widgets']));
    }

    public function reset_dashboard()
    {
        update_staff_meta(get_staff_user_id(), 'dashboard_widgets_visibility', null);
        update_staff_meta(get_staff_user_id(), 'dashboard_widgets_order', null);

        redirect(admin_url());
    }

    public function save_hidden_table_columns()
    {
        hooks()->do_action('before_save_hidden_table_columns');
        $data   = $this->input->post();
        $id     = $data['id'];
        $hidden = isset($data['hidden']) ? $data['hidden'] : [];
        update_staff_meta(get_staff_user_id(), 'hidden-columns-' . $id, json_encode($hidden));
    }

    public function change_language($lang = '')
    {
        hooks()->do_action('before_staff_change_language', $lang);

        $this->db->where('staffid', get_staff_user_id());
        $this->db->update(db_prefix() . 'staff', ['default_language' => $lang]);
        if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            redirect(admin_url());
        }
    }

    public function timesheets()
    {
        $data['view_all'] = false;
        if (is_admin() && $this->input->get('view') == 'all') {
            $data['staff_members_with_timesheets'] = $this->db->query('SELECT DISTINCT staff_id FROM ' . db_prefix() . 'taskstimers WHERE staff_id !=' . get_staff_user_id())->result_array();
            $data['view_all']                      = true;
        }

        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('staff_timesheets', ['view_all' => $data['view_all']]);
        }

        if ($data['view_all'] == false) {
            unset($data['view_all']);
        }

        $data['logged_time'] = $this->staff_model->get_logged_time_data(get_staff_user_id());
        $data['title']       = '';
        $this->load->view('admin/staff/timesheets', $data);
    }

    public function delete()
    {
        if (!is_admin() && is_admin($this->input->post('id'))) {
            die('Busted, you can\'t delete administrators');
        }

        if (has_permission('staff', '', 'delete')) {
            $success = $this->staff_model->delete($this->input->post('id'), $this->input->post('transfer_data_to'));
            if ($success) {
                set_alert('success', _l('deleted', _l('staff_member')));
            }
        }

        redirect(admin_url('staff'));
    }

    /* When staff edit his profile */
    public function edit_profile()
    {
        if ($this->input->post()) {
            handle_staff_profile_image_upload();
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('firstname', 'Firstname', 'required|max_length[155]');
            
            if($GLOBALS['current_user']->role != 9){
                $this->form_validation->set_rules('organisation', 'Organisation', 'required|max_length[155]');
            }
            
            $this->form_validation->set_rules('phonenumber', 'Phonenumber', 'is_natural|max_length[10]');
			
            
			
			$response['success'] = false;
			if ($this->form_validation->run() !== false) {
				$data = $this->input->post();
				
				$response = $this->staff_model->update_profile($data, get_staff_user_id());
				
				if ($response['success']) {
					set_alert('success', _l('staff_profile_updated'));
				} else {
					
					set_alert('danger', $response['message']);
				}
			}
			
            //redirect(admin_url('staff/edit_profile/' . get_staff_user_id()));
        }
		
        $member = $this->staff_model->get(get_staff_user_id());
		
        $data['member']            = $member;
        $data['title']             = $member->firstname . ' ' . $member->lastname;
		
		$data['random_salt'] = random_salt_token();
		
        $this->load->view('admin/staff/profile', $data);
    }

    /* Remove staff profile image / ajax */
    public function remove_staff_profile_image($id = '')
    {
        $staff_id = get_staff_user_id();
        if (is_numeric($id) && (has_permission('staff', '', 'create') || has_permission('staff', '', 'edit'))) {
            $staff_id = $id;
        }
        hooks()->do_action('before_remove_staff_profile_image');
        $member = $this->staff_model->get($staff_id);
        if (file_exists(get_upload_path_by_type('staff') . $staff_id)) {
            delete_dir(get_upload_path_by_type('staff') . $staff_id);
        }
        $this->db->where('staffid', $staff_id);
        $this->db->update(db_prefix() . 'staff', [
            'profile_image' => null,
        ]);

        if (!is_numeric($id)) {
            redirect(admin_url('staff/edit_profile/' . $staff_id));
        } else {
            redirect(admin_url('staff/member/' . $staff_id));
        }
    }

    /* When staff change his password */
    public function change_password_profile()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
			
			$data['oldpassword'] = cryptoJsAesDecrypt($_SESSION['random_salt'], $data['oldpassword']);
			$data['newpassword'] = cryptoJsAesDecrypt($_SESSION['random_salt'], $data['newpassword']);
			
			
            if (trim($data['oldpassword']) == trim($data['newpassword'])) {
                set_alert('danger', _l("Old password & new password can't be same."));
				
                redirect(admin_url('staff/edit_profile'));
            }
			
            $response = $this->staff_model->change_password($this->input->post(null, false), get_staff_user_id());
			
            if (is_array($response) && isset($response[0]['passwordnotmatch'])) {
                set_alert('danger', _l('staff_old_password_incorrect'));
            } else {
                if ($response == true) {
                    $member = $this->staff_model->get(get_staff_user_id());
					
                    $sent = send_mail_template('staff_password_resetted', $member->email, $member->staffid, $data['newpassword']);
					
                    set_alert('success', _l('staff_password_changed'));
                } else {
                    set_alert('warning', _l('staff_problem_changing_password'));
                }
            }
			
            redirect(admin_url('staff/edit_profile'));
        }
    }

    /* View public profile. If id passed view profile by staff id else current user*/
    public function profile($id = '')
    {
        if ($id == '') {
            $id = get_staff_user_id();
        }

        hooks()->do_action('staff_profile_access', $id);

        $data['logged_time'] = $this->staff_model->get_logged_time_data($id);
        $data['staff_p']     = $this->staff_model->get($id);

        if (!$data['staff_p']) {
            blank_page('Staff Member Not Found', 'danger');
        }

        $this->load->model('departments_model');
        $data['staff_departments'] = $this->departments_model->get_staff_departments($data['staff_p']->staffid);
        $data['departments']       = $this->departments_model->get();
        $data['title']             = _l('staff_profile_string') . ' - ' . $data['staff_p']->firstname . ' ' . $data['staff_p']->lastname;
        // notifications
        $total_notifications = total_rows(db_prefix() . 'notifications', [
            'touserid' => get_staff_user_id(),
        ]);
        $data['total_pages'] = ceil($total_notifications / $this->misc_model->get_notifications_limit());
        $this->load->view('admin/staff/myprofile', $data);
    }

    public function update_staff_status($id, $status)
    {
        $update_status = $this->staff_model->change_staff_status($id, $status);
        if ($update_status) {
            $response = [
                'success' => true,
                'message' => "Status has been updated successfully."
            ];
        } else {
            $response = [
                'success' => false,
                'message' => "Something went wrong!"
            ];
        }
        return $response;
    }
    public function update_contact_status()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
        $update_status = $this->staff_model->change_contact_status($data['id'], $data['status']);
        // print_r($update_status);exit;
        if ($update_status) {
            $response = [
                'success' => true,
                'message' => "Status has been updated successfully."
            ];
        } else {
            $response = [
                'success' => false,
                'message' => "Something went wrong!"
            ];
        }
        echo json_encode($response);
        
    }
    }

    
    /* Change status to staff active or inactive / ajax */
    public function change_staff_status()
    {
        if (has_permission('staff', '', 'edit')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                if (!$data['status']) {
                    $slug_arr = ['at', 'ata'];
                    $is_projects_active = false;
                    if (in_array($data['slug_url'], $slug_arr)) {
                        $is_projects_active = $this->staff_model->check_staff_projects_status($data['id']);
                        $message = 'User has active projects';
                    }
                    if (in_array($data['slug_url'], ['ar'])) {
                        $is_projects_active = $this->staff_model->get_active_ats($data['id']);
                        $message = 'User has active Project Leaders';
                        // pre($is_projects_active);
                    }
                    // $is_projects_active = false;
                    if ($is_projects_active) {
                        $response = array(
                            'success' => false,
                            'message' => $message,
                            'check_status' => 1
                        );
                    } else {
                        $response = $this->update_staff_status($data['id'], $data['status']);
                        if ($data['slug_url'] == "at")
                            $this->staff_model->remove_staff_issue($data['id']);
                        $response['check_status'] = 0;
                    }
					
					$response['updated_csrf_token'] = $this->security->get_csrf_hash();
					
                    echo json_encode($response);
                    die;
                }
				
                $member = $this->staff_model->get_member(['staffid' => $data['id']])[0];

                $if_member_email_exist = $this->staff_model->get_member(['email' => $member['email'], "active" => 1, "staffid !=" => $data['id']]);
                $if_member_phone_exist = $this->staff_model->get_member(['phonenumber' => $member['phonenumber'], "active" => 1, "staffid !=" => $data['id']]);
                 //    removing phone validation
                            $if_member_phone_exist=[];
                 // 
                if ($data['slug_url'] != 'ae-area' || $data['slug_url'] != 'ae-global') {
                    $if_member_area_exist = $this->staff_model->get_member(['area' => $member['area'], 'active' => 1]);
                    if (count($if_member_area_exist) > 0) {
                        $response = array(
                            'success' => false,
                            'message' => "User already exists in this Area.",
                            'check_status' => 0
                        );
                    }
                }


                $if_disabled_region = [];
                $if_disabled_subregion = [];
                if ($data["slug_url"] == "ae-area") {
                    $check_region = $this->staff_model->check_staff_region_status($data['id'], ["r.status" => 1]);
                    if (count($check_region) > 0) {
                        $if_disabled_region = [];
                    } else {
                        $if_disabled_region = [1];
                    }
                } else {
                    $if_disabled_region = $this->staff_model->check_staff_region_status($data['id'], ["r.status" => 0]);
                    $if_disabled_subregion = $this->staff_model->check_staff_subregion_status($data['id'], ["r.status" => 0]);
                }

                if (count($if_member_email_exist) > 0) {
                    $response = array(
                        'success' => false,
                        'message' => "Email is already in use.",
                        'check_status' => 0
                    );
                } elseif (count($if_member_phone_exist) > 0) {
                    $response = array(
                        'success' => false,
                        'message' => "Phone number is already in use.",
                        'check_status' => 0
                    );
                } elseif (count($if_disabled_region) > 0) {
                    $response = array(
                        'success' => false,
                        'message' => "The user cannot be activated if their geography is inactive.",
                        'check_status' => 0
                    );
                } elseif (count($if_disabled_subregion) > 0) {
                    $response = array(
                        'success' => false,
                        'message' => "The user cannot be activated if their geography is inactive.",
                        'check_status' => 0
                    );
                } else {
                    $response = $this->update_staff_status($data['id'], $data['status']);
                    $response['check_status'] = 1;
                }
            }
        }
		$response['updated_csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($response);
        die;
    }

    /* Logged in staff notifications*/
    public function notifications()
    {
        $this->load->model('misc_model');
        if ($this->input->post()) {
            $page   = $this->input->post('page');
            $offset = ($page * $this->misc_model->get_notifications_limit());
            $this->db->limit($this->misc_model->get_notifications_limit(), $offset);
            $this->db->where('touserid', get_staff_user_id());
            $this->db->order_by('date', 'desc');
            $notifications = $this->db->get(db_prefix() . 'notifications')->result_array();
            $i             = 0;
            foreach ($notifications as $notification) {
                if (($notification['fromcompany'] == null && $notification['fromuserid'] != 0) || ($notification['fromcompany'] == null && $notification['fromclientid'] != 0)) {
                    if ($notification['fromuserid'] != 0) {
                        $notifications[$i]['profile_image'] = '<a href="' . admin_url('staff/profile/' . $notification['fromuserid']) . '">' . staff_profile_image($notification['fromuserid'], [
                            'staff-profile-image-small',
                            'img-circle',
                            'pull-left',
                        ]) . '</a>';
                    } else {
                        $notifications[$i]['profile_image'] = '<a href="' . admin_url('clients/client/' . $notification['fromclientid']) . '">
                    <img class="client-profile-image-small img-circle pull-left" src="' . contact_profile_image_url($notification['fromclientid']) . '"></a>';
                    }
                } else {
                    $notifications[$i]['profile_image'] = '';
                    $notifications[$i]['full_name']     = '';
                }
                $additional_data = '';
                if (!empty($notification['additional_data'])) {
                    $additional_data = unserialize($notification['additional_data']);
                    $x               = 0;
                    foreach ($additional_data as $data) {
                        if (strpos($data, '<lang>') !== false) {
                            $lang = get_string_between($data, '<lang>', '</lang>');
                            $temp = _l($lang);
                            if (strpos($temp, 'project_status_') !== false) {
                                $status = get_project_status_by_id(strafter($temp, 'project_status_'));
                                $temp   = $status['name'];
                            }
                            $additional_data[$x] = $temp;
                        }
                        $x++;
                    }
                }
                $notifications[$i]['description'] = _l($notification['description'], $additional_data);
                $notifications[$i]['date']        = time_ago($notification['date']);
                $notifications[$i]['full_date']   = $notification['date'];
                $i++;
            } //$notifications as $notification
            echo json_encode($notifications);
            die;
        }
    }


    /* Save Area Admin */
    public function save()
    {
        if (!has_permission('staff', '', 'view')) {
            access_denied('staff');
        }
      
        if ($this->input->post()) {
            $data = $this->input->post();
     
           
            /* Update staff */
            if ($data['id'] != "") {
                $form_data = array(
                    "firstname" => isset($data['name']) ? general_validate($data['name']) : "",
                    "email" => isset($data['email']) ? general_validate($data['email']) : "",
                    "organisation" => isset($data['organisation']) ? general_validate($data['organisation']) : "",
                    // "password" => random_string(),
                    "area" => isset($data['departments']) ? general_validate($data['departments']) : "",
                    "phonenumber" => isset($data['phone']) ? general_validate($data['phone']) : "",
                    "designation" => isset($data['designation']) ? general_validate($data['designation']) : "",
                    // "org_id" => isset($data['organization_new']) ? $data['organization_new'] : "",
                    "desig_id" => isset($data['department_new']) ? json_encode($data['department_new']) : "",
                    // "org_kml_file" => isset($file) ? $file : "",
                    // "dept_kml_file" => isset($dept_kml_file) ? $dept_kml_file : "",
                );

                $member = $this->staff_model->get_member(['staffid' => $data['id']])[0];
                // check if email id was changed
                if ($member['email'] != $form_data['email']) {
                    $form_data["send_welcome_email"] = "on";
                }
                $if_member_area_exist = $if_member_email_exist = $if_member_phone_exist = $response = [];
                if ($member['area'] != $form_data['area']) {
                    $if_member_area_exist = $this->staff_model->get_member(['role' => $form_data['role'], 'area' => $form_data['area'], 'active' => 1]);
                }
                if ($member['email'] != $form_data['email']) {
                    $if_member_email_exist = $this->staff_model->get_member(['email' => $form_data['email'], 'active' => 1]);
                }
                if ($member['phonenumber']  != $form_data['phonenumber']) {
                    $if_member_phone_exist = $this->staff_model->get_member(['phonenumber' => $form_data['phonenumber'], 'active' => 1]);
                    //    removing phone validation
                    $if_member_phone_exist=[];
                    // 
                }

                if (count($if_member_area_exist) > 0) {
                    $response = array(
                        'success' => false,
                        'message' => _l('state_admin_already_exists_in_this_state')
                    );
                } elseif (count($if_member_email_exist) > 0) {
                    $response = array(
                        'success' => false,
                        'message' => _l("Email is already in use.")
                    );
                } elseif (count($if_member_phone_exist) > 0) {
                    $response = array(
                        'success' => false,
                        'message' => _l("phone_number_is_already_in_use")
                    );
                } else {
                    $result = $this->staff_model->update_staff($form_data, $data['id']);
                    $role_data = $this->roles_model->get($data['role']);
                    if (!empty($role_data->permissions)) {
                        $permissions = $role_data->permissions;
                    }
                    $this->staff_model->update_permissions($permissions, $data['id']);

                    if ($result || !$result) {
                        $response = array(
                            'success' => true,
                            'message' => _l("member_has_been_added_successfully")
                        );
                    }
                }
                echo json_encode($response);
                die;
            }

            /* Add new staff */
            $form_data = array(
                "firstname" => isset($data['name']) ? general_validate($data['name']) : "",
                "email" => isset($data['email']) ? general_validate($data['email']) : "",
                "organisation" => isset($data['organisation']) ? general_validate($data['organisation']) : "",
                // "password" => isset($data['password']) ? general_validate($data['password']) : "",
                "password" => random_password(),
                "role" => isset($data['role']) ? general_validate($data['role']) : "",
                "area" => isset($data['departments']) ? general_validate($data['departments']) : "",
                "phonenumber" => isset($data['phone']) ? general_validate($data['phone']) : "",
                "designation" => isset($data['designation']) ? general_validate($data['designation']) : "",
                "org_id" => isset($data['organization_new']) ? $data['organization_new'] : "",
                "desig_id" => isset($data['department_new']) ? json_encode($data['department_new']) : "",
                // "org_kml_file" => isset($file) ? $file : "",
                // "dept_kml_file" => isset($dept_kml_file) ? $dept_kml_file : "",
                "datecreated" => date('Y-m-d H:i:s'),
                "active" => '1'
            );
            $if_member_email_exist = $this->staff_model->get_member(['email' => $form_data['email'], 'active' => 1]);
            $if_member_phone_exist = $this->staff_model->get_member(['phonenumber' => $form_data['phonenumber'], 'active' => 1]);
            //    removing phone validation
            $if_member_phone_exist=[];
            // 
            $if_member_area_exist = $this->staff_model->get_member(['role' => $form_data['role'], 'area' => $form_data['area'], 'active' => 1]);


            if (count($if_member_area_exist) > 0) {
                $response = array(
                    'success' => false,
                    'message' => _l('state_admin_already_exists_in_this_state')
                );
            } elseif (count($if_member_email_exist) > 0) {
                $response = array(
                    'success' => false,
                    'message' => _l("Email is already in use.")
                );
            } elseif (count($if_member_phone_exist) > 0) {
                $response = array(
                    'success' => false,
                    'message' => _l("phone_number_is_already_in_use")
                );
            } else {
                $result = $this->staff_model->save($form_data);
                // update staff permission
                $role_data = $this->roles_model->get($data['role']);
                if (!empty($role_data->permissions)) {
                    $permissions = $role_data->permissions;
                }
                $this->staff_model->update_permissions($permissions, $result);

                if ($result) {
                    $response = array(
                        'success' => true,
                        'message' => _l("member_has_been_added_successfully")
                    );
                }
            }
        }
        echo json_encode($response);
        die;
    }

    /* Get staff details edit / ajax */
    public function get_staff_detail()
    {
        $data               = $this->input->post();

        $id                 = $data['staffid'];
        $data     = $this->staff_model->get($id);
        $response = array(
            'success' => true,
            'staff'   => $data,
            'message' => ""
        );
        echo json_encode($response);
    }

    

    /* Get at assistants details edit / ajax */
    public function get_ata()
    {
        $data               = $this->input->post();

        $id                 = $data['staffid'];
        $data     = $this->staff_model->get_ata($id);
        $wardList     = $this->staff_model->getWard($id);
        
        $this->load->model("issue_model");
        $issues = $this->issue_model->get_staff_issues($id);

        $response = [
            "success" => true,
            "staff"   => $data,
            "issues"  => $issues,
            "wardList"  => $wardList,
            "message" => ""
        ];
        echo json_encode($response);
        
    }

    public function prepare_staff_region_data($data, $staff_id)
    {
        $date = date('Y-m-d H:i:s');
        $region_data = array();
        foreach ($data as $key => $region) {
            array_push($region_data, ['staff_id' => $staff_id, 'region' => $region, 'created_date' => $date]);

            // if (is_array($region)) {
            //     foreach ($region as $index => $innerRegion) {
            //         array_push($region_data, ['staff_id' => $staff_id, 'region' => $key, 'sub_region' => $index, 'created_date' => $date]);
            //     }
            // } else {
            //     array_push($region_data, ['staff_id' => $staff_id, 'region' => $key, 'sub_region' => 0, 'created_date' => $date]);
            // }
        }
        return $region_data;
    }

    public function save_area_enforcer()
    {
        if (!has_permission('staff_ae-area', '', 'create')) {
            access_denied('staff_ae-area');
        }
		
        if ($this->input->post()) {
            $data = $this->input->post();
            // $orgNewKey = 'org_id';
            // $deptNewKey = 'desig_id';
            // // Replace the key
            // if (array_key_exists('organization_new', $data)) {
            //     $data[$orgNewKey] = $data['organization_new'];
            //     unset($data['organization_new']);
            // }
            // if (array_key_exists('department_new', $data)) {
            //     $data[$deptNewKey] = json_encode($data['department_new']);
            //     unset($data['department_new']);
            // }
      
			
            #Check if Region selected
            if (!isset($data['region'])) {
                $response = array(
                    'success' => false,
                    'message' => "Please select a City.",
					'updated_csrf_token' => $this->security->get_csrf_hash(),
                );
                echo json_encode($response);
                die;
            }
			
			$this->load->library('form_validation');
			
            $this->form_validation->set_rules('email', 'Email', 'trim|max_length[90]|required|valid_email');
			
            if ($this->form_validation->run() == FALSE) {
                echo json_encode([
                    'success'              => false,
                    'message'              => 'Invalid email.',
					'updated_csrf_token' => $this->security->get_csrf_hash(),
                ]);
                die;
            }
			
			$this->form_validation->set_rules('firstname', 'Name', 'trim|max_length[50]|required');
			// $this->form_validation->set_rules('organisation', 'Organisation', 'trim|alpha_numeric_input|max_length[125]|required');
			$this->form_validation->set_rules('phonenumber', 'Phone number', 'trim|max_length[12]|min_length[8]|required');
			
			if ($this->form_validation->run() == false) {
				echo json_encode([
                    'success'              => false,
                    'message'              => _l('special_characters_are_not_allowed'),
					'updated_csrf_token' => $this->security->get_csrf_hash(),
                ]);
                die;
			}

            if(isset($data['organization_new'])){
                $org_get = $this->staff_model->get_organisation($data['organization_new']);
                if($org_get){
                    $org_name = $org_get->name;
                } else {
                    $org_name = ' ';
                }
            } else {
                $org_name = ' ';
            }
			
			if ($data['id'] != "") {
				$staff_form_data = [
					"firstname" => isset($data['firstname']) ? general_validate($data['firstname']) : "",
					"email" => isset($data['email']) ? general_validate($data['email']) : "",
					// "organisation" => isset($data['organisation']) ? general_validate($data['organisation']) : "",
                    // "organisation" => $org_name,
					"phonenumber" => isset($data['phonenumber']) ? general_validate($data['phonenumber']) : "",
                    // "org_id" => isset($data['organization_new']) ? $data['organization_new'] : "",
                    "desig_id" => isset($data['department_new']) ? json_encode($data['department_new']) : "",
				];

				$member = $this->staff_model->get_member(['staffid' => $data['id']])[0];
				
				// check if email id was changed
				if ($member['email'] != $staff_form_data['email']) {
					$staff_form_data["send_welcome_email"] = "on";
				}

				$if_member_email_exist = $if_member_phone_exist = $response = [];
				if ($member['email'] != $staff_form_data['email']) {
					$if_member_email_exist = $this->staff_model->get_member(['email' => $staff_form_data['email'], 'active' => 1]);
				}
				if ($member['phonenumber'] != $staff_form_data['phonenumber']) {
					$if_member_phone_exist = $this->staff_model->get_member(['phonenumber' => $staff_form_data['phonenumber'], 'active' => 1]);
					//    removing phone validation
						   $if_member_phone_exist=[];
					// 
				}

				if (count($if_member_email_exist) > 0) {
					$response = array(
						'success' => false,
						'message' => "Email is already in use."
					);
				} elseif (count($if_member_phone_exist) > 0) {
					$response = array(
						'success' => false,
						'message' => "Phone number is already in use."
					);
				} else {
					$result = $this->staff_model->update_staff($staff_form_data, $data['id']);

					$role_data = $this->roles_model->get($data['role']);
					if (!empty($role_data->permissions)) {
						$permissions = $role_data->permissions;
					}
					$this->staff_model->update_permissions($permissions, $data['id']);

					if ($result || !$result) {
						$region_data = $this->prepare_staff_region_data($data['region'],  $data['id']);

						$this->staff_model->delete_staff_data($data['id'], 'staff_region');
						$this->staff_model->save_staff_region($region_data);

						$response = array(
							'success' => true,
							'message' => "Member has been updated successfully",
							'updated_csrf_token' => $this->security->get_csrf_hash(),
						);
					}
				}
				echo json_encode($response);
				die;
			}
			
			#prepare staff data
			$date = date('Y-m-d H:i:s');
			$staff_form_data = [
				"firstname" => isset($data['firstname']) ? general_validate($data['firstname']) : "",
				"email" => isset($data['email']) ? general_validate($data['email']) : "",
				// "organisation" => isset($data['organisation']) ? general_validate($data['organisation']) : "",
                "organisation" => $org_name,
				"role" => isset($data['role']) ? general_validate($data['role']) : "",
				"area" => isset($data['area']) ? general_validate($data['area']) : "",
				"phonenumber" => isset($data['phonenumber']) ? general_validate($data['phonenumber']) : "",
                "org_id" => isset($data['organization_new']) ? $data['organization_new'] : "",
                "desig_id" => isset($data['department_new']) ? json_encode($data['department_new']) : "",
				"password" => random_password(),
				"datecreated" => $date,
				"active" => '1'
			];

			#check if phone and email already exist
			$if_member_email_exist = $this->staff_model->get_member(['email' => $data['email'], 'active' => 1]);
			$if_member_phone_exist = $this->staff_model->get_member(['phonenumber' => $data['phonenumber'], 'active' => 1]);
			// removing phone validation
				$if_member_phone_exist=[];
			// 
			if (count($if_member_email_exist) > 0) {
				$response = array(
					'success' => false,
					'message' => "Email is already in use.",
					'updated_csrf_token' => $this->security->get_csrf_hash(),
				);
			} elseif (count($if_member_phone_exist) > 0) {
				$response = array(
					'success' => false,
					'message' => "Phone number is already in use.",
					'updated_csrf_token' => $this->security->get_csrf_hash(),
				);
			} else {
				#Save Staff Data
				$staff_id = $this->staff_model->save($staff_form_data);

				#Check if Staff added
				if ($staff_id) {
					# update staff permission
					$role_data = $this->roles_model->get($staff_form_data['role']);
					if (!empty($role_data->permissions)) {
						$permissions = $role_data->permissions;
					}
					$this->staff_model->update_permissions($permissions, $staff_id);

					#preparing region data
					$region_data = $this->prepare_staff_region_data($data['region'], $staff_id);

					#Save Region Data
					$result = $this->staff_model->save_staff_region($region_data);
					
					if ($result) {
						// sms api
						// $this->sendSms($data['phonenumber'],$staff_id,'created');
						// sms api
						
						$response = array(
							'success' => true,
							'message' => "Member has been added successfully",
							'updated_csrf_token' => $this->security->get_csrf_hash(),
						);
					} else {
						$response = array(
							'success' => false,
							'message' => "Something went wrong.",
							'updated_csrf_token' => $this->security->get_csrf_hash(),
						);
					}
				}
			}
			
			echo json_encode($response);
			die;
        }
    }


    public function save_action_reviewer()
    {
        if (!has_permission('staff_ar', '', 'create')) {
            access_denied('staff_ar');
        }
        if ($this->input->post()) {
            $data = $this->input->post();
            // print_r($data); exit;
			
            $this->load->library('form_validation');
			$this->form_validation->set_rules('email', 'Email', 'trim|max_length[90]|valid_email|required');
			
            if ($this->form_validation->run() == FALSE) {
                echo json_encode([
                    'success'              => false,
                    'message'              => 'Invalid email.',
					'updated_csrf_token' => $this->security->get_csrf_hash(),
                ]);
                die;
            }
            $this->form_validation->set_rules('region', 'City Corporation', 'trim|required');
			$this->form_validation->set_rules('firstname', 'Name', 'trim|max_length[50]|required');
			// $this->form_validation->set_rules('organisation', 'Organisation', 'trim|alpha_numeric_input|max_length[125]|required');
			$this->form_validation->set_rules('phonenumber', 'Phone number', 'trim|max_length[12]|min_length[8]|required');
			
			if ($this->form_validation->run() == false) {
				echo json_encode([
                    'success'              => false,
                    'message'              => _l('special_characters_are_not_allowed'),
					'updated_csrf_token' => $this->security->get_csrf_hash(),
                ]);
                die;
			}

            /*if (trim($data['firstname']) == '') {
                echo json_encode([
                    'success'              => false,
                    'message'              => 'Invalid name.',
                ]);
                die;
            } elseif (trim($data['organisation']) == '') {
                echo json_encode([
                    'success'              => false,
                    'message'              => 'Invalid organisation.',
                ]);
                die;
            }*/

            if(isset($data['organization_new'])){
                $org_get = $this->staff_model->get_organisation($data['organization_new']);
                if($org_get){
                    $org_name = $org_get->name;
                } else {
                    $org_name = ' ';
                }
            } else {
                $org_name = ' ';
            }
			
            // Update action reviewer
            if (isset($data['id']) && $data['id'] != "") {
				
                $form_data = array(
                    "firstname" => isset($data['firstname']) ? general_validate(trim(ucwords($data['firstname']))) : "",
                    "email" => isset($data['email']) ? general_validate($data['email']) : "",
                    "designation" => isset($data['designation']) ? general_validate(trim(ucwords($data['designation']))) : "",
                    "area" => isset($data['area']) ? general_validate($data['area']) : "",
                    "role" => isset($data['role']) ? general_validate($data['role']) : "",
                    "phonenumber" => isset($data['phonenumber']) ? general_validate(trim($data['phonenumber'])) : "",    
                     "org_id" => isset($data['organization_new']) ? $data['organization_new'] : "",
                     "organisation" => $org_name,
                    "desig_id" => isset($data['department_new']) ? json_encode($data['department_new']) : "",
                );
				
                $member = $this->staff_model->get_member(['staffid' => $data['id']])[0];

                // check if email id was changed
                if ($member['email'] != $form_data['email']) {
                    $form_data["send_welcome_email"] = "on";
                }
                $if_member_email_exist = $if_member_phone_exist = $response = [];

                if ($member['email'] != $form_data['email']) {
                    $if_member_email_exist = $this->staff_model->get_member(['email' => $form_data['email'], 'active' => 1]);
                }
                if ($member['phonenumber']  != $form_data['phonenumber']) {
                    $if_member_phone_exist = $this->staff_model->get_member(['phonenumber' => $form_data['phonenumber'], 'active' => 1]);
                // removing phone validation
                    $if_member_phone_exist=[];
                // 
                }

                if (count($if_member_email_exist) > 0) {
                    $response = array(
                        'success' => false,
                        'message' => "Email is already in use."
                    );
                } elseif (count($if_member_phone_exist) > 0) {
                    $response = array(
                        'success' => false,
                        'message' => "Phone number is already in use."
                    );
                } else {

                    $date = date('Y-m-d H:i:s');
                    $result = $this->staff_model->update_staff($form_data, $data['id']);

                    #Save Region and Sub-Region data
                    $region_data = [
                        'staff_id' => $data['id'],
                        'region' => $data['region'],
                        'created_date' => $date
                    ];
                    $this->staff_model->delete_staff_data($data['id'], 'staff_region');
                    //$this->save_staff_region_in_array($region_data);
                    $this->staff_model->save_staff_region($region_data, false);

                    $role_data = $this->roles_model->get($data['role']);
                    if (!empty($role_data->permissions)) {
                        $permissions = $role_data->permissions;
                    }
                    $this->staff_model->update_permissions($permissions, $data['id']);

                    if ($result || !$result) {
                        $response = array(
                            'success' => true,
                            'message' => "Member has been updated successfully"
                        );
                    }
                }
				
				$response['updated_csrf_token'] = $this->security->get_csrf_hash();
                echo json_encode($response);
                die;
            }

            // Add action reviewer

            $date = date('Y-m-d H:i:s');
            $staff_form_data = [
                "firstname" => isset($data['firstname']) ? general_validate(trim(ucwords($data['firstname']))) : "",
                "email" => isset($data['email']) ? general_validate($data['email']) : "",
                "organisation" => $org_name,
                "designation" => isset($data['designation']) ? general_validate(trim(ucwords($data['designation']))) : "",
                "role" => isset($data['role']) ? general_validate($data['role']) : "",
                "area" => isset($data['area']) ? general_validate($data['area']) : "",
                "phonenumber" => isset($data['phonenumber']) ? general_validate(trim($data['phonenumber'])) : "",
                "org_id" => isset($data['organization_new']) ? $data['organization_new'] : "",
                "desig_id" => isset($data['department_new']) ? json_encode($data['department_new']) : "",
                "password" => random_password(),
                "datecreated" => $date,
                "active" => '1'
            ];

            #check if phone and email already exist
            $if_member_email_exist = $this->staff_model->get_member(['email' => $data['email'], 'active' => 1]);
            $if_member_phone_exist = $this->staff_model->get_member(['phonenumber' => $data['phonenumber'], 'active' => 1]);
            // removing phone validation
                $if_member_phone_exist=[];
            //             
            if (count($if_member_email_exist) > 0) {
                $response = array(
                    'success' => false,
                    'message' => "Email is already in use."
                );
            } elseif (count($if_member_phone_exist) > 0) {
                $response = array(
                    'success' => false,
                    'message' => "Phone number is already in use."
                );
            } else {
                #Save Staff Data
                $staff_id = $this->staff_model->save($staff_form_data);

                #Check if Staff added
                if ($staff_id) {

                    #Save Region and Sub-Region data
                    $region_data = [
                        'staff_id' => $staff_id,
                        'region' => $data['region'],
                        'created_date' => $date
                    ];
                    $this->staff_model->save_staff_region($region_data, false);
                    //$this->save_staff_region_in_array($region_data);

                    # update staff permission
                    $role_data = $this->roles_model->get($staff_form_data['role']);
                    if (!empty($role_data->permissions)) {
                        $permissions = $role_data->permissions;
                    }
                    $this->staff_model->update_permissions($permissions, $staff_id);
					// sms api
					// $this->sendSms($data['phonenumber'],$staff_id,'created');
					// sms api
					
                    $response = array(
                        'success' => true,
                        'message' => "Member has been added successfully"
                    );
                }
            }
			
			$response['updated_csrf_token'] = $this->security->get_csrf_hash();
            echo json_encode($response);
            die;
        }
    }

    public function save_surveyor_qc()
    {
        if (!has_permission('staff_qc', '', 'create')) {
            access_denied('staff_qc');
        }
        if ($this->input->post()) {
            $data = $this->input->post();
            // print_r($data); exit;
			
            $this->load->library('form_validation');
			$this->form_validation->set_rules('email', 'Email', 'trim|max_length[90]|valid_email|required');
			
            if ($this->form_validation->run() == FALSE) {
                echo json_encode([
                    'success'              => false,
                    'message'              => 'Invalid email.',
					'updated_csrf_token' => $this->security->get_csrf_hash(),
                ]);
                die;
            }
            $this->form_validation->set_rules('region', 'City Corporation', 'trim|required');
			$this->form_validation->set_rules('firstname', 'Name', 'trim|max_length[50]|required');
			// $this->form_validation->set_rules('organisation', 'Organisation', 'trim|alpha_numeric_input|max_length[125]|required');
			$this->form_validation->set_rules('phonenumber', 'Phone number', 'trim|max_length[12]|min_length[8]|required');
			
			if ($this->form_validation->run() == false) {
				echo json_encode([
                    'success'              => false,
                    'message'              => _l('special_characters_are_not_allowed'),
					'updated_csrf_token' => $this->security->get_csrf_hash(),
                ]);
                die;
			}

            // Update action reviewer
            if (isset($data['id']) && $data['id'] != "") {
				
                $form_data = array(
                    "firstname" => isset($data['firstname']) ? general_validate(trim(ucwords($data['firstname']))) : "",
                    "email" => isset($data['email']) ? general_validate($data['email']) : "",
                    "area" => isset($data['area']) ? general_validate($data['area']) : "",
                    "role" => isset($data['role']) ? general_validate($data['role']) : "",
                    "phonenumber" => isset($data['phonenumber']) ? general_validate(trim($data['phonenumber'])) : "",    
                );
				
                $member = $this->staff_model->get_member(['staffid' => $data['id']])[0];

                // check if email id was changed
                if ($member['email'] != $form_data['email']) {
                    $form_data["send_welcome_email"] = "on";
                }
                $if_member_email_exist = $if_member_phone_exist = $response = [];

                if ($member['email'] != $form_data['email']) {
                    $if_member_email_exist = $this->staff_model->get_member(['email' => $form_data['email'], 'active' => 1]);
                }
                if ($member['phonenumber']  != $form_data['phonenumber']) {
                    $if_member_phone_exist = $this->staff_model->get_member(['phonenumber' => $form_data['phonenumber'], 'active' => 1]);
                // removing phone validation
                    $if_member_phone_exist=[];
                // 
                }

                if (count($if_member_email_exist) > 0) {
                    $response = array(
                        'success' => false,
                        'message' => "Email is already in use."
                    );
                } elseif (count($if_member_phone_exist) > 0) {
                    $response = array(
                        'success' => false,
                        'message' => "Phone number is already in use."
                    );
                } else {

                    $date = date('Y-m-d H:i:s');
                    $result = $this->staff_model->update_staff($form_data, $data['id']);

                    #Save Region and Sub-Region data
                    $region_data = [
                        'staff_id' => $data['id'],
                        'region' => $data['region'],
                        'created_date' => $date
                    ];
                    $this->staff_model->delete_staff_data($data['id'], 'staff_region');
                    //$this->save_staff_region_in_array($region_data);
                    $this->staff_model->save_staff_region($region_data, false);

                    $role_data = $this->roles_model->get($data['role']);
                    if (!empty($role_data->permissions)) {
                        $permissions = $role_data->permissions;
                    }
                    $this->staff_model->update_permissions($permissions, $data['id']);

                    if ($result || !$result) {
                        $response = array(
                            'success' => true,
                            'message' => "Member has been updated successfully"
                        );
                    }
                }
				
				$response['updated_csrf_token'] = $this->security->get_csrf_hash();
                echo json_encode($response);
                die;
            }

            // Add action reviewer

            $date = date('Y-m-d H:i:s');
            $staff_form_data = [
                "firstname" => isset($data['firstname']) ? general_validate(trim(ucwords($data['firstname']))) : "",
                "email" => isset($data['email']) ? general_validate($data['email']) : "",
                "role" => isset($data['role']) ? general_validate($data['role']) : "",
                "area" => isset($data['area']) ? general_validate($data['area']) : "",
                "phonenumber" => isset($data['phonenumber']) ? general_validate(trim($data['phonenumber'])) : "",
                "password" => random_password(),
                "datecreated" => $date,
                "active" => '1'
            ];

            #check if phone and email already exist
            $if_member_email_exist = $this->staff_model->get_member(['email' => $data['email'], 'active' => 1]);
            $if_member_phone_exist = $this->staff_model->get_member(['phonenumber' => $data['phonenumber'], 'active' => 1]);
            // removing phone validation
                $if_member_phone_exist=[];
            //             
            if (count($if_member_email_exist) > 0) {
                $response = array(
                    'success' => false,
                    'message' => "Email is already in use."
                );
            } elseif (count($if_member_phone_exist) > 0) {
                $response = array(
                    'success' => false,
                    'message' => "Phone number is already in use."
                );
            } else {
                #Save Staff Data
                $staff_id = $this->staff_model->save($staff_form_data);

                #Check if Staff added
                if ($staff_id) {

                    #Save Region and Sub-Region data
                    $region_data = [
                        'staff_id' => $staff_id,
                        'region' => $data['region'],
                        'created_date' => $date
                    ];
                    $this->staff_model->save_staff_region($region_data, false);
                    //$this->save_staff_region_in_array($region_data);

                    # update staff permission
                    $role_data = $this->roles_model->get($staff_form_data['role']);
                    if (!empty($role_data->permissions)) {
                        $permissions = $role_data->permissions;
                    }
                    $this->staff_model->update_permissions($permissions, $staff_id);
					// sms api
					// $this->sendSms($data['phonenumber'],$staff_id,'created');
					// sms api
					
                    $response = array(
                        'success' => true,
                        'message' => "Member has been added successfully"
                    );
                }
            }
			
			$response['updated_csrf_token'] = $this->security->get_csrf_hash();
            echo json_encode($response);
            die;
        }
    }
	
    public function get_staff_region()
    {
        if ($this->input->post()) {
            $this->load->model('region_model');
            $regions =  $this->region_model->get_staff_region($this->input->post('id'));
            if ($regions) {
                $response = [
                    'success' => true,
                    'message' => "Staff Regions fetched successfully.",
                    'regions' => $regions
                ];
            } else {
                $response = array(
                    'success' => false,
                    'message' => "Something went wrong."
                );
            }
        }

        echo json_encode($response);
        die;
    }

    public function get_area_reviewers()
    {
        if ($this->input->post()) {
            $reviewers = $this->staff_model->get_area_reviewers($this->input->post('area_id'));
            if ($reviewers) {
                $response = [
                    'success' => true,
                    'message' => "Reviewers fetched successfully.",
                    'reviewers' => $reviewers
                ];
            } else {
                $response = array(
                    'success' => false,
                    'message' => "Something went wrong."
                );
            }
        }
        echo json_encode($response);
        die;
    }

    public function get_city_reviewers()
    {
        if ($this->input->post()) {
            $reviewers = $this->staff_model->get_city_reviewers($this->input->post('area_id'),$this->input->post('region_id'));
            if ($reviewers) {
                $response = [
                    'success' => true,
                    'message' => "Reviewers fetched successfully.",
                    'reviewers' => $reviewers
                ];
            } else {
                $response = array(
                    'success' => false,
                    'message' => "Something went wrong."
                );
            }
        }
        echo json_encode($response);
        die;
    }

    /**
     * Function to prepare and save action taker's category
     * @param Array $data       Form data
     */

    public function save_action_taker_category($data)
    {
        $form_data = [];
        $date = date('Y-m-d H:i:s');

        foreach ($data['categories'] as $value) {
            array_push($form_data, ['staff_id' => $data['staff_id'], 'issue_id' => $value, 'created_at' => $date]);
        }
        $this->staff_model->save_action_taker_category($form_data);
        return true;
    }

    public function validate_custom_input($data, $field_names)
    {
        foreach ($field_names as $key => $field_name) {

            if (!isset($data[$key]) || empty($data[$key])) {
                $response = array(
                    'success' => false,
                    'message' => ucwords($field_name) . " is required."
                );
                echo json_encode($response);
                die;
            }
        }
    }

    /* Function to Save Action Taker */
    public function save_action_taker()
    {
        if (!has_permission('staff_ae-area', '', 'create')) {
            access_denied('staff_ae-area');
        }
		
        if ($this->input->post()) {
            $data = $this->input->post();
			
            $this->load->library('form_validation');
			$this->form_validation->set_rules('email', 'Email', 'trim|max_length[90]|valid_email|required');
			
            if ($this->form_validation->run() == FALSE) {
                echo json_encode([
                    'success'              => false,
                    'message'              => 'Invalid email.',
					'updated_csrf_token' => $this->security->get_csrf_hash(),
                ]);
                die;
            }
			
			$this->form_validation->set_rules('firstname', 'Name', 'trim|max_length[50]|required');
			// $this->form_validation->set_rules('organisation', 'Organisation', 'trim|alpha_numeric_input|max_length[125]|required');
			$this->form_validation->set_rules('phonenumber', 'Phone number', 'trim|max_length[12]|min_length[8]|required');
			
			if ($this->form_validation->run() == false) {
				echo json_encode([
                    'success'              => false,
                    'message'              => _l('special_characters_are_not_allowed'),
					'updated_csrf_token' => $this->security->get_csrf_hash(),
                ]);
                die;
			}
			
			
            $input_fields = ['region' => "City", 'sub_region' => "Municipal Zone", 'reviewer' => 'reviewer'];
			
            #validate inputs
            $this->validate_custom_input($data, $input_fields);
            $rid = $this->input->post("region");
            $srid = $this->input->post("sub_region");
            $cats = $this->input->post("categories");
			
            $this->load->model("issue_model");
			
            // if ($data['id'] == "") {
            //     $existing_cats = $this->issue_model->get_existing_area_issues($srid, $rid);
            //     if ($existing_cats) {
            //         foreach ($data["categories"] as $category) {
            //             if (in_array($category, $existing_cats)) {
            //                 $response = array(
            //                     'success' => false,
            //                     'message' => "Selected category is already assigned to another action taker.",
			// 					'updated_csrf_token' => $this->security->get_csrf_hash(),
            //                 );
            //                 echo json_encode($response);
            //                 die;
            //             }
            //         }
            //     }
            // }
            $date = date('Y-m-d H:i:s');

            if(isset($data['organization_new'])){
                $org_get = $this->staff_model->get_organisation($data['organization_new']);
                if($org_get){
                    $org_name = $org_get->name;
                } else {
                    $org_name = ' ';
                }
            } else {
                $org_name = ' ';
            }
            if ($data['id'] != "") {
                $staff_form_data = [
                    "firstname" => isset($data['firstname']) ? general_validate(trim(ucwords($data['firstname']))) : "",
                    "email" => isset($data['email']) ? general_validate($data['email']) : "",
                    // "organisation" => isset($data['organisation']) ? general_validate(trim(ucwords($data['organisation']))) : "",
                    "organisation" => $org_name,
                    "designation" => isset($data['designation']) ? general_validate(trim(ucwords($data['designation']))) : "",
                    "phonenumber" => isset($data['phonenumber']) ? general_validate(trim($data['phonenumber'])) : "",
                    // "org_id" => isset($data['organization_new']) ? $data['organization_new'] : "",
                    "desig_id" => isset($data['department_new']) ? json_encode($data['department_new']) : "",
                ];
                if($org_get){
                    $staff_form_data["org_id"] = isset($data['organization_new']) ? $data['organization_new'] : "";
                }
                // pre($staff_form_data);
                $member = $this->staff_model->get_member(['staffid' => $data['id']])[0];
                // check if email id was changed
                if ($member['email'] != $staff_form_data['email']) {
                    $staff_form_data["send_welcome_email"] = "on";
                }
                $if_member_email_exist = $if_member_phone_exist = $response = [];
                if ($member['email'] != $staff_form_data['email']) {
                    $if_member_email_exist = $this->staff_model->get_member(['email' => $staff_form_data['email'], 'active' => 1]);
                }
                if ($member['phonenumber']  != $staff_form_data['phonenumber']) {
                    $if_member_phone_exist = $this->staff_model->get_member(['phonenumber' => $staff_form_data['phonenumber'], 'active' => 1]);
                //    removing phone validation
                    $if_member_phone_exist=[];
                // 
                }

                if (count($if_member_email_exist) > 0) {
                    $response = array(
                        'success' => false,
                        'message' => "Email is already in use."
                    );
                } elseif (count($if_member_phone_exist) > 0) {
                    $response = array(
                        'success' => false,
                        'message' => "Phone number is already in use."
                    );
                } else {
                    $result = $this->staff_model->update_staff($staff_form_data, $data['id']);

                    $role_data = $this->roles_model->get($data['role']);
                    if (!empty($role_data->permissions)) {
                        $permissions = $role_data->permissions;
                    }
                    $this->staff_model->update_permissions($permissions, $data['id']);

                    if ($result || !$result) {


                        #Save Region and Sub-Region data
                        $region_data = [
                            'staff_id' => $data['id'],
                            'region' => $data['region'],
                            'sub_region' => $data['sub_region'],
                            'created_date' => $date
                        ];
                        $this->staff_model->delete_staff_data($data['id'], 'staff_region');
                        $this->staff_model->save_staff_region($region_data, false);

                        #Save Category Data
                        // $category_data = [
                        //     'categories' => $data['categories'],
                        //     'staff_id' => $data['id']
                        // ];
                        // $this->staff_model->delete_staff_data($data['id'], 'staff_issues');
                        // $this->save_action_taker_category($category_data);

                        #Save Reviewer Data
                        $reviewer_data = [
                            'staff_id' => $data['id'],
                            'assistant_id' => $data['reviewer'],
                            'created_at' => $date
                        ];
                        $this->staff_model->delete_staff_data($data['id'], 'staff_assistance');
                        $this->staff_model->save_action_taker_reviewer($reviewer_data);

                        $response = array(
                            'success' => true,
                            'message' => "Member has been updated successfully"
                        );
                        #umair's check
                        //$this->assign_to_tickets(getstaffarea($data['id']), $data['region'], $data['sub_region'], $data['categories'], $data['id']);
                    }
                }
				
				$response['updated_csrf_token'] = $this->security->get_csrf_hash();
                echo json_encode($response);
                die;
            }
			
            $form_data = [
                "firstname" => isset($data['firstname']) ? general_validate(trim(ucwords($data['firstname']))) : "",
                "email" => isset($data['email']) ? general_validate($data['email']) : "",
                "designation" => isset($data['designation']) ? general_validate(trim(ucwords($data['designation']))) : "",
                // "organisation" => isset($data['organisation']) ? general_validate(trim(ucwords($data['organisation']))) : "",
                "organisation" => $org_name,
                "role" => isset($data['role']) ? general_validate($data['role']) : "",
                "area" => isset($data['area']) ? general_validate($data['area']) : "",
                "phonenumber" => isset($data['phonenumber']) ? general_validate(trim($data['phonenumber'])) : "",
                "org_id" => isset($data['organization_new']) ? $data['organization_new'] : "",
                "desig_id" => isset($data['department_new']) ? json_encode($data['department_new']) : "",
                "password" => random_password(),
                "datecreated" => $date,
                "active" => '1'
            ];

            #check if phone and email already exist
            $if_member_email_exist = $this->staff_model->get_member(['email' => $data['email'], 'active' => 1]);
            $if_member_phone_exist = $this->staff_model->get_member(['phonenumber' => $data['phonenumber'], 'active' => 1]);
            //    removing phone validation
            $if_member_phone_exist=[];
            // 
            if (count($if_member_email_exist) > 0) {
                $response = array(
                    'success' => false,
                    'message' => "Email is already in use."
                );
            } elseif (count($if_member_phone_exist) > 0) {
                $response = array(
                    'success' => false,
                    'message' => "Phone number is already in use."
                );
            } else {
                #Save Staff Data
                $staff_id = $this->staff_model->save($form_data);

                #Check if Staff added
                if ($staff_id) {
                    # update staff permission
                    $role_data = $this->roles_model->get($form_data['role']);
                    if (!empty($role_data->permissions)) {
                        $permissions = $role_data->permissions;
                    }
                    $this->staff_model->update_permissions($permissions, $staff_id);

                    #Save Region and Sub-Region data
                    $region_data = [
                        'staff_id' => $staff_id,
                        'region' => $data['region'],
                        'sub_region' => $data['sub_region'],
                        'created_date' => $date
                    ];
                    $this->staff_model->save_staff_region($region_data, false);

                    // #Save Category Data
                    // $category_data = [
                    //     'categories' => $data['categories'],
                    //     'staff_id' => $staff_id
                    // ];
                    // $this->save_action_taker_category($category_data);

                    #Save Reviewer Data
                    $reviewer_data = [
                        'staff_id' => $staff_id,
                        'assistant_id' => $data['reviewer'],
                        'created_at' => $date
                    ];
                    $this->staff_model->save_action_taker_reviewer($reviewer_data);
                    #umair's check
                    //$this->assign_to_tickets(getstaffarea($staff_id), $data['region'], $data['sub_region'], $data['categories'], $staff_id);
                    // sms api
                    // $this->sendSms($data['phonenumber'],$staff_id,'created');
                    // sms api
                    $response = array(
                        'success' => true,
                        'message' => "Member has been added successfully"
                    );
                }
            }
			$response['updated_csrf_token'] = $this->security->get_csrf_hash();
            echo json_encode($response);
            die;
        }
    }


    public function get_action_taker()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $at = $this->staff_model->get_action_taker($data);
        }
        echo json_encode($at);
        die;
    }

    public function save_staff_region_in_array($data)
    {
        $form_data = [];
        $date = date('Y-m-d H:i:s');
        
        foreach ($data['ward'] as $key=>$value) {
            array_push($form_data, ['staff_id' => $data['staff_id'], 'region' => $data['region'], 'sub_region' => $data['sub_region'], 'ward' => $value, 'created_date' => $date]);
        }
        $this->staff_model->save_staff_region($form_data, true);
        return true;
    }


    public function save_action_assistance()
    {
        if (!has_permission('staff_ata', '', 'create')) {
            access_denied('staff_ata');
        }

		
        if ($this->input->post()) {
            $data = $this->input->post();
		
            $date = date('Y-m-d H:i:s');
            $input_fields = ['region' => 'City', 'sub_region' => 'Municipal Zone', 'ward' => 'Ward', 'categories' => 'Action Item'];
            #validate inputs
            $this->validate_custom_input($data, $input_fields);

            /*$this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            if ($this->form_validation->run() == FALSE) {

                echo json_encode([
                    'success'              => false,
                    'message'              => 'Invalid email.',
                ]);
                die;
            }
            if (trim($data['firstname']) == '') {
                echo json_encode([
                    'success'              => false,
                    'message'              => 'Invalid name.',
                ]);
                die;
            } elseif (trim($data['organisation']) == '') {
                echo json_encode([
                    'success'              => false,
                    'message'              => 'Invalid organisation.',
                ]);
                die;
            }*/
			
            $this->load->library('form_validation');
			$this->form_validation->set_rules('email', 'Email', 'trim|max_length[90]|valid_email|required');
			
            if ($this->form_validation->run() == FALSE) {
                echo json_encode([
                    'success'              => false,
                    'message'              => 'Invalid email.',
					'updated_csrf_token' => $this->security->get_csrf_hash(),
                ]);
                die;
            }
			
			$this->form_validation->set_rules('firstname', 'Name', 'trim|max_length[50]|required');
			// $this->form_validation->set_rules('organisation', 'Organisation', 'trim|alpha_numeric_input|max_length[125]|required');
			$this->form_validation->set_rules('phonenumber', 'Phone number', 'trim|max_length[12]|min_length[8]|required');
            $rid = $this->input->post("region");
            $srid = $this->input->post("sub_region");
            $wid = $this->input->post("ward");

            $this->load->model("issue_model");

            if (!isset($data['id'])) {

                $existing_cats = $this->issue_model->get_existing_area_issues_with_ward($wid, $srid, $rid);
                if ($existing_cats) {
                    foreach ($data["categories"] as $category) {
                        if (in_array($category, $existing_cats)) {
                            $response = array(
                                'success' => false,
                                'message' => "Selected category is already assigned to another Assistance.",
								'updated_csrf_token' => $this->security->get_csrf_hash(),
                            );
                            echo json_encode($response);
                            die;
                        }
                    }
                }
            }
			
			if ($this->form_validation->run() == false) {
				echo json_encode([
                    'success'              => false,
                    'message'              => _l('special_characters_are_not_allowed'),
					'updated_csrf_token' => $this->security->get_csrf_hash(),
                ]);
                die;
			}

            if(isset($data['organization_new'])){
                $org_get = $this->staff_model->get_organisation($data['organization_new']);
                if($org_get){
                    $org_name = $org_get->name;
                } else {
                    $org_name = ' ';
                }
            } else {
                $org_name = ' ';
            }
			
            // Update action reviewer
            if (isset($data['id']) && $data['id'] != "") {
                $form_data = array(
                    "firstname" => isset($data['firstname']) ? general_validate(trim(ucwords($data['firstname']))) : "",
                    "email" => isset($data['email']) ? general_validate($data['email']) : "",
                    "designation" => isset($data['designation']) ? general_validate(trim(ucwords($data['designation']))) : "",
                    "organisation" => $org_name,
                    "area" => isset($data['area']) ? general_validate($data['area']) : "",
                    "role" => isset($data['role']) ? general_validate($data['role']) : "",
                    "phonenumber" => isset($data['phonenumber']) ? general_validate(trim($data['phonenumber'])) : "",
                    // "org_id" => isset($data['organization_new']) ? $data['organization_new'] : "",
                    "desig_id" => isset($data['department_new']) ? json_encode($data['department_new']) : "",
                );
                if($org_get){
                    $form_data["org_id"] = isset($data['organization_new']) ? $data['organization_new'] : "";
                }
                // pre($form_data);
                $member = $this->staff_model->get_member(['staffid' => $data['id']])[0];
				
                // check if email id was changed
                if ($member['email'] != $form_data['email']) {
                    $form_data["send_welcome_email"] = "on";
                }
                $if_member_email_exist = $if_member_phone_exist = $response = [];

                if ($member['email'] != $form_data['email']) {
                    $if_member_email_exist = $this->staff_model->get_member(['email' => $form_data['email'], 'active' => 1]);
                }
                if ($member['phonenumber']  != $form_data['phonenumber']) {
                    $if_member_phone_exist = $this->staff_model->get_member(['phonenumber' => $form_data['phonenumber'], 'active' => 1]);
                    //    removing phone validation
                            $if_member_phone_exist=[];
                    // 
                }

                if (count($if_member_email_exist) > 0) {
                    $response = array(
                        'success' => false,
                        'message' => "Email is already in use."
                    );
                } elseif (count($if_member_phone_exist) > 0) {
                    $response = array(
                        'success' => false,
                        'message' => "Phone number is already in use."
                    );
                } else {

                    $result = $this->staff_model->update_staff($form_data, $data['id']);

                    $role_data = $this->roles_model->get($data['role']);
                    if (!empty($role_data->permissions)) {
                        $permissions = $role_data->permissions;
                    }
                    $this->staff_model->update_permissions($permissions, $data['id']);

                    if ($result || !$result) {

                        #Save Category Data
                        $category_data = [
                            'categories' => $data['categories'],
                            'staff_id' => $data['id']
                        ];
                        $this->staff_model->delete_staff_data($data['id'], 'staff_issues');
                        $this->save_action_taker_category($category_data);

                        #Save Region and Sub-Region data
                        $region_data = [
                            'staff_id' => $data['id'],
                            'region' => $data['region'],
                            'sub_region' => $data['sub_region'],
                            'ward' => $data['ward'],
                            'created_date' => $date
                        ];
                        $this->staff_model->delete_staff_data($data['id'], 'staff_region');
                        $this->save_staff_region_in_array($region_data);
                        //$this->staff_model->save_staff_region($region_data, false);

                        #Save Action Taker
                        $action_taker_data = [
                            'staff_id' => $data['id'],
                            'assistant_id' => $data['action_taker'],
                            'created_at' => $date
                        ];
                        $this->staff_model->delete_staff_data($data['id'], 'staff_assistance');
                        $this->staff_model->save_action_taker_reviewer($action_taker_data);
                        $response = array(
                            'success' => true,
                            'message' => "Member has been updated successfully"
                        );
                    }
                }
				
				$response['updated_csrf_token'] = $this->security->get_csrf_hash();
                echo json_encode($response);
                die;
            }

            // Add action reviewer


            $staff_form_data = [
                "firstname" => isset($data['firstname']) ? general_validate(trim(ucwords($data['firstname']))) : "",
                "email" => isset($data['email']) ? general_validate($data['email']) : "",
                // "organisation" => isset($data['organisation']) ? general_validate(trim(ucwords($data['organisation']))) : "",
                "organisation" => $org_name,
                "designation" => isset($data['designation']) ? general_validate(trim(ucwords($data['designation']))) : "",
                "role" => isset($data['role']) ? general_validate($data['role']) : "",
                "area" => isset($data['area']) ? general_validate($data['area']) : "",
                "phonenumber" => isset($data['phonenumber']) ? general_validate(trim($data['phonenumber'])) : "",
                "org_id" => isset($data['organization_new']) ? $data['organization_new'] : "",
                "desig_id" => isset($data['department_new']) ? json_encode($data['department_new']) : "",
                "password" => random_password(),
                "datecreated" => $date,
                "active" => '1'
            ];

            #check if phone and email already exist
            $if_member_email_exist = $this->staff_model->get_member(['email' => $data['email'], 'active' => 1]);
            $if_member_phone_exist = $this->staff_model->get_member(['phonenumber' => $data['phonenumber'], 'active' => 1]);
            //    removing phone validation
            $if_member_phone_exist=[];
            // 
            if (count($if_member_email_exist) > 0) {
                $response = array(
                    'success' => false,
                    'message' => "Email is already in use."
                );
            } elseif (count($if_member_phone_exist) > 0) {
                $response = array(
                    'success' => false,
                    'message' => "Phone number is already in use."
                );
            } else {
                #Save Staff Data
                $staff_id = $this->staff_model->save($staff_form_data);

                #Check if Staff added
                if ($staff_id) {
                    # update staff permission
                    $role_data = $this->roles_model->get($staff_form_data['role']);
                    if (!empty($role_data->permissions)) {
                        $permissions = $role_data->permissions;
                    }
                    $this->staff_model->update_permissions($permissions, $staff_id);

                    #Save action taker
                    $at_data = [
                        'staff_id' => $staff_id,
                        'assistant_id' => $data['action_taker'],
                        'created_at' => $date
                    ];
                    $this->staff_model->save_action_taker_reviewer($at_data);

                    #Save Category Data
                    $category_data = [
                        'categories' => $data['categories'],
                        'staff_id' => $staff_id
                    ];
                    $this->save_action_taker_category($category_data);

                    #Save Region and Sub-Region data
                    $region_data = [
                        'staff_id' => $staff_id,
                        'region' => $data['region'],
                        'sub_region' => $data['sub_region'],
                        'ward' => $data['ward'],
                        'created_date' => $date
                    ];
                    $this->save_staff_region_in_array($region_data);

                    if(isset($data['p_id']) && $data['p_id']){

                        $this->db->where('project_id', $data['p_id']);
                        $this->db->update(db_prefix() . 'project_members', [
                            'assigned' => 0
                        ]);

                        $this->db->insert(db_prefix() . 'project_members', [
                            'project_id' => $data['p_id'],
                            'staff_id'   => $staff_id,
                        ]);

                        $this->db->where('id', $data['p_id']);
                        $this->db->update(db_prefix() . 'projects', ['status' => 1,'is_assigned'=>1]);

                        $additional_data = array(
                            'assigned_by' => get_staff_user_id(),
                            'assigned_to' => $staff_id,
                            'taskId' => '',
                            'status' => 1,
                            'comment' => '',
                            'parent_ticket_id' => ''
                        );

                        $additionalData = json_encode($additional_data);

                        $activity=[];

                        $activity['staff_id']            = $staff_id;
                        $activity['fullname']           = isset($data['firstname']) ? general_validate(trim(ucwords($data['firstname']))) : "";
                        $activity['description_key']     = "ticket_assigned_to_ata";
                        $activity['additional_data']     = $additionalData;
                        $activity['visible_to_customer'] = 1;
                        $activity['status'] = 1;
                        $activity['project_id']          = $data['p_id'];
                        $activity['dateadded']           = date('Y-m-d H:i:s');

                        $this->db->insert(db_prefix() . 'project_activity', $activity);

                        if(isset($data['t_id']) && $data['t_id']){
                            $taskAssigned = [];
                            $taskAssigned['staffid']      = $staff_id;
                            $taskAssigned['taskid']       = $data['t_id'];
                            $taskAssigned['assigned_from']= get_staff_user_id();
                            $taskAssigned['assigned_date']= date('Y-m-d H:i:s');
                            $taskAssigned['status']       = 1;
    
                            $this->db->where('taskid', $data['t_id']);
                            $this->db->update(db_prefix() . 'task_assigned', $taskAssigned);
                        }

                    }
                    
                // sms api
                    // $this->sendSms($data['phonenumber'],$staff_id,'created');
                // sms api
                    $response = array(
                        'success' => true,
                        'message' => "Member has been added successfully"
                    );
                }
            }
			
			$response['updated_csrf_token'] = $this->security->get_csrf_hash();
            echo json_encode($response);
            die;
        }
    }

    public function get_staff_issues()
    {
        $staff_id = $this->input->post("staffId");
        $this->load->model("issue_model");
        $issues = $this->issue_model->get_staff_issues($staff_id);
        $response = [
            "success" => false,
            "message" => "No issues found!"
        ];
        if ($issues) {
            $response = [
                "success" => true,
                "message" => "Issues found successfully",
                "issues" => $issues
            ];
        }

        echo json_encode($response);
        die;
    }
    public function assign_to_tickets($area, $region, $subregion, $category, $staffid)
    {
        $this->load->model('projects_model');
        for ($i = 0; $i < count($category); $i++) {
            $this->projects_model->assign_at($area, $region, $subregion, $category[$i], $staffid);
        }
    }

    //sms api integration
    public function sendSms($phonenumber,$staff_id,$type){
        $ch  =  curl_init();
        $timeout  =  30; 
        $message="";
        if($type=='created'){
            $message=$this->load->view('sms_templates/onboard',[],true);
            $url=SMS_API_URL.'&mobiles='.$phonenumber.'&message='.$message;
            // die($url);
            curl_setopt ($ch,CURLOPT_URL, $url);
            curl_setopt ($ch,CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($ch, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');
            curl_setopt ($ch,CURLOPT_CONNECTTIMEOUT, $timeout) ;
            $response = curl_exec($ch) ;
            curl_close($ch) ;
        }

    }
    // end
    

    public function organization()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('organization');

        }
        $data['title']                = _l('organization');
    
        $this->load->view('admin/organization/manage', $data);
    }

  
    public function addedit(){
        
        $stateId =  $GLOBALS['current_user']->area;
 
         $file = NULL;
         if ($this->input->post()) {
             $if_organization_exist = [];
             $message          = '';
             $data             = $this->input->post();
             
             $input_fields = ['region_id' => 'City'];
            
             $this->validate_custom_input($data, $input_fields);

                 if(trim($data['name']) == '')
                 {
                     echo json_encode([
                         'success'              => false,
                         'message'              => _l('invalid_organization_name'),
                     ]);
                 }
                 else if (!$this->input->post('id')) {
 
                     $if_organization_exist = $this->Organization_model->get_organization(['name' => trim($data['name']),'region_id' => $data['region_id']]);
 
                     if (count($if_organization_exist) > 0) {
 
                     echo json_encode([
                         'success'              => false,
                         'message'              => _l('organization_already_in_use'),
                     ]);
                   
                     }else{
 
                        //  if(isset($_FILES["file"]['name']) && $_FILES["file"]['name'] == '')
                        //  {
                        //      echo json_encode([
                        //          'success'              => false,
                        //          'message'              => _l('kml_file_is_required'),
                        //      ]);
                        //      exit;
                        //  }
 
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
                        
                         $data['state_id'] = $stateId;
                         $id = $this->Organization_model->add($data,$file);
                         if ($id) {
                            if(!empty($_FILES["file"]['name'])){
                                $fileUpload= base_url('/uploads/organization/'.$file);
        
                                $xml=simplexml_load_file($fileUpload);
                               // echo "<pre>";print_r($xml);die();
                               if(empty($xml->Document->Folder)){
                                    $placemarks= $xml->Document->Placemark;
                                    $hasSchema = false;
                                }else{
                                    $placemarks= $xml->Document->Folder->Placemark;
                                    //$iterative_cord = 'coordinates'
                                    $hasSchema = true;
                                }
                        
                                $query = [];
                                $run='';
                            
                                for ($i = 0; $i < sizeof($placemarks); $i++) {
                                    $query ='';
                                    $qtmp=array();
                                    // $corNameArray=array();
                                    $cornames=array();
                                    if($hasSchema){
                                        if(!empty( $placemarks[$i]->ExtendedData->SchemaData)){
                                            $coordinates = $placemarks[$i]->ExtendedData->SchemaData->SimpleData;
                                            foreach($coordinates as $key=>$value){
                                                $name = ''.$value['name'];
                                                $cornames[$name] = (string)$value;
                                            }
                                        }
                                        if(!empty($placemarks[$i]->Polygon->outerBoundaryIs->LinearRing->coordinates)){
                                            $cor_d  =  explode(' ', $placemarks[$i]->Polygon->outerBoundaryIs->LinearRing->coordinates);
                                        }else{
                                            $cor_d  =  explode(' ', $placemarks[$i]->LineString->coordinates);
                                        }
                                    }else{
                                        $wardNO = $wardName = json_encode(json_decode($placemarks[$i]->name, true));
                                        $cor_d  =  explode(' ', $placemarks[$i]->LineString->coordinates);
                                        //print_r($cor_d);
                                    }
                                    
                                    
                        
                                    // for ($j = 0; $j < sizeof($coordinates); $j++) {
                                    //     $name = ''.$coordinates[$j]['name'];
                                    //     $cornames[] = (object) [$name => (string)$coordinates[$j]];
                                    // }
                        
                                    //array_push($corNameArray, $cornames);
                                    
                                    foreach($cor_d as $value){
                                        $value = trim($value);
                                        if($value!=''){
                                            $tmp = explode(',',$value);
                                            $ttmp=$tmp[1];
                                            $tmp[1]=$tmp[0];
                                            $tmp[0]=$ttmp; 
                                            if(count($tmp) == 2)
                                                $qtmp[]= '(' . $tmp[0] . ',' .$tmp[1].')';
                                            if(count($tmp) == 3)
                                                $qtmp[]= '(' . $tmp[0] . ',' .$tmp[1].','.$tmp[2].')';
                                        }
                                    }
                        
                                    $cor_d = json_encode($qtmp);
                                    //$cornames = json_encode($corNameArray);
                                    
                                    
                                    $cornamesEncode = json_encode($cornames);
                                    
                                    if(isset($cornames['WRD_NAME']) && isset($cornames['WRD_NO'])){
                                        $wardName = $cornames['WRD_NAME'];
                                        $wardNO = $cornames['WRD_NO'];
                                    } 
                                    if(isset($cornames['OBJECTID']) && isset($cornames['Ward_No'])){
                                        $wardName = $cornames['Ward_No'];
                                        $wardNO = $cornames['Ward_No'];
                                    }
                                    
                                    if(isset($wardName) && isset($wardNO)){
                                        

                                        $query .='\''.$cornamesEncode.'\', \''.$cor_d.'\'';

                                        $this->load->model('manageward_model');

                                        //$if_ward_exist = $this->manageward_model->get_ward(['ward_name' => $wardName, 'organisation_id' => $id]);
                                        $if_ward_exist = $this->manageward_model->get_ward(['ward_name' => $wardName, 'ward_no' => $wardNO, 'organisation_id' => $id]);

                                        $wardData=['organisation_id' => $id, 'region_id' => $data['region_id'], 'ward_name' => $wardName, 'ward_no' => $wardNO, 'coordinates' => $cor_d];

                                        if (empty($if_ward_exist)) {
                                            $this->manageward_model->add($wardData);
                                        }
                                    }
                                    
                                }
                            }

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
                    
                    //  if(isset($_FILES["file"]['name']) && $_FILES["file"]['name'] == '')
                    //  {
                    //      echo json_encode([
                    //          'success'              => false,
                    //          'message'              => _l('kml_file_is_required'),
                    //      ]);
                    //      exit;
                    //  }
 
                    //  if(!empty($_FILES["file"]['name'])){
                    //      $name = str_replace([' ', '.'], '_', basename($_FILES["file"]['name'], ".kml"));
                    //      $file = time().'-'.$name.".kml";  
                       
                    //      $upload = $this->file_upload($file);
                    //      if($upload != 1){
                    //          echo json_encode([
                    //                          'success'              => false,
                    //                          'message'              => $upload,
                    //                      ]);
                    //          exit; 
                    //      }
                    //  }
                     
                     if ($org['name'] != trim($data['name'])) {
                         $if_organization_exist = $this->Organization_model->get_organization(['name' => trim($data['name']),'region_id' => $data['region_id']]);
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

                         if(!empty($_FILES["file"]['name'])){

                            if(!empty($org['kml_file'])){
                                $fileName= FCPATH.'/uploads/organization/'.$org['kml_file'];
                                $file_path = FCPATH . '/uploads/organization/'.$org['kml_file'];
                                
                                if(file_exists($file_path)){
                                    unlink($file_path);
                                }
                            }

                            $fileUpload= FCPATH.'/uploads/organization/'.$file;
    
                            // $xml=simplexml_load_file($fileUpload);
                            $xml=simplexml_load_file($fileUpload);
                            
                            if(empty($xml->Document->Folder)){
                                $placemarks= $xml->Document->Placemark;
                                $hasSchema = false;
                                
                            }else{
                                $placemarks= $xml->Document->Folder->Placemark;
                                //$iterative_cord = 'coordinates'
                                $hasSchema = true;
                                
                            }
                            // print_r($a);exit;
                    
                            $query = [];
                            $run='';

                            $this->load->model('manageward_model');

                            $if_ward_exist = $this->manageward_model->get_ward_org(['organisation_id' => $id]);

                            foreach($if_ward_exist as $key){
                                $if_ps_exist_on_ward = $this->manageward_model->get_ps(['ward' => $key['id']]);
                                if (empty($if_ps_exist_on_ward)) {
                                    $this->manageward_model->delete_ward(['id' => $key['id']]);
                                } 
                            }
                        
                            for ($i = 0; $i < sizeof($placemarks); $i++) {
                                //  echo "<pre>";
                                
                                 //$wardName = (string) $xmlObject->name;
                                $query ='';
                                $qtmp=array();
                                $cornames=array();
                                
                                if($hasSchema){
                                    if(!empty( $placemarks[$i]->ExtendedData->SchemaData)){
                                        
                                        $coordinates = $placemarks[$i]->ExtendedData->SchemaData->SimpleData;
                                        foreach($coordinates as $key=>$value){
                                            $name = ''.$value['name'];
                                            $cornames[$name] = (string)$value;
                                        }
                                    }
                                   
                                    if(!empty($placemarks[$i]->Polygon->outerBoundaryIs->LinearRing->coordinates)){
                                        $cor_d  =  explode(' ', $placemarks[$i]->Polygon->outerBoundaryIs->LinearRing->coordinates);
                                    }else{
                                        $cor_d  =  explode(' ', $placemarks[$i]->LineString->coordinates);
                                    }
                                    
                                    
                                }else{
                                    //$wardNO = $wardName = json_encode(json_decode($placemarks[$i]->name, true));
                                    
                                        $wardNO = $wardName = (string)$placemarks[$i]->name;
                                    
                                    // $wardNO = $wardName = json_encode(json_decode($placemarks[$i]->name, true));
                                    if(!empty($placemarks[$i]->Polygon->outerBoundaryIs->LinearRing->coordinates)){
                                        $cor_d  =  explode(' ', $placemarks[$i]->Polygon->outerBoundaryIs->LinearRing->coordinates);
                                    }else{
                                        $cor_d  =  explode(' ', $placemarks[$i]->LineString->coordinates);
                                    }
                                    //$cor_d  =  explode(' ', $placemarks[$i]->LineString->coordinates);
                                    
                                }
                                
                    
                                foreach($cor_d as $value){
                                    $value = trim($value);
                                    if($value!=''){
                                        $tmp = explode(',',$value);
                                        $ttmp=$tmp[1];
                                        $tmp[1]=$tmp[0];
                                        $tmp[0]=$ttmp; 
                                        if(count($tmp) == 2)
                                            $qtmp[]= '(' . $tmp[0] . ',' .$tmp[1].')';
                                        if(count($tmp) == 3)
                                            $qtmp[]= '(' . $tmp[0] . ',' .$tmp[1].','.$tmp[2].')';
                                    }
                                }
                    
                                $cor_d = json_encode($qtmp);
                                $cornamesEncode = json_encode($cornames);
                                // echo "<pre>";print_r($cor_d);
                                // echo "<pre>";print_r($cornames);die();
                                
                                if(isset($cornames['WRD_NAME']) && isset($cornames['WRD_NO'])){
                                    $wardName = $cornames['WRD_NAME'];
                                    $wardNO = $cornames['WRD_NO'];
                                }
                                if(isset($cornames['OBJECTID']) && isset($cornames['Ward_No'])){
                                    $wardName = $cornames['Ward_No'];
                                    $wardNO = $cornames['Ward_No'];
                                }
                                
                                if(isset($wardName) && isset($wardNO)){

                                    $query .='\''.$cornamesEncode.'\', \''.$cor_d.'\'';

                                    $if_ward_exist_new = $this->manageward_model->get_ward(['ward_name' => $wardName, 'ward_no' => $wardNO, 'organisation_id' => $id]);

                                    $wardData=['organisation_id' => $id, 'region_id' => $data['region_id'], 'ward_name' => $wardName, 'ward_no' => $wardNO, 'coordinates' => $cor_d];

                                    if (empty($if_ward_exist_new)) {
                                        $this->manageward_model->add($wardData);
                                    }
                                }
                                
                            }
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
        $stateId =  $GLOBALS['current_user']->area;
        $file = NULL;
        $if_organization_exist = [];
        $message          = '';
    
            if(trim($this->input->post('deparment_name')) == '')
            {
                echo json_encode([
                    'success'              => false,
                    'message'              => _l('invalid_deparment_name'),
                ]);
            } else if ($this->input->post('org_id') && $this->input->post('deprt_id')) {

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
                    $data['org_id']  = $this->input->post('org_id');
                    $data['state_id']  = $stateId;
                    $dept_id = $this->input->post('deprt_id');
                    $this->load->model('organization_model');
                    $this->load->model('department_model');

                    $city_id = $this->organization_model->get_organization_city(['id' => $data['org_id']]);
                    
                    if(!empty($_FILES["file"]['name'])){

                        $department = $this->Department_model->get_department(['id' => $dept_id])[0];

                        if(!empty($department['dept_kml_file'])){
                            $fileName= base_url('uploads/organization/'.$department['dept_kml_file']);
                            $file_path = FCPATH . 'uploads/organization/'.$department['dept_kml_file'];
                                
                            if(file_exists($file_path)){
                                unlink($file_path);
                            }
                            // if(file_exists($fileName)){
                            //     unlink($fileName);
                            // }
                        }

                        $this->load->model('manageward_model');

                        $if_ward_exist = $this->manageward_model->get_ward_org(['organisation_id' => $data['org_id'], 'department_id' => $dept_id]);

                        foreach($if_ward_exist as $key){
                            $if_ps_exist_on_ward = $this->manageward_model->get_ps(['ward' => $key['id']]);
                            if (empty($if_ps_exist_on_ward)) {
                                $this->manageward_model->delete_ward(['id' => $key['id']]);
                            } 
                        }

                        $fileUpload= base_url('/uploads/organization/'.$file);
    
                        $xml=simplexml_load_file($fileUpload);
                        if(empty($xml->Document->Folder)){
                            $placemarks= $xml->Document->Placemark;
                            $hasSchema = false;
                        }else{
                            $placemarks= $xml->Document->Folder->Placemark;
                            //$iterative_cord = 'coordinates'
                            $hasSchema = true;
                        }
                        
                
                        $query = [];
                        $run='';
                        
                        for ($i = 0; $i < sizeof($placemarks); $i++) {
                            $query ='';
                            //$coordinates = $placemarks[$i]->ExtendedData->SchemaData->SimpleData;
                            
                            //$cor_d  =  explode(' ', $placemarks[$i]->Polygon->outerBoundaryIs->LinearRing->coordinates);
                            $qtmp=array();
                            $cornames=array();
                
                            if($hasSchema){
                                if(!empty( $placemarks[$i]->ExtendedData->SchemaData)){
                                    $coordinates = $placemarks[$i]->ExtendedData->SchemaData->SimpleData;
                                    foreach($coordinates as $key=>$value){
                                        $name = ''.$value['name'];
                                        $cornames[$name] = (string)$value;
                                    }
                                }
                                if(!empty($placemarks[$i]->Polygon->outerBoundaryIs->LinearRing->coordinates)){
                                    $cor_d  =  explode(' ', $placemarks[$i]->Polygon->outerBoundaryIs->LinearRing->coordinates);
                                }else{
                                    $cor_d  =  explode(' ', $placemarks[$i]->LineString->coordinates);
                                }
                                
                                
                            }else{
                                $wardNO = $wardName = json_encode(json_decode($placemarks[$i]->name, true));
                                $cor_d  =  explode(' ', $placemarks[$i]->LineString->coordinates);
                                //print_r($cor_d);
                            }
                     
                
                            foreach($cor_d as $value){
                                $value = trim($value);
                                if($value!=''){
                                    $tmp = explode(',',$value);
                                    $ttmp=$tmp[1];
                                    $tmp[1]=$tmp[0];
                                    $tmp[0]=$ttmp; 
                                    if(count($tmp) == 2)
                                        $qtmp[]= '(' . $tmp[0] . ',' .$tmp[1].')';
                                    if(count($tmp) == 3)
                                        $qtmp[]= '(' . $tmp[0] . ',' .$tmp[1].','.$tmp[2].')';
                                }
                            }
                
                            $cor_d = json_encode($qtmp);
                            
                            $cornamesEncode = json_encode($cornames);

                            if(isset($cornames['WRD_NAME']) && isset($cornames['WRD_NO'])){
                                $wardName = $cornames['WRD_NAME'];
                                $wardNO = $cornames['WRD_NO'];
                            }
                            if(isset($cornames['OBJECTID']) && isset($cornames['Ward_No'])){
                                $wardName = $cornames['Ward_No'];
                                $wardNO = $cornames['Ward_No'];
                            }

                            if(isset($wardName) && isset($wardNO)){
                    
                                $query .='\''.$cornamesEncode.'\', \''.$cor_d.'\'';

                                $if_ward_exist_new = $this->manageward_model->get_ward(['ward_name' => $wardName, 'ward_no' => $wardNO, 'organisation_id' => $data['org_id'], 'department_id' => $dept_id]);

                                $wardData=['organisation_id' => $data['org_id'], 'region_id'=> $city_id, 'department_id' => $dept_id, 'coordinates' => $cor_d, 'ward_name' => $wardName, 'ward_no' => $wardNO];

                                if (empty($if_ward_exist_new)) {
                                    $this->manageward_model->add($wardData);
                                }
                            }
                            
                        }
                    }
                    $id = $this->Department_model->updateDepartment($data,$file,$dept_id);
                    if ($id) {
                        $success = true;
                        $message = _l('updated_successfully_department');
                    }
                    echo json_encode([
                        'success'              => $success,
                        'message'              => $message,
                    ]);
                // }
            } else {
                $if_department_exist = $this->Department_model->get_department(['depart_name' => trim($this->input->post('deparment_name')),'org_id'=>$this->input->post('org_id')]);

                if (count($if_department_exist) > 0) {

                    echo json_encode([
                        'success'              => false,
                        'message'              => _l('deparment_already_in_use'),
                    ]);
            
                }else{

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
                    $data['org_id']  = $this->input->post('org_id');
                    $data['state_id']  = $stateId;
                    $this->load->model('organization_model');
                    $city_id = $this->organization_model->get_organization_city(['id' => $data['org_id']]);
                    $id = $this->Department_model->add($data,$file);
                    if ($id) {

                        if(!empty($_FILES["file"]['name'])){
                            $fileUpload= base_url('/uploads/organization/'.$file);
        
                            $xml=simplexml_load_file($fileUpload);
                            if(empty($xml->Document->Folder)){
                                $placemarks= $xml->Document->Placemark;
                                $hasSchema = false;
                            }else{
                                $placemarks= $xml->Document->Folder->Placemark;
                                //$iterative_cord = 'coordinates'
                                $hasSchema = true;
                            }
                    
                            $query = [];
                            $run='';
                            
                            for ($i = 0; $i < sizeof($placemarks); $i++) {
                                $query ='';
                                //$coordinates = $placemarks[$i]->ExtendedData->SchemaData->SimpleData;
                                
                                //$cor_d  =  explode(' ', $placemarks[$i]->Polygon->outerBoundaryIs->LinearRing->coordinates);
                                $qtmp=array();
                                $cornames=array();

                                if($hasSchema){
                                    if(!empty( $placemarks[$i]->ExtendedData->SchemaData)){
                                        $coordinates = $placemarks[$i]->ExtendedData->SchemaData->SimpleData;
                                        foreach($coordinates as $key=>$value){
                                            $name = ''.$value['name'];
                                            $cornames[$name] = (string)$value;
                                        }
                                    }
                                    if(!empty($placemarks[$i]->Polygon->outerBoundaryIs->LinearRing->coordinates)){
                                        $cor_d  =  explode(' ', $placemarks[$i]->Polygon->outerBoundaryIs->LinearRing->coordinates);
                                    }else{
                                        $cor_d  =  explode(' ', $placemarks[$i]->LineString->coordinates);
                                    }
                                }else{
                                    $wardNO = $wardName = json_encode(json_decode($placemarks[$i]->name, true));
                                    $cor_d  =  explode(' ', $placemarks[$i]->LineString->coordinates);
                                    //print_r($cor_d);
                                }
                    
                                // foreach($coordinates as $key=>$value){
                                //     $name = ''.$value['name'];
                                //     $cornames[$name] = (string)$value;
                                // }

                                foreach($cor_d as $value){
                                    $value = trim($value);
                                    if($value!=''){
                                        $tmp = explode(',',$value);
                                        $ttmp=$tmp[1];
                                        $tmp[1]=$tmp[0];
                                        $tmp[0]=$ttmp; 
                                        if(count($tmp) == 2)
                                            $qtmp[]= '(' . $tmp[0] . ',' .$tmp[1].')';
                                        if(count($tmp) == 3)
                                            $qtmp[]= '(' . $tmp[0] . ',' .$tmp[1].','.$tmp[2].')';
                                    }
                                }
                                
                                // foreach($cor_d as $value){
                                //     $tmp = explode(',',$value);
                                //     $ttmp=$tmp[1];
                                //     $tmp[1]=$tmp[0];
                                //     $tmp[0]=$ttmp; 
                                //     $qtmp[]= '(' . $tmp[0] . ',' .$tmp[1].')';
                                // }
                    
                                $cor_d = json_encode($qtmp);
                                //$cornames = json_encode($corNameArray);
                                    
                                $cornamesEncode = json_encode($cornames);
                                
                                if(isset($cornames['WRD_NAME']) && isset($cornames['WRD_NO'])){
                                    $wardName = $cornames['WRD_NAME'];
                                    $wardNO = $cornames['WRD_NO'];
                                } 
                                if(isset($cornames['OBJECTID']) && isset($cornames['Ward_No'])){
                                    $wardName = $cornames['Ward_No'];
                                    $wardNO = $cornames['Ward_No'];
                                }

                                if(isset($wardName) && isset($wardNO)){
                        
                                    $query .='\''.$cornamesEncode.'\', \''.$cor_d.'\'';

                                    $this->load->model('manageward_model');

                                    $if_ward_exist = $this->manageward_model->get_ward(['ward_name' => $wardName, 'ward_no' => $wardNO, 'organisation_id' => $data['org_id'], 'department_id' => $id]);

                                    $wardData=['organisation_id' => $data['org_id'], 'region_id'=> $city_id, 'department_id' => $id, 'coordinates' => $cor_d, 'ward_name' => $wardName, 'ward_no' => $wardNO];

                                    if (empty($if_ward_exist)) {
                                        $this->manageward_model->add($wardData);
                                    }
                                }
                                
                            }
                        }
                        
                        $success = true;
                        $message = _l('added_successfully_department');
                    }
                    echo json_encode([
                        'success'              => $success,
                        'message'              => $message,
                    ]);
                }
            }
    }

    public function change_area_status()
    {
       
        if ($this->input->is_ajax_request()) {
            $data = $this->input->post();
            $id = $data['id'];
            $status = $data['status'];
            $update_status = $this->Organization_model->change_area_status($id, $status);
 
            $response = [
                'success' => true,
                'message' => _l("status_has_been_updated_successfully"),
                'check_status' => 1
            ];
            echo json_encode($response);
        }
    }
    public function change_department_status()
    {
       
        if ($this->input->is_ajax_request()) {
            $data = $this->input->post();
            $id = $data['id'];
            $status = $data['status'];
            $update_status = $this->Department_model->change_department_status($id, $status);
 
            $response = [
                'success' => true,
                'message' => _l("status_has_been_updated_successfully"),
                'check_status' => $status
            ];
            echo json_encode($response);
        }
    }

    public function file_upload($file)
    {
        $config = array(
            'upload_path' =>  FCPATH . 'uploads/organization/',
            'allowed_types' => "kml",
            'overwrite' => TRUE,
            'file_name' => $file,
            'max_size'      => '50000',
            );
            $this->load->library('upload', $config);
            
            if (!$this->upload->do_upload('file')) {
                $error = array('error' => $this->upload->display_errors());
                // return $error['error'];
                if($error['error'] == '<p>The filetype you are attempting to upload is not allowed.</p>'){
                 
                    return "Only KML file type is allowed";
                }else if($error['error'] == '<p>The file you are attempting to upload is larger than the permitted size.</p>'){
                 
                    return "File uploaded is greater than 50 MB";
                }else{
                    return "Something went wrong.";
                }
              
            }
           
            return true;
    }

    public function getOrganizationDepartment(){
        $postData = $this->input->post();
        $this->load->model('Department_model');
        $data = $this->Department_model->getOrgDepartment($postData);
        echo json_encode($data);
    }

    public function getOrgDept()
    {
        $data = $this->input->post();

        
        $organizationNew = $this->Organization_model->getOrganization($data['region_id']);
        $departmentNew = $this->Department_model->getOrgDepartment($data['organizationId']);
        $alreadyDepartmentIds = $this->area_model->getDepartmentIdFomStaff($data['staffId']);
        $alreadyOrgId = $this->area_model->getOrgIdFomStaff($data['staffId']);
        echo json_encode(['departmentNew'=>$departmentNew,'organizationNew'=>$organizationNew ,'alreadyDepartmentIds'=>$alreadyDepartmentIds,'alreadyOrgId'=>$alreadyOrgId]);
        die;
    }

    public  function departments($org_id = ''){
        if ($this->input->is_ajax_request($org_id)) {
            $this->app->get_table_data('department');

        }
        $data['title']                = _l('department').'-'.getOrganizationName($org_id);
        $data['org_id']                = $org_id;
        $this->load->view('admin/department/manage', $data);
    }

    public function deletekml(){

        if ($this->input->is_ajax_request()) {

            $data = $this->input->post();
            $id = $data['orgId'];

            $this->load->model('organization_model');

            $org_data = $this->organization_model->get($id);
            
            if($org_data->kml_file){
                $fileName= base_url('uploads/organization/'.$org_data->kml_file);
                $file_path = FCPATH . 'uploads/organization/'.$org_data->kml_file;

                if(file_exists($file_path)){
                    unlink($file_path);
                }

                //unlink($fileName);
            }

            $this->db->where('id', $id);
            $this->db->update(db_prefix() . 'organization', ["kml_file"=>NULL]);

            $this->load->model('manageward_model');

            $psExist = [];

            $if_ward_exist = $this->manageward_model->get_ward_without_coordinates(['organisation_id' => $id]);

            foreach($if_ward_exist as $key){

                    $if_ps_exist_on_ward = $this->manageward_model->get_ps(['ward' => $key['id']]);
                    if (empty($if_ps_exist_on_ward)) {
                        $this->manageward_model->delete_ward(['id' => $key['id']]);
                    } else {
                        array_push($psExist,$key);
                    }
            }

            $notDelete = !empty($psExist) ? true : false;

            $response = [
                'success' => true,
                'message' => "KML file has been deleted successfully.",
                'not_deleted' => $notDelete
            ];
            echo json_encode($response);

         }
    }

    public function deleteKmlToDepartment(){

        if ($this->input->is_ajax_request()) {

            $data = $this->input->post();
            $id = $data['departId'];

            $this->load->model('department_model');

            $org_data = $this->department_model->getDepartment($id);
            
            if($org_data->dept_kml_file){
                $fileName= base_url('uploads/organization/'.$org_data->dept_kml_file);
                $file_path = FCPATH . 'uploads/organization/'.$org_data->dept_kml_file;
                if(file_exists($file_path)){
                    unlink($file_path);
                }
                //unlink($fileName);
            }

            $this->db->where('id', $id);
            $this->db->update(db_prefix() . 'department', ["dept_kml_file"=>NULL]);

            $this->load->model('manageward_model');

            $psExist = [];

            $if_ward_exist = $this->manageward_model->get_ward_without_coordinates(['department_id' => $id]);

            foreach($if_ward_exist as $key){

                    $if_ps_exist_on_ward = $this->manageward_model->get_ps(['ward' => $key['id']]);
                    if (empty($if_ps_exist_on_ward)) {
                        $this->manageward_model->delete_ward(['id' => $key['id']]);
                    } else {
                        array_push($psExist,$key);
                    }
            }

            $notDelete = !empty($psExist) ? true : false;

            $response = [
                'success' => true,
                'message' => "KML file has been deleted successfully.",
                'not_deleted' => $notDelete
            ];
            echo json_encode($response);

         }
    }

    public function add_edit_serveyor(){
        if (!has_permission('staff_sv', '', 'create')) {
        access_denied('staff_sv');
    }
        $data = $this->input->post();
        $clientid = $this->clients_model->add([

            'firstname'           => $data['firstname'],
            'email'               => $data['email'],
            'contact_phonenumber' => $data['contact_phonenumber'] ,
            'password'            => $data['passwordr'],
            'is_cc'               =>0,
            'is_primary'          =>1,
            'custom_fields'       => isset($data['custom_fields']) && is_array($data['custom_fields']) ? $data['custom_fields'] : [],
      ], true);

      if ($clientid) {
        $response = array(
            'success' => true,
            'message' => "Member has been added successfully"
        );
    }
    echo json_encode($response);
    exit;
    
    }
    
}

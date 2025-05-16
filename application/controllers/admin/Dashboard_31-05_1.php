<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('dashboard_model');
        $this->load->model('projects_model');
        $this->load->model('issue_model');
        $this->load->model("area_model");
        $this->load->model("report_model");
        $this->load->helper('date');
        $this->load->model('atr_report_model');
        $this->load->model('department_model');
        //$this->load->model('staff_model');
    }

    /* This is admin dashboard view */
    public function index()
    {

        close_setup_menu();
        if ($this->input->get("area")) {
            $area = base64_decode($this->input->get("area"));
            $data = $this->prepare_summary_data($area);
            $this->load->view('admin/dashboard/gm_sub_dashboard', $data);
            // $this->load_sub_dashboard($area);
            return;
        }
        // $data['statuses'] = $this->projects_model->get_project_status();

        $data['dashboard'] = true;
        $data['userRole'] = $userRole = $GLOBALS['current_user']->role_slug_url;

        $data['user_dashboard_visibility'] = get_staff_meta(get_staff_user_id(), 'dashboard_widgets_visibility');

        if (!$data['user_dashboard_visibility']) {
            $data['user_dashboard_visibility'] = [];
        } else {
            $data['user_dashboard_visibility'] = unserialize($data['user_dashboard_visibility']);
        }
        $data['user_dashboard_visibility'] = json_encode($data['user_dashboard_visibility']);

        if (in_array($GLOBALS['current_user']->role_slug_url, ['at', 'ata', 'aa'])) {
            $data['action_items_notview'] = $this->dashboard_model->get_action_item_notviewed_counts();
            $data['action_items_notview_refered'] = $this->dashboard_model->get_action_item_notviewed_refered_counts();
            $data['action_items_notview_sendingforapproval'] = $this->dashboard_model->get_action_item_notviewed_sendingforapproval_counts();
            $data['action_items_cnt'] = $this->dashboard_model->get_action_item_counts();
            $data['action_items_refered_cnt'] = $this->dashboard_model->get_action_item_refered_counts();
            $data['action_items_pendingforapproval_cnt'] = $this->dashboard_model->get_action_item_pendingforapproval_counts();
            $data['upcoming_deadline_data_cnt'] = $this->dashboard_model->next_week_deadline_count();
        }

        if (in_array($GLOBALS['current_user']->role_slug_url, ['qc','aa'])) {
            if (in_array($GLOBALS['current_user']->role_slug_url, ['qc'])) {
                $data['action_items_notview_closed'] = $this->dashboard_model->get_action_item_closed_counts(1);
                $data['action_items_closed_cnt'] = $this->dashboard_model->get_action_item_closed_counts();
            }
            
            $data['action_items_notview_verified'] = $this->dashboard_model->get_action_item_verified_counts(1);
            $data['action_items_verified_cnt'] = $this->dashboard_model->get_action_item_verified_counts();
        }
        $data['userDetails'] = $this->staff_model->get_userDetails(get_staff_user_id());

        if ($userRole == 'at') {
            $userId = $GLOBALS['current_user']->staffid;
            //Get Assistant Details
            $data['assistantDetails'] = $this->staff_model->get_staff_assistance($userId);
            //Get Rejection List
            $this->load->model('exception_model');
            $data['exceptionDetails'] = $this->exception_model->get_exception_list();
            //$data['plDetail'] = $this->staff_model->get_staff_assistance($userId);
                $reviewres = $this->staff_model->getReviewers($userId);
                $data['reviewersDetails'] = $this->staff_model->get_userDetails($reviewres->assistant_id);

                $data['plDetail'] = $this->staff_model->get_staff_assistance($data['reviewersDetails']->staffid);
        }
        if ($userRole == 'ata') {
            $userId = $GLOBALS['current_user']->staffid;
           
            //Get Assistant Details
            $data['assistantDetails'] = $this->staff_model->get_staff_same_pl($userId);
            $data['plDetail'] = $this->staff_model->get_reporting_person($userId);
            
            //Get Rejection List
            $this->load->model('exception_model');
            $data['exceptionDetails'] = $this->exception_model->get_exception_list();
        }
        if (in_array($userRole, ['ar'])) {
            $userId = $GLOBALS['current_user']->staffid;
            $area = $GLOBALS['current_user']->area;
            $data['reviewers']= $this->staff_model->get_area_reviewers($area);
            $data['assignedAts'] = $this->staff_model->get_staff_assistance($userId);
            $data['action_items_notview'] = $this->dashboard_model->get_action_item_notviewed_counts();
            $data['action_items_notview_refered'] = $this->dashboard_model->get_action_item_notviewed_refered_counts();
            $data['action_items_cnt'] = $this->dashboard_model->total_ar_action_items();
            $data['recently_closed'] = $this->dashboard_model->recently_ar_closed_tickets();
            $data['recently_closed_cnt'] = $this->dashboard_model->total_ar_closed_tickets();
            $data['action_items_refered_cnt'] = $this->dashboard_model->get_action_item_refered_counts();
            
        }
        //echo 111;exit;
        if ($userRole == 'ae-global') {
            $data = $this->prepare_summary_data();
        }

        $dashboard_widget_data = $this->dashboard_model->get_dashboard_widget_data();

        $partialy_resolved = 0;
        $data['escalated'] = $escalated = !empty($dashboard_widget_data->escalated) ? $dashboard_widget_data->escalated : 0;
        $data['new'] = $new = !empty($dashboard_widget_data->new) ? $dashboard_widget_data->new : 0;
        $data['ongoing'] = $ongoing = !empty($dashboard_widget_data->ongoing) ? $dashboard_widget_data->ongoing : 0;

        $data['resolved'] = $resolved = !empty($dashboard_widget_data->resolved) ? $dashboard_widget_data->resolved : 0;
        $data['part_resolved'] = $part_resolved = !empty($dashboard_widget_data->part_resolved) ? $dashboard_widget_data->part_resolved : 0;
        $data['unresolved'] = $resolved = !empty($dashboard_widget_data->unresolved) ? $dashboard_widget_data->unresolved : 0;
        $data['reopen'] = $reopen = !empty($dashboard_widget_data->reopen) ? $dashboard_widget_data->reopen : 0;
        $data['closed'] = $closed = !empty($dashboard_widget_data->closed) ? $dashboard_widget_data->closed : 0;
        $data['rejected'] = $rejected = !empty($dashboard_widget_data->rejected) ? $dashboard_widget_data->rejected : 0;
        $data['referred'] = $referred = !empty($dashboard_widget_data->referred) ? $dashboard_widget_data->referred : 0;
        $data['longterm'] = $longterm = !empty($dashboard_widget_data->longterm) ? $dashboard_widget_data->longterm : 0;

        $data['submitforapproval'] = $submitforapproval = !empty($dashboard_widget_data->submitforapproval) ? $dashboard_widget_data->submitforapproval : 0;
        $data['unassigned'] = $unassigned = !empty($dashboard_widget_data->unassigned) ? $dashboard_widget_data->unassigned : 0;
        $data['verified'] = !empty($dashboard_widget_data->verified) ? $dashboard_widget_data->verified : 0;
        // $data['verified'] = $verified + $resolved + $partialy_resolved;
        $data['total_activity'] = !empty($dashboard_widget_data->total_activity) ? $dashboard_widget_data->total_activity : 0;
        // echo'<pre>';

        $data = hooks()->apply_filters('before_dashboard_render', $data);
        if ($userRole == 'ap-sa') {
            redirect(admin_url('area'));
        } else if ($userRole == 'ar') {
            $this->load->view('admin/dashboard/ar_dashboard', $data);
        } else if ($userRole == "ae-area") {
            $data = $this->prepare_summary_data($GLOBALS['current_user']->area);

            //print_r($data);exit;

            //pre($this->input->post());
            $this->load->view('admin/dashboard/gm_sub_dashboard', $data);
        } else if ($userRole == 'ae-global') {

            $data['filter_data'] = $this->get_filter_duration_name($this->input->post('report_months'));


            $this->load->view('admin/dashboard/gm_dashboard', $data);
        } else
            $this->load->view('admin/dashboard/dashboard', $data);

    }

    public function get_filter_duration_name($duration_name)
    {
        if (isset($duration_name)) {
            switch ($duration_name) {
                case 'custom':
                    return 'Custom';
                    break;
                case 'this_month':
                    return 'This Month';
                    break;
                case 'last_month':
                    return 'Last Month';
                    break;
                case 'this_year':
                    return 'This Year';
                    break;
                case 'last_year':
                    return 'Last Year';
                    break;
                case '3':
                    return 'Last 3 Months';
                    break;
                case '6':
                    return 'Last 6 Months';
                    break;
                case '12':
                    return 'Last 12 Months';
                    break;
                default:
                    return 'Currently';

            }
        } else {
            return 'Currently';
        }
    }

    public function prepare_summary_data($area = null)
    {
        if ($this->input->post())
            $post_data = $this->input->post();
        else if ($this->input->get())
            $post_data = $this->input->get();

        if ($area == null) {
            $area = $GLOBALS['current_user']->area;
        }
        $data["durations"] = [
            ["id" => "30", "duration" => "< 1 month"],
            ["id" => "31 - 183", "duration" => "1 month - 6 months"],
            ["id" => "184 - 365", "duration" => "6 months - 1 year"],
            ["id" => "366 - 1095", "duration" => "1 year - 3 years"],
            ["id" => "1096 - 1825", "duration" => "3 years - 5 years"],
            ["id" => "1826", "duration" => "> 5 years"],
        ];
        $data['categories'] = $this->issue_model->get_area_issues($area);
        $tableParams = [
            'category' => (!empty($post_data['category'])) ? $post_data['category'] : '',
            'duration' => (!empty($post_data['duration'])) ? $post_data['duration'] : '',
            'report_date' => (!empty($post_data['report_months'])) ? $post_data['report_months'] : '',
            'to_date' => (!empty($post_data['report-to'])) ? $post_data['report-to'] : '',
            'from_date' => (!empty($post_data['report-from'])) ? $post_data['report-from'] : '',
        ];
        if ($area != null) {
            $tableParams['area'] = $area;
            $tableParams["area_name"] = $this->area_model->get_area(["areaid" => $area]);
            $tableParams["area_name"] = !empty($tableParams["area_name"]) ? $tableParams["area_name"][0]["name"] : "";
        }
        $data['tableParams'] = $tableParams;
        $data['statuses'] = $this->dashboard_model->get_ae_global($tableParams);

        return $data;
    }

    public function load_sub_dashboard($area = null, $export = false)
    {
        $response = [
            "success" => false,
            "message" => "No Data Found!",
            "data" => []
        ];

        if ($area == null)
            $area = $this->input->post("area");
        $data = $this->prepare_summary_data($area);

        // get statuses on the basis of area
        if (!empty($data['statuses'])) {
            $this->load->model("region_model");
            $regions = $this->region_model->get_region(['area_id' => $area]);
            $region_summary = [];
            foreach ($regions as $region) {
                $data['tableParams']["region_list"] = $region["id"];
                $region_data = $this->dashboard_model->get_ae_global($data['tableParams']);
                if (!empty($region_data))
                    array_push($region_summary, $region_data[0]);
            }
            // get statuses on the basis of regions
            $data['statuses'] = $region_summary;

            $this->load->model("subregion_model");
            $sub_regions = [];
            foreach ($data['statuses'] as $key => $status) {
                $data['statuses'][$key]["sub_region_status"] = [];
                // get sub-regions on the basis of region id
                $sub_region = $this->subregion_model->get_subregion_collection(["region_id" => $status["region_id"]]);
                $data['tableParams']["region_list"] = [];
                foreach ($sub_region as $region) {
                    array_push($sub_regions, $region["id"]);
                    $data['tableParams']["sub_region_list"] = $region["id"];
                    $sub_region_data = $this->dashboard_model->get_ae_global($data['tableParams']);
                    if (!empty($sub_region_data))
                        array_push($data['statuses'][$key]["sub_region_status"], $sub_region_data[0]);
                }
            }
        }
        $filter_data = $this->get_filter_duration_name($this->input->post('report_months'));


        if (!empty($data)) {
            $response = [
                "success" => true,
                "message" => "Data Fetched Successfully",
                "data" => $data['statuses'],
                "filter_data" => $filter_data

            ];
        }
        if ($export)
            return $data;
        else {

            echo json_encode($response);
            die;
        }
    }

    /* Chart weekly payments statistics on home page / ajax */
    public function weekly_payments_statistics($currency)
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->dashboard_model->get_weekly_payments_statistics($currency));
            die();
        }
    }

    public function welcome()
    {
        $this->load->view("admin/dashboard/checklist");
    }

    public function action_items()
    {
        if ($this->input->is_ajax_request()) {
            $userRole = $GLOBALS['current_user']->role_slug_url;
            $userId = $GLOBALS['current_user']->staffid;
            $userName = $GLOBALS['current_user']->full_name;
            $action_items_countview = 0;
            $action_items_cnt = 0;
            if (in_array($userRole, ['ar'])) {
                $action_items_cnt = $this->dashboard_model->total_ar_action_items();
            } else {
                $action_items_cnt = $this->dashboard_model->get_action_item_counts();
            }
            $action_items_countviewadd = $this->dashboard_model->get_action_item_notviewed_counts();

            $action_items = array();
            if ($action_items_cnt > 0) {
                
                if (in_array($userRole, ['ar'])) {
                    $action_items = $this->dashboard_model->get_ar_action_items();
                } else {
                    $action_items = $this->dashboard_model->get_action_items();
                }

                $actionList = '';
                $roles = array('at');
                $this->load->model('projects_model');
                $this->load->model('staff_model');

                foreach ($action_items as $key => $item) {
                    $project_name = !empty($item['project_name']) ? $item['project_name'] : '';
                    $landmark = !empty($item['landmark']) ? $item['landmark'] : '';
                    $reassigned = !empty($item['reassigned']) ? $item['reassigned'] : '';
                    $description = !empty($item['description']) ? $item['description'] : '';
                    $statusId = !empty($item['status']) ? $item['status'] : '';
                    $statusColor = !empty($item['color']) ? $item['color'] : '';
                    $statusBgColor = !empty($item['bg-color']) ? $item['bg-color'] : '';
                    $status_name = !empty($item['status_name']) ? $item['status_name'] : '';
                    $deadline = !empty($item['deadline']) ? setDateFormat($item['deadline']) : '';
                    $frozen = !empty($item['frozen']) ? $item['frozen'] : '';
                    //$action_date = !empty($item['action_date']) ? setDateFormat($item['action_date']) : '';
                    $start_date = !empty($item['project_created']) ? date('d-M-Y h:i:s', strtotime($item['project_created'])) : '';
                    // $project_total_days = !empty($item['deadline']) ? date('d-M-Y', strtotime($item['deadline'])) : '';
                    $projectId = $item['project_id'];

                    if ($userRole == 'aa') {
                        if (!empty($item['ward_id'])) {
                            $psIdCheck = findAtwithWard($item['area_id'], $item['region_id'], $item['subregion_id'], $item['ward_id'], $item['issue_id']);
                        }
                    }

                    $assignedUser = getProjectAssignedUser($projectId);
                    if ($userRole == 'ata') {
                        $statusId = !empty($item['status_id']) ? $item['status_id'] : '';
                    }

                    $action = '';
                    if ($statusId == 1 || $statusId == 6) {
                       // code commented for visible new project status
                        if (in_array($userRole,['aa','ata','ar','at'])) {
                            $action = '<div class="btn-container">
                            <a href="javascript:void(0)" class="btn ticket-btn ticket_details"  data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '" data-closed="1">'.
                                             ($GLOBALS['current_user']->role_slug_url =='ata'? _l("close"):_l("close"))
                                            .'</a>
                                            <a href="javascript:void(0)" class="btn reject-btn refer-btn-popup" data-projectid="' . $projectId . '">'.
                                             ($GLOBALS['current_user']->role_slug_url =='ata'? _l("refer"):_l("refer"))
                                            .'</a>
                                        </div>';
                        } 
                        //else {
                        //     $action = _l("total_assigned");
                        // }
                        // if ($reassigned) {
                        //     $status_name = _l("total_referred");
                        // }
                        // if (in_array($userRole, ['at', 'ar']) && $item['action_date'] <= date('Y-m-d')) {
                        //     // $status_name = 'Delayed'; //Unaccepted
                        //     // $statusColor = "#fc0332"; //red
                        //     // $statusBgColor = "#ffd6de";
                        // }
                        //$action = _l("total_assigned");
                    }
                    // else if ($statusId == 2 || $statusId == 6) {
                    //     //$userRole != 'ata'
                    //     if ($userRole != '') {
                    //         // $ticketDetails = $this->projects_model->get_project_details($projectId);
                    //         // $assignedUser = !empty($ticketDetails->assigned_user_id) ? $ticketDetails->assigned_user_id : '';
                    //         $assignedUserDetails = $this->staff_model->get_userDetails($assignedUser);
                    //         $name = !empty($assignedUserDetails->full_name) ? $assignedUserDetails->full_name : '';
                    //         $phoneNumber = !empty($assignedUserDetails->phonenumber) ? $assignedUserDetails->phonenumber : '';
                    //         $organisation = !empty($assignedUserDetails->organisation) ? $assignedUserDetails->organisation : '';

                    //         if ($assignedUser == get_staff_user_id()) {
                    //             $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">Close Project</p>';
                    //         } else {
                    //             $action = '<div class="ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                    //                             <p class="action-label">Call - 
                    //                                 <span class="ata-name">' . $name . '</span>
                    //                             </p>
                    //                             <p class="action-label">Phone No - 
                    //                                 <span class="ata-name">' . $phoneNumber . '</span>
                    //                             </p>
                    //                         </div>';
                    //         }
                    //     } else {
                    //         $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">'._l("total_reopen").'</p>';
                    //     }
                    // } 
                    else if ($statusId == 3) {
                        $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">' . _l("total_closed") . '</p>';
                    } else if ($statusId == 4) {
                        $status_name = 'In Progress';
                        $statusColor = "#fc0332";
                        $statusBgColor = "#ffd6de";
                        $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">Review</p>';
                    } else if ($statusId == 5) {
                        if ($userRole == 'ar') {
                            $projectNotes = project_latest_notes($projectId, $statusId);
                            $projectNote_content = !empty($projectNotes->content) ? $projectNotes->content : '';
                            $task_id = !empty($projectNotes->task_id) ? $projectNotes->task_id : '';
                            $projectNote_status = !empty($projectNotes->status) ? $projectNotes->status : '';
                            $exception = (!empty($projectNotes->exception) && $projectNotes->exception != 6) ? getExceptionName($projectNotes->exception) : $projectNote_content;
                            $action = '<div class="ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                                            <p class="action-label">' . _l("total_referred") . '</p>
                                            <p class="ata-name">' . $projectNote_content . '</p>
                                        </div>';
                        } else {
                            $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">' . _l("total_referred") . '</p>';
                        }
                    } 
                    /* else if ($statusId == 6) {
                        //  $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">'._l("total_closed").'</p>';
                        $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">' . _l("total_reopen") . '2222</p>';
                    } */
                    else if ($statusId == 9) {
                        //  $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">'._l("total_closed").'</p>';
                        $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">' . _l("total_unassigned") . '</p>';
                    }
                    if (!empty($item['ward_id'])) {                     
                        if (empty($psIdCheck) && $statusId == 9 && $userRole == 'aa') {

                            $action = '<div class="btn-container">
                                            <a href="javascript:void(0)" class="btn accept-btn add-new-ps" data-projectid="' . $projectId . '">' . _l("add_project_support") . '</a>
                                        </div>';
                        } else if ($statusId == 9 && $userRole == 'aa' && !empty($psIdCheck)) {
                            $action = '<div class="btn-container">
                                            <a href="javascript:void(0)" class="btn accept-btn assigned-new-ps" data-projectid="' . $projectId . '" data-staffid="' . $psIdCheck . '">' . _l("assigned_project_support") . '</a>
                                        </div>';
                        }
                    }

                    if (!in_array($userRole, ['aa','ata']) && $statusId != 1) {
                        $assignedAT = $this->projects_model->get_project_at($projectId);
                        $assignedUserDetails = $this->staff_model->get_userDetails($assignedAT);
                        $name = !empty($assignedUserDetails->full_name) ? $assignedUserDetails->full_name : '';
                        $phoneNumber = !empty($assignedUserDetails->phonenumber) ? $assignedUserDetails->phonenumber : '';
                        $action = '<div class="ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                                        <p class="action-label">'._l("call").' - 
                                            <span class="ata-name">' . $name . '</span>
                                        </p>
                                        <p class="action-label">'._l("clients_contact_number").' - 
                                            <span class="ata-name">' . $phoneNumber . '</span>
                                        </p>
                                    </div>';
                    }


                    // $dueStatus = '';
                    // if (!in_array($statusId, [1, 3, 5])) {
                    //     // $status_name = 'WIP';
                    //     // $statusColor = "#e43b05";
                    //     // $statusBgColor = "#ffe0d6";
                    //     $dueDaysLeft = $this->projects_model->calculate_project_days_left($projectId);

                    //     $dueStatus = 'Due in '.$dueDaysLeft.' days';
                    // }

                    // if (!in_array($statusId, [3, 5]) && $item['action_date'] < date('Y-m-d')) {
                    //     $status_name = _l('total_delayed');
                    //     $statusColor = "#fc0332"; //red
                    //     $statusBgColor = "#ffd6de";
                    // }
                    if ($userRole == 'at') {
                        if ($statusId == 12 || $statusId == 11) {
                            $status_name = _l('total_pendingforapp');
                        }
                    } else {
                        if ($statusId == 12 || $statusId == 11) {
                            $status_name = _l('total_submitforapp');
                        }
                    }
                    if ($statusId == 1) {
                        $status_name = _l('total_assigned');
                    }

                    if ($statusId == 3) {
                        $status_name = _l('total_closed');
                    }

                    if ($statusId == 5) {
                        $status_name = _l('total_referred');
                    }
                    if ($statusId == 6) {
                        $status_name = _l('total_reopen');
                    }
                    if ($statusId == 7) {
                        $status_name = _l('total_delayed');
                    }

                    if ($statusId == 9) {
                        $status_name = _l('total_unassigned');
                    }
                    if ($statusId == 10) {
                        $status_name = _l('total_longterm');
                    }

                    $viewed_projects = $this->dashboard_model->getViewStatus($projectId);
                    
                    if ($viewed_projects == 0) {
                        $action_items_countview++;
                        if($userRole == 'qc'){
                            $colorbg = '#fff';
                        }else{
                            $colorbg = '#eaf1fb';
                        }
                       
                    } else {
                        $colorbg = '#fff';
                    }
                    if($userRole == 'aa'){
                        $colorbg = '#fff';
                    }
                    $getSurvayerLatLong = $this->dashboard_model->getsarvayerLatLong($projectId);
                    $latitude = $getSurvayerLatLong['latitude'];
                    $longitude = $getSurvayerLatLong['longitude'];
                    $actionList .= '<div class="dashboard-row delayed project_' . $projectId . '" style="background-color:' . $colorbg . '">
                                        <div class="dashboard-cell w170 action-item ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                                            <p>
                                                
                                                <strong>Issue no.</strong>
                                                <span class="landmark" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . strip_tags($landmark) . '">' . $projectId . '</span>
                                            </p>
                                        </div>
                                        <div class="dashboard-cell w270 action-item ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                                            <p>
                                                <strong data-toggle="tooltip" data-placement="top" title="" data-original-title="' . strip_tags($project_name) . '">' . mb_strimwidth($project_name, 0, 30, '...') . '</strong>
                                                <span>near</span>
                                                <span class="landmark" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . strip_tags($landmark) . '">' . mb_strimwidth($landmark, 0, 30, '...') . '</span>
                                            </p>
                                        </div>
                                        <div class="dashboard-cell w170 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                                            <p class="deadline">' . $start_date . '</p>
                                            <span class="status status_' . $projectId . '" style="background-color:' . $statusBgColor . '; color:' . $statusColor . ';">' . $status_name . '</span>
                                        </div>
                                        <div class="dashboard-cell w270 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                                            <p data-toggle="tooltip" data-placement="top" title="' . strip_tags($description) . '">' . mb_strimwidth($description, 0, 100, '...') . '</p>
                                        </div>
                                        <div class="dashboard-cell evidence w170">
                                            <p class="evidence_img report-location" data-img_type="original" data-project_id="' . $projectId . '">
                                            <i class="fa fa-eye" aria-hidden="true"></i><span>View</span></p>
                                        </div>';
                                        $actionList .=  '<div class="dashboard-cell evidence w170">
                                        <p class="evidence_img report-location" >';
                                        if(!empty($latitude) && !empty($longitude) && $longitude!=0 && $latitude !=0){
                                            $actionList .=  '
                                            <a href="https://maps.google.com/maps?q='.$latitude.','.$longitude.'" class="mT10 text-center d-block" target="_blank"><i class="fa fa-map-marker"></i>View Location</a>
                                        ';
                                        }
                                        $actionList .=  '</p></div>';  
                                        
                    if (in_array($userRole, ['at', 'ar', 'aa','ata','qc'])) {
                        $actionList .= '<div class="dashboard-cell action-cell w20P action_' . $projectId . '">' . $action . '</div>';
                    }

                    $actionList .= '</div><div class="row-saperator"></div>';
                }

                echo json_encode([
                    'success' => TRUE,
                    'message' => $actionList,
                    'action_items_cnt' => $action_items_cnt,
                    'action_items_countview' => $action_items_countviewadd
                ]);
            } else {
                $actionList = '';
                echo json_encode([
                    'success' => TRUE,
                    'message' => $actionList,
                    'action_items_cnt' => $action_items_cnt,
                    'action_items_countview' => $action_items_countviewadd
                ]);
            }
        } else {
            echo json_encode([
                'fail' => FALSE,
                'message' => 'Invalid request. Please try again.',
            ]);
        }
    }

    public function assigned_to_ps() {
        if ($this->input->is_ajax_request()) {
            $data = $this->input->post();

            $projectId = $data['projectId'];
            $staffid = $data['staffid'];
            $projectDetail = $this->projects_model->get_project_details_ids($projectId);

            if(isset($data['projectId']) && $data['projectId']){

                $this->db->where('project_id', $data['projectId']);
                $this->db->update(db_prefix() . 'project_members', [
                    'assigned' => 0
                ]);

                $this->db->insert(db_prefix() . 'project_members', [
                    'project_id' => $data['projectId'],
                    'staff_id'   => $staffid,
                ]);

                $this->db->where('id', $data['projectId']);
                $this->db->update(db_prefix() . 'projects', ['status' => 1,'is_assigned'=>1]);

                $additional_data = array(
                    'assigned_by' => get_staff_user_id(),
                    'assigned_to' => $staffid,
                    'taskId' => '',
                    'status' => 1,
                    'comment' => '',
                    'parent_ticket_id' => ''
                );

                $additionalData = json_encode($additional_data);

                $this->load->model('staff_model');
                $staffDetail = $this->staff_model->get_userDetails($staffid);
                
                $activity=[];

                $activity['staff_id']            = $staffid;
                $activity['fullname']           = isset($staffDetail->full_name) ? general_validate(trim(ucwords($staffDetail->full_name))) : "";
                $activity['description_key']     = "ticket_assigned_to_ata";
                $activity['additional_data']     = $additionalData;
                $activity['visible_to_customer'] = 1;
                $activity['status'] = 1;
                $activity['project_id']          = $data['projectId'];
                $activity['dateadded']           = date('Y-m-d H:i:s');

                $this->db->insert(db_prefix() . 'project_activity', $activity);

                if(isset($project_details->update_task_id) && $project_details->update_task_id){
                    $taskAssigned = [];
                    $taskAssigned['staffid']      = $staffid;
                    $taskAssigned['taskid']       = $project_details->update_task_id;
                    $taskAssigned['assigned_from']= get_staff_user_id();
                    $taskAssigned['assigned_date']= date('Y-m-d H:i:s');
                    $taskAssigned['status']       = 1;

                    $this->db->where('taskid', $project_details->update_task_id);
                    $this->db->update(db_prefix() . 'task_assigned', $taskAssigned);
                }

            }


            $response = array(
                'success' => true,
                'message' => "Project has been assigned successfully."
            );

        } else {
            $response = array(
                'success' => false,
                'message' => "Invalid request. Please try again."
            );
        }

        echo json_encode($response);
        die;
    }


   

    public function action_items_refered()
    {
        
        if ($this->input->is_ajax_request()) {
            $userRole = $GLOBALS['current_user']->role_slug_url;
            $userId = $GLOBALS['current_user']->staffid;
            $userName = $GLOBALS['current_user']->full_name;
            $action_items_refered_countview = 0;
            $action_items_refered_cnt = 0;
            // if (in_array($userRole, ['ar'])) {
            //     $action_items_refered_cnt = $this->dashboard_model->total_ar_action_items();
            // } else {
                $action_items_refered_cnt = $this->dashboard_model->get_action_item_refered_counts();
           // }


            if ($action_items_refered_cnt > 0) {
                $action_items = array();
                // if (in_array($userRole, ['ar'])) {
                //     $action_items = $this->dashboard_model->get_ar_action_items();
                // } else {
                    $action_items = $this->dashboard_model->get_action_items_refered();
               // }

                $actionList = '';
                $roles = array('at');
                $this->load->model('projects_model');
                $this->load->model('staff_model');

                foreach ($action_items as $key => $item) {
                    $project_name = !empty($item['project_name']) ? $item['project_name'] : '';
                    $landmark = !empty($item['landmark']) ? $item['landmark'] : '';
                    $reassigned = !empty($item['reassigned']) ? $item['reassigned'] : '';
                    $description = !empty($item['description']) ? $item['description'] : '';
                    $statusId = !empty($item['status']) ? $item['status'] : '';
                    $statusColor = !empty($item['color']) ? $item['color'] : '';
                    $statusBgColor = !empty($item['bg-color']) ? $item['bg-color'] : '';
                    $status_name = !empty($item['status_name']) ? $item['status_name'] : '';
                    $deadline = !empty($item['deadline']) ? setDateFormat($item['deadline']) : '';
                    $frozen = !empty($item['frozen']) ? $item['frozen'] : '';
                    $action_date = !empty($item['action_date']) ? setDateFormat($item['action_date']) : '';
                     $start_date = !empty($item['project_created']) ? date('d-M-Y h:i:s', strtotime($item['project_created'])) : '';
                    // $project_total_days = !empty($item['deadline']) ? date('d-M-Y', strtotime($item['deadline'])) : '';
                    $projectId = $item['project_id'];

                    if ($userRole == 'aa') {
                        $psIdCheck = findAtwithWard($item['area_id'], $item['region_id'], $item['subregion_id'], $item['ward_id'], $item['issue_id']);
                    }

                    $assignedUser = getProjectAssignedUser($projectId);
                    if ($userRole == 'ata') {
                        $statusId = !empty($item['status_id']) ? $item['status_id'] : '';
                    }

                    $action = '';
                    if ($statusId == 1) {
                        
                        //code commented for visible new project status
                        // if (in_array($userRole, $roles) && ($assignedUser == get_staff_user_id()) && empty($frozen)) {
                        //     $action = '<div class="btn-container">
                        //                     <a href="javascript:void(0)" class="btn accept-btn accept-btn-popup" data-projectid="' . $projectId . '">'._l("accept").'</a>
                        //                 </div>
                        //                 <div class="btn-container">
                        //                     <a href="javascript:void(0)" class="btn reject-btn reject-btn-popup" data-projectid="' . $projectId . '">'.
                        //                      ($GLOBALS['current_user']->role_slug_url =='at'? _l("total_referred"):_l("reject"))
                        //                     .'</a>
                        //                 </div>';
                        // } else {
                        //     $action = _l("total_assigned");
                        // }
                        // if ($reassigned) {
                        //     $status_name = _l("total_referred");
                        // }
                        // if (in_array($userRole, ['at', 'ar']) && $item['action_date'] <= date('Y-m-d')) {
                        //     // $status_name = 'Delayed'; //Unaccepted
                        //     // $statusColor = "#fc0332"; //red
                        //     // $statusBgColor = "#ffd6de";
                        // }
                        //$action = _l("total_assigned");
                    }
                    // else if ($statusId == 2 || $statusId == 6) {
                    //     //$userRole != 'ata'
                    //     if ($userRole != '') {
                    //         // $ticketDetails = $this->projects_model->get_project_details($projectId);
                    //         // $assignedUser = !empty($ticketDetails->assigned_user_id) ? $ticketDetails->assigned_user_id : '';
                    //         $assignedUserDetails = $this->staff_model->get_userDetails($assignedUser);
                    //         $name = !empty($assignedUserDetails->full_name) ? $assignedUserDetails->full_name : '';
                    //         $phoneNumber = !empty($assignedUserDetails->phonenumber) ? $assignedUserDetails->phonenumber : '';
                    //         $organisation = !empty($assignedUserDetails->organisation) ? $assignedUserDetails->organisation : '';

                    //         if ($assignedUser == get_staff_user_id()) {
                    //             $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">Close Project</p>';
                    //         } else {
                    //             $action = '<div class="ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                    //                             <p class="action-label">Call - 
                    //                                 <span class="ata-name">' . $name . '</span>
                    //                             </p>
                    //                             <p class="action-label">Phone No - 
                    //                                 <span class="ata-name">' . $phoneNumber . '</span>
                    //                             </p>
                    //                         </div>';
                    //         }
                    //     } else {
                    //         $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">'._l("total_reopen").'</p>';
                    //     }
                    // } 
                    else if ($statusId == 3) {
                        $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">' . _l("total_closed") . '</p>';
                    } else if ($statusId == 4) {
                        $status_name = 'In Progress';
                        $statusColor = "#fc0332";
                        $statusBgColor = "#ffd6de";
                        $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">Review</p>';
                    } else if ($statusId == 5) {
                        $statusBgColor = "#ffd6de";
                        // if ($userRole == 'ar') {
                        //     $projectNotes = project_latest_notes($projectId, $statusId);
                        //     $projectNote_content = !empty($projectNotes->content) ? $projectNotes->content : '';
                        //     $task_id = !empty($projectNotes->task_id) ? $projectNotes->task_id : '';
                        //     $projectNote_status = !empty($projectNotes->status) ? $projectNotes->status : '';
                        //     $exception = (!empty($projectNotes->exception) && $projectNotes->exception != 6) ? getExceptionName($projectNotes->exception) : $projectNote_content;
                        //     $action = '<div class="ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                        //                     <p class="action-label">' . _l("refer") . '</p>
                        //                     <p class="ata-name">' . $projectNote_content . '</p>
                        //                 </div>';
                        // } else {
                            //$action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">' . _l("refer") . '</p>';
                            if (in_array($userRole,['at','ata','ar'])) {
                                $action = '<div class="btn-container">
                                                <a href="javascript:void(0)" class="btn reject-btn refer-btn-popup" data-projectid="' . $projectId . '">'.
                                                 ($GLOBALS['current_user']->role_slug_url =='ata'? _l("refer"):_l("refer"))
                                                .'</a>
                                            </div>';
                            } 
                        //}
                    } else if ($statusId == 6) {
                        //  $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">'._l("total_closed").'</p>';
                        $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">' . _l("total_reopen") . '</p>';
                    } else if ($statusId == 9) {
                        //  $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">'._l("total_closed").'</p>';
                        $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">' . _l("total_unassigned") . '</p>';
                    }
                    if (!empty($item['ward_id'])) {
                        if (empty($psIdCheck) && $statusId == 9 && $userRole == 'aa') {

                            $action = '<div class="btn-container">
                                            <a href="javascript:void(0)" class="btn accept-btn add-new-ps" data-projectid="' . $projectId . '">' . _l("add_project_support") . '</a>
                                        </div>';
                        }
                    }
                    if (!in_array($userRole, ['aa','at','ata','ar'])) {
                        $assignedAT = $this->projects_model->get_project_at($projectId);
                        $assignedUserDetails = $this->staff_model->get_userDetails($assignedAT);
                        $name = !empty($assignedUserDetails->full_name) ? $assignedUserDetails->full_name : '';
                        $phoneNumber = !empty($assignedUserDetails->phonenumber) ? $assignedUserDetails->phonenumber : '';
                        $action = '<div class="ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                                        <p class="action-label">'._l("call").' - 
                                            <span class="ata-name">' . $name . '</span>
                                        </p>
                                        <p class="action-label">'._l("clients_contact_number").' - 
                                            <span class="ata-name">' . $phoneNumber . '</span>
                                        </p>
                                    </div>';
                    }
                    // if (in_array($userRole, ['ar']) && !in_array($statusId, [3, 5])) {
                    //     $assignedAT = $this->projects_model->get_project_at($projectId);
                    //     $assignedUserDetails = $this->staff_model->get_userDetails($assignedAT);
                    //     $name = !empty($assignedUserDetails->full_name) ? $assignedUserDetails->full_name : '';
                    //     $phoneNumber = !empty($assignedUserDetails->phonenumber) ? $assignedUserDetails->phonenumber : '';
                    //     $action = '<div class="ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                    //                     <p class="action-label">'._l("call").' - 
                    //                         <span class="ata-name">' . $name . '</span>
                    //                     </p>
                    //                     <p class="action-label">'._l("clients_contact_number").' - 
                    //                         <span class="ata-name">' . $phoneNumber . '</span>
                    //                     </p>
                    //                 </div>';
                    // }


                    // $dueStatus = '';
                    // if (!in_array($statusId, [1, 3, 5])) {
                    //     // $status_name = 'WIP';
                    //     // $statusColor = "#e43b05";
                    //     // $statusBgColor = "#ffe0d6";
                    //     $dueDaysLeft = $this->projects_model->calculate_project_days_left($projectId);

                    //     $dueStatus = 'Due in '.$dueDaysLeft.' days';
                    // }

                    // if (!in_array($statusId, [3, 5]) && $item['action_date'] < date('Y-m-d')) {
                    //     $status_name = _l('total_delayed');
                    //     $statusColor = "#fc0332"; //red
                    //     $statusBgColor = "#ffd6de";
                    // }
                    if ($userRole == 'at') {
                        if ($statusId == 12 || $statusId == 11) {
                            $status_name = _l('total_pendingforapp');
                        }
                    } else {
                        if ($statusId == 12 || $statusId == 11) {
                            $status_name = _l('total_submitforapp');
                        }
                    }
                    if ($statusId == 1) {
                        $status_name = _l('total_assigned');
                    }

                    if ($statusId == 3) {
                        $status_name = _l('total_closed');
                    }

                    if ($statusId == 5) {
                        $status_name = _l('total_referred');
                    }
                    if ($statusId == 6) {
                        $status_name = _l('total_reopen');
                    }
                    if ($statusId == 7) {
                        $status_name = _l('total_delayed');
                    }

                    if ($statusId == 9) {
                        $status_name = _l('total_unassigned');
                    }
                    if ($statusId == 10) {
                        $status_name = _l('total_longterm');
                    }


                    $viewed_projects = $this->dashboard_model->getViewStatus($projectId);
                    if ($viewed_projects == 0) {
                        $action_items_refered_countview++;
                        $colorbg = '#eaf1fb';
                    } else {
                        $colorbg = '#fff';
                    }
                    $getSurvayerLatLong = $this->dashboard_model->getsarvayerLatLong($projectId);
                    $latitude = $getSurvayerLatLong['latitude'];
                    $longitude = $getSurvayerLatLong['longitude'];
                    $actionList .= '<div class="dashboard-row delayed project_' . $projectId . '" style="background-color:' . $colorbg . '">
                                        <div class="dashboard-cell w170 action-item ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                                            <p>
                                                
                                                <strong>Issue no.</strong>
                                                <span class="landmark" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . strip_tags($landmark) . '">' . $projectId . '</span>
                                            </p>
                                        </div>
                                        <div class="dashboard-cell w270 action-item ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                                            <p>
                                                <strong data-toggle="tooltip" data-placement="top" title="" data-original-title="' . strip_tags($project_name) . '">' . mb_strimwidth($project_name, 0, 30, '...') . '</strong>
                                                <span>near</span>
                                                <span class="landmark" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . strip_tags($landmark) . '">' . mb_strimwidth($landmark, 0, 30, '...') . '</span>
                                            </p>
                                        </div>
                                        <div class="dashboard-cell w170 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                                            <p class="deadline">' . $start_date . '</p>
                                            <span class="status status_' . $projectId . '" style="background-color:' . $statusBgColor . '; color:' . $statusColor . ';">' . $status_name . '</span>
                                        </div>
                                        <div class="dashboard-cell w270 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                                            <p data-toggle="tooltip" data-placement="top" title="' . strip_tags($description) . '">' . mb_strimwidth($description, 0, 100, '...') . '</p>
                                        </div>
                                        <div class="dashboard-cell evidence w170">
                                            <p class="evidence_img report-location" data-img_type="original" data-project_id="' . $projectId . '">
                                            <i class="fa fa-eye" aria-hidden="true"></i><span>View</span></p>
                                        </div>';
                                        $actionList .=  '<div class="dashboard-cell evidence w170">
                                        <p class="evidence_img report-location" >';
                                        if(!empty($latitude) && !empty($longitude) && $longitude!=0 && $latitude !=0){
                                            $actionList .=  '
                                            <a href="https://maps.google.com/maps?q='.$latitude.','.$longitude.'" class="mT10 text-center d-block" target="_blank"><i class="fa fa-map-marker"></i>View Location</a>
                                        ';
                                        }
                                        $actionList .=  '</p></div>';  
                    if (in_array($userRole, ['at', 'ar', 'aa','ata'])) {
                        $actionList .= '<div class="dashboard-cell action-cell w20P action_' . $projectId . '">' . $action . '</div>';
                    }

                    $actionList .= '</div><div class="row-saperator"></div>';
                }

                echo json_encode([
                    'success' => TRUE,
                    'message' => $actionList,
                    'action_items_refered_cnt' => $action_items_refered_cnt,
                    'action_items_refered_viewedcnt' => $action_items_refered_countview
                ]);
            } else {
                $actionList = '';
                echo json_encode([
                    'success' => TRUE,
                    'message' => $actionList,
                    'action_items_refered_cnt' => $action_items_refered_cnt,
                    'action_items_refered_viewedcnt' => $action_items_refered_countview
                ]);
            }
        } else {
            echo json_encode([
                'fail' => FALSE,
                'message' => 'Invalid request. Please try again.',
            ]);
        }
    }
    public function action_items_pending_forapproval()
    {
        if ($this->input->is_ajax_request()) {
            $userRole = $GLOBALS['current_user']->role_slug_url;
            $userId = $GLOBALS['current_user']->staffid;
            $userName = $GLOBALS['current_user']->full_name;
            $action_items_pendingforapproval_cntviewed = 0;
            $action_items_pendingforapproval_cnt = 0;
            // if (in_array($userRole, ['ar'])) {
            //     $action_items_pendingforapproval_cnt = $this->dashboard_model->total_ar_action_items();
            // } else {
                $action_items_pendingforapproval_cnt = $this->dashboard_model->get_action_item_pendingforapproval_counts();
            //}

            $action_items_pendingforapproval_cntviewed_current = $this->dashboard_model->get_action_item_notviewed_sendingforapproval_counts();
            if ($action_items_pendingforapproval_cnt > 0) {
                $action_items = array();
                // if (in_array($userRole, ['ar'])) {
                //     $action_items = $this->dashboard_model->get_ar_action_items();
                // } else {
                    $action_items = $this->dashboard_model->get_action_items_pendingforapproval();
                //}

                $actionList = '';
                $roles = array('at');
                $this->load->model('projects_model');
                $this->load->model('staff_model');

                foreach ($action_items as $key => $item) {
                    $project_name = !empty($item['project_name']) ? $item['project_name'] : '';
                    $landmark = !empty($item['landmark']) ? $item['landmark'] : '';
                    $reassigned = !empty($item['reassigned']) ? $item['reassigned'] : '';
                    $description = !empty($item['description']) ? $item['description'] : '';
                    $statusId = !empty($item['status']) ? $item['status'] : '';
                    $statusColor = !empty($item['color']) ? $item['color'] : '';
                    $statusBgColor = !empty($item['bg-color']) ? $item['bg-color'] : '';
                    $status_name = !empty($item['status_name']) ? $item['status_name'] : '';
                    $deadline = !empty($item['deadline']) ? setDateFormat($item['deadline']) : '';
                    $frozen = !empty($item['frozen']) ? $item['frozen'] : '';
                    $action_date = !empty($item['action_date']) ? setDateFormat($item['action_date']) : '';
                    //  $start_date = !empty($item['start_date']) ? date('Y-m-d', strtotime($item['start_date'])) : '';
                    $start_date = !empty($item['project_created']) ? date('d-M-Y h:i:s', strtotime($item['project_created'])) : '';
                    // $project_total_days = !empty($item['deadline']) ? date('d-M-Y', strtotime($item['deadline'])) : '';
                    $projectId = $item['project_id'];

                    
                    $assignedUser = getProjectAssignedUser($projectId);
                    if ($userRole == 'ata') {
                        $statusId = !empty($item['status_id']) ? $item['status_id'] : '';
                    }

                    $action = '';
                    if ($statusId == 1) {
                        //code commented for visible new project status
                        // if (in_array($userRole, $roles) && ($assignedUser == get_staff_user_id()) && empty($frozen)) {
                        //     $action = '<div class="btn-container">
                        //                     <a href="javascript:void(0)" class="btn accept-btn accept-btn-popup" data-projectid="' . $projectId . '">'._l("accept").'</a>
                        //                 </div>
                        //                 <div class="btn-container">
                        //                     <a href="javascript:void(0)" class="btn reject-btn reject-btn-popup" data-projectid="' . $projectId . '">'.
                        //                      ($GLOBALS['current_user']->role_slug_url =='at'? _l("total_referred"):_l("reject"))
                        //                     .'</a>
                        //                 </div>';
                        // } else {
                        //     $action = _l("total_assigned");
                        // }
                        // if ($reassigned) {
                        //     $status_name = _l("total_referred");
                        // }
                        // if (in_array($userRole, ['at', 'ar']) && $item['action_date'] <= date('Y-m-d')) {
                        //     // $status_name = 'Delayed'; //Unaccepted
                        //     // $statusColor = "#fc0332"; //red
                        //     // $statusBgColor = "#ffd6de";
                        // }
                        $action = _l("total_assigned");
                    }
                    // else if ($statusId == 2 || $statusId == 6) {
                    //     //$userRole != 'ata'
                    //     if ($userRole != '') {
                    //         // $ticketDetails = $this->projects_model->get_project_details($projectId);
                    //         // $assignedUser = !empty($ticketDetails->assigned_user_id) ? $ticketDetails->assigned_user_id : '';
                    //         $assignedUserDetails = $this->staff_model->get_userDetails($assignedUser);
                    //         $name = !empty($assignedUserDetails->full_name) ? $assignedUserDetails->full_name : '';
                    //         $phoneNumber = !empty($assignedUserDetails->phonenumber) ? $assignedUserDetails->phonenumber : '';
                    //         $organisation = !empty($assignedUserDetails->organisation) ? $assignedUserDetails->organisation : '';

                    //         if ($assignedUser == get_staff_user_id()) {
                    //             $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">Close Project</p>';
                    //         } else {
                    //             $action = '<div class="ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                    //                             <p class="action-label">Call - 
                    //                                 <span class="ata-name">' . $name . '</span>
                    //                             </p>
                    //                             <p class="action-label">Phone No - 
                    //                                 <span class="ata-name">' . $phoneNumber . '</span>
                    //                             </p>
                    //                         </div>';
                    //         }
                    //     } else {
                    //         $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">'._l("total_reopen").'</p>';
                    //     }
                    // } 
                    else if ($statusId == 3) {
                        $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">' . _l("total_closed") . '</p>';
                    } else if ($statusId == 4) {
                        $status_name = 'In Progress';
                        $statusColor = "#fc0332";
                        $statusBgColor = "#ffd6de";
                        $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">Review</p>';
                    } else if ($statusId == 5) {
                        if ($userRole == 'ar') {
                            $projectNotes = project_latest_notes($projectId, $statusId);
                            $projectNote_content = !empty($projectNotes->content) ? $projectNotes->content : '';
                            $task_id = !empty($projectNotes->task_id) ? $projectNotes->task_id : '';
                            $projectNote_status = !empty($projectNotes->status) ? $projectNotes->status : '';
                            $exception = (!empty($projectNotes->exception) && $projectNotes->exception != 6) ? getExceptionName($projectNotes->exception) : $projectNote_content;
                            $action = '<div class="ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                                            <p class="action-label">' . _l("total_referred") . '</p>
                                            <p class="ata-name">' . $projectNote_content . '</p>
                                        </div>';
                        } else {
                            $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">' . _l("total_referred") . '</p>';
                        }
                    } else if ($statusId == 6) {
                        //  $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">'._l("total_closed").'</p>';
                        $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">' . _l("total_reopen") . '</p>';
                    } else if ($statusId == 9) {
                        //  $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">'._l("total_closed").'</p>';
                        $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">' . _l("total_unassigned") . '</p>';
                    }
                    if (!empty($item['ward_id'])) {
                        if (empty($psIdCheck) && $statusId == 9 && $userRole == 'aa') {

                            $action = '<div class="btn-container">
                                            <a href="javascript:void(0)" class="btn accept-btn add-new-ps" data-projectid="' . $projectId . '">' . _l("add_project_support") . '</a>
                                        </div>';
                        }
                    }
                    if (!in_array($userRole, ['aa'])) {
                        $assignedAT = $this->projects_model->get_project_at($projectId);
                        $assignedUserDetails = $this->staff_model->get_userDetails($assignedAT);
                        $name = !empty($assignedUserDetails->full_name) ? $assignedUserDetails->full_name : '';
                        $phoneNumber = !empty($assignedUserDetails->phonenumber) ? $assignedUserDetails->phonenumber : '';
                        $action = '<div class="ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                                        <p class="action-label">'._l("call").' - 
                                            <span class="ata-name">' . $name . '</span>
                                        </p>
                                        <p class="action-label">'._l("clients_contact_number").' - 
                                            <span class="ata-name">' . $phoneNumber . '</span>
                                        </p>
                                    </div>';
                    }
                    // if (in_array($userRole, ['ar']) && !in_array($statusId, [3, 5])) {
                    //     $assignedAT = $this->projects_model->get_project_at($projectId);
                    //     $assignedUserDetails = $this->staff_model->get_userDetails($assignedAT);
                    //     $name = !empty($assignedUserDetails->full_name) ? $assignedUserDetails->full_name : '';
                    //     $phoneNumber = !empty($assignedUserDetails->phonenumber) ? $assignedUserDetails->phonenumber : '';
                    //     $action = '<div class="ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                    //                     <p class="action-label">'._l("call").' - 
                    //                         <span class="ata-name">' . $name . '</span>
                    //                     </p>
                    //                     <p class="action-label">'._l("clients_contact_number").' - 
                    //                         <span class="ata-name">' . $phoneNumber . '</span>
                    //                     </p>
                    //                 </div>';
                    // }


                    // $dueStatus = '';
                    // if (!in_array($statusId, [1, 3, 5])) {
                    //     // $status_name = 'WIP';
                    //     // $statusColor = "#e43b05";
                    //     // $statusBgColor = "#ffe0d6";
                    //     $dueDaysLeft = $this->projects_model->calculate_project_days_left($projectId);

                    //     $dueStatus = 'Due in '.$dueDaysLeft.' days';
                    // }

                    // if (!in_array($statusId, [3, 5]) && $item['action_date'] < date('Y-m-d')) {
                    //     $status_name = _l('total_delayed');
                    //     $statusColor = "#fc0332"; //red
                    //     $statusBgColor = "#ffd6de";
                    // }
                    if ($userRole == 'at') {
                        if ($statusId == 12 || $statusId == 11) {
                            $status_name = _l('total_pendingforapp');
                        }
                    } else {
                        if ($statusId == 12 || $statusId == 11) {
                            $status_name = _l('total_submitforapp');
                        }
                    }
                    if ($statusId == 1) {
                        $status_name = _l('total_assigned');
                    }

                    if ($statusId == 3) {
                        $status_name = _l('total_closed');
                    }

                    if ($statusId == 5) {
                        $status_name = _l('total_referred');
                    }
                    if ($statusId == 6) {
                        $status_name = _l('total_reopen');
                    }
                    if ($statusId == 7) {
                        $status_name = _l('total_delayed');
                    }

                    if ($statusId == 9) {
                        $status_name = _l('total_unassigned');
                    }
                    if ($statusId == 10) {
                        $status_name = _l('total_longterm');
                    }


                    $viewed_projects = $this->dashboard_model->getViewStatus($projectId);
                    if ($viewed_projects == 0) {
                        $colorbg = '#eaf1fb';
                        $action_items_pendingforapproval_cntviewed++;
                    } else {
                        $colorbg = '#fff';
                    }

                    $getSurvayerLatLong = $this->dashboard_model->getsarvayerLatLong($projectId);
                    $latitude = $getSurvayerLatLong['latitude'];
                    $longitude = $getSurvayerLatLong['longitude'];
                    $actionList .= '<div class="dashboard-row delayed project_' . $projectId . '" style="background-color:' . $colorbg . '">
                                        <div class="dashboard-cell w170 action-item ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                                            <p>
                                                
                                                <strong>Issue no.</strong>
                                                <span class="landmark" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . strip_tags($landmark) . '">' . $projectId . '</span>
                                            </p>
                                        </div>
                                        <div class="dashboard-cell w270 action-item ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                                            <p>
                                                <strong data-toggle="tooltip" data-placement="top" title="" data-original-title="' . strip_tags($project_name) . '">' . mb_strimwidth($project_name, 0, 30, '...') . '</strong>
                                                <span>near</span>
                                                <span class="landmark" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . strip_tags($landmark) . '">' . mb_strimwidth($landmark, 0, 30, '...') . '</span>
                                            </p>
                                        </div>
                                        <div class="dashboard-cell w170 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                                            <p class="deadline">' . $start_date . '</p>
                                            <span class="status status_' . $projectId . '" style="background-color:' . $statusBgColor . '; color:' . $statusColor . ';">' . $status_name . '</span>
                                        </div>
                                        <div class="dashboard-cell w270 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                                            <p data-toggle="tooltip" data-placement="top" title="' . strip_tags($description) . '">' . mb_strimwidth($description, 0, 100, '...') . '</p>
                                        </div>
                                        <div class="dashboard-cell evidence w170">
                                            <p class="evidence_img report-location" data-img_type="original" data-project_id="' . $projectId . '">
                                            <i class="fa fa-eye" aria-hidden="true"></i><span>View</span></p>
                                        </div>';
                                        $actionList .=  '<div class="dashboard-cell evidence w170">
                                        <p class="evidence_img report-location" >';
                                        if(!empty($latitude) && !empty($longitude) && $longitude!=0 && $latitude !=0){
                                            $actionList .=  '
                                            <a href="https://maps.google.com/maps?q='.$latitude.','.$longitude.'" class="mT10 text-center d-block" target="_blank"><i class="fa fa-map-marker"></i>View Location</a>
                                        ';
                                        }
                                        $actionList .=  '</p></div>';       

                    if (in_array($userRole, ['at', 'ar', 'aa','ata'])) {
                        $actionList .= '<div class="dashboard-cell action-cell w20P action_' . $projectId . '">' . $action . '</div>';
                    }

                    $actionList .= '</div><div class="row-saperator"></div>';
                }

                echo json_encode([
                    'success' => TRUE,
                    'message' => $actionList,
                    'action_items_pendingforapproval_cnt' => $action_items_pendingforapproval_cnt,
                    'action_items_pendingforapproval_cntviewed' => $action_items_pendingforapproval_cntviewed_current
                ]);
            } else {
                $actionList = '';
                echo json_encode([
                    'success' => TRUE,
                    'message' => $actionList,
                    'action_items_pendingforapproval_cnt' => $action_items_pendingforapproval_cnt,
                    'action_items_pendingforapproval_cntviewed' => $action_items_pendingforapproval_cntviewed_current
                ]);
            }
        } else {
            echo json_encode([
                'fail' => FALSE,
                'message' => 'Invalid request. Please try again.',
            ]);
        }
    }
    public function upcoming_deadline()
    {
        if ($this->input->is_ajax_request()) {
            $upcoming_deadline_data_cnt = $this->dashboard_model->next_week_deadline_count();
            if ($upcoming_deadline_data_cnt > 0) {
                $upcomingDeadlines = $this->dashboard_model->next_week_deadline();

                $actionList = '';
                $userRole = $GLOBALS['current_user']->role_slug_url;
                $roles = array('at');

                foreach ($upcomingDeadlines as $key => $item) {
                    $project_name = !empty($item['project_name']) ? $item['project_name'] : '';
                    $landmark = !empty($item['landmark']) ? $item['landmark'] : '';
                    $description = !empty($item['description']) ? $item['description'] : '';
                    $statusId = !empty($item['status']) ? $item['status'] : '';
                    $statusColor = !empty($item['color']) ? $item['color'] : '';
                    $statusBgColor = !empty($item['bg-color']) ? $item['bg-color'] : '';
                    $status_name = !empty($item['status_name']) ? $item['status_name'] : '';
                    // $deadline = !empty($item['deadline']) ? date('d-M-Y', strtotime($item['deadline'])) : '';
                    // $action_date = !empty($item['action_date']) ? date('d-M-Y', strtotime($item['action_date'])) : '';
                    $deadline = !empty($item['deadline']) ? setDateFormat($item['deadline']) : '';
                    $action_date = !empty($item['action_date']) ? setDateFormat($item['action_date']) : '';
                    $projectId = $item['project_id'];

                    $action = '';
                    if ($statusId == 2) {
                        if ($userRole != 'ata') {
                            $this->load->model('projects_model');
                            $ticketDetails = $this->projects_model->get_project_details($projectId);
                            $assignedUser = getProjectAssignedUser($projectId);

                            $this->load->model('staff_model');
                            $assignedUserDetails = $this->staff_model->get_userDetails($assignedUser);
                            $name = !empty($assignedUserDetails->full_name) ? $assignedUserDetails->full_name : '';
                            $phoneNumber = !empty($assignedUserDetails->phonenumber) ? $assignedUserDetails->phonenumber : '';
                            $organisation = !empty($assignedUserDetails->organisation) ? $assignedUserDetails->organisation : '';

                            if ($assignedUser == get_staff_user_id()) {
                                $action = '<p class="ata-name font14">Close</p>';
                            } else {
                                $action = '<div class="">
                                                <p class="action-label">Call - 
                                                    <span class="ata-name">' . $name . '</span>
                                                </p>
                                                <p class="action-label">Phone No - 
                                                    <span class="ata-name">' . $phoneNumber . '</span>
                                                </p>
                                            </div>';
                            }
                        } else {
                            $action = '<p class="ata-name font14">Review and Resolve</p>';
                        }
                    } else if ($statusId == 3) {
                        $action = '<p class="ata-name font14">Closed</p>';
                    } else if ($statusId == 4) {
                        $action = '<p class="ata-name font14">Review</p>';
                    } else if ($statusId == 5) {
                        $action = '<p class="ata-name font14">Rejected</p>';
                    } else if ($statusId == 6) {
                        $action = '<p class="ata-name font14">Reopened</p>';
                    }

                    $dueStatus = '';
                    if (in_array($statusId, [2, 4, 6])) {
                        // $dueDaysLeft = $this->projects_model->calculate_project_days_left($projectId);
                        $dueDaysLeft = dateDiffInDays($action_date, date('Y-m-d'));
                        if ($statusId == 4) {
                            //$status_name = $status_name . ' Due in ' . $dueDaysLeft . ' days';
                        } else {
                            $status_name = 'Due in ' . $dueDaysLeft . ' day(s)';
                        }
                    }

                    if ($statusId != 1 && $item['action_date'] < date('Y-m-d')) {
                        $status_name = 'Delayed';
                        $statusColor = "#fc0332"; //red
                        $statusBgColor = "#ffd6de";
                    }

                    $actionList .= '<div class="dashboard-row delayed project_' . $projectId . '">
                                        <div class="dashboard-cell w270 action-item ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                                        <p>
                                            <strong data-toggle="tooltip" data-placement="top" title="" data-original-title="' . strip_tags($project_name) . '">' . mb_strimwidth($project_name, 0, 30, '...') . '</strong>
                                            <span>near</span>
                                            <span class="landmark" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . strip_tags($landmark) . '">' . mb_strimwidth($landmark, 0, 30, '...') . '</span>
                                        </p>
                                        </div>
                                        <div class="dashboard-cell w170 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                                            <p class="deadline">' . $action_date . '</p>
                                            <span class="status status_' . $projectId . '" style="background-color:' . $statusBgColor . '; color:' . $statusColor . ';">' . $status_name . ' ' . $dueStatus . '</span>
                                        </div>
                                        <div class="dashboard-cell w270 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                                            <p data-toggle="tooltip" data-placement="top" title="' . strip_tags($description) . '">' . mb_strimwidth($description, 0, 100, '...') . '</p>
                                        </div>
                                        <div class="dashboard-cell evidence w170">
                                            <p class="evidence_img report-location" data-img_type="original" data-project_id="' . $projectId . '">
                                            <i class="fa fa-eye" aria-hidden="true"></i><span>View</span></p>
                                        </div>';

                    if (in_array($userRole, ['at', 'ar'])) {
                        $actionList .= '<div class="dashboard-cell action-cell w20P ticket_details  action_' . $projectId . '" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">' . $action . '</div>';
                    }

                    $actionList .= '</div><div class="row-saperator"></div>';
                }

                echo json_encode([
                    'success' => TRUE,
                    'message' => $actionList,
                    'upcoming_deadline_data_cnt' => $upcoming_deadline_data_cnt
                ]);
            } else {
                $actionList = '';
                echo json_encode([
                    'success' => TRUE,
                    'message' => $actionList,
                    'upcoming_deadline_data_cnt' => $upcoming_deadline_data_cnt
                ]);
            }
        } else {
            echo json_encode([
                'fail' => FALSE,
                'message' => 'Invalid request. Please try again.',
            ]);
        }
    }

    public function verifiedData()
    {
        if ($this->input->is_ajax_request()) {
            $verified_data_cnt = $this->dashboard_model->get_action_item_verified_counts();

            if ($verified_data_cnt > 0) {
                $verified_data = $this->dashboard_model->get_action_item_verified();

                $actionList = '';
                $userRole = $GLOBALS['current_user']->role_slug_url;
                $roles = array('qc','aa');

                foreach ($verified_data as $key => $item) {
                    $project_name = !empty($item['project_name']) ? $item['project_name'] : '';
                    $landmark = !empty($item['landmark']) ? $item['landmark'] : '';
                    $description = !empty($item['description']) ? $item['description'] : '';
                    $statusId = !empty($item['status']) ? $item['status'] : '';
                    $statusColor = !empty($item['color']) ? $item['color'] : '';
                    $statusBgColor = !empty($item['bg-color']) ? $item['bg-color'] : '';
                    $status_name = !empty($item['status_name']) ? $item['status_name'] : '';
                    $start_date = !empty($item['project_created']) ? date('d-M-Y h:i:s', strtotime($item['project_created'])) : '';
                    $deadline = !empty($item['deadline']) ? setDateFormat($item['deadline']) : '';
                    $action_date = !empty($item['action_date']) ? setDateFormat($item['action_date']) : '';
                    $projectId = $item['project_id'];

                    
                    if ($statusId == 2) {
                        if ($userRole != 'ata') {
                            $this->load->model('projects_model');
                            $ticketDetails = $this->projects_model->get_project_details($projectId);
                            $assignedUser = getProjectAssignedUser($projectId);

                            $this->load->model('staff_model');
                            $assignedUserDetails = $this->staff_model->get_userDetails($assignedUser);
                            $name = !empty($assignedUserDetails->full_name) ? $assignedUserDetails->full_name : '';
                            $phoneNumber = !empty($assignedUserDetails->phonenumber) ? $assignedUserDetails->phonenumber : '';
                            $organisation = !empty($assignedUserDetails->organisation) ? $assignedUserDetails->organisation : '';

                            if ($assignedUser == get_staff_user_id()) {
                                $action = '<p class="ata-name font14">Close</p>';
                            } else {
                                $action = '<div class="">
                                                <p class="action-label">Call - 
                                                    <span class="ata-name">' . $name . '</span>
                                                </p>
                                                <p class="action-label">Phone No - 
                                                    <span class="ata-name">' . $phoneNumber . '</span>
                                                </p>
                                            </div>';
                            }
                        } else {
                            $action = '<p class="ata-name font14">Review and Resolve</p>';
                        }
                    } else if ($statusId == 3) {
                        $action = '<p class="ata-name font14">Closed</p>';
                    } else if ($statusId == 4) {
                        $action = '<p class="ata-name font14">Review</p>';
                    } else if ($statusId == 5) {
                        $action = '<p class="ata-name font14">Rejected</p>';
                    } else if ($statusId == 6) {
                        $action = '<p class="ata-name font14">Reopened</p>';
                    }

                    $dueStatus = '';
                    // if (in_array($statusId, [2, 4, 6])) {
                    //     // $dueDaysLeft = $this->projects_model->calculate_project_days_left($projectId);
                    //     $dueDaysLeft = dateDiffInDays($action_date, date('Y-m-d'));
                    //     if ($statusId == 4) {
                    //         //$status_name = $status_name . ' Due in ' . $dueDaysLeft . ' days';
                    //     } else {
                    //         $status_name = 'Due in ' . $dueDaysLeft . ' day(s)';
                    //     }
                    // }

                    // if ($statusId != 1 && $item['action_date'] < date('Y-m-d')) {
                    //     $status_name = 'Delayed';
                    //     $statusColor = "#fc0332"; //red
                    //     $statusBgColor = "#ffd6de";
                    // }

                    // $actionList .= '<div class="dashboard-row delayed project_' . $projectId . '">
                    //                     <div class="dashboard-cell w270 action-item ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                    //                     <p>
                    //                         <strong data-toggle="tooltip" data-placement="top" title="" data-original-title="' . strip_tags($project_name) . '">' . mb_strimwidth($project_name, 0, 30, '...') . '</strong>
                    //                         <span>near</span>
                    //                         <span class="landmark" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . strip_tags($landmark) . '">' . mb_strimwidth($landmark, 0, 30, '...') . '</span>
                    //                     </p>
                    //                     </div>
                    //                     <div class="dashboard-cell w170 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                    //                         <p class="deadline">' . $action_date . '</p>
                    //                         <span class="status status_' . $projectId . '" style="background-color:' . $statusBgColor . '; color:' . $statusColor . ';">' . $status_name . ' ' . $dueStatus . '</span>
                    //                     </div>
                    //                     <div class="dashboard-cell w270 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                    //                         <p data-toggle="tooltip" data-placement="top" title="' . strip_tags($description) . '">' . mb_strimwidth($description, 0, 100, '...') . '</p>
                    //                     </div>
                    //                     <div class="dashboard-cell evidence w170">
                    //                         <p class="evidence_img report-location" data-img_type="original" data-project_id="' . $projectId . '">
                    //                         <i class="fa fa-eye" aria-hidden="true"></i><span>View</span></p>
                    //                     </div>';
                    if (!in_array($userRole, ['at', 'ar'])) {
                        $assignedAT = $this->projects_model->get_project_at($projectId);
                        $assignedUserDetails = $this->staff_model->get_userDetails($assignedAT);
                        $name = !empty($assignedUserDetails->full_name) ? $assignedUserDetails->full_name : '';
                        $phoneNumber = !empty($assignedUserDetails->phonenumber) ? $assignedUserDetails->phonenumber : '';
                        $action = '<div class="ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                                        <p class="action-label">'._l("call").' - 
                                            <span class="ata-name">' . $name . '</span>
                                        </p>
                                        <p class="action-label">'._l("clients_contact_number").' - 
                                            <span class="ata-name">' . $phoneNumber . '</span>
                                        </p>
                                    </div>';
                    }
                    
                    $viewed_projects = $this->dashboard_model->getViewStatus($projectId);
                    
                    if ($viewed_projects == 0) {
                        $colorbg = '#fff';
                    } else {
                        $colorbg = '#fff';
                    }
                    $getSurvayerLatLong = $this->dashboard_model->getsarvayerLatLong($projectId);
                    $latitude = $getSurvayerLatLong['latitude'];
                    $longitude = $getSurvayerLatLong['longitude'];
                    
                    $actionList .= '<div class="dashboard-row delayed project_' . $projectId . '" style="background-color:' . $colorbg . '">
                    <div class="dashboard-cell w270 action-item ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                        <p>
                            
                            <strong>Issue no.</strong>
                            <span class="landmark" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . strip_tags($landmark) . '">' . $projectId . '</span>
                        </p>
                    </div>
                    <div class="dashboard-cell w270 action-item ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                        <p>
                            <strong data-toggle="tooltip" data-placement="top" title="" data-original-title="' . strip_tags($project_name) . '">' . mb_strimwidth($project_name, 0, 30, '...') . '</strong>
                            <span>near</span>
                            <span class="landmark" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . strip_tags($landmark) . '">' . mb_strimwidth($landmark, 0, 30, '...') . '</span>
                        </p>
                    </div>
                    <div class="dashboard-cell w170 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                        <p class="deadline">' . $start_date . '</p>
                        <span class="status status_' . $projectId . '" style="background-color:' . $statusBgColor . '; color:' . $statusColor . ';">' . $status_name . '</span>
                    </div>
                    <div class="dashboard-cell w270 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                        <p data-toggle="tooltip" data-placement="top" title="' . strip_tags($description) . '">' . mb_strimwidth($description, 0, 100, '...') . '</p>
                    </div>
                    <div class="dashboard-cell evidence w170">
                        <p class="evidence_img report-location" data-img_type="original" data-project_id="' . $projectId . '">
                        <i class="fa fa-eye" aria-hidden="true"></i><span>View</span></p>
                    </div>';
                    $actionList .=  '<div class="dashboard-cell evidence w170">
                    <p class="evidence_img report-location" >';
                    if(!empty($latitude) && !empty($longitude) && $longitude!=0 && $latitude !=0){
                        $actionList .=  '
                        <a href="https://maps.google.com/maps?q='.$latitude.','.$longitude.'" class="mT10 text-center d-block" target="_blank"><i class="fa fa-map-marker"></i>View Location</a>
                    ';
                    }
                    $actionList .=  '</p></div>';
                    if (!in_array($userRole, ['at', 'ar'])) {
                        $actionList .= '<div class="dashboard-cell action-cell w20P ticket_details  action_' . $projectId . '" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">' . $action . '</div>';
                    }

                    $actionList .= '</div><div class="row-saperator"></div>';
                }

                echo json_encode([
                    'success' => TRUE,
                    'message' => $actionList,
                    'upcoming_deadline_data_cnt' => $verified_data_cnt
                ]);
            } else {
                $actionList = '';
                echo json_encode([
                    'success' => TRUE,
                    'message' => $actionList,
                    'upcoming_deadline_data_cnt' => $verified_data_cnt
                ]);
            }
        } else {
            echo json_encode([
                'fail' => FALSE,
                'message' => 'Invalid request. Please try again.',
            ]);
        }
    }

    //Dashboard widget data
    public function dashboard_widget_data()
    {
        if ($this->input->is_ajax_request()) {
            $dashboard_widget_data = $this->dashboard_model->get_dashboard_widget_data();
            //print_r($dashboard_widget_data);
            $escalated = !empty($dashboard_widget_data->escalated) ? $dashboard_widget_data->escalated : 0;
            $new = !empty($dashboard_widget_data->new) ? $dashboard_widget_data->new : 0;
            $reopen = !empty($dashboard_widget_data->reopen) ? $dashboard_widget_data->reopen : 0;
            $longterm = !empty($dashboard_widget_data->longterm) ? $dashboard_widget_data->longterm : 0;
            $submitforapproval_total = !empty($dashboard_widget_data->submitforapproval_total) ? $dashboard_widget_data->submitforapproval_total : 0;
            $ongoing = !empty($dashboard_widget_data->ongoing) ? $dashboard_widget_data->ongoing : 0;
            $closed = !empty($dashboard_widget_data->closed) ? $dashboard_widget_data->closed : 0;
            $rejected = !empty($dashboard_widget_data->rejected) ? $dashboard_widget_data->rejected : 0;
            $total_activity = $escalated + $new + $ongoing + $closed + $rejected;
            $action_items = 0;
            $upcoming_deadline = 0;
            $recently_closed = 0;

            if (in_array($GLOBALS['current_user']->role_slug_url, ['at', 'ata', 'aa'])) {
                $action_items = $this->dashboard_model->get_action_item_counts();
                $upcoming_deadline = $this->dashboard_model->next_week_deadline_count();
            } else if (in_array($GLOBALS['current_user']->role_slug_url, ['ar'])) {
                $action_items = $this->dashboard_model->total_ar_action_items();
                $recently_closed = $this->dashboard_model->total_ar_closed_tickets();
            }

            $widgetData = array(
                'escalated' => $escalated,
                'new' => $new,
                'ongoing' => $ongoing,
                'closed' => $closed,
                'reopen' => $reopen,
                'longterm' => $longterm,
                'submitforapproval_total' => $submitforapproval_total,
                'rejected' => $rejected,
                'total_activity' => $total_activity,
                'action_items' => $action_items,
                'upcoming_deadline' => $upcoming_deadline,
                'recently_closed' => $recently_closed
            );
            echo json_encode($widgetData);
            die;
        }
    }

    public function evidence_image()
    {
        if ($this->input->is_ajax_request()) {
            $data['project_id'] = $projectId = !empty($_GET['projectId']) ? $_GET['projectId'] : '';

            $data['evidenceImages'] = $this->dashboard_model->get_evidence_image($projectId, '', 1); //1=>original Image

            //Resolved Milestone Images
            $this->load->model('report_model');
            $milestone = $this->report_model->get_current_milestone($projectId);
            $milestone = !empty($milestone[0]) ? $milestone[0] : '';

            $projectStatus = $this->projects_model->getProjectStatus($projectId);
            if (in_array($projectStatus, [2, 4, 6])) {
                $resolvedMilestone = $this->report_model->get_current_milestone($projectId, 4);
                // $milestone['task_id'] = $resolvedMilestone[0]['task_id'];
                $milestone['task_id'] = !empty($resolvedMilestone[0]['task_id']) ? $resolvedMilestone[0]['task_id'] : (!empty($milestone['task_id']) ? $milestone['task_id'] : '');
            }

            $taskId = (!empty($milestone['task_id'])) ? $milestone['task_id'] : '';
            // $data['latestImages'] = $this->dashboard_model->get_evidence_image($projectId, $taskId, 2);//2=>Latest Image
            $data['latestImages'] = !empty($taskId) ? $this->dashboard_model->get_evidence_image($projectId, $taskId) : ''; //2=>Latest Image
            $this->dashboard_model->updateViewStatus($projectId);

            $this->load->view('admin/tickets/evidence_image', $data);
        }
    }

    public function evidence_location()
    {
        if ($this->input->is_ajax_request()) {
            $projectId = !empty($_GET['projectId']) ? $_GET['projectId'] : '';
            $fileId = !empty($_GET['fileId']) ? $_GET['fileId'] : '';
            $evidenceLocData = '';
            $noLocation = '<div class="col-lg-12 mB30">
                                <ul class="detail-list issue-detail-list mB20">
                                    <li class="mT0"><label></label><span>No Location data found.</span></li>
                                </ul>
                            </div>';

            if (!empty($projectId)) {
                $evidenceLocation = $this->dashboard_model->get_evidence_location($projectId, $fileId);

                $evidenceLocData .= '<div class="row original-images">
                                        <h4 class="modal-title">
                                            <span class="add-title">Location</span>
                                        </h4>
                                    <hr class="hr-panel-model">';
                if (!empty($evidenceLocation)) {
                    $latitude = $evidenceLocation->latitude;
                    $longitude = $evidenceLocation->longitude;
                    $address = $evidenceLocation->address;
                    if (!empty($latitude) && !empty($longitude)) {
                        $evidenceLocData .= '<div class="col-lg-12 mB30">
                                                <ul class="detail-list issue-detail-list mB20">
                                                    <li class="mT0"><label>Location: </label><span>' . $address . '</span></li>
                                                </ul>
                                            <iframe width="100%" height="500" style="border: 1px solid blue;" src="https://maps.google.com/maps?q=' . $latitude . ',' . $longitude . '&output=embed"></iframe>
                                        </div>';
                    } else {
                        $evidenceLocData .= $noLocation;
                    }
                } else {
                    $evidenceLocData .= $noLocation;
                }
                $evidenceLocData .= '</div>';
            }

            echo $evidenceLocData;
            die();
        }
    }

    public function recently_closed()
    {
        if ($this->input->is_ajax_request()) {
            $recently_closed_cnt = $this->dashboard_model->total_ar_closed_tickets();

            if ($recently_closed_cnt > 0) {
                $recentlyClosed = $this->dashboard_model->recently_ar_closed_tickets();

                $actionList = '';
                $userRole = $GLOBALS['current_user']->role_slug_url;
                $roles = array('at');

                foreach ($recentlyClosed as $key => $item) {
                    $project_name = !empty($item['project_name']) ? $item['project_name'] : '';
                    $landmark = !empty($item['landmark']) ? $item['landmark'] : '';
                    $description = !empty($item['description']) ? $item['description'] : '';
                    $statusId = !empty($item['status_id']) ? $item['status_id'] : '';
                    $statusColor = !empty($item['color']) ? $item['color'] : '';
                    $status_name = !empty($item['status_name']) ? $item['status_name'] : '';
                    $deadline = !empty($item['deadline']) ? setDateFormat($item['deadline']) : '';
                    $action_date = !empty($item['action_date']) ? setDateFormat($item['action_date']) : '';
                    $date_finished = !empty($item['date_finished']) ? setDateFormat($item['date_finished']) : '';
                    $projectId = $item['project_id'];
                    $sub_ticket_id = !empty($item['sub_ticket_id']) ? $item['sub_ticket_id'] : '';

                    $assignedUser = getProjectAssignedUser($projectId);

                    $activeUser = true;
                    //check if assigned AT is disable or not
                    $assignedUser = $this->report_model->get_report_leader($projectId);
                    if (empty($assignedUser->staff_status)) {
                        $activeUser = false;
                    }

                    // $projectNotes = project_latest_notes($projectId, 4);
                    $projectNotes = project_latest_notes($projectId);
                    $projectNote_content = !empty($projectNotes->content) ? $projectNotes->content : '';
                    // $task_id = !empty($projectNotes->task_id) ? $projectNotes->task_id : '';
                    // $projectNote_status = !empty($projectNotes->status) ? $projectNotes->status : '';
                    // $exception = (!empty($projectNotes->exception) && $projectNotes->exception != 6) ? getExceptionName($projectNotes->exception) : $projectNote_content;

                    $actionList .= '<div class="dashboard-row delayed project_' . $projectId . '">
                                    <div class="dashboard-cell w270 action-item ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                                        <p>
                                            <strong data-toggle="tooltip" data-placement="top" title="" data-original-title="' . strip_tags($project_name) . '">' . mb_strimwidth($project_name, 0, 30, '...') . '</strong>
                                            <span>near</span>
                                            <span class="landmark" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . strip_tags($landmark) . '">' . mb_strimwidth($landmark, 0, 30, '...') . '</span>
                                        </p>
                                    </div>
                                    <div class="dashboard-cell w120 ticket_details  status_' . $projectId . '" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                                        <p>' . $date_finished . '</p>
                                    </div>
                                    <div class="dashboard-cell w270 ticket_details  status_' . $projectId . '" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                                        <p data-toggle="tooltip" data-placement="top" title="' . strip_tags($description) . '">' . mb_strimwidth($description, 0, 100, '...') . '</p>
                                    </div>
                                    <div class="dashboard-cell w270 ticket_details  status_' . $projectId . '" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
                                        <p data-toggle="tooltip" data-placement="top" title="' . strip_tags($projectNote_content) . '">' . mb_strimwidth($projectNote_content, 0, 100, '...') . '</p>
                                    </div>
                                    <div class="dashboard-cell evidence w170">
                                        <p class="evidence_img report-location" data-img_type="original" data-project_id="' . $projectId . '">
                                        <i class="fa fa-eye" aria-hidden="true"></i><span>View</span></p>
                                    </div>
                                    <div class="dashboard-cell evidence w20P ticket_details  status_' . $projectId . '" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">';
                    if ($userRole == 'ar' && ($statusId == 3 && (get_staff_user_id() != $assignedUser)) && empty($sub_ticket_id) && $activeUser) {
                        $actionList .= '<a href="javascript:void(0)" class="btn reject-btn" data-projectid="' . $projectId . '">Reopen</a>';
                    } else {
                        $actionList .= '<p class="text-center">NA</p>';
                    }
                    $actionList .= '</div>
                                </div>
                                <div class="row-saperator"></div>';
                }

                echo json_encode([
                    'success' => TRUE,
                    'message' => $actionList,
                    'recently_closed_cnt' => $recently_closed_cnt
                ]);
            } else {
                $actionList = '';
                echo json_encode([
                    'success' => TRUE,
                    'message' => $actionList,
                    'recently_closed_cnt' => $recently_closed_cnt
                ]);
            }
        } else {
            echo json_encode([
                'fail' => FALSE,
                'message' => 'Invalid request. Please try again.',
            ]);
        }
    }

    public function get_region()
    {
        if ($this->input->post()) {
            $post_data = $this->input->post();
            $tableParams = [
                'category' => (!empty($post_data['category'])) ? $post_data['category'] : '',
                'area_id' => (!empty($post_data['area_id'])) ? $post_data['area_id'] : '',
            ];
            $region_list = $this->dashboard_model->get_region($tableParams);

            if (count($region_list) > 0) {
                echo json_encode([
                    'success' => true,
                    'message' => "Successfully fetched the region list.",
                    'region_list' => $region_list
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

    public function get_chart_data($area = null)
    {
        $data = [];
        if ($this->input->post()) {
            $area = $this->input->post('area');
            $data = $this->input->post();
        }
        // $data = $this->dashboard_model->get_chart_data($area, $data);
        $cats = [];
        if (!empty($data['category'])) {
            if (!empty($data['category'][0])) {

                if (strpos($data['category'][0], ",") != false) {
                    $cats = explode(",", $data['category'][0]);
                } else {
                    $cats = $data['category'];
                }
            } else {
                $cats = [];
            }
        }

        $range = [];
        if (!empty($data['duration'])) {
            if (!empty($data['duration'][0])) {
                if (strpos($data['duration'][0], ",") != false) {
                    $range = explode(",", $data['duration'][0]);
                } else {
                    $range = $data['duration'];
                }
            } else {
                $range = [];
            }
        }

        $tableParams = [
            'category' => (!empty($cats)) ? $cats : '',
            'report_date' => (!empty($data['report_months'])) ? $data['report_months'] : '',
            'to_date' => (!empty($data['report_to'])) ? $data['report_to'] : '',
            'from_date' => (!empty($data['report_from'])) ? $data['report_from'] : '',
            'statusIds' => [1, 2, 3, 5, 7, 8, 9],
            'duration' => (!empty($range)) ? $range : '',
        ];
        if ($area) {
            $tableParams['areaid'] = [$area];
        }


        $data = $this->atr_report_model->get_report_summary($tableParams);
        if (!empty($data)) {
            //print_r($data);
            $chart_data = [['Task', 'Project Status']];
            foreach ($data[0] as $key => $value) {
                $value = (int) $value;
                
                if ($key == "new") {
                    array_push($chart_data, ["Assigned", $value]);
                } else if ($key == "close") {
                    array_push($chart_data, ["Closed", $value]);
                } else if ($key == "reopen") {
                    array_push($chart_data, ["Re-open", $value]);
                } else if ($key == "referred") {
                    array_push($chart_data, ["Referred", $value]);
                } else if ($key == "longterm") {
                    array_push($chart_data, ["Long Term", $value]);
                } else if ($key == "unassign") {
                    array_push($chart_data, ["Unassigned", $value]);
                } else if ($key == "total_pendingforapp") {
                    array_push($chart_data, ["Pending for approval", $value]);
                }else if ($key == "verified") {
                    array_push($chart_data, ["Verified", $value]);
                }
            }
            echo json_encode([
                "success" => true,
                "message" => "chart data fetched successfully",
                "data" => $chart_data
            ]);
            die;
        }
        echo json_encode([
            "success" => false,
            "message" => "failed to fetch chart data",
            "data" => []
        ]);
        die;
    }

    public function download_export()
    {
        $data = $this->prepare_export_data();
        $data['report_date'] = (!empty($data['tableParams']['report_date'])) ? $data['tableParams']['report_date'] : '';
        $filename = (empty($data['tableParams']['area_name'])) ? 'Summary of India' : 'Summary of ' . $data['tableParams']['area_name'];
        header('Content-Type: application/xls; charset=binary');
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-disposition: attachment; filename=' . $filename . '.xls');
        header("Content-Transfer-Encoding: binary ");
        $data['filename'] = $filename;

        //$data['html'] = '';
        $html = $this->load->view('admin/dashboard/aeg_projects_pdf_excel', $data, true);
        echo $html;
        die();
        pre($html);
        $title = ["Area Name12", "New", "Escalated", "Closed", "In Progress", "Total"];
        $f = fopen('php://output', 'w');
        fputcsv($f, $title);
        foreach ($data['statuses'] as $key => $line) {
            if ($line["areaid"] != "") {
                unset($line["areaid"]);
            }
        }
    }

    public function download_ae_export()
    {
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="ae_projects_summary.csv";');
        $area = $this->input->get("area");
        $data = $this->load_sub_dashboard($area, true);
        $title = ["Name", "New", "Escalated", "Closed", "In Progress", "Total"];
        $f = fopen('php://output', 'w');
        fputcsv($f, $title);
        $temp_arr = [];
        foreach ($data['statuses'] as $key => $line) {
            unset($line["region_id"]);
            $temp_arr = $line;
            unset($temp_arr["sub_region_status"]);
            fputcsv($f, $temp_arr);
            if (!empty($line["sub_region_status"])) {
                fputcsv($f, ["--------", "--------", "--------", "--------", "--------", "--------"]);
                foreach ($line["sub_region_status"] as $val) {
                    unset($val["sub_region_id"]);
                    fputcsv($f, $val);
                }
                fputcsv($f, ["--------", "--------", "--------", "--------", "--------", "--------"]);
            }
        }
    }

    public function prepare_export_data()
    {
        $post_data = $this->input->get();

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
        $tableParams = [
            'category' => (!empty($cats)) ? $cats : '',
            'report_date' => (!empty($post_data['report_months'])) ? $post_data['report_months'] : '',
            'to_date' => (!empty($post_data['report-to'])) ? $post_data['report-to'] : '',
            'from_date' => (!empty($post_data['report-from'])) ? $post_data['report-from'] : '',
            'statusIds' => [1, 2, 3, 5, 7, 8, 9],
            'duration' => (!empty($range)) ? $range : '',
        ];

        if (!empty($post_data['area'])) {
            $tableParams['areaid'] = [$post_data['area']];
            $data = $this->prepare_summary_data($post_data['area']);
        } else {
            $data = $this->prepare_summary_data();
        }

        $data['html'] = $this->get_filter_table($post_data);
        $summary = $this->report_model->get_report_summary($tableParams);

        $this->load->model("region_model");
        if (!empty($data['statuses'])) {

            foreach ($data['statuses'] as $key => $area) {
                $data['statuses'][$key]["region_status"] = [];

                $regions = $this->region_model->get_region(["area_id" => $area['areaid']]);

                foreach ($regions as $region) {
                    $tableParams["region_list"] = $region["id"];

                    $region_data = $this->dashboard_model->get_ae_global($tableParams);

                    if (!empty($region_data))

                        array_push($data['statuses'][$key]["region_status"], $region_data[0]);


                    if (!empty($data['statuses'][$key]['region_status'])) {
                        $this->load->model("subregion_model");
                        $sub_regions = [];
                        foreach ($data['statuses'][$key]['region_status'] as $keys => $status) {
                            $data['statuses'][$key]['region_status'][$keys]["sub_region_status"] = [];
                            // get sub-regions on the basis of region id


                            $sub_region = $this->subregion_model->get_subregion_collection(["region_id" => $status["region_id"]]);
                            $tableParams["region_list"] = [];

                            foreach ($sub_region as $region) {
                                array_push($sub_regions, $region["id"]);
                                $tableParams["sub_region_list"] = $region["id"];
                                $sub_region_data = $this->dashboard_model->get_ae_global($tableParams);

                                if (!empty($sub_region_data))
                                    array_push($data['statuses'][$key]['region_status'][$keys]["sub_region_status"], $sub_region_data[0]);
                            }
                            $tableParams["sub_region_list"] = [];
                        }
                    }
                }
            }
        }

        $data['selected_regions'] = "";
        if (!empty($data['statuses']) & !empty($data['statuses'][0]['region_status'])) {
            foreach ($data['statuses'][0]['region_status'] as $index => $selected_region) {
                if ($index == 0)
                    $data['selected_regions'] = $selected_region['region_name'];
                else
                    $data['selected_regions'] = $data['selected_regions'] . ", " . $selected_region['region_name'];
            }
        }

        $data['chart_data'] = [];
        if (!empty($summary)) {
            $data['chart_data'] = $summary[0];
        }

        return $data;
    }

    public function create_aeg_pdf()
    {
        $data = $this->prepare_export_data();
        $data['report_date'] = (!empty($data['tableParams']['report_date'])) ? $data['tableParams']['report_date'] : '';
        $filename = (empty($data['tableParams']['area_name'])) ? 'Summary of India' : 'Summary of ' . $data['tableParams']['area_name'];
        $this->load->library('pdf');
        $data['filename'] = $filename;
        $html = $this->load->view('admin/dashboard/aeg_projects_pdf', $data, true);
        $this->pdf->createPDF($html, $filename);
        die();
    }

    public function create_ae_pdf()
    {
        $area = $this->input->get('area');
        $data = $this->prepare_summary_data($area);
        $this->load->model('area_model');
        $area_data = $this->area_model->get_area(["areaid" => $area]);
        $data["area_name"] = !empty($area_data) ? $area_data[0]['name'] : "";

        if (!empty($data['statuses'])) {
            $this->load->model("region_model");
            $regions = $this->region_model->get_region(['area_id' => $area]);
            $region_summary = [];
            foreach ($regions as $region) {
                $tableParams["region_list"] = $region["id"];
                $region_data = $this->dashboard_model->get_ae_global($tableParams);
                if (!empty($region_data))
                    array_push($region_summary, $region_data[0]);
            }
            // get statuses on the basis of regions
            if (!empty($region_summary))
                $data['statuses'] = $region_summary;

            if (!empty($data['statuses'])) {

                $this->load->model("subregion_model");
                $sub_regions = [];
                foreach ($data['statuses'] as $key => $status) {
                    $data['statuses'][$key]["sub_region_status"] = [];
                    // get sub-regions on the basis of region id
                    $sub_region = $this->subregion_model->get_subregion_collection(["region_id" => $status["region_id"]]);
                    $tableParams["region_list"] = [];
                    foreach ($sub_region as $region) {
                        array_push($sub_regions, $region["id"]);
                        $tableParams["sub_region_list"] = $region["id"];
                        $sub_region_data = $this->dashboard_model->get_ae_global($tableParams);
                        if (!empty($sub_region_data))
                            array_push($data['statuses'][$key]["sub_region_status"], $sub_region_data[0]);
                    }
                }
            }
        }

        $summary = $this->dashboard_model->get_chart_data($area, $data);
        $data['chart_data'] = [];
        if (!empty($summary)) {
            $total = 0;
            foreach ($summary[0] as $val) {
                $total = $total + $val;
            }
            $summary[0]['total'] = $total;
            $data['chart_data'] = $summary[0];
        }
        $this->load->library('pdf');
        $html = $this->load->view('admin/dashboard/ae_projects_pdf', $data, true);
        $this->pdf->createPDF($html, 'AE - Projects Summary');
        die();
    }

    public function get_filter_table($post_data)
    {
        $role = $GLOBALS['current_user']->role;
        $tableParams = [

            'category' => (!empty($post_data['category'])) ? $post_data['category'] : '',
            'report_date' => (!empty($post_data['report_months'])) ? $post_data['report_months'] : '',
            'to_date' => (!empty($post_data['report-to'])) ? $post_data['report-to'] : '',
            'from_date' => (!empty($post_data['report-from'])) ? $post_data['report-from'] : '',
            'duration' => (!empty($post_data['duration'])) ? $post_data['duration'] : '',
        ];

        $html = array();


        if (!empty($post_data['duration'])) {
            $duration_name = $this->report_model->get_duration_name($post_data['duration']);
            $html['duration_name'] = $duration_name;
        } else {
            $html['duration_name'] = "";
        }

        if ($tableParams['category']) {
            $category = $this->report_model->get_category_name($tableParams['category']);
            $html['category'] = $category;
        } else {
            $html['category'] = "";
        }


        if ($tableParams['report_date'] && $tableParams['report_date'] != 'custom') {

            $time = "";
            if ($tableParams['report_date'] == 'this_month') {
                $time = "This Month (" . date('01-m-Y') . " - " . date('t-m-Y') . ")";
            } else if ($tableParams['report_date'] == 'last_month') {
                $time = "Last Month (" . date('01-m-Y', strtotime("-1 MONTH")) . " - " . date('t-m-Y', strtotime("-1 MONTH")) . ")";
            } else if ($tableParams['report_date'] == 'this_year') {
                $time = "This Year (" . date('01-01-Y') . " - " . date('31-12-Y') . ")";
            } else if ($tableParams['report_date'] == 'last_year') {
                $time = "Last Year (" . date('01-01-Y', strtotime("-1 YEAR")) . " - " . date('31-12-Y', strtotime("-1 YEAR")) . ")";
            } else if ($tableParams['report_date'] == '3') {
                $time = "Last 3 Months (" . date('01-m-Y', strtotime("-2 MONTH")) . " - " . date('t-m-Y') . ")";
            } else if ($tableParams['report_date'] == '6') {
                $time = "Last 6 Months (" . date('01-m-Y', strtotime("-5 MONTH")) . " - " . date('t-m-Y') . ")";
            } else if ($tableParams['report_date'] == '12') {
                $time = "Last 12 Months (" . date('01-m-Y', strtotime("-11 MONTH")) . " - " . date('t-m-Y') . ")";
            }


            $html['duration'] = $time;
        } else if (!empty($tableParams['report_date']) && $tableParams['report_date'] == 'custom' && !empty($tableParams['to_date']) && !empty($tableParams['from_date'])) {
            $html['duration'] = "Custom Period (" . $tableParams['from_date'] . " - " . $tableParams['to_date'] . ")";
            // $html['duration'] = $time;
        } else {
            $html['duration'] = "";
        }

        if (empty($html['category']) && empty($html['duration_name']) && empty($html['duration'])) {
            $table = 'This report contains projects across <b>all values</b> as filtered.';
        } else {
            $table = 'This report contains projects across the following values as filtered:<br/><br/>';

            $table = '<caption>' . $table . '</caption>';
            $table .= '<table border="1"><thead>
            <tr><th>Field</th><th colspan="4">Values</th></tr> </thead><tbody>';
            if (!empty($html['duration'])) {
                $table .= '<tr><td>Date Range</td><td colspan="4">' . $html['duration'] . '</td></tr>';
            }
            if (!empty($html['duration_name'])) {
                $table .= '<tr><td >Duration</td><td colspan="4">' . $html['duration_name'] . '</td></tr>';
            }
            if (!empty($html['category'])) {
                $table .= '<tr><td>Action Items</td><td colspan="4">' . $html['category'] . '</td></tr>';
            }

            $table .= ' </tbody></table>';
        }

        return $table;
    }



    /* This is admin dashboard map view */
    public function mapview_bkp()
    {
        close_setup_menu();
        

        $data['dashboard'] = true;
        $data['userRole'] = $userRole = $GLOBALS['current_user']->role_slug_url;

        if ($userRole != 'ar' && $userRole != 'ae-area' && $userRole != 'at' && $userRole != 'ae-global' && $userRole != 'aa' && $userRole != 'ata') {
            redirect('/admin');
        }
// print_r($_POST);exit;
        //$area = $GLOBALS['current_user']->area;

        $area = (!empty($post_data['areaid'])) ? $post_data['areaid'] : $GLOBALS['current_user']->area;
        //$regionid = (!empty($post_data['region'])) ? $post_data['region'] : '';

        $data['area'] = $area;
        $data['region'] = $this->report_model->get_region($area);

        $data['action_taker'] = $this->report_model->get_action_taker($area);

        $data['clients'] = $this->clients_model->get();
        $data['subregion'] = $this->report_model->get_subregion($area);
        $data['ticket'] = $this->atr_report_model->get_ticket();
        $data['organization'] = $this->atr_report_model->get_organization('','');
        $data['department'] = $this->atr_report_model->get_department();
        $data['projectsupport'] = $this->atr_report_model->get_project_support('','','');
        $data['ward'] = $this->atr_report_model->get_wards('','');
        //$data['action_taker'] = $this->report_model->get_action_taker($get_area);
        $data['action_reviewer'] = $this->report_model->get_action_reviewer($area);

        $categories = $this->issue_model->get_area_issues(4);
        $data['categories'] = (!empty($categories)) ? $categories : [];

        $data['user_dashboard_visibility'] = get_staff_meta(get_staff_user_id(), 'dashboard_widgets_visibility');

        if (!$data['user_dashboard_visibility']) {
            $data['user_dashboard_visibility'] = [];
        } else {
            $data['user_dashboard_visibility'] = unserialize($data['user_dashboard_visibility']);
        }
        $data['user_dashboard_visibility'] = json_encode($data['user_dashboard_visibility']);

        $data['userDetails'] = $this->staff_model->get_userDetails(get_staff_user_id());

        $post_data = $this->input->post('form');
        if(!empty($post_data2['ticket'])){
            if(in_array("7", $post_data['ticket'])){
                $post_data_ticket = array(1,3,5,6,7,9,10,11,12,13,15,16);
            }else{
               $post_data_ticket = (!empty($post_data['ticket'])) ? $post_data['ticket'] : "";
           }
       }else{
           $post_data_ticket = (!empty($post_data['ticket'])) ? $post_data['ticket'] : "";  
       }
        // $tableParams = [
        //     'subregion' => (!empty($post_data['subregion'])) ? $post_data['subregion'] : '',
        //     'category' => (!empty($cats)) ? $cats : '',
        //     'clients' => (!empty($post_data['clients'])) ? $post_data['clients'] : '',
        //     'report_date' => (!empty($post_data['report_months'])) ? $post_data['report_months'] : '',
        //     'to_date' => (!empty($post_data['report-to'])) ? $post_data['report-to'] : '',
        //     'from_date' => (!empty($post_data['report-from'])) ? $post_data['report-from'] : '',
        //     'statusIds' => (!empty($post_data['ticket'])) ? $post_data['ticket'] : "",
        // ];
        $tableParams = [
        'areaid'        => (!empty($post_data['areaid'])) ? $post_data['areaid'] : '',
        'region'        => (!empty($post_data['region'])) ? $post_data['region'] : '',
        'subregion'     => (!empty($post_data['subregion'])) ? $post_data['subregion'] : '',
        'category'      => (!empty($cats)) ? $cats : '',
        'bug'           => (!empty($post_data['bug'])) ? $post_data['bug'] : '',
        'action_taker'  => (!empty($post_data['action_taker'])) ? $post_data['action_taker'] : '',
        'action_reviewer' => (!empty($post_data['action_reviewer'])) ? $post_data['action_reviewer'] : '',
        'report_date'   => (!empty($post_data['report_months'])) ? $post_data['report_months'] : '',
        'to_date'       => (!empty($post_data['report-to'])) ? $post_data['report-to'] : '',
        'from_date'     => (!empty($post_data['report-from'])) ? $post_data['report-from'] : '',
        // 'statusIds'     => (!empty($post_data2['ticket'])) ? $post_data2['ticket'] : "",
        'statusIds'     => $post_data_ticket,
        'duration'      => (!empty($range)) ? $range : '',
      ];
        $data['tableParams'] = $tableParams;
        

        $data = hooks()->apply_filters('before_dashboard_render', $data);

        
            $data["durations"] = [
                ["id" => "30", "duration" => "< 1 month"],
                ["id" => "31 - 183", "duration" => "1 month - 6 months"],
                ["id" => "184 - 365", "duration" => "6 months - 1 year"],
                ["id" => "366 - 1095", "duration" => "1 year - 3 years"],
                ["id" => "1096 - 1825", "duration" => "3 years - 5 years"],
                ["id" => "1826", "duration" => "> 5 years"],
            ];
        

        if ($userRole == 'ar' || $userRole == 'ae-area' || $userRole != 'at' || $userRole != 'ae-global' || $userRole != 'aa' || $userRole != 'ata') {

            $this->load->view('admin/dashboard/ar_dashboard_mapview', $data);

        }
    }
    public function mapview()
    {
        close_setup_menu();
        
//---------------------------------

        //echo '<pre>'; print_r($GLOBALS['current_user']); exit;
        $staffid = $GLOBALS['current_user']->staffid;
        $post_data = $this->input->post();
       
        
        //pre($GLOBALS['current_user']);
        $get_area = $this->input->get('area') ? base64_decode($this->input->get('area')) : "";
        $get_status = $this->input->get('status') ? base64_decode($this->input->get('status')) : "";
        $get_city = $this->input->get('city') ? base64_decode($this->input->get('city')) : "";
        $get_organization = $this->input->get('organization') ? base64_decode($this->input->get('organization')) : "";
         $get_projectsupport = $this->input->get('projectsupport') ? base64_decode($this->input->get('projectsupport')) : "";
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
            $user_at  = $this->staff_model->get_userDetails($GLOBALS['current_user']->staffid);
            if($role==6){
                $org_id ='';
            }else{
                $org_id = (!empty($user_at->org_id)) ? array($user_at->org_id) : '';
            }
           $region_at = (!empty($user_at->region)) ? array($user_at->region) : '';
             $subregion_at = (!empty($user_at->sub_region)) ?  array($user_at->sub_region) : '';
$region = !empty($post_data['region']) ? $post_data['region'] : $area;
        
        $areaid = (!empty($post_data['areaid'])) ? $post_data['areaid'] : '';
        $regionid = (!empty($post_data['region'])) ? $post_data['region'] : $region_at;
        $organizationid = (!empty($post_data['organization'])) ? $post_data['organization'] : '';
        $projectsupportid = (!empty($post_data['projectsupport'])) ? $post_data['projectsupport'] : '';
        $subregionid = (!empty($post_data['subregion'])) ? $region : $subregion_at;
        
        $wardid = (!empty($post_data['ward'])) ? $post_data['ward'] : '';

        if ($role_slug == 'ae-global') {
            $data['title']         = 'Action Taken Report - National Observer';
            $data['action_taker'] = $this->atr_report_model->get_action_taker('', $wardid);
            $data['action_reviewer'] = $this->atr_report_model->get_action_reviewer();
            $data['areas'] = $this->atr_report_model->get_area(); 
            $data['organization'] = $this->atr_report_model->get_organization($organizationid,'');
            $data['projectsupport'] = $this->atr_report_model->get_project_support($areaid,$projectsupportid,'');
            $data['region'] = $this->atr_report_model->get_region('',$areaid);
            $data['subregion'] = $this->atr_report_model->get_subregion('',$regionid);
            $data['ward'] = $this->atr_report_model->get_wards('', $subregionid);
      
        } else {
            $data['title']         = $siteTitle[$role_slug];
            if($role!=6){
                $data['action_taker'] = $this->atr_report_model->get_action_taker($area, $wardid='',$staffid,$regionid,$subregionid);
                
            }else{
                $data['action_taker'] = $this->atr_report_model->get_action_taker($area, $wardid='',$staffid,$regionid,$subregionid);
            }
            
            $data['action_reviewer'] = $this->atr_report_model->get_action_reviewer($area,$wardid,$regionid,$subregionid);
            //if($role == 7 || $role == 3 || $role == 8){
            //    $data['region'] = $this->report_model->get_ae_region();
            //    $data['subregion'] = $this->report_model->get_ae_subregion();
            //}else{
            $data['organization'] = $this->atr_report_model->get_organization($org_id,$area);
            if($role!=6){
                if($role==4){
                    $plStaffId = array();
                    $data_projectsupport = array();
                    // $getPLids = $this->atr_report_model->get_action_taker($area, $wardid,$staffid);
                    $getPLids = $this->atr_report_model->get_action_taker($area, $wardid='',$staffid,$regionid,$subregionid);
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
                            $categories = $this->issue_model->get_area_issues($area,'',$data_projectsupport);
                }else if($role==3){
                    $plStaffId = array();
                    $data_projectsupport = array();
                    $subregion_data = (!empty($post_data['subregion'])) ? $post_data['subregion'] : '';
                    $ward_data = (!empty($post_data['ward'])) ? $post_data['ward'] : '';
                    $category_data = (!empty($post_data['category'])) ? $post_data['category'] : '';
                    if(!empty($ward_data) || !empty($category_data) || !empty($subregion_data)){
                       
                        $data['projectsupport'] = $this->atr_report_model->get_project_support($area,$ward_data,$category_data,$staffid,$regionid='',$subregion_data);
                        $dataprojectsupport = $this->atr_report_model->get_project_support($area,$ward_data,$category_data,$staffid,$regionid='',$subregion_data);
                    }else{
                        $data['projectsupport'] = $this->atr_report_model->get_project_support('','','',$staffid,$regionid);
                        $dataprojectsupport = $this->atr_report_model->get_project_support('','','',$staffid,$regionid);
                    }
                    
                    if(count($dataprojectsupport)>0){
                        foreach($dataprojectsupport as $val){
                            $data_projectsupport []= $val['staffid'];
                        }}
                            $categories = $this->issue_model->get_area_issues($area,'',$data_projectsupport);//show all categories for reporting
                }else{
                    $data['projectsupport'] = $this->atr_report_model->get_project_support('','','',$staffid,$regionid);
                    $categories = $this->issue_model->get_area_issues($area,'',$staffid, $regionid);//show all categories for reporting
                }
            
            }else{
                $data['projectsupport'] = $this->atr_report_model->get_project_support($area,'','','',$regionid);  
                $categories = $this->issue_model->get_area_issues($area,1,$projectsupportid,$regionid);
            }
            if($role==9){
                $categories = $this->issue_model->get_area_issues($area, 1, '', $GLOBALS['current_user']->location['region_id']);
                
            }
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
            
           // $data['subregion'] = $this->atr_report_model->get_subregion($area,$regionid);
           if($role == 3){
            $data['subregion'] = $this->atr_report_model->get_subregion($area,$regionid,'',$staffid);
            }else if($role == 4){
                $data_action_taker = array();
                    if(count($data['action_taker'])>0){
                        foreach($data['action_taker'] as $val){
                            $data_action_taker []= $val['staffid'];
                        }}
                $data['subregion'] = $this->atr_report_model->get_subregion($area,$regionid,'',$data_action_taker);
            }else{
                $data['subregion'] = $this->atr_report_model->get_subregion($area,$regionid,'',$staffid='');
            }
            //if(!empty($subregionid)){
                //$data['ward'] = $this->atr_report_model->get_wards($area, $subregionid, $ward_id = '', $staff_id = '',$regionid);
            // }else{
            //     $data['ward'] = $this->atr_report_model->get_wards('','','',$this->session->userdata('staff_user_id'));
            // }
            if($role == 8){
                $staffid = $GLOBALS['current_user']->staffid;
                $data['ward'] = $this->atr_report_model->get_wards($area, $subregionid, $wardid='', $staffid,$regionid);
            }else if($role == 3){
                $data_subregion = array();
                if(count($data['subregion'])>0){
                    foreach($data['subregion'] as $val){
                        $data_subregion []= $val['id'];
                    }}
                $data['ward'] = $this->atr_report_model->get_wards($area, $data_subregion, $wardid ='', $staffid='',$regionid);
            }else if($role == 4){
                $data_subregion = array();
                if(count($data['subregion'])>0){
                    foreach($data['subregion'] as $val){
                        $data_subregion []= $val['id'];
                    }}
                $data['ward'] = $this->atr_report_model->get_wards($area, $data_subregion, $wardid ='', $staffid='',$regionid);
            }else{
                $data['ward'] = $this->atr_report_model->get_wards($area, $subregionid, $wardid='', $staffid='',$regionid);
            }
            
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
        }}
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
        if ($get_projectsupport != "") {
            $get_projectsupport_data = $this->atr_report_model->get_project_support('',$get_projectsupport, '')[0];
            if ($get_projectsupport_data)
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

        //$data['statuses'] = $this->atr_report_model->get_report_summary($tableParams,1);
       
        //$data['totals'] = $this->atr_report_model->get_report_summary($tableParams);//report data
        // $data['totals'] = $this->report_model->get_report_total($tableParams);

        //$data['post_data'] = $post_data;
        $data['tableParams'] = $tableParams;
        // if($role!=6){
        //     $categories = $this->issue_model->get_area_issues($area,'',$staffid);//show all categories for reporting
        // }else{
        //     $categories = $this->issue_model->get_area_issues($area);//show all categories for reporting
        // }
       
        $data['categories'] = (!empty($categories)) ? $categories : [];
        $getOrgide = array();
        $data['department']= array();
        if($role==6){
            $getOrgids = $this->atr_report_model->get_organization($org_id,$area);
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
        $data["durations"] = [
            ["id" => "30", "duration" => "< 1 month"],
            ["id" => "31 - 183", "duration" => "1 month - 6 months"],
            ["id" => "184 - 365", "duration" => "6 months - 1 year"],
            ["id" => "366 - 1095", "duration" => "1 year - 3 years"],
            ["id" => "1096 - 1825", "duration" => "3 years - 5 years"],
            ["id" => "1826", "duration" => "> 5 years"],
                ];

        // print_r($data);
        // die();
        // $data['total'] = 4;
        
        $this->load->view('admin/dashboard/ar_dashboard_mapview', $data);
    

//------------------------------------
        

       
    }
    /* Admin dashboard map view data on ajax request */
    public function mapview_ajax()
    {
        if ($this->input->is_ajax_request()) {

            $userRole = $GLOBALS['current_user']->role_slug_url;

            $locInfo = [];
            $locationMarkers = [];
            $dashboard_map_data1 = [];
            $dashboard_map_data2 = [];
            $dashboard_map_data3 = [];
            $dashboard_map_data4 = [];

            if ($userRole == "ar") {

                $post_data = [];
                if ($this->input->post()) {

                    parse_str($this->input->post('form_data'), $post_array);

                    //pre($post_array);exit;

                    if (isset($post_array['subregion'])) {
                        if (!empty($post_array['subregion']) && count($post_array['subregion']) > 0) {
                            $post_data['subregion'] = implode(",", $post_array['subregion']);
                        }
                    }

                    /*if (isset($post_array['action_taker'])) {
                                       if (!empty($post_array['action_taker']) && count($post_array['action_taker']) > 0) {
                                           $post_data['action_taker'] = implode(",", $post_array['action_taker']);
                                       }
                                   }*/

                    if (isset($post_array['clients'])) {
                        if (!empty($post_array['clients']) && count($post_array['clients']) > 0) {
                            $post_data['clients'] = implode(",", $post_array['clients']);
                        }
                    }

                    if (isset($post_array['category'])) {
                        if (!empty($post_array['category']) && count($post_array['category']) > 0) {
                            $post_data['category'] = implode(",", $post_array['category']);
                        }
                    }

                    //project status
                    if (isset($post_array['ticket'])) {
                        if (!empty($post_array['ticket']) && count($post_array['ticket']) > 0) {
                            $post_data['ticket'] = implode(",", $post_array['ticket']);
                        }
                    }

                    if (isset($post_array['report_months'])) {
                        if ($post_array['report_months'] == 'custom') {

                            $post_data['to_date'] = $post_array['report-to'];
                            $post_data['from_date'] = $post_array['report-from'];

                        }
                        $post_data['report_date'] = $post_array['report_months'];
                    }
                }

                //referred => 5
                //unassign => 9

                if (isset($post_array['ticket'])) {
                    //new => 1
                    if (in_array(1, $post_array['ticket'])) {
                        $dashboard_map_data1 = $this->dashboard_model->get_dashboard_map_data($post_data, 'new');
                    }

                    //in-progress => 2
                    if (in_array(2, $post_array['ticket'])) {
                        $dashboard_map_data2 = $this->dashboard_model->get_dashboard_map_data($post_data, 'ongoing');
                    }
                    //closed => 3
                    if (in_array(3, $post_array['ticket'])) {
                        $dashboard_map_data3 = $this->dashboard_model->get_dashboard_map_data($post_data, 'closed');
                    }

                    //delayed => 7 //frozen => 8
                    if (in_array(7, $post_array['ticket']) || in_array(8, $post_array['ticket'])) {
                        $dashboard_map_data4 = $this->dashboard_model->get_dashboard_map_data($post_data, 'escalated');
                    }

                } else {

                    $dashboard_map_data1 = $this->dashboard_model->get_dashboard_map_data($post_data, 'new');
                    $dashboard_map_data2 = $this->dashboard_model->get_dashboard_map_data($post_data, 'ongoing');
                    $dashboard_map_data3 = $this->dashboard_model->get_dashboard_map_data($post_data, 'closed');
                    $dashboard_map_data4 = $this->dashboard_model->get_dashboard_map_data($post_data, 'escalated');

                }

                $dashboard_map_data = array_merge($dashboard_map_data1, $dashboard_map_data2, $dashboard_map_data3, $dashboard_map_data4);

            } else if ($userRole == "ae-area" || $userRole == "aa" || $userRole == "at" || $userRole == "ae-global") {

                $dashboard_map_data5 = [];
                $dashboard_map_data6 = [];
                $dashboard_map_data7 = [];
                $dashboard_map_data10 = [];
                $dashboard_map_data11 = [];
                $dashboard_map_data12 = [];
                $dashboard_map_data13 = [];
                $dashboard_map_data15 = [];
                $dashboard_map_data16 = [];
                $dashboard_map_data61 = [];
                //prepare data for "State Observer"
                /*if ($this->input->post()) {
                                
                                $post_array = $this->input->post('form_data');
                                
                            } else if ($this->input->get()) {
                                $post_array = $this->input->get();
                            }*/
                            // $post_array = $this->input->post('form_data');
                 parse_str($this->input->post('form_data'), $post_array);

                 //pre($post_array);

                $area = $GLOBALS['current_user']->area;

             //$ticket_arr = (!empty($post_array['ticket'])) ? $post_array['ticket'] : [];
             
               $ticket_arr = (!empty($post_array['ticket'])) ? $post_array['ticket'] : "";  
           
             $tableParams = [
                'areaid'        => (!empty($post_array['area'])) ? $post_array['area'] : '',
                'region'        => (!empty($post_array['region'])) ? $post_array['region'] : '',
                'subregion'     => (!empty($post_array['subregion'])) ? $post_array['subregion'] : '',
                'category'      => (!empty($post_array['category'])) ? $post_array['category'] : '',
                'bug'           => (!empty($post_array['bug'])) ? $post_array['bug'] : '',
                'action_taker'  => (!empty($post_array['action_taker'])) ? $post_array['action_taker'] : '',
                'action_reviewer' => (!empty($post_array['action_reviewer'])) ? $post_array['action_reviewer'] : '',
                'department' => (!empty($post_array['department'])) ? $post_array['department'] : '',
                'organization' => (!empty($post_array['organization'])) ? $post_array['organization'] : '',
                'report_date'   => (!empty($post_array['report_months'])) ? $post_array['report_months'] : '',
                'to_date'       => (!empty($post_array['report-to'])) ? $post_array['report-to'] : '',
                'from_date'     => (!empty($post_array['report-from'])) ? $post_array['report-from'] : '',
                // 'statusIds'     => (!empty($post_data2['ticket'])) ? $post_data2['ticket'] : "",
                'statusIds'     => $ticket_arr,
              ];
              //print_r($tableParams);
                // $tableParams = [
                //     'area' => (!empty($post_array['area'])) ? $post_array['area'] : '',
                //     'region' => (!empty($post_array['region'])) ? $post_array['region'] : '',
                //     'subregion' => (!empty($post_array['subregion'])) ? $post_array['subregion'] : '',
                //     'action_taker' => (!empty($post_array['action_taker'])) ? $post_array['action_taker'] : '',
                //     'report_months' => (!empty($post_array['report_months'])) ? $post_array['report_months'] : '',
                //     'from_date' => (!empty($post_array['report-from'])) ? $post_array['report-from'] : '',
                //     'to_date' => (!empty($post_array['report-to'])) ? $post_array['report-to'] : '',
                // ];

                if ($area != null) {
                    $tableParams['area'] = $area;
                    $tableParams["area_name"] = $this->area_model->get_area(["areaid" => $area]);
                    $tableParams["area_name"] = !empty($tableParams["area_name"]) ? $tableParams["area_name"][0]["name"] : "";
                }

                

                    $dashboard_map_data1 = $this->dashboard_model->get_ae_area_map_data($tableParams, $ticket_arr);
                    // $dashboard_map_data2 = $this->dashboard_model->get_ae_area_map_data($tableParams, 7);
                    // $dashboard_map_data3 = $this->dashboard_model->get_ae_area_map_data($tableParams, 3);
                    // $dashboard_map_data4 = $this->dashboard_model->get_ae_area_map_data($tableParams, 2);
                    // $dashboard_map_data5 = $this->dashboard_model->get_ae_area_map_data($tableParams, 5);
                    // $dashboard_map_data61 = $this->dashboard_model->get_ae_area_map_data($tableParams, 6);
                    // $dashboard_map_data6 = $this->dashboard_model->get_ae_area_map_data($tableParams, 9);
                    // $dashboard_map_data7 = $this->dashboard_model->get_ae_area_map_data($tableParams, 8);
                    // $dashboard_map_data10 = $this->dashboard_model->get_ae_area_map_data($tableParams, 10);
                    // $dashboard_map_data11 = $this->dashboard_model->get_ae_area_map_data($tableParams, 11);
                    // $dashboard_map_data12 = $this->dashboard_model->get_ae_area_map_data($tableParams, 12);
                    // $dashboard_map_data13 = $this->dashboard_model->get_ae_area_map_data($tableParams, 13);
                    // $dashboard_map_data15 = $this->dashboard_model->get_ae_area_map_data($tableParams, 15);
                    // $dashboard_map_data16 = $this->dashboard_model->get_ae_area_map_data($tableParams, 16);
                

                $dashboard_map_data = array_merge($dashboard_map_data1);
            }

 //print_r($dashboard_map_data);exit;

            if ($dashboard_map_data) {
                $this->load->model('projects_model');
                foreach ($dashboard_map_data as $map_data) {

                    $arr_files_data = $this->dashboard_model->get_latest_project_files_data($map_data['projectId']);

                    if ($arr_files_data) {
                        $ticketDetails = $this->projects_model->get_project_details($map_data['projectId']);
                        ;

                        $img = '';
                        $file = 'uploads/projects/' . $map_data['projectId'] . '/' . $arr_files_data->thumbnail_link;
                        if (file_exists($file)) {

                            $img = "<img src=" . base_url($file) . " style=height:200px; />";
                        }

                        $locationMarkers[] = [$ticketDetails->project_name, $arr_files_data->latitude, $arr_files_data->longitude, $map_data['markerColor']];

                        $locInfo[] = [
                            "<div class=ticket_details data-project_id=" . $map_data['projectId']
                            . " ><h4> Project Id : " . $map_data['projectId'] . "</h4><h4>" . $ticketDetails->project_name . "</h4><p>(" . $ticketDetails->landmark . ")</p><p style=text-align:center;>" . $img . "</p></div>"
                        ];
                    }
                }
            }

            echo json_encode([
                'success' => true,
                'locationMarkers' => $locationMarkers,
                'locInfo' => $locInfo
            ]);
        }
    }
    public function mapview_ajax_bkp()
    {
        if ($this->input->is_ajax_request()) {

            $userRole = $GLOBALS['current_user']->role_slug_url;

            $locInfo = [];
            $locationMarkers = [];
            $dashboard_map_data1 = [];
            $dashboard_map_data2 = [];
            $dashboard_map_data3 = [];
            $dashboard_map_data4 = [];

            if ($userRole == "ar") {

                $post_data = [];
                if ($this->input->post()) {

                    parse_str($this->input->post('form_data'), $post_array);

                    //pre($post_array);exit;

                    if (isset($post_array['subregion'])) {
                        if (!empty($post_array['subregion']) && count($post_array['subregion']) > 0) {
                            $post_data['subregion'] = implode(",", $post_array['subregion']);
                        }
                    }

                    /*if (isset($post_array['action_taker'])) {
                                       if (!empty($post_array['action_taker']) && count($post_array['action_taker']) > 0) {
                                           $post_data['action_taker'] = implode(",", $post_array['action_taker']);
                                       }
                                   }*/

                    if (isset($post_array['clients'])) {
                        if (!empty($post_array['clients']) && count($post_array['clients']) > 0) {
                            $post_data['clients'] = implode(",", $post_array['clients']);
                        }
                    }

                    if (isset($post_array['category'])) {
                        if (!empty($post_array['category']) && count($post_array['category']) > 0) {
                            $post_data['category'] = implode(",", $post_array['category']);
                        }
                    }

                    //project status
                    if (isset($post_array['ticket'])) {
                        if (!empty($post_array['ticket']) && count($post_array['ticket']) > 0) {
                            $post_data['ticket'] = implode(",", $post_array['ticket']);
                        }
                    }

                    if (isset($post_array['report_months'])) {
                        if ($post_array['report_months'] == 'custom') {

                            $post_data['to_date'] = $post_array['report-to'];
                            $post_data['from_date'] = $post_array['report-from'];

                        }
                        $post_data['report_date'] = $post_array['report_months'];
                    }
                }

                //referred => 5
                //unassign => 9

                if (isset($post_array['ticket'])) {
                    //new => 1
                    if (in_array(1, $post_array['ticket'])) {
                        $dashboard_map_data1 = $this->dashboard_model->get_dashboard_map_data($post_data, 'new');
                    }

                    //in-progress => 2
                    if (in_array(2, $post_array['ticket'])) {
                        $dashboard_map_data2 = $this->dashboard_model->get_dashboard_map_data($post_data, 'ongoing');
                    }
                    //closed => 3
                    if (in_array(3, $post_array['ticket'])) {
                        $dashboard_map_data3 = $this->dashboard_model->get_dashboard_map_data($post_data, 'closed');
                    }

                    //delayed => 7 //frozen => 8
                    if (in_array(7, $post_array['ticket']) || in_array(8, $post_array['ticket'])) {
                        $dashboard_map_data4 = $this->dashboard_model->get_dashboard_map_data($post_data, 'escalated');
                    }

                } else {

                    $dashboard_map_data1 = $this->dashboard_model->get_dashboard_map_data($post_data, 'new');
                    $dashboard_map_data2 = $this->dashboard_model->get_dashboard_map_data($post_data, 'ongoing');
                    $dashboard_map_data3 = $this->dashboard_model->get_dashboard_map_data($post_data, 'closed');
                    $dashboard_map_data4 = $this->dashboard_model->get_dashboard_map_data($post_data, 'escalated');

                }

                $dashboard_map_data = array_merge($dashboard_map_data1, $dashboard_map_data2, $dashboard_map_data3, $dashboard_map_data4);

            } else if ($userRole == "ae-area" || $userRole == "aa" || $userRole == "at" || $userRole == "ae-global") {

                $dashboard_map_data5 = [];
                $dashboard_map_data6 = [];
                $dashboard_map_data7 = [];
                $dashboard_map_data10 = [];
                $dashboard_map_data11 = [];
                $dashboard_map_data12 = [];
                $dashboard_map_data13 = [];
                $dashboard_map_data15 = [];
                $dashboard_map_data16 = [];
                $dashboard_map_data61 = [];
                //prepare data for "State Observer"
                /*if ($this->input->post()) {
                                
                                $post_array = $this->input->post('form_data');
                                
                            } else if ($this->input->get()) {
                                $post_array = $this->input->get();
                            }*/

                parse_str($this->input->post('form_data'), $post_array);

                //pre($post_array);

                $area = $GLOBALS['current_user']->area;

             $ticket_arr = (!empty($post_array['ticket'])) ? $post_array['ticket'] : [];

                $tableParams = [
                    'area' => (!empty($post_array['area'])) ? $post_array['area'] : '',
                    'region' => (!empty($post_array['region'])) ? $post_array['region'] : '',
                    'subregion' => (!empty($post_array['subregion'])) ? $post_array['subregion'] : '',
                    'action_taker' => (!empty($post_array['action_taker'])) ? $post_array['action_taker'] : '',
                    'report_months' => (!empty($post_array['report_months'])) ? $post_array['report_months'] : '',
                    'from_date' => (!empty($post_array['report-from'])) ? $post_array['report-from'] : '',
                    'to_date' => (!empty($post_array['report-to'])) ? $post_array['report-to'] : '',
                ];

                if ($area != null) {
                    $tableParams['area'] = $area;
                    $tableParams["area_name"] = $this->area_model->get_area(["areaid" => $area]);
                    $tableParams["area_name"] = !empty($tableParams["area_name"]) ? $tableParams["area_name"][0]["name"] : "";
                }

                if (count($ticket_arr) > 0) {
                    //new => 1
                    if (in_array(1, $ticket_arr)) {
                        $dashboard_map_data1 = $this->dashboard_model->get_ae_area_map_data($tableParams, 1);
                    }

                    //wip => 2
                    if (in_array(2, $ticket_arr)) {
                        $dashboard_map_data4 = $this->dashboard_model->get_ae_area_map_data($tableParams, 2);
                    }

                    //closed => 3
                    if (in_array(3, $ticket_arr)) {
                        $dashboard_map_data3 = $this->dashboard_model->get_ae_area_map_data($tableParams, 3);
                    }

                    //referred (rejected) => 5
                    if (in_array(5, $ticket_arr)) {
                        $dashboard_map_data5 = $this->dashboard_model->get_ae_area_map_data($tableParams, 5);
                    }
                        //escalated (Reopen) => 6
                        if (in_array(6, $ticket_arr)) {
                            $dashboard_map_data61 = $this->dashboard_model->get_ae_area_map_data($tableParams, 6);
                        }
                    //escalated (Delayed) => 7
                    if (in_array(7, $ticket_arr)) {
                        $dashboard_map_data2 = $this->dashboard_model->get_ae_area_map_data($tableParams, 7);
                    }

                    //frozen => 8
                    if (in_array(8, $ticket_arr)) {
                        $dashboard_map_data7 = $this->dashboard_model->get_ae_area_map_data($tableParams, 8);
                    }

                    //unassigned => 9
                    if (in_array(9, $ticket_arr)) {
                        $dashboard_map_data6 = $this->dashboard_model->get_ae_area_map_data($tableParams, 9);
                    }
                     //Long Term => 10
                     if (in_array(10, $ticket_arr)) {
                        $dashboard_map_data10 = $this->dashboard_model->get_ae_area_map_data($tableParams, 10);
                    }
                     //Send for approval => 11
                     if (in_array(11, $ticket_arr)) {
                        $dashboard_map_data11 = $this->dashboard_model->get_ae_area_map_data($tableParams, 11);
                    }
                     //Submit for approval => 12
                     if (in_array(12, $ticket_arr)) {
                        $dashboard_map_data12 = $this->dashboard_model->get_ae_area_map_data($tableParams, 12);
                    }
                    //Verified => 13
                    if (in_array(13, $ticket_arr)) {
                        $dashboard_map_data13 = $this->dashboard_model->get_ae_area_map_data($tableParams, 13);
                    }
                    //Unresolved => 15
                    if (in_array(15, $ticket_arr)) {
                        $dashboard_map_data15 = $this->dashboard_model->get_ae_area_map_data($tableParams, 15);
                    }
                    //Partiallyreresolved => 16
                    if (in_array(16, $ticket_arr)) {
                        $dashboard_map_data16 = $this->dashboard_model->get_ae_area_map_data($tableParams, 16);
                    }



                } else {

                    $dashboard_map_data1 = $this->dashboard_model->get_ae_area_map_data($tableParams, 1);
                    $dashboard_map_data2 = $this->dashboard_model->get_ae_area_map_data($tableParams, 7);
                    $dashboard_map_data3 = $this->dashboard_model->get_ae_area_map_data($tableParams, 3);
                    $dashboard_map_data4 = $this->dashboard_model->get_ae_area_map_data($tableParams, 2);
                    $dashboard_map_data5 = $this->dashboard_model->get_ae_area_map_data($tableParams, 5);
                    $dashboard_map_data61 = $this->dashboard_model->get_ae_area_map_data($tableParams, 6);
                    $dashboard_map_data6 = $this->dashboard_model->get_ae_area_map_data($tableParams, 9);
                    $dashboard_map_data7 = $this->dashboard_model->get_ae_area_map_data($tableParams, 8);
                    $dashboard_map_data10 = $this->dashboard_model->get_ae_area_map_data($tableParams, 10);
                    $dashboard_map_data11 = $this->dashboard_model->get_ae_area_map_data($tableParams, 11);
                    $dashboard_map_data12 = $this->dashboard_model->get_ae_area_map_data($tableParams, 12);
                    $dashboard_map_data13 = $this->dashboard_model->get_ae_area_map_data($tableParams, 13);
                    $dashboard_map_data15 = $this->dashboard_model->get_ae_area_map_data($tableParams, 15);
                    $dashboard_map_data16 = $this->dashboard_model->get_ae_area_map_data($tableParams, 16);
                }

                $dashboard_map_data = array_merge($dashboard_map_data1, $dashboard_map_data2, $dashboard_map_data3, $dashboard_map_data4, $dashboard_map_data5, $dashboard_map_data6,$dashboard_map_data61, $dashboard_map_data7, $dashboard_map_data10, $dashboard_map_data11, $dashboard_map_data12, $dashboard_map_data13, $dashboard_map_data15, $dashboard_map_data16);
            }

// print_r($dashboard_map_data);exit;

            if ($dashboard_map_data) {
                $this->load->model('projects_model');
                foreach ($dashboard_map_data as $map_data) {

                    $arr_files_data = $this->dashboard_model->get_latest_project_files_data($map_data['projectId']);

                    if ($arr_files_data) {
                        $ticketDetails = $this->projects_model->get_project_details($map_data['projectId']);
                        ;

                        $img = '';
                        $file = 'uploads/projects/' . $map_data['projectId'] . '/' . $arr_files_data->thumbnail_link;
                        if (file_exists($file)) {

                            $img = "<img src=" . base_url($file) . " style=height:200px; />";
                        }

                        $locationMarkers[] = [$ticketDetails->project_name, $arr_files_data->latitude, $arr_files_data->longitude, $map_data['markerColor']];

                        $locInfo[] = [
                            "<div class=ticket_details data-project_id=" . $map_data['projectId']
                            . " ><h4> Project Id : " . $map_data['projectId'] . "</h4><h4>" . $ticketDetails->project_name . "</h4><p>(" . $ticketDetails->landmark . ")</p><p style=text-align:center;>" . $img . "</p></div>"
                        ];
                    }
                }
            }

            echo json_encode([
                'success' => true,
                'locationMarkers' => $locationMarkers,
                'locInfo' => $locInfo
            ]);
        }
    }

    public function checklist()
    {

        $data['userRole'] = $userRole = $GLOBALS['current_user']->role_slug_url;

        if ($userRole == 'aa') {
            $this->load->view('admin/dashboard/checklist');
        }
    }

    public function unassigneditem()
    {

        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('unassigned');
        }

        $data['title'] = 'Unassigned Projects';
        //$area_id = $this->region_model->get_area($this->session->userdata('staff_user_id'));
        //$data['region'] = $this->subregion_model->get_region($area_id);

        $this->load->view('admin/staff/unassigned', $data);

        // if ($this->input->is_ajax_request()) {
        //     $userRole = $GLOBALS['current_user']->role_slug_url;
        //     $userId = $GLOBALS['current_user']->staffid;
        //     $userName = $GLOBALS['current_user']->full_name;

        //         $action_items = array();
        //         $action_items = $this->dashboard_model->get_action_items();

        //         $actionList = '';
        //         $this->load->model('projects_model');
        //         $this->load->model('staff_model');

        //         foreach ($action_items as $key => $item) {
        //             $project_name = !empty($item['project_name']) ? $item['project_name'] : '';
        //             $landmark = !empty($item['landmark']) ? $item['landmark'] : '';
        //             $reassigned = !empty($item['reassigned']) ? $item['reassigned'] : '';
        //             $description = !empty($item['description']) ? $item['description'] : '';
        //             $statusId = !empty($item['status']) ? $item['status'] : '';
        //             $statusColor = !empty($item['color']) ? $item['color'] : '';
        //             $statusBgColor = !empty($item['bg-color']) ? $item['bg-color'] : '';
        //             $status_name = !empty($item['status_name']) ? $item['status_name'] : '';
        //             $deadline = !empty($item['deadline']) ? setDateFormat($item['deadline']) : '';
        //             $frozen = !empty($item['frozen']) ? $item['frozen'] : '';
        //             $action_date = !empty($item['action_date']) ? setDateFormat($item['action_date']) : '';
        //             // $start_date = !empty($item['start_date']) ? date('Y-m-d', strtotime($item['start_date'])) : '';
        //             // $project_total_days = !empty($item['deadline']) ? date('d-M-Y', strtotime($item['deadline'])) : '';
        //             $projectId =  $item['project_id'];

        //             $assignedUser = getProjectAssignedUser($projectId);
        //             if ($userRole == 'ata') {
        //                 $statusId = !empty($item['status_id']) ? $item['status_id'] : '';
        //             }

        //             $action = '';
        //             if ($statusId == 1) {
        //                 if (in_array($userRole, $roles) && ($assignedUser == get_staff_user_id()) && empty($frozen)) {
        //                     $action = '<div class="btn-container">
        //                                     <a href="javascript:void(0)" class="btn accept-btn accept-btn-popup" data-projectid="' . $projectId . '">Accept</a>
        //                                 </div>
        //                                 <div class="btn-container">
        //                                     <a href="javascript:void(0)" class="btn reject-btn reject-btn-popup" data-projectid="' . $projectId . '">'.
        //                                      ($GLOBALS['current_user']->role_slug_url =='at'? 'Refer':'Reject')
        //                                     .'</a>
        //                                 </div>';
        //                 } else {
        //                     $action = 'New Project';
        //                 }
        //                 if ($reassigned) {
        //                     $status_name = 'Re-assigned';
        //                 }
        //                 if (in_array($userRole, ['at', 'ar']) && $item['action_date'] <= date('Y-m-d')) {
        //                     // $status_name = 'Delayed'; //Unaccepted
        //                     // $statusColor = "#fc0332"; //red
        //                     // $statusBgColor = "#ffd6de";
        //                 }
        //             } else if ($statusId == 2 || $statusId == 6) {
        //                 if ($userRole != 'ata') {
        //                     // $ticketDetails = $this->projects_model->get_project_details($projectId);
        //                     // $assignedUser = !empty($ticketDetails->assigned_user_id) ? $ticketDetails->assigned_user_id : '';
        //                     $assignedUserDetails = $this->staff_model->get_userDetails($assignedUser);
        //                     $name = !empty($assignedUserDetails->full_name) ? $assignedUserDetails->full_name : '';
        //                     $phoneNumber = !empty($assignedUserDetails->phonenumber) ? $assignedUserDetails->phonenumber : '';
        //                     $organisation = !empty($assignedUserDetails->organisation) ? $assignedUserDetails->organisation : '';

        //                     if ($assignedUser == get_staff_user_id()) {
        //                         $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">Close Project</p>';
        //                     } else {
        //                         $action = '<div class="ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
        //                                         <p class="action-label">Call - 
        //                                             <span class="ata-name">' . $name . '</span>
        //                                         </p>
        //                                         <p class="action-label">Phone No - 
        //                                             <span class="ata-name">' . $phoneNumber . '</span>
        //                                         </p>
        //                                     </div>';
        //                     }
        //                 } else {
        //                     $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">Review and Resolve</p>';
        //                 }
        //             } else if ($statusId == 3) {
        //                 $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">Closed</p>';
        //             } else if ($statusId == 4) {
        //                 $status_name = 'In Progress';
        //                 $statusColor = "#fc0332";
        //                 $statusBgColor = "#ffd6de";
        //                 $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">Review</p>';
        //             } else if ($statusId == 5) {
        //                 if ($userRole == 'ar') {
        //                     $projectNotes = project_latest_notes($projectId, $statusId);
        //                     $projectNote_content = !empty($projectNotes->content) ? $projectNotes->content : '';
        //                     $task_id = !empty($projectNotes->task_id) ? $projectNotes->task_id : '';
        //                     $projectNote_status = !empty($projectNotes->status) ? $projectNotes->status : '';
        //                     $exception = (!empty($projectNotes->exception) && $projectNotes->exception != 6) ? getExceptionName($projectNotes->exception) : $projectNote_content;
        //                     $action = '<div class="ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
        //                                     <p class="action-label">Rejected</p>
        //                                     <p class="ata-name">' . $projectNote_content . '</p>
        //                                 </div>';
        //                 } else {
        //                     $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">Rejected</p>';
        //                 }
        //             } else if ($statusId == 6) {
        //                 //  $action = '<p class="ata-name font14 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">Close</p>';
        //             }

        //             if (in_array($userRole, ['ar']) && !in_array($statusId, [3, 5])) {
        //                 $assignedAT = $this->projects_model->get_project_at($projectId);
        //                 $assignedUserDetails = $this->staff_model->get_userDetails($assignedAT);
        //                 $name = !empty($assignedUserDetails->full_name) ? $assignedUserDetails->full_name : '';
        //                 $phoneNumber = !empty($assignedUserDetails->phonenumber) ? $assignedUserDetails->phonenumber : '';
        //                 $action = '<div class="ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
        //                                 <p class="action-label">Call - 
        //                                     <span class="ata-name">' . $name . '</span>
        //                                 </p>
        //                                 <p class="action-label">Phone No - 
        //                                     <span class="ata-name">' . $phoneNumber . '</span>
        //                                 </p>
        //                             </div>';
        //             }

        //             if (!in_array($statusId, [3, 5]) && $item['action_date'] < date('Y-m-d')) {
        //                 $status_name = 'Delayed';
        //                 $statusColor = "#fc0332"; //red
        //                 $statusBgColor = "#ffd6de";
        //             }

        //             $actionList .= '<div class="dashboard-row delayed project_' . $projectId . '">
        //                                 <div class="dashboard-cell w270 action-item ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
        //                                     <p>

        //                                         <strong>Issue no.</strong>
        //                                         <span class="landmark" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . strip_tags($landmark) . '">' . $projectId  . '</span>
        //                                     </p>
        //                                 </div>
        //                                 <div class="dashboard-cell w270 action-item ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
        //                                     <p>
        //                                         <strong data-toggle="tooltip" data-placement="top" title="" data-original-title="' . strip_tags($project_name) . '">' . mb_strimwidth($project_name, 0, 30, '...')  . '</strong>
        //                                         <span>near</span>
        //                                         <span class="landmark" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . strip_tags($landmark) . '">' . mb_strimwidth($landmark, 0, 30, '...')  . '</span>
        //                                     </p>
        //                                 </div>
        //                                 <div class="dashboard-cell w170 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
        //                                     <p class="deadline">' . $action_date . '</p>
        //                                     <span class="status status_' . $projectId . '" style="background-color:' . $statusBgColor . '; color:' . $statusColor . ';">' . $status_name . '</span>
        //                                 </div>
        //                                 <div class="dashboard-cell w270 ticket_details" data-project_id="' . $projectId . '" data-role="' . $userRole . '" data-status="' . $statusId . '">
        //                                     <p data-toggle="tooltip" data-placement="top" title="' . strip_tags($description) . '">' . mb_strimwidth($description, 0, 100, '...') . '</p>
        //                                 </div>
        //                                 <div class="dashboard-cell evidence w170">
        //                                     <p class="evidence_img report-location" data-img_type="original" data-project_id="' . $projectId . '">
        //                                     <i class="fa fa-eye" aria-hidden="true"></i><span>View</span></p>
        //                                 </div>';

        //             if (in_array($userRole, ['at', 'ar'])) {
        //                 $actionList .= '<div class="dashboard-cell action-cell w20P action_' . $projectId . '">' . $action . '</div>';
        //             }

        //             $actionList .= '</div><div class="row-saperator"></div>';
        //         }

        //         echo json_encode([
        //             'success' => TRUE,
        //             'message' => $actionList,
        //             'action_items_cnt' => $action_items_cnt
        //         ]);
        // } else {
        //     echo json_encode([
        //         'fail' => FALSE,
        //         'message' => 'Invalid request. Please try again.',
        //     ]);
        // }
    }





}
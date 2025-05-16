<!-- <link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" /> -->
<link href="<?= base_url('/assets/css/jquery-ui.css'); ?>" rel="stylesheet" />
<?php


$project_id = ($ticketDetails->project_id) ? $ticketDetails->project_id : '';
$project_name = !empty($ticketDetails->project_name) ? ucfirst($ticketDetails->project_name) : '';
$landmark = !empty($ticketDetails->landmark) ? $ticketDetails->landmark : '';
$projectStatus = !empty($ticketDetails->project_status) ? $ticketDetails->project_status : '';
$frozen = !empty($ticketDetails->frozen) ? $ticketDetails->frozen : 0;
$sub_ticket_id = !empty($ticketDetails->sub_ticket_id) ? $ticketDetails->sub_ticket_id : 0;
$parent_id = !empty($ticketDetails->parent_id) ? $ticketDetails->parent_id : 0;
$title = $project_name . '<span> near, </span>' . $landmark;
$ticket_id = !empty($ticketDetails->sub_id) ? $ticketDetails->sub_id : $project_id;
$projectDueDate = !empty($ticketDetails->deadline) ? setDateFormat($ticketDetails->deadline) : "";

$area = !empty($ticketDetails->area_name) ? $ticketDetails->area_name : '';
$region = !empty($ticketDetails->region_name) ? $ticketDetails->region_name : '';
$subregion =  !empty($ticketDetails->sub_region_name) ? $ticketDetails->sub_region_name : '';
$source = !empty($ticketDetails->company) ? $ticketDetails->company : (!empty($ticketDetails->firstname) ? $ticketDetails->firstname. ' '.$ticketDetails->lastname:'');

$userRole = $GLOBALS['current_user']->role_slug_url;

$ticketStatus = !empty($ticketDetails->status_name) ? $ticketDetails->status_name : '';
if ($projectStatus == 5) {
    $projectNotes = project_latest_notes($project_id, $projectStatus);
    $projectNote_content = !empty($projectNotes->content) ? $projectNotes->content : '';
    // $ticketStatus = $ticketStatus . ' (' . $projectNote_content . ')';
}

?>
<div class="content pT0">
    <div class="box-wrapper">

    </div>
    
    <input type="hidden" class="ticketPopupId" value="<?php echo $project_id; ?>" />
    <?php
    if (empty($frozen)) {
        if ((get_staff_user_id() == $assignedUser)) {
            if ($userRole == 'at') {
                // if ($projectStatus == 1 || $projectStatus == 2 || $projectStatus == 5 || ($projectStatus == 4 && $milestoneStatus == 4) || $projectStatus == 11 || $projectStatus == 12) {
                if ($projectStatus == 1 || $projectStatus == 2 || $projectStatus == 5 || $projectStatus == 11 || $projectStatus == 12 || $projectStatus == 15 || $projectStatus == 16) {
                    $actionLabel = '';
                    if ($projectStatus == 1 || $projectStatus == 2 || $projectStatus == 5) {
                        $actionLabel = ' - Accept or Refer';
                    } else if ($projectStatus == 12 || $projectStatus == 15 || $projectStatus == 16) {
                        $actionLabel = ' - Close/Reopen Project';
                    }else if($projectStatus == 11){
                        $actionLabel = ' - Approve/Reject Longterm Request';
                    }
                    ?>
                    <div class="box-wrapper mB30">
                        <div class="panel panel-default sub-ticket-panel mB0 border-bottom-0">
                            <div class="panel-heading">
                                Action<?= $actionLabel ?>
                            </div>
                        </div>
                        <?php $this->load->view('admin/tickets/at_ticket_detail'); ?>
                    </div>
                <?php }
            }
            if ((($userRole == 'ata') && (get_staff_user_id() == $assignedUser)) && empty($sub_ticket_id)) {

                if (!empty($projectStatus)) {
                    $actionLabel = '';
                    if ($projectStatus == 1 || $projectStatus == 2 || $projectStatus == 5 || $projectStatus == 6 || $projectStatus == 10) {
                        $actionLabel = ' ';
                    }
                ?>
                
                    <div class="box-wrapper mB30">
                            <div class="panel panel-default sub-ticket-panel mB0 border-bottom-0">
                                <div class="panel-heading">
                                    Action<?= $actionLabel ?>
                                </div>
                            </div>
                            <?php $this->load->view('admin/tickets/ata_ticket_detail'); ?>
                    </div>
                <?php }
            }
        }
        //3=>Closed, 5=>Rejected
        // if ($userRole == 'ar' && ($projectStatus == 3 || $projectStatus == 5)) {
        // if ($userRole == 'ar' && (($projectStatus == 3 && (get_staff_user_id() != $assignedUser) && empty($sub_ticket_id)) || $projectStatus == 5)) {

        // if ($userRole == 'ar' && (get_staff_user_id() == $assignedUser || (!empty($assignedAts) && (in_array($assignedUser,$assignedAts)))) && (($projectStatus == 3  && empty($sub_ticket_id)) || $projectStatus == 5)) {
        // if ($userRole == 'ar' && ((get_staff_user_id() == $assignedUser && $projectStatus != 3)|| (!empty($assignedAts) && (in_array($assignedUser,$assignedAts)))) && (empty($sub_ticket_id) || $projectStatus == 5)) {
        if ($userRole == 'ar') {
            $actionLabel = '';

            // if ($projectStatus == 3 && !$activeUser) {
               
            // }else{
                // if ($projectStatus == 3 && empty($sub_ticket_id)) {
                //     $actionLabel = ' - Reopen';
                // }
                // if(in_array($projectStatus,['3','5'])){
                
                    
                ?>
                <div class="box-wrapper mB30">
                    <?php
                    if(get_staff_user_id() == $assignedUser){
                        $actionLabel = ' - Refer'; ?>
                
                        <div class="panel panel-default sub-ticket-panel mB0 border-bottom-0">
                            <div class="panel-heading">
                                Action<?= $actionLabel ?>
                            </div>
                        </div>

                    <?php } ?>
                    <?php $this->load->view('admin/tickets/ar_ticket_detail'); ?>
                </div>
        <?php //} 
            //} 
        }

        if (($userRole == 'qc') && $projectStatus == 3) {

            if (!empty($projectStatus)) {
                $actionLabel = '';
                if ($projectStatus == 3) {
                    $actionLabel = ' - Verified';
                }
            ?>
                <div class="box-wrapper mB30">
                        
                        <?php $this->load->view('admin/tickets/qc_ticket_detail'); ?>
                </div>
            <?php }
        }

        if (($userRole == 'aa') && $projectStatus == 13) {

            if (!empty($projectStatus)) {
                $actionLabel = '';

                if ($projectStatus == 13) {
                    $actionLabel = ' - Mark Ticket Verification Status'; ?>
                        <div class="box-wrapper mB30">
                
                        <div class="panel panel-default sub-ticket-panel mB0 border-bottom-0">
                            <div class="panel-heading">
                                Action <?= $actionLabel ?>
                            </div>
                        </div>

                            <?php $this->load->view('admin/tickets/aa_ticket_detail'); ?>
                        </div>
                    <?php } ?>
                
            <?php }
        }
    } ?>
    <div class='thetop' id="section2"></div>
    <?php
    
        if($projectStatus==3 && $this->session->userdata('staff_role')==3){
            
            ?>
        
    <!-- <div class="box-wrapper">
        <div class="titleHead">
            <h4 class="titleHead_title">Action - Reopen</h4>
            <div class="titleHead_title-wrapper">
                <span style="color:red" id="reopenMsg"></span>
            <input class="form-control" type="text" name="reasontext" id="reasontext" placeholder="Reason">
            
            <button onclick="reopenTicket();" type="submit" class="btn btn-primary mT15">Re-open</button>
            </div>
        </div>
    </div> -->
    <?php } ?>
    
    <div class="box-wrapper">
        <h4 class="action-title sliderTitle no-border pull-right">
            <?php //echo $title; 
            ?>
            <!-- <a href="#" class="pdf-download position-relative pull-right" title="Download as PDF" onclick="printDiv('ticketDetailsPopup')"> <i class="fa fa-file-pdf-o"></i></a> -->
            <a href="#" class="pdf-download position-relative pull-right" title="Download as PDF" id="ticket_detail_pdf"> <i class="fa fa-file-pdf-o"></i></a>
        </h4>
        <ul class="nav nav-tabs pull-left ticket-detail-tabs">
            <li class="active project-detail-list"><a href="#ticket-details" data-toggle="tab"><?php echo _l('project_details');?></a></li>
            <li class="print-none project-history-list"><a class="" href="#ticket-history" data-toggle="tab"><?php echo _l('project_history');?></a></li>
        </ul>

        <div class="clearfix"></div>
        <div class="tab-content faq-cat-content">
            <div class="tab-pane active in fade" id="ticket-details">
                <div class="panel-group" id="accordion-cat-1">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <a data-parent="#accordion-cat-1" href="javascript:void(0)">
                                <h4 class="panel-title text-center">
                                    <p class="pull-left"><label><?php echo _l('ticket_id');?>: </label><span><?php echo $ticket_id; ?></span></p>
                                    <?php if ($frozen) { ?>
                                    <p class="d-inline-block"><label class="frozen mR0">FROZEN</label></p>
                                    <?php } ?>
                                    <p class="pull-right"><label><?php echo _l('raised_on')?>: </label><span><?php echo !empty($ticketDetails->logged_date) ? setDateFormat($ticketDetails->logged_date) : ''; ?></span></p>
                                    <div class="clearfix "></div>
                                </h4>
                            </a>
                        </div>
                        <div id="basic-details" class="panel-collapse ">
                            <div class="panel-body pB0">
                                <p class="reported-detail font-size-14 semibold"><?php echo $project_name; ?></p>
                                <!-- <p>This has been reported in (Region), (Subregion) near (Landmark) by (Type of user) </p> -->
                                <p class="reported-detail"><?php echo _l('this_reported_in');?> <span class="font-size-14"><?php echo $region; ?></span>, <span class="font-size-14"><?php echo $subregion; ?></span> <?php echo _l('near');?> <span class="font-size-14"><?php echo $landmark; ?></span> <?php echo _l('by');?> <span class="font-size-14"><?php echo $source; ?></span></p>

                                <p class="reported-detail due-date">
                                <?php echo _l('milestone_due_date');?>: <strong class="font-size-14"> <?php echo $projectDueDate; ?></strong>
                                </p>
                                <?php if (!empty($tasks)) { ?>
                                    <div class="panel panel-default border-0 box-shadow-0">
                                        <!-- <div class="panel-heading">
                                            <a data-parent="#accordion-cat-1" href="javascript:void(0)">
                                                <h4 class="panel-title">
                                                    Status (<?php echo $ticketStatus; ?>) -->
                                        <!--<span class="pull-right"><i></i></span>-->
                                        <!-- </h4>
                                            </a>
                                        </div> -->

                                        <div id="status" class="panel-collapse  status-panel">
                                            <div class="panel-body pL0 pR0 table-scroll">
                                                <table class="table table-borderless table-fixed">
                                                    <thead>
                                                        <tr>
                                                            <th width="100"><?php echo _l('project_milestone');?></th>
                                                            <!-- <th>Duration</th> -->
                                                            <th width="100"><?php echo _l('status');?></th>
                                                            <th width="120"><?php echo _l('task_single_start_date');?></th>
                                                            <!-- <th>Due Date</th> -->
                                                            <th width="140"><?php echo _l('task_assigned');?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($tasks as $task) {
                                                            $task_status = !empty($task['task_status']) ? $task['task_status'] : "Pending";
                                                            $taskId = $task['task_id'];
                                                            $assignedUserName = '';
                                                            $childProjectStatus = '';
                                                            if ($hasChild) {
                                                                $childTaskDetails = get_child_task_details($taskId);
                                                                $task['status'] = !empty($childTaskDetails->status) ? $childTaskDetails->status : 0;
                                                                $subTaskTicketId = !empty($childTaskDetails->rel_id) ? $childTaskDetails->rel_id : $ticket_id;
                                                                $subTicketAssignee = !empty(getProjectAssignedUser($subTaskTicketId)) ? getProjectAssignedUser($subTaskTicketId) : '';
                                                                $assignedUserName = get_staff_full_name($subTicketAssignee);
                                                                // pre($task['status']);
                                                                $childProjectStatus = get_project_status($subTaskTicketId);
                                                                if ((empty($childProjectStatus) || $childProjectStatus == 1) && $task['status'] == 2) {
                                                                    $task['status'] = 0;
                                                                }
                                                                if ($childProjectStatus == 3) {
                                                                    $task['status'] = 4;
                                                                }
                                                            } else {
                                                                $assignedUserName = !empty($assignedUserDetails->full_name) ? $assignedUserDetails->full_name : '';
                                                            }
                                                            
                                                            if(!empty($ticketDetails->task_status) && $ticketDetails->task_status == 1){
                                                                $task['status'] = 0;
                                                            }
                                                            if(!empty($ticketDetails->task_status) && $ticketDetails->task_status == 3 && $task['status'] == 2){
                                                                $task['status'] = 0;
                                                            }
                                                            
                                                            $td_color = "complete";
                                                            if (empty($task['status'])) {
                                                                $td_color = "pending";
                                                                $task_status = 'Pending';
                                                            } else if ($task['status'] == 2) {
                                                                $td_color = "wip";
                                                                $task_status = 'In Progress';
                                                            } else if ($task['status'] == 4) {
                                                                $td_color = "complete";
                                                                $task_status = 'Completed';
                                                            }
                                                            if ($hasChild && $task['status'] == 4) {
                                                                $task_status = 'Completed';
                                                            }
                                                        ?>
                                                            <tr>
                                                                <td><?= $task['task_name'] ? $task['task_name'] : ""; ?></td>
                                                                <!-- <td><?= $task['task_days'] ? $task['task_days'] . " Days" : ""; ?></td> -->
                                                                <td class="<?= $td_color; ?>"><?= $task_status; ?> </td>
                                                                <td><?= $task['startdate'] ? setDateFormat($task['startdate']) : ""; ?></td>
                                                                <!-- <td><?= $task['duedate'] ? $task['duedate'] : ""; ?></td> -->
                                                                <td><?= $assignedUserName ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        function reopenTicket() {
                                       var project_id =<?php echo $ticket_id; ?>;
                                       var reasontext = $("#reasontext").val();  
                                       if(reasontext!=''){
                                        $.ajax({
                                            type: "POST",
                                            data: {project_id:project_id,reasontext:reasontext},
                                            url: admin_url + 'reportatr/reopen_ticket',
                                            success: function(data){
                                            $("#reopenMsg").html("Ticket Re-open successfully.");
                                            }
                                        }); 
                                    }else{
                                        $("#reopenMsg").html("Please enter the reason to Re-open ticket.");
                                       }              
                                    }

                                    </script>
                                <?php } ?>
                                <div class="spearator"></div>
                            </div>
                            <div id="issue-details" class="panel-collapse ">
                                <div class="panel-body pL0 pR0 pB0">
                                    <ul class="detail-list issue-detail-list pR0">

                                        <!-- Separate  Image and Document Start -->
                                        
                                        <?php if (!empty($project_id) && !empty($evidenceImages)) {
                                            $evidenceImageData = array();
                                            
                                            if(!empty($evidenceImages)){
                                                foreach ($evidenceImages as $image) {
                                                    if($image['task_id']!=0){
                                                        $project_ids = $image['task_id'];
                                                        $tasks_ids = 'tasks';
                                                    }else{
                                                        $project_ids = $image['project_id'];
                                                        $tasks_ids = 'projects';
                                                    }
                                                    $file_id = $image['id'];
                                                    $file_name = $image['file_name'];
                                                    $file_thumb = $image['subject'];
                                                    $file_optimized = $image['thumbnail_link'];
                                                    $milestone = $image['milestone'];
                                                    $latitude = $image['latitude'];
                                                    $longitude = $image['longitude'];
                                                    $filetype = $image['filetype'];
                                                    $contact_id = $image['contact_id'];
                                                    $staffid = $image['staffid'];
                                                    $dateadded = $image['dateadded'];
                                                    $imgPath = 'uploads/'.$tasks_ids.'/' . $project_ids . '/';

                                                    $location = '';
                                                    if (!empty($latitude) && !empty($longitude)) {
                                                        // $url = base_url('/admin/tickets/viewmap/' . $project_id . '?lat=' . $latitude . '&lang=' . $longitude . '&output=embed');
                                                        // $location = '<a href="' . $url . '" class="mT10 text-center d-block" target="_blank"><i class="fa fa-map-marker"></i>View Location</a>';
                                                        $location = '<a href="https://maps.google.com/maps?q=' . $latitude . ',' . $longitude . '" class="mT10 text-center d-block" target="_blank"><i class="fa fa-map-marker"></i>View Location</a>';
                                                    } else {
                                                        $location = '';
                                                    }

                                                    if ($filetype == 'application/pdf') {
                                                        $evidenceImageData['document'][] = array(
                                                            'imgPath' => $imgPath,
                                                            'milestone' => $milestone,
                                                            'projectId' => $project_id,
                                                            'file_id' => $file_id,
                                                            'file_name' => $file_name,
                                                            'file_thumb' => $file_thumb,
                                                            'file_optimized' => $file_optimized,
                                                            'location' => $location,
                                                            'filetype' => $filetype,
                                                            'contact_id' => $contact_id,
                                                            'staffid' => $staffid,
                                                            'dateadded' => $dateadded
                                                        );
                                                    } else {
                                                        $evidenceImageData['image'][] = array(
                                                            'imgPath' => $imgPath,
                                                            'milestone' => $milestone,
                                                            'projectId' => $project_id,
                                                            'file_id' => $file_id,
                                                            'file_name' => $file_name,
                                                            'file_thumb' => $file_thumb,
                                                            'file_optimized' => $file_optimized,
                                                            'location' => $location,
                                                            'filetype' => $filetype,
                                                            'contact_id' => $contact_id,
                                                            'staffid' => $staffid,
                                                            'dateadded' => $dateadded
                                                        );
                                                    }
                                                }
                                            }

                                            $latestImageData = array();
                                            if(!empty($latestImages )){
                                                $date = '';
                                                //echo "<pre>";print_r($latestImages);die();
                                                foreach ($latestImages as $image) {
                                                    $file_id = $image['id'];
                                                    $file_name = $image['file_name'];
                                                    $file_thumb = $image['subject'];
                                                    $file_optimized = $image['thumbnail_link'];
                                                    $milestone = $image['milestone'];
                                                    $latitude = $image['latitude'];
                                                    $longitude = $image['longitude'];
                                                    $filetype = $image['filetype'];
                                                    $contact_id = $image['contact_id'];
                                                    $staffid = $image['staffid'];
                                                    $dateAdded = date('Y-m-d',strtotime($image['dateadded']));
                                                    if(empty($date)){
                                                        $date = $dateAdded;
                                                    }

                                                    if($date != $dateAdded){
                                                        continue;
                                                    }

                                                    $imgPath = 'uploads/tasks/' . $milestone . '/';

                                                    $location = '';
                                                    if (!empty($latitude) && !empty($longitude)) {
                                                        // $url = base_url('/admin/tickets/viewmap/' . $project_id . '?lat=' . $latitude . '&lang=' . $longitude . '&output=embed');
                                                        // $location = '<a href="' . $url . '" class="mT10 text-center d-block" target="_blank"><i class="fa fa-map-marker"></i>View Location</a>';
                                                        $location = '<a href="https://maps.google.com/maps?q=' . $latitude . ',' . $longitude . '" class="mT10 text-center d-block" target="_blank"><i class="fa fa-map-marker"></i>View Location</a>';
                                                    } else {
                                                        $location = '';
                                                    }

                                                    if ($filetype == 'application/pdf') {
                                                        $latestImageData['document'][] = array(
                                                            'imgPath' => $imgPath,
                                                            'milestone' => $milestone,
                                                            'projectId' => $project_id,
                                                            'file_id' => $file_id,
                                                            'file_name' => $file_name,
                                                            'file_thumb' => $file_thumb,
                                                            'file_optimized' => $file_optimized,
                                                            'location' => $location,
                                                            'filetype' => $filetype,
                                                            'contact_id' => $contact_id,
                                                            'staffid' => $staffid
                                                        );
                                                    } else {
                                                        $latestImageData['image'][] = array(
                                                            'imgPath' => $imgPath,
                                                            'milestone' => $milestone,
                                                            'projectId' => $project_id,
                                                            'file_id' => $file_id,
                                                            'file_name' => $file_name,
                                                            'file_thumb' => $file_thumb,
                                                            'file_optimized' => $file_optimized,
                                                            'location' => $location,
                                                            'filetype' => $filetype,
                                                            'contact_id' => $contact_id,
                                                            'staffid' => $staffid
                                                        );
                                                    }
                                                }
                                            }
                                            // pre($evidenceImageData);
                                            $imageActive = '';
                                            $docActive = '';
                                            $latImageActive = '';
                                            $latDocActive = '';
                                            if (!empty($evidenceImageData['image'])) {
                                                $imageActive = ' active in ';
                                                $docActive = '';
                                            }else{
                                                $imageActive = '';
                                                $docActive = ' active in ';
                                            }
                                            if(!empty($latestImageData )){
                                                $imageActive = '';
                                                $docActive = '';
                                                if (!empty($latestImageData['image'])) {
                                                    $latImageActive = ' active in ';
                                                    $latDocActive = '';
                                                }else{
                                                    $latImageActive = '';
                                                    $latDocActive = ' active in ';
                                                }
                                            }
                                            ?>

                                            <ul class="nav nav-tabs ticket-detail-tabs w100P pL15 mB15">
                                                <li class="<?= $imageActive ?> print-none"><a href="#image" data-toggle="tab"><?php echo _l('images_doc');?></a></li>
                                                <!-- <li class="<?php //$docActive ?> print-none"><a href="#document" data-toggle="tab">Raised Document(s)</a></li> -->
                                            <?php if(!empty($latestImageData) && empty($latestImageData)){ ?>
                                                <li class="mT0 pL15 pT5 mT0 mL15 md-hide" style="border-left: 1px solid #ddd"><a style=" border-radius:0" class="p-0 deskH35"></a></li>
                                                <li class="<?= $latImageActive ?> print-none mL15"><a href="#latestEvi" data-toggle="tab">Latest Image(s) & Latest Document(s)</a></li>
                                                <!-- <li class="<?php $latDocActive ?> print-none"><a href="#latestLoc" data-toggle="tab">Latest Document(s)</a></li> -->
                                            <?php } ?>
                                            </ul>

                                            <ul class="nav nav-tabs only-print">
                                                <li class="" style="font-size: 14px; font-weight: 700; margin-top:15px;">Image(s)</li>
                                            </ul>
                                            <div class="clearfix "></div>

                                            <div class="tab-content faq-cat-content">
                                                <div class="panel-body pL0 pR0 pB0">
                                                    <div class="tab-content">
                                                        <!-- Images -->
                                                        <div class="tab-pane active in fade  pr-mT0" id="image">
                                                            <div class="">
                                                                <ul class="beforeAfterImage">
                                                                    <li>
                                                                        <h4><?php echo _l('raised');?></h4>
                                                                        <div class="beforeAfterImage_ListWrapper">
                                                                        <?php
                                                                if (!empty($evidenceImageData['image'])) {
                                                                    ?>
                                                                    <ul class="lightgallery">
                                                                        <?php
                                                                        foreach ($evidenceImageData['image'] as $imageData) {
                                                                            $imgPath = !empty($imageData['imgPath']) ? $imageData['imgPath'] : '';
                                                                            $file_name = !empty($imageData['file_name']) ? $imageData['file_name'] : '';
                                                                            $file_thumb = !empty($imageData['file_thumb']) ? $imageData['file_thumb'] : '';
                                                                            $file_optimized = !empty($imageData['file_optimized']) ? $imageData['file_optimized'] : '';
                                                                            $loc = !empty($imageData['location']) ? $imageData['location'] : '';
                                                                            if($imageData['contact_id']!=0){
                                                                        ?>
                                                                            <li style="border-right:none" class="text-center img_item" data-responsive="<?php echo base_url($imgPath . $file_name); ?>" data-src="<?php echo base_url($imgPath . $file_name); ?>">
                                                                                <a href="javascript:void(0);">
                                                                                    <figure>
                                                                                        <img class="" src="<?php echo base_url($imgPath . $file_name); ?>" data-src="<?php echo base_url($imgPath . $file_name); ?>">
                                                                                    </figure>
                                                                                </a>
                                                                            </li>
                                                                            <li style="border-right:none" class="text-center" data-responsive="<?php echo base_url($imgPath . $file_name); ?>" data-src="<?php echo base_url($imgPath . $file_name); ?>">
                                                                                <?php echo $loc; ?>
                                                                            </li>
                                                                        <?php }} ?>
                                                                            </ul>

                                                                   
                                                                <?php   } else { 
                                                                    
                                                                    ?>
                                                                    <p class="text-warning mT10"><i class="fa fa-warning"></i><?php echo _l('no_img_available');?></p>
                                                                <?php } ?>
                                                                <ul>
                                                                        <?php if (!empty($evidenceImageData['document'])) {
                                                                    foreach ($evidenceImageData['document'] as $imageData) {
                                                                        $imgPath = !empty($imageData['imgPath']) ? $imageData['imgPath'] : '';
                                                                        $file_name = !empty($imageData['file_name']) ? $imageData['file_name'] : '';
                                                                        $loc = !empty($imageData['location']) ? $imageData['location'] : '';
                                                                        if($imageData['contact_id']!=0){
                                                                ?>
                                                                        <li style="border-right:none" class="col-xs-6 col-sm-4 col-md-3" data-responsive="<?php echo base_url($imgPath . $file_name); ?>" data-src="<?php echo base_url($imgPath . $file_name); ?>">
                                                                            <a href="<?php echo base_url($imgPath . $file_name); ?>" target="_blank">
                                                                                <figure><img class="print-document-icon" src="<?php echo base_url('/assets/images/pdf-icon.png'); ?>"></figure>
                                                                            </a>
                                                                        </li>
                                                                        <li style="border-right:none" class="text-center" data-responsive="<?php echo base_url($imgPath . $file_name); ?>" data-src="<?php echo base_url($imgPath . $file_name); ?>">
                                                                                <?php echo $loc; ?>
                                                                            </li>
                                                                    <?php }}
                                                                } else { ?>
                                                                    <p class="text-warning mT10"><i class="fa fa-warning"></i> <?php echo _l('no_doc_available');?></p>
                                                                <?php } ?>
                                                                    </ul>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <h4><?php echo _l('closed');?></h4>
                                                                        <div class="beforeAfterImage_ListWrapper">
                                                                        <?php if (!empty($evidenceImageData['image'])) { ?>
                                                                    <ul class="lightgallery">
                                                                        <?php
                                                                        $no_img = $evidence_date = array();
                                                                        $showevidence = true;
                                                                        // echo "<pre>";print_r($evidenceImageData);echo "</pre>";
                                                                        foreach ($evidenceImageData['image'] as $k=>$imageData) {
                                                                            $imgPath = !empty($imageData['imgPath']) ? $imageData['imgPath'] : '';
                                                                            $file_name = !empty($imageData['file_name']) ? $imageData['file_name'] : '';
                                                                            $file_thumb = !empty($imageData['file_thumb']) ? $imageData['file_thumb'] : '';
                                                                            $file_optimized = !empty($imageData['file_optimized']) ? $imageData['file_optimized'] : '';
                                                                            $loc = !empty($imageData['location']) ? $imageData['location'] : '';
                                                                            $evidence_date[$k] = $imageData['dateadded'];
                                                                            if($imageData['staffid']!=0){
                                                                                $roleId = get_roleid($imageData['staffid']);
                                                                            } else {
                                                                                $roleId =0;
                                                                            } 
                                                                            $datediff = 0; 
                                                                            // echo "<pre>";print_r($evidence_date);echo "</pre>";
                                                                            if(!empty($evidence_date[$k-1])){
                                                                                $datediff = strtotime($evidence_date[$k-1])-strtotime($evidence_date[$k]);
                                                                                if($k==1) $datediff = 0;
                                                                                if($datediff<=60)
                                                                                    $showevidence = true;
                                                                                else
                                                                                    $showevidence = false;
                                                                                    
                                                                                //  echo "Hello ".count($evidence_date). " - ".$k." - ".$datediff;
                                                                            }
                                                                            if($showevidence==false) break;
                                                                            if($imageData['staffid']!=0 && $roleId != 9 && (count($evidence_date)<=2 || $showevidence )){
                                                                                
                                                                                $no_img []= 1;
                                                                        ?>
                                                                            <li style="border-right:none" class="text-center img_item" data-responsive="<?php echo base_url($imgPath . $file_name); ?>" data-src="<?php echo base_url($imgPath . $file_name); ?>">
                                                                                <a href="javascript:void(0);">
                                                                                    <figure>
                                                                                        <img class="" src="<?php echo base_url($imgPath . $file_name); ?>" data-src="<?php echo base_url($imgPath . $file_name); ?>">
                                                                                    </figure>
                                                                                </a>
                                                                            </li>
                                                                            <li style="border-right:none" class="text-center" data-responsive="<?php echo base_url($imgPath . $file_name); ?>" data-src="<?php echo base_url($imgPath . $file_name); ?>">
                                                                                <?php echo $loc; ?>
                                                                        <?php }else{
                                                                            
                                                                            
                                                                            ?>
                                                                            
                                                                            <?php }}
                                                                             ?>
                                                                            
                                                                    </ul>
                                                                     
                                                                <?php 
                                                            if(!in_array('1',$no_img)){ 
                                                                
                                                                   
                                                                ?>
                                                            <p class="text-warning mT10"><i class="fa fa-warning"></i><?php echo _l('no_img_available');?></p>
                                                            <?php }
                                                            
                                                            }  ?>
                                                                    
                                                                <ul>
                                                                <?php if (!empty($evidenceImageData['document'])) {
                                                                            foreach ($evidenceImageData['document'] as $imageData) {
                                                                                $imgPath = !empty($imageData['imgPath']) ? $imageData['imgPath'] : '';
                                                                                $file_name = !empty($imageData['file_name']) ? $imageData['file_name'] : '';
                                                                                $loc = !empty($imageData['location']) ? $imageData['location'] : '';
                                                                                if($imageData['staffid']!=0){
                                                                                    $roleId = get_roleid($imageData['staffid']);
                                                                                } else {
                                                                                    $roleId =0;
                                                                                }
                                                                                if($imageData['staffid']!=0 && $roleId != 9){
                                                                        ?>
                                                                        <li style="border-right:none" class="col-xs-6 col-sm-4 col-md-3" data-responsive="<?php echo base_url($imgPath . $file_name); ?>" data-src="<?php echo base_url($imgPath . $file_name); ?>">
                                                                            <a href="<?php echo base_url($imgPath . $file_name); ?>" target="_blank">
                                                                                <figure><img class="print-document-icon" src="<?php echo base_url('/assets/images/pdf-icon.png'); ?>"></figure>
                                                                            </a>
                                                                        </li>
                                                                        <li style="border-right:none" class="text-center" data-responsive="<?php echo base_url($imgPath . $file_name); ?>" data-src="<?php echo base_url($imgPath . $file_name); ?>">
                                                                                <?php echo $loc; ?>
                                                                            </li>
                                                                        <?php }}
                                                                        } else { ?>
                                                                                <p class="text-warning mT10"><i class="fa fa-warning"></i> <?php echo _l('no_doc_available');?></p>
                                                                            <?php } ?>
                                                                </ul>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <h4><?php echo _l('qc_verified');?></h4>
                                                                        <div class="beforeAfterImage_ListWrapper">
                                                                        <?php if (!empty($evidenceImageData['image'])) { ?>
                                                                        <ul class="lightgallery">
                                                                            <?php
                                                                            $no_img= array();
                                                                            foreach ($evidenceImageData['image'] as $imageData) {
                                                                                $imgPath = !empty($imageData['imgPath']) ? $imageData['imgPath'] : '';
                                                                                $file_name = !empty($imageData['file_name']) ? $imageData['file_name'] : '';
                                                                                $file_thumb = !empty($imageData['file_thumb']) ? $imageData['file_thumb'] : '';
                                                                                $file_optimized = !empty($imageData['file_optimized']) ? $imageData['file_optimized'] : '';
                                                                                $loc = !empty($imageData['location']) ? $imageData['location'] : '';
                                                                                if($imageData['staffid']!=0){
                                                                                    $roleId = get_roleid($imageData['staffid']);
                                                                                } else {
                                                                                    $roleId =0;
                                                                                }
                                                                                if($imageData['staffid']!=0 && $roleId == 9){
                                                                                    $no_img []= 1;
                                                                                    if($file_name!='no'){
                                                                            ?>
                                                                                <li style="border-right:none" class="text-center img_item" data-responsive="<?php echo base_url($imgPath . $file_name); ?>" data-src="<?php echo base_url($imgPath . $file_name); ?>">
                                                                                    <a href="javascript:void(0);"><?php echo $file_name;?>
                                                                                        <figure>
                                                                                            <img class="" src="<?php echo base_url($imgPath . $file_name); ?>" data-src="<?php echo base_url($imgPath . $file_name); ?>">
                                                                                        </figure>
                                                                                    </a>
                                                                                </li>
                                                                                <?php } ?>
                                                                                <li style="border-right:none" class="text-center" data-responsive="<?php echo base_url($imgPath . $file_name); ?>" data-src="<?php echo base_url($imgPath . $file_name); ?>">
                                                                                    <?php echo $loc; ?>
                                                                            <?php }else{
                                                                                
                                                                                ?>
                                                                                
                                                                                <?php }} ?>
                                                                        </ul>
                                                                        
                                                                        <?php 
                                                                        if(!in_array('1',$no_img)){ ?>
                                                                        <p class="text-warning mT10"><i class="fa fa-warning"></i><?php echo _l('no_img_available');?></p>
                                                                        <?php }

                                                                        } else { ?>
                                                                        <!-- <p class="text-warning mT10"><i class="fa fa-warning"></i>No evidence image available</p> -->
                                                                        <?php } ?>
                                                                        <ul>
                                                                            <?php if (!empty($evidenceImageData['document'])) {
                                                                                foreach ($evidenceImageData['document'] as $imageData) {
                                                                                    $imgPath = !empty($imageData['imgPath']) ? $imageData['imgPath'] : '';
                                                                                    $file_name = !empty($imageData['file_name']) ? $imageData['file_name'] : '';
                                                                                    $loc = !empty($imageData['location']) ? $imageData['location'] : '';
                                                                                    if($imageData['staffid']!=0){
                                                                                        $roleId = get_roleid($imageData['staffid']);
                                                                                    } else {
                                                                                        $roleId =0;
                                                                                    }
                                                                                    if($imageData['staffid']!=0 && $roleId == 9){
                                                                            ?>
                                                                            <li style="border-right:none" class="col-xs-6 col-sm-4 col-md-3" data-responsive="<?php echo base_url($imgPath . $file_name); ?>" data-src="<?php echo base_url($imgPath . $file_name); ?>">
                                                                                <a href="<?php echo base_url($imgPath . $file_name); ?>" target="_blank">
                                                                                    <figure><img class="print-document-icon" src="<?php echo base_url('/assets/images/pdf-icon.png'); ?>"></figure>
                                                                                </a>
                                                                            </li>
                                                                            <li style="border-right:none" class="text-center" data-responsive="<?php echo base_url($imgPath . $file_name); ?>" data-src="<?php echo base_url($imgPath . $file_name); ?>">
                                                                                    <?php echo $loc; ?>
                                                                                </li>
                                                                            <?php }}
                                                                            } else { ?>
                                                                                    <p class="text-warning mT10"><i class="fa fa-warning"></i> <?php echo _l('no_doc_available');?></p>
                                                                            <?php } ?>
                                                                        </ul>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <!-- Document -->
                                                        <div class="tab-pane <?php $docActive ?> fade" id="document">
                                                        <div class="">
                                                                <ul class="beforeAfterImage">
                                                                    <li>
                                                                        <h4>Raised</h4>
                                                                        <div class="beforeAfterImage_ListWrapper">
                                                                        <ul>
                                                                <?php if (!empty($evidenceImageData['document'])) {
                                                                    foreach ($evidenceImageData['document'] as $imageData) {
                                                                        $imgPath = !empty($imageData['imgPath']) ? $imageData['imgPath'] : '';
                                                                        $file_name = !empty($imageData['file_name']) ? $imageData['file_name'] : '';
                                                                        $loc = !empty($imageData['location']) ? $imageData['location'] : '';
                                                                        if($imageData['contact_id']!=0){
                                                                ?>
                                                                        <li style="border-right:none" class="col-xs-6 col-sm-4 col-md-3" data-responsive="<?php echo base_url($imgPath . $file_name); ?>" data-src="<?php echo base_url($imgPath . $file_name); ?>">
                                                                            <a href="<?php echo base_url($imgPath . $file_name); ?>" target="_blank">
                                                                                <figure><img class="print-document-icon" src="<?php echo base_url('/assets/images/pdf-icon.png'); ?>"></figure>
                                                                            </a>
                                                                        </li>
                                                                        <li style="border-right:none" class="text-center" data-responsive="<?php echo base_url($imgPath . $file_name); ?>" data-src="<?php echo base_url($imgPath . $file_name); ?>">
                                                                                <?php echo $loc; ?>
                                                                            </li>
                                                                    <?php }}
                                                                } else { ?>
                                                                    <p class="text-warning mT10"><i class="fa fa-warning"></i> <?php echo _l('no_doc_available');?></p>
                                                                <?php } ?>
                                                            </ul> 
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <h4>Closed</h4>
                                                                        <div class="beforeAfterImage_ListWrapper">
                                                                        <ul>
                                                                        <?php if (!empty($evidenceImageData['document'])) {
                                                                            foreach ($evidenceImageData['document'] as $imageData) {
                                                                                $imgPath = !empty($imageData['imgPath']) ? $imageData['imgPath'] : '';
                                                                                $file_name = !empty($imageData['file_name']) ? $imageData['file_name'] : '';
                                                                                $loc = !empty($imageData['location']) ? $imageData['location'] : '';
                                                                                if($imageData['staffid']!=0){
                                                                                    $roleId = get_roleid($imageData['staffid']);
                                                                                } else {
                                                                                    $roleId =0;
                                                                                }
                                                                                if($imageData['staffid']!=0 && $roleId != 9){
                                                                        ?>
                                                                        <li style="border-right:none" class="col-xs-6 col-sm-4 col-md-3" data-responsive="<?php echo base_url($imgPath . $file_name); ?>" data-src="<?php echo base_url($imgPath . $file_name); ?>">
                                                                            <a href="<?php echo base_url($imgPath . $file_name); ?>" target="_blank">
                                                                                <figure><img class="print-document-icon" src="<?php echo base_url('/assets/images/pdf-icon.png'); ?>"></figure>
                                                                            </a>
                                                                        </li>
                                                                        <li style="border-right:none" class="text-center" data-responsive="<?php echo base_url($imgPath . $file_name); ?>" data-src="<?php echo base_url($imgPath . $file_name); ?>">
                                                                                <?php echo $loc; ?>
                                                                            </li>
                                                                        <?php }}
                                                                        } else { ?>
                                                                                <p class="text-warning mT10"><i class="fa fa-warning"></i> <?php echo _l('no_doc_available');?></p>
                                                                            <?php } ?>
                                                                        </ul>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <h4>QC Verified</h4>
                                                                        <div class="beforeAfterImage_ListWrapper">
                                                                        <ul>
                                                                            <?php if (!empty($evidenceImageData['document'])) {
                                                                                foreach ($evidenceImageData['document'] as $imageData) {
                                                                                    $imgPath = !empty($imageData['imgPath']) ? $imageData['imgPath'] : '';
                                                                                    $file_name = !empty($imageData['file_name']) ? $imageData['file_name'] : '';
                                                                                    $loc = !empty($imageData['location']) ? $imageData['location'] : '';
                                                                                    if($imageData['staffid']!=0){
                                                                                        $roleId = get_roleid($imageData['staffid']);
                                                                                    } else {
                                                                                        $roleId =0;
                                                                                    }
                                                                                    if($imageData['staffid']!=0 && $roleId == 9){
                                                                            ?>
                                                                            <li style="border-right:none" class="col-xs-6 col-sm-4 col-md-3" data-responsive="<?php echo base_url($imgPath . $file_name); ?>" data-src="<?php echo base_url($imgPath . $file_name); ?>">
                                                                                <a href="<?php echo base_url($imgPath . $file_name); ?>" target="_blank">
                                                                                    <figure><img class="print-document-icon" src="<?php echo base_url('/assets/images/pdf-icon.png'); ?>"></figure>
                                                                                </a>
                                                                            </li>
                                                                            <li style="border-right:none" class="text-center" data-responsive="<?php echo base_url($imgPath . $file_name); ?>" data-src="<?php echo base_url($imgPath . $file_name); ?>">
                                                                                    <?php echo $loc; ?>
                                                                                </li>
                                                                            <?php }}
                                                                            } else { ?>
                                                                                    <p class="text-warning mT10"><i class="fa fa-warning"></i> <?php echo _l('no_doc_available');?></p>
                                                                            <?php } ?>
                                                                        </ul>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                                <div class="row beforeAfterImage">
                                                                <ul class="nav nav-tabs only-print">
                                                                  <li class="" style="font-size: 14px; font-weight: 700; margin-top:25px; margin-bottom: 20px">Document(s)</li>
                                                                </ul>
                                                                <div class="col-md-4">
 
                                                                    </div>
                                                                    <div class="col-md-4">
 
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                    
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            
                                                        </div>
                                                    <?php if(!empty($latestImageData ) && empty($latestImageData )){ ?>
                                                        <!-- Latest Evidence -->
                                                        <div class="tab-pane <?= $latImageActive ?> fade" id="latestEvi">
                                                            <div class="content pr-mT0 print-p-0 pB10">
                                                                <?php if (!empty($latestImageData['image'])) { ?>
                                                                    <ul class="row lightgallery">
                                                                        <?php
                                                                        foreach ($latestImageData['image'] as $imageData) {
                                                                            $imgPath = !empty($imageData['imgPath']) ? $imageData['imgPath'] : '';
                                                                            $file_name = !empty($imageData['file_name']) ? $imageData['file_name'] : '';
                                                                            $file_thumb = !empty($imageData['file_thumb']) ? $imageData['file_thumb'] : '';
                                                                            $file_optimized = !empty($imageData['file_optimized']) ? $imageData['file_optimized'] : '';
                                                                            $loc = !empty($imageData['location']) ? $imageData['location'] : '';
                                                                        ?>
                                                                            <li class="col-xs-6 col-sm-4 col-md-3 text-center" data-responsive="<?php echo base_url($imgPath . $file_name); ?>" data-src="<?php echo base_url($imgPath . $file_name); ?>">
                                                                                <a href="javascript:void(0);">
                                                                                    <figure>
                                                                                        <img class="" src="<?php echo base_url($imgPath . $file_name); ?>" data-src="<?php echo base_url($imgPath . $file_name); ?>">
                                                                                    </figure>
                                                                                </a>
                                                                            </li>
                                                                            
                                                                        <?php } ?>
                                                                    </ul>

                                                                    <ul class="row">
                                                                        <?php
                                                                        if (!empty($latestImageData['image']))
                                                                            foreach ($latestImageData['image'] as $imageData) {
                                                                                $loc = !empty($imageData['location']) ? $imageData['location'] : '';
                                                                        ?>
                                                                            <li class="col-xs-6 col-sm-4 col-md-3 text-center" data-responsive="<?php echo base_url($imgPath . $file_name); ?>" data-src="<?php echo base_url($imgPath . $file_name); ?>">
                                                                                <?php echo $loc; ?>
                                                                            </li>
                                                                        <?php } ?>
                                                                    </ul>

                                                                <?php   } else { ?>
                                                                    <p class="text-warning mT10"><i class="fa fa-warning"></i><?php echo _l('no_img_available');?></p>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                        <!-- Latest Location -->
                                                        <div class="tab-pane <?= $latDocActive ?> fade" id="latestLoc">
                                                            <ul class="nav nav-tabs only-print">
                                                                <li class="" style="font-size: 14px; font-weight: 700; margin-top:25px; margin-bottom: 20px">Document(s)</li>
                                                            </ul>
                                                            <ul class="row docgallery content">
                                                                <?php if (!empty($latestImageData['document'])) {
                                                                    foreach ($latestImageData['document'] as $imageData) {
                                                                        $imgPath = !empty($imageData['imgPath']) ? $imageData['imgPath'] : '';
                                                                        $file_name = !empty($imageData['file_name']) ? $imageData['file_name'] : '';
                                                                        $loc = !empty($imageData['location']) ? $imageData['location'] : '';
                                                                ?>
                                                                        <li class="col-xs-6 col-sm-4 col-md-3" data-responsive="<?php echo base_url($imgPath . $file_name); ?>" data-src="<?php echo base_url($imgPath . $file_name); ?>">
                                                                            <a href="<?php echo base_url($imgPath . $file_name); ?>" target="_blank">
                                                                                <figure><img class="print-document-icon" src="<?php echo base_url('/assets/images/pdf-icon.png'); ?>"></figure>
                                                                            </a>
                                                                        </li>
                                                                    <?php }
                                                                } else { ?>
                                                                    <p class="text-warning mT10"><i class="fa fa-warning"></i> No document available</p>
                                                                <?php } ?>
                                                            </ul>
                                                        </div>
                                                    <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Separate  Image and Document End -->

                                        <?php } else { ?>
                                            <p class="text-warning mT10"><i class="fa fa-warning"></i> <?php echo _l('no_evidence_available');?></p>
                                        <?php } ?>
                                        <ul class="d-flex w100P raisedComment">
                                            <li class="w100P mT20 pL15 pR15 mR0" style="padding:14px;border-top:1px solid #ddd;border-right:1px solid #ddd;margin-top:0 !important"><label style="font-size:13px; font-weight:700; color: #2d2d2d;"><?php echo _l('raised_comment');?></label><span><?php echo !empty($ticketDetails->description) ? $ticketDetails->description : ''; ?></span></li>
                                            <li class="w100P mT20 pL15 pR15 mR0" style="padding:14px;border-top:1px solid #ddd;border-right:1px solid #ddd;margin-top:0 !important"><label style="font-size:13px; font-weight:700; color: #2d2d2d;"><?php echo _l('closed_comment');?></label><span><?php echo !empty($projectClosedNotes->content) ? $projectClosedNotes->content : ''; ?></span></li>
                                            <li class="w100P mT20 pL15 pR15 mR0" style="padding:14px; border-top:1px solid #ddd;border-right:1px solid #ddd;margin-top:0 !important"><label style="font-size:13px; font-weight:700; color: #2d2d2d;"><?php echo _l('qc_verified_comment');?></label><span><?php echo !empty($projectVerifiedNotes->content) ? $projectVerifiedNotes->content : ''; ?></span></li>
                                        </ul>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="page-break-after: always;"></div>
            <ul class="nav nav-tabs only-print">
                <li class="" style="font-size: 14px; font-weight: 700; margin-top:15px; margin-bottom: 10px">Project History</li>
            </ul>
            <div class="tab-pane fade" id="ticket-history">
                <p>No data</p>
                NO DATA
            </div>
        </div>
    </div>

    <style>
        .lg-outer .lg-thumb {
            margin: auto;
        }
        #lightgallery>li {
            margin-right: 0;
            margin-top: 0;
        }
    </style>

    <script>
        $(document).ready(function() {
            $('.lightgallery').lightGallery({
                selector: '.img_item',
            });
        });

        $(document).on('click', "#scroll-bottom", function() {
            $('#mCSB_2_container').css('top', 0);
        });

        $(document).on('click', "#scroll-top", function() {
            let divHeight = $('#ticket-history').innerHeight();
            // $('#mCSB_2_container').css('top',-(height-400));
            // $('#mCSB_2_container').css('top',height);
            $('#mCSB_2_container').animate({
                top: -(divHeight - 400)
            }, 500);
        });
    </script>
    
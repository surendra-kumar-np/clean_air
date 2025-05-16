
<div class="panel panel-default sub-ticket-panel  border-top-0">
    <?php
        $projectStatus = !empty($ticketDetails->project_status) ? $ticketDetails->project_status : '';
        $project_id = ($ticketDetails->project_id) ? $ticketDetails->project_id : '';
        $parent_id = !empty($ticketDetails->parent_id) ? $ticketDetails->parent_id : 0;
        $sub_ticket_id = !empty($ticketDetails->sub_ticket_id) ? $ticketDetails->sub_ticket_id : 0;
        $userId = $GLOBALS['current_user']->staffid;

        $rvList = '<option value="" selected>Refer To</option>';
        if (!empty($assignedAts)){
            foreach ($assignedAts as $assistant) {
                if(!empty($assistant['staffid'])){
                    // $plname = $assistant['full_name'].'('.$assistant['organisation'].')';
                    $plname = $assistant['full_name'].'('.$assistant['designation'].'-'.$assistant['organisation'].')';
                    $rvList .= '<option value="'.$assistant['staffid'] .'">PL('. $plname . ')</option>';
                }
            }
        }
        if (!empty($reviewers)){
            foreach ($reviewers as $rv) {
                if(!empty($rv['staffid'])){
                    if($rv['staffid'] != $userId){
                        // $name = $rv['name'].'('.$rv['organisation'].')';
                        $name = $rv['name'].'('.$rv['designation'].'-'.$rv['organisation'].')';
                        $rvList .= '<option value="'.$rv['staffid'] .'">RV('. $name . ')</option>';
                    }
                }
            }
        }
    ?>

<?php
    if(get_staff_user_id() == $assignedUser){ ?>
        <div class="panel-body p-0">
            <div class="create-dropdown accept-reject p15">
                <div class="row">
                    <div class="col-lg-9">
                        <label>Refer Project </label>
                        <div class="form-select-field mB15">
                            <select class="assignTDList">
                                <?php echo $rvList; ?>
                            </select>
                            <label class="select-label">Refer To</label>
                        </div>
                        <div class="">
                            <a href="#" class="btn accept-btn pull-right assignTDTicket" data-ticketdetail="ticketDetail">Refer</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    <?php }
?>   
</div>
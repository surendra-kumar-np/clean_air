
<div class="panel panel-default sub-ticket-panel  border-top-0">
    <?php
        $projectStatus = !empty($ticketDetails->project_status) ? $ticketDetails->project_status : '';
        $project_id = ($ticketDetails->project_id) ? $ticketDetails->project_id : '';
        $parent_id = !empty($ticketDetails->parent_id) ? $ticketDetails->parent_id : 0;
        $sub_ticket_id = !empty($ticketDetails->sub_ticket_id) ? $ticketDetails->sub_ticket_id : 0;
        $userId = $GLOBALS['current_user']->staffid;

        $rvList = '';
        
        $rvList .= '<option value="" selected disabled>Select</option>';
        $rvList .= '<option value="4">Resolved</option>';
        $rvList .= '<option value="15">Unresolved</option>';
        $rvList .= '<option value="16">Partially Resolved</option>';

    ?>

        <div class="panel-body p-0">
            <div class="create-dropdown accept-reject p15">
                <div class="row">
                    <div class="col-lg-5">
                        <label>Mark Ticket Status</label>
                        <div class="form-select-field mB15">
                            <select class="markVerificationList">
                                <?php echo $rvList; ?>
                            </select>
                            
                        </div>
                        <div class="">
                            <a href="#" class="btn accept-btn pull-right markVerification">Submit</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
     
</div>
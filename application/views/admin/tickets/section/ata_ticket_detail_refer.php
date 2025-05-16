<div class="panel panel-default sub-ticket-panel">
    <!-- start code for refer and close -->
    <?php
    $projectStatus = !empty($ticketDetails->project_status) ? $ticketDetails->project_status : '';
    $project_id = ($ticketDetails->project_id) ? $ticketDetails->project_id : '';
    if ($projectStatus == 1 || $projectStatus == 5 || $projectStatus == 2 || $projectStatus == 6 || $projectStatus == 10) {
        $userId = $GLOBALS['current_user']->staffid;
        $userName = $GLOBALS['current_user']->full_name;

        // $assistantList = '<option value="">Assign To</option>';
        // $assistantList .= '<option value="'.$userId .'">'. $userName . ' (Self)</option>';
        // if (!empty($assistantDetails))
        //     foreach ($assistantDetails as $assistant) {
        //         $name = $assistant['full_name'].'('.$assistant['organisation'].')';
        //         $assistantList .= '<option value="'.$assistant['staffid'] .'">'. $name . '</option>';
        //     }

        $psList = '<option value="" selected>Refer To</option>';
        if (!empty($PsWithSamePl)){
            foreach ($PsWithSamePl as $pswithsamepls) {
                if(!empty($pswithsamepls['staffid'])){
                    // $name = $pswithsamepls['full_name'].'('.$pswithsamepls['organisation'].')';
                    $name = $pswithsamepls['full_name'].'('.$pswithsamepls['designation'].'-'.$pswithsamepls['organisation'].')';
                    $psList .= '<option value="'.$pswithsamepls['staffid'] .'">'. $name . '</option>';
                }
            }
                //$psList .= '<option value="'.$plDetail->staffid .'">Long Term</option>';
        }
        if (!empty($plDetail)){
            if(!empty($plDetail->staffid)){
                $psList .= '<option value="'.$plDetail->staffid .'">Own PL('. $plDetail->full_name . '('.$plDetail->designation.'-'.$plDetail->organisation.'))</option>';
            }
        }

        $exceptionList = '<option value="">Reason</option>';
        if (!empty($exceptionDetails))
            foreach ($exceptionDetails as $exception) {
                $exceptionList .= '<option value="'.$exception['id'] .'">'. $exception['name'] . '</option>';
        }
    ?>
    <div class="panel panel-default sub-ticket-panel border-bottom-0">
        <div class="panel-heading">
            Action - Refer
        </div>
    </div>
    <div class="panel-body p-0">
        <div class="create-dropdown accept-reject p15">
            <div class="row">
                <div class="col-lg-5 closed_referaction">
                    <label>Refer Project</label>
                    <div class="form-select-field mB15">
                        <select class="assignTDList">
                            <?php echo $psList; ?>
                        </select>
                        <label class="select-label">Refer To</label>
                    </div>
                    <div class="">
                        <a href="#" class="btn accept-btn assignTDTicket" data-ticketdetail="ticketDetail">Refer</a>
                    </div>
                </div>
                <!-- <p class="or">OR</p> -->
                <div class="col-lg-1"></div>
                <div class="col-lg-6"></div>

                <div class="col-lg-12 mT10">
                    <label><?php echo "Mark as Long Term"; ?>
                    </label>
                    <div style="display: flex;align-items:center"><input class="rejectTDList" style="margin-top:0px;margin-right:5px" type="checkbox" value="11"><label style="margin-bottom:0px;">Long Term</label></div>

                    <?php echo form_open(admin_url('tickets/newissue'), array('id' => 'longterm_form')); ?>
                    
                    <div class="hide-longterm hide">
                        <div class="form-select-field mB15">
                            
                            <!-- <select class="rejectTDList">
                                <?php //echo $exceptionList; ?>
                            </select> -->
                            <!-- <label class="select-label">Select Reason</label> -->
                        </div>
                        <div class="otherTDReason mB10">
                            <textarea id="other-area" name="otherTDException" class="form-textarea otherTDException"></textarea>
                            <label for="other-area" title="Reason" data-title="Reason"></label>
                            <span class="reason_span" id="reason_span" style="color:red;"></span>
                        </div>
                        <div id="additionalhiddenfields"></div>

                        <div class="create-dropdown pT0">
                            <div class="row">
                                <div class="col-lg-12 action-ticket-btn">
                                    <label class="mB20">Extend Deadlines</label>
                                    <div class="form-group extended-table table-scroll">
                                        <table class="table table-borderless ticket-milestone table-fixed">


                                            <tbody>
                                            <div class="col-md-12">
                                                <div class="row">

                                                    <div class="input_fields_wrap1"></div>

                                                    
                                            
                                                    
                                                    <div class="disabled_wrapper">
                                                        
                                                        
                                                    </div>

                                                    <div class="input_fields_wrap_add_field"></div>
                                                    <div class="add-milestone-btn btn_add_test">
                                                        <label for="" class="mR0"><?php echo _l('add_milestone') ?> <span><i class="fa fa-plus-square-o btn-color add_field_button fa-2x" style=""></i></span></label>
                                                    </div>

                                                </div>
                                            </div>
                                                
                                                
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <!-- <a href="#" class="rejectTDTicket btn btn-custom" data-ticketdetail="ticketDetail">
                            <?php 
                                //echo "Request";
                            ?>
                            </a> -->
                            <button type="submit" id="submit_issue" class="btn btn-custom"><?php echo _l('submit'); ?></button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
                <input type="hidden" name="rejectProjectId" class="rejectProjectId" value="<?php echo $project_id; ?>" />
                <div class="col-lg-6 mT10 ticketDetailsRejection hide">
                    <div class="form-group mB0">
                        <div class="form-input-field mB0">
                            <input type="hidden" name="exceptionId" class="exceptionId" value="" />
                            
                            <input type="text" name="detialOtherException" class="otherException">
                            <label for="name" title="Reason" data-title="Reason"></label>
                            <label class="btn-container mT10"><a href="#" class="text-white semibold btn reject-btn submitException" data-ticketdetail="ticketDetail">Reject Project</a>
                            </label>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php } else if ($projectStatus == 4 && $milestoneStatus == 4) { ?>
    <!-- <div class="panel-body">
        <div class="create-dropdown pT0">
            <div class="row">
                <div class="col-lg-12 action-ticket-btn">
                    <label class="btn-container"><a href="javascript:void(0)" class="semibold btn accept-btn close-ticket" data-ticketdetail="ticketDetail" data-projectid="<?php// echo $project_id; ?>">Close</a>
                    </label>
                    <label class="btn-container"><a href="javascript:void(0)" class="semibold btn reject-btn reopen-ticket mL5" data-projectid="<?php //echo $project_id; ?>">Reopen</a>
                    </label>
                </div>

                <div class="col-lg-6 mT10 reopenTicketComment hide">
                    <div class="form-group mB0">
                        <div class="form-input-field mB0">
                            <input type="hidden" name="reopenProjectId" class="reopenProjectId" value="<?php //echo $project_id; ?>" />
                            <input type="text" name="reopenReason" class="reopenReason">
                            <label for="reopenReason" title="Reason" data-title="Reason"></label>
                        </div>
                        <label class="btn-container mT10">
                            <a href="javascript:void(0)" class="semibold btn reject-btn reopenTicket" data-ticketdetail="ticketDetail" data-projectid="<?php// echo $project_id; ?>">Reopen Project</a>
                        </label>
                    </div>
                </div>

            </div>
        </div>
    </div> -->
    <?php } ?>
    <!-- end code for refer and close -->
    
    
    
</div>
